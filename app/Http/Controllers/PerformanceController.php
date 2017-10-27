<?php

namespace App\Http\Controllers;

use App\Component;
use App\Server;
use App\ComponentServer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\ComModules\OrchestratorRequest;
use App\Cpu;
use App\DataCollection;
use App\Jobs\ComponentJob;
use App\Jobs\ServerJob;
use App\Jobs\DataCollectionJob;
use App\Jobs\ComponentServerJob;
use Exception;


class PerformanceController extends Controller
{

    private function verifyXModuleToken($xModuleToken){

        $components = Component::where('name', $xModuleToken)->first();
        if(!empty($components)){
          return $components->id;
        }
        else {
          $id = $this->storeComponents($xModuleToken);
          return $id;
        }

    }

    private function verifyServer($ip){

        $servers = Server::where('ip', $ip)->first();

        if(!empty($servers)){
          return $servers->id;
        }
        else {
          $id = $this->storeServers($ip);
          return $id;
        }

    }


    private  function storeDataCollections($io,$memory,$cpus,$serverId)
    {
        $compServerId = Server::find($serverId)->componentServers->last()->id;
        dispatch(new DataCollectionJob($io,$memory,$cpus,$compServerId));
    }


    private  function storeComponents($xModelToken)
    {
        //create and store new component to database
        dispatch(new ComponentJob($xModuleToken));
        return Component::all()->last()->id;
    }

    private  function storeServers($ip)
    {
        //create and store new server to database
        dispatch(new ServerJob($ip));
        return Server::all()->last()->id;
    }

    private  function storeComponentServers($idComponent, $idServer)//é necessáro criar um job por causa disto???
    {
        $server= Server::find($idServer);
        dispatch(new ComponentServerJob($server, $idComponent));
    }

    public function saveDataToDB(Request $request)
    {

        //dd($request->all());
        $performance = $request->all();
        $xModuleToken = $request->header("x-module-token");
        $ip = $performance["ip"];

        $result = OrchestratorRequest::askCheckToken($xModuleToken);

        if ($result) {
            $idComponent = $this->verifyXModuleToken($xModuleToken);
            $idServer = $this->verifyServer($ip);
            $this->storeComponentServers($idComponent, $idServer);
            $cpus = $performance["cpu"];
            $memory = $performance["memory"];
            $io = $performance["io"];
            $this->storeDataCollections($io, $memory, $cpus, $idServer);
        }

    }


    public function getAllServers(Request $request){

        $serversData=[];
        $servers = Server::all();

        if($servers == null) return [];
        foreach ($servers as $server){
            $serversData[]= ['ip' =>$server->getAttribute('ip'), 'id' =>$server->getAttribute('id')]; //isto não poderia passar só para uma query?
        }

        return $serversData;
    }
    public function getAllComponents(Request $request){

        $componentsData=[];
        $components = Component::all();

        foreach ($components as $component){
            $componentsData[]= ['name' =>$component->getAttribute('name'), 'id' =>$component->getAttribute('id')]; //isto não poderia passar só para uma query?
        }

        return $componentsData ;
    }


    public function sendPerformanceFromDBByComponentServer(Request $request)
    {

        try {
            $name =$request->json('name');

            $timeFilter = $request->json('timeFilter');
            $serverIp = $request->json('serverIp');
            $component = $request->json('component');

            if($serverIp==null | $timeFilter == null | $component == null)
                abort(400);

            $server = Server::whereIp($serverIp)->firstOrFail();

            $serverId = $server->id;

            $componentTemp = Component::whereName($component)->firstOrFail();
            $componentId = $componentTemp->id;


            if ($timeFilter != "range") {
                switch ($timeFilter) {
                    case "5mins":
                        $time = Carbon::now()->addMinutes(-5);
                        break;
                    case "15mins":
                        $time = Carbon::now()->addMinutes(-15);
                        break;
                    case "1h":
                        $time = Carbon::now()->addHours(-1);
                        break;
                    case "1d":
                        $time = Carbon::now()->addDays(-1);
                        break;
                    case "1w":
                        $time = Carbon::now()->addWeeks(-1);
                        break;
                    case "1m":
                        $time = Carbon::now()->addMonths(-1);
                        break;
                    case "1y":
                        $time = Carbon::now()->addYears(-1);
                        break;
                    default:
                        return "time error";
                        break;
                }

                $compServers = ComponentServer::where('server_id',$serverId)->where('component_id',$componentId)
                    ->where('created_at','>=',$time)->get();

                $dataCollections = [];
                foreach ($compServers as $compServer) {
                    $dataCollection = DataCollection::where('component_server_id',$compServer->id)
                        ->where('created_at','>=',$time)->get();
                    if(!empty($dataCollection->toArray())) {
                        $dataCollections[]=$dataCollection->toArray();
                    }
                }
                $cpus = [];
                foreach ($dataCollections as $dCollection) {
                    foreach ($dCollection as $dataCollection) {
                       $cpus[] = Cpu::where('data_collection_id',$dataCollection["id"])->where('created_at', '>=', $time)->get();

                    }
                }

            } else {

                $startRangeTemp = strtotime($request->json('startRange'));
                $startRange = date('Y-m-d',$startRangeTemp);
                $endRangeTemp = strtotime($request->json('endRange'));
                $endRange = date('Y-m-d',$endRangeTemp);

                $compServers = ComponentServer::where('server_id',$serverId)->where('component_id',$componentId)
                    ->where('created_at','>=',$startRange)->where('created_at','<=',$endRange)->get();

                $dataCollections = [];
                foreach ($compServers as $compServer) {
                    $dataCollection = DataCollection::where('component_server_id',$compServer->id)
                        ->where('created_at','>=',$startRange)->where('created_at','<=',$endRange)->get();
                    if(!empty($dataCollection->toArray())) {
                        $dataCollections[]=$dataCollection->toArray();
                    }
                }
                $cpus = [];
                foreach ($dataCollections as $dCollections) {
                    foreach ($dCollections as $dataCollection)
                    $cpus[] = Cpu::where('data_collection_id',$dataCollection["id"])->where('created_at', '>=', $startRange)
                        ->where('created_at', '<=', $endRange)->get();
                }

            }

        $data = ["compServers" => $compServers,
            "componentName"=> $name,
            "dataCollections" => $dataCollections,
            "cpus" => $cpus];

           return response()->json($data, 200);

        }catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Data not Found'], 404);

       } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }


    public function sendPerformanceFromDBForBarsGraph(Request $request)
    {


        try {

            $serverIp = $request->json('serverIp');
            $component = $request->json('component');

            if($serverIp==null | $component == null)
                abort(400);

            $server = Server::whereIp($serverIp)->firstOrFail();

            $serverId = $server->id;

            $componentTemp = Component::whereName($component)->firstOrFail();
            $componentId = $componentTemp->id;


            $startRangeTemp = strtotime($request->json('startRange'));
            $startRange = date('Y-m-d',$startRangeTemp);
            $endRangeTemp = strtotime($request->json('endRange'));
            $endRange = date('Y-m-d',$endRangeTemp);

            $compServers = ComponentServer::where('server_id',$serverId)->where('component_id',$componentId)
                ->where('created_at','>=',$startRange)->where('created_at','<=',$endRange)->get();

            $dataCollections = [];
            foreach ($compServers as $compServer) {
                $dataCollection= DataCollection::where('component_server_id',$compServer->id)
                    ->where('created_at','>=',$startRange)->where('created_at','<=',$endRange)->get();
                if(!empty($dataCollection->toArray())) {
                    $dataCollections[]=$dataCollection->toArray();
                }
            }
            $cpus = [];
            foreach ($dataCollections as $dCollections) {
                foreach ($dCollections as $dataCollection) {
                    $cpus[] = Cpu::where('data_collection_id', $dataCollection["id"])->where('created_at', '>=', $startRange)
                        ->where('created_at', '<=', $endRange)->get();
                }
            }

            $data = ["compServers" => $compServers,
                "componentName"=> $component,
                "dataCollections" => $dataCollections,
                "cpus" => $cpus];

            return response()->json($data, 200);

        }catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Data not Found'], 404);
        }catch (Exception $e) {
            return response()->json(['error' => 'Error trying to retrieve data'], 400);
        }
    }

}
