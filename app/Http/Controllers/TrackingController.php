<?php

namespace App\Http\Controllers;


use App\Log;
use App\One\One;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Tracking;
use App\TrackingRequest;
use App\Jobs\TrackingJob;
use App\Jobs\TrackingRequestJob;
use App\Server;
use Exception;
use Carbon\Carbon;


class TrackingController extends Controller
{
    private  function storeTrackingData($data)
    {
        dispatch(new TrackingJob($data));
    }

    private  function storeTrackingRequestData($data)
    {
        dispatch(new TrackingRequestJob($data));
    }


    private  function updateTrackingEndTime($time_end, $table_key = null , $site_key = null)
    {
        if (!is_null($table_key)) {
            $track = Tracking::whereTableKey($table_key)->whereTimeEnd(null)->first();
            if(!is_null($site_key)){
                $track->site_key = $site_key;
            }
            $track->time_end = $time_end;
            $track->save();
        } else {
            $track = Tracking::all()->last();
            $track->time_end = $time_end;
            $track->save();
        }
    }
    private  function updateTrackingRequestEndTime($time_end)
    {
        $trackRequest = TrackingRequest::all()->last();
        $trackRequest->time_end = $time_end;
        $trackRequest->save();
    }

    public function saveTrackingDataToDB(Request $request)
    {
        $data = $request->all();
        $this->storeTrackingData($data);
    }

    public function updateTrackingDataToDB(Request $request)
    {
        $data = $request->all();
        $this->updateTrackingEndTime($data["time_end"], $data["table_key"] ?? null , $data["site_key"] ?? null);
    }

    public function updateTrackingRequestDataToDB(Request $request)
    {
        $data = $request->all();
        $this->updateTrackingRequestEndTime($data["time_end"]);
    }


    public function saveTrackingRequestDataToDB(Request $request)
    {
        $data['table_key'] = $request->json('table_key');
        $data['url'] = $request->json('url');
        $data['method'] = $request->json('method');
        $data['module_token'] = $request->json('module_token');
        $data['result'] = $request->json('result');
        $data['time_start'] = $request->json('time_start');
        $data['message'] = $request->json('message');

        $this->storeTrackingRequestData($data);
    }


    public function getLastTrackingKey(){

        $tracking= Tracking::all()->last();
        return $tracking->table_key;

    }

    public function getTrackingDataByTimeFilter(Request $request){

        One::verifyToken($request);

        try{
            $logList = Log::query();
            $tableData = $request->input('tableData') ?? null;
            $recordsTotal = $logList->count();
            $query = $logList;

            $recordsFiltered = $query->count();

            if(!empty($tableData['start']))
                $query = $query
                    ->skip($tableData['start']);


            $logs = $query
                ->take($tableData['length'])
                ->get();

            $data['logs'] = $logs;
            $data['recordsTotal'] = $recordsTotal;
            $data['recordsFiltered'] = $recordsFiltered;

            return response()->json($data, 200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Failed to get Logs'], 500);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Email not Found'], 404);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function getTrackingData(Request $request){

        $id = $request->id;

        $tracking= Tracking::where('id', '=',$id)->get();

        return response()->json($tracking, 200);

    }

    public function getTrackingRequestsData (Request $request){

        $id = $request->tracking_id;

        $trackingRequests=TrackingRequest::where('tracking_id','=',$id)->get();

        return response()->json($trackingRequests,200);

    }
    public function updateMessageException(Request $request)
    {
        $data = $request->all();
        $message=$data["message"];
        $track = TrackingRequest::all()->last();
        $track->message = $message;
        $track->save();
        return;

    }

    public function getLastLog(){

        $log= Tracking::all()->last();
        return $log;

    }
}
