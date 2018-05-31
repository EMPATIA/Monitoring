<?php

namespace App\Http\Controllers;

use App\Access;
use App\One\One;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\DB;
use App\ComModules\EMPATIA;

/**
 * Class LogsController
 * @package App\Http\Controllers
 */
class AccessesController extends Controller
{

    protected $keysRequired = [

        'date_and_time',
        'IP',
        'url',
        'session_id',
        'action',
        'result',
    ];

    /**
     * Requests a list of logs.
     * Returns the list of logs.
     *
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {

        try {
            $accesses = Access::select('ip','site_key','user_key','cb_key','date','action','result','error','details');

            //sites
            if(!empty($request->sites)){
                $accesses = $accesses->where('site_key','=',$request->sites);
            }
            //cbs
            if(!empty($request->cbs)){
                $accesses = $accesses->where('cb_key','=',$request->cbs);
            }
            //select date
            if(!empty($request->start_date) && !empty($request->end_date) ){
                if($request->start_date == $request->end_date){
                    $accesses  = $accesses->where('date','=', $request->end_date);

                }
                $accesses  = $accesses->whereBetween('date',[$request->start_date, $request->end_date]);
            }
            //action
            if(!empty($request->actions)){
                $accesses = $accesses->whereIn('action',$request->actions);
            }
            //result
            if(!empty($request->result)){
                if($request->result != 'all'){
                    if($request->result == 'Ok'){
                        $accesses = $accesses->where('result','=',1);
                    }
                    else{
                        $accesses = $accesses->where('result','=',0);
                    }
                }
            }
            //ip
            if(!empty($request->ip)){
                $accesses = $accesses->where('ip','=',$request->ip);
            }

            $accesses = $accesses->orderBy('date','desc')->get();

            //get all users (name and key)
            $users = EMPATIA::getUsers();


            //replace the user_key with the name
            if(isset($users) && count($users) >0){
                foreach ($accesses as $access) {
                    $access->email = "";
                    foreach($users as $userKey => $userName ) {
                        if(strcmp($access->user_key,$userKey) == 0) {
                            $access->user_key = $userName->user_key;
                            $access->name = $userName->name;
                            $access->email = $userName->email;
                        }
                        elseif($access->user_key == null){
                            $access->user_key = 'anonymous';
                        }
                    }
                }
            }

            //email
            if(!empty($request->email)){
                foreach($accesses as $key => $access){
                    if($access->email != $request->email){
                        unset($accesses[$key]);
                    }
                }
            }

            //get all cbs (name and key)
            $cbs = EMPATIA::getCbs();

            //replace the cb_key with the name
            if(isset($cbs) && count($cbs) >0){
                foreach ($accesses as $access) {
                    if($access->cb_key != null){
                        foreach($cbs as $cbName => $cbKey) {
                            if(strcmp($access->cb_key,$cbKey) == 0) {
                                $access->cb_key = $cbName;
                            }
                        }
                    }
                }
            }

            //get all sites (name and key)
            $sites = EMPATIA::getSites();


            if(isset($sites) && count($sites) >0){
                foreach ($accesses as $access) {
                    if($access->site_key != null){
                        foreach($sites as $siteName => $siteKey) {
                            if(strcmp($access->site_key,$siteKey) == 0) {
                                $access->site_key = $siteName;
                            }
                        }
                    }
                }
            }

            //replaces the result value
            foreach ($accesses as $access) {
                if($access->result == 1){
                    $access->result = 'Ok';
                }
                else{
                    $access->result = 'Ko';
                }
            }

            return response()->json(['data' => $accesses], 200);
        }
        catch(Exception $e) {
            return response()->json(['error' => 'Failed to retrieve the access list'], 500);
        }
        return response()->json(['error' => 'Unauthorized' ], 401);
    }


    /**
     * Store a newly created log in storage.
     * Returns the details of the newly created log.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        try {
            $access = Access::create([
                'date' => Carbon::now()->toDateTimeString(),
                'IP'            => $request->json('ip'),
                'url'           => $request->json('url'),
                'session_id'    => $request->json('session_id'),
                'entity_key'    => $request->json('entity_key'),
                'site_key'      => $request->json('site_key'),
                'user_key'      => !empty($request->json('user_key')) ?  $request->json('user_key') : null,
                'content_key'   => !empty($request->json('content_key')) ? $request->json('content_key') : null,
                'cb_key'        => !empty($request->json('cb_key')) ? $request->json('cb_key') : null,
                'topic_key'     => !empty($request->json('topic_key')) ? $request->json('topic_key') : null,
                'post_key'      => !empty($request->json('post_key')) ? $request->json('post_key') : null,
                'q_key'         => !empty($request->json('q_key')) ? $request->json('q_key') : null,
                'vote_key'      => !empty($request->json('vote_key')) ? $request->json('vote_key') : null,
                'action'        => $request->json('action'),
                'result'        => $request->json('result'),
                'details'       => $request->json('details') ?? null,
                'error'         => $request->json('error') ?? null,

            ]);

            return response()->json(['data' => $access], 201);
        }
        catch(Exception $e){
            return response()->json(['error' => 'Failed to store new access'], 500);
        }

        return response()->json(['error' => 'Unauthorized' ], 401);
    }

    /**
     * Requests a list of actions.
     * Returns the list of actions.
     *
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function action(Request $request)
    {
        try {
            $allActions = Access::distinct()->get(['action'])->pluck('action');
            $actions =[];
            foreach($allActions as $key => $allAction) {
                $actions[$allAction]=$allAction;
            }

            return response()->json(['actions' => $actions], 200);
        }
        catch(Exception $e) {
            return response()->json(['error' => 'Failed to retrieve the access list'], 500);
        }

        return response()->json(['error' => 'Unauthorized' ], 401);
    }

    /**
     * Requests a list of cbs.
     * Returns the list of cbs.
     *
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cb(Request $request)
    {
        try {
            //get all cbs (name and key)
            $allCbs = EMPATIA::getCbs();
            $allLogsCbs = Access::distinct()->get(['cb_key'])->pluck('cb_key');

            $cbs=[];
            //replace the cb_key with the name
            if((isset($allLogsCbs) && count($allLogsCbs) >0) && (isset($allCbs) && count($allCbs) >0)) {
                foreach ($allLogsCbs as $key => $allLogsCb) {
                    foreach($allCbs as $cbName => $cbKey) {
                        if(strcmp($allLogsCb,$cbKey) == 0) {
                            $cbs[$cbKey]=$cbName;
                        }
                    }
                }
            }
            return response()->json(['cbs' => $cbs], 200);
        }
        catch(Exception $e) {
            return response()->json(['error' => 'Failed to retrieve the access list'], 500);
        }

        return response()->json(['error' => 'Unauthorized' ], 401);
    }
}

