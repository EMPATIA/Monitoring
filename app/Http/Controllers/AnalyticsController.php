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


/**
 * Class LogsController
 * @package App\Http\Controllers
 */
class AnalyticsController extends Controller
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
            //days
            $days = [];
            if(!empty($request->dayStart) && !empty($request->dayEnd) ){
                $startDay = Carbon::createFromFormat('Y-m-d', $request->dayStart,'Europe/London');
                $dayEnd = Carbon::createFromFormat('Y-m-d', $request->dayEnd,'Europe/London');

                for( $startDay ;$startDay->toDateString() <= $dayEnd->toDateString(); $startDay->addDay() ){
                    array_push($days,$startDay->toDateString());
                }
            }
            else{
                $startDay =  Carbon::now()->subDays(29);
                $endDay = Carbon::now();

                for( $startDay ; $startDay->toDateString() <= $endDay->toDateString(); $startDay->addDay() ){
                    array_push($days,$startDay->toDateString());
                }
            }

            //login Ok
            if($request->entity_key == '0'){
                $count_loginOk = Access::where('action','=','login')
                                        ->where('result','=','1');
            }
            else{
                $count_loginOk = Access::where('entity_key','=',$request->entity_key)
                                        ->where('action','=','login')
                                        ->where('result','=','1');
            }

            if(!empty($request->dayStart) && !empty($request->dayEnd) ){

                $count_loginOk = $count_loginOk->where('date', '>=', $request->dayStart.' 00:00:00')
                                                ->where('date', '<=', $request->dayEnd.' 23:59:59');
            }
            else{
                $count_loginOk = $count_loginOk->where('date','>=', Carbon::now()->subDays(29)->toDateString());
            }

            $count_loginOk = $count_loginOk->select(DB::raw('count(*) as loginOk'), DB::raw('DATE(date) as day'))
                                            ->groupBy('day')
                                            ->pluck('loginOk','day')
                                            ->toArray();

            $keys = [];
            $loginOk = array_fill(0, count($days), 0);

            if(isset($count_loginOk) && count($count_loginOk) > 0){
                foreach($days as $dayKey => $select_day){
                    foreach ($count_loginOk as $day => $countLoginOk) {
                        if (strcmp($select_day, $day) == 0)
                            array_push($keys, $dayKey);
                    }
                }
                foreach($days as $dayKey => $select_day){
                    foreach ($keys as $key => $value) {
                        if($dayKey == $value){
                            $loginOk[$dayKey] = $count_loginOk[$select_day];
                        }
                    }
                }
            }

            //login Ko
            if($request->entity_key == '0'){
                $count_loginKo = Access::where('action','=','login')
                                        ->where('result','=','0');
            }
            else{
                $count_loginKo = Access::where('entity_key','=',$request->entity_key)
                                        ->where('action','=','login')
                                        ->where('result','=','0');
            }

            if(!empty($request->dayStart) && !empty($request->dayEnd) ){

                $count_loginKo = $count_loginKo->where('date', '>=', $request->dayStart.' 00:00:00')
                                                ->where('date', '<=', $request->dayEnd.' 23:59:59');
            }
            else{
                $count_loginKo = $count_loginKo->where('date','>=', Carbon::now()->subDays(29)->toDateString());
            }

            $count_loginKo = $count_loginKo->select(DB::raw('count(*) as loginKo'), DB::raw('DATE(date) as day'))
                                            ->groupBy('day')
                                            ->pluck('loginKo','day')
                                            ->toArray();

            $keys = [];
            $loginKo = array_fill(0, count($days), 0);

            if(isset($count_loginKo) && count($count_loginKo) > 0){
                foreach($days as $dayKey => $select_day){
                    foreach ($count_loginKo as $day => $countLoginKo) {
                        if (strcmp($select_day, $day) == 0)
                            array_push($keys, $dayKey);
                    }
                }
                foreach($days as $dayKey => $select_day){
                    foreach ($keys as $key => $value) {
                        if($dayKey == $value){
                            $loginKo[$dayKey] = $count_loginKo[$select_day];
                        }
                    }
                }
            }

            //register OK
            if($request->entity_key == '0'){
                $count_registerOk = Access:: where('action','=','new_registration')
                                            ->where('result','=','1');
            }
            else{
                $count_registerOk = Access:: where('entity_key','=',$request->entity_key)
                    ->where('action','=','new_registration')
                    ->where('result','=','1');
            }

            if(!empty($request->dayStart) && !empty($request->dayEnd) ){

                $count_registerOk = $count_registerOk->where('date', '>=', $request->dayStart.' 00:00:00')
                                                     ->where('date', '<=', $request->dayEnd.' 23:59:59');
            }
            else{
                $count_registerOk = $count_registerOk->where('date','>=', Carbon::now()->subDays(29)->toDateString());
            }

            $count_registerOk = $count_registerOk->select(DB::raw('count(*) as registerOk'), DB::raw('DATE(date) as day'))
                                                    ->groupBy('day')
                                                    ->pluck('registerOk','day')
                                                    ->toArray();

            $keys = [];
            $registerOk = array_fill(0, count($days), 0);

            if(isset($count_registerOk) && count($count_registerOk) > 0){
                foreach($days as $dayKey => $select_day){
                    foreach ($count_registerOk as $day => $countRegisterOk) {
                        if (strcmp($select_day, $day) == 0)
                            array_push($keys, $dayKey);
                    }
                }
                foreach($days as $dayKey => $select_day){
                    foreach ($keys as $key => $value) {
                        if($dayKey == $value){
                            $registerOk[$dayKey] = $count_registerOk[$select_day];
                        }
                    }
                }
            }

            //register Ko
            if($request->entity_key == '0'){
                $count_registerKo = Access::where('action','=','new_registration')
                                            ->where('result','=','0');
            }
            else{
                $count_registerKo = Access::where('entity_key','=',$request->entity_key)
                                        ->where('action','=','new_registration')
                                        ->where('result','=','0');
            }

            if(!empty($request->dayStart) && !empty($request->dayEnd) ){

                $count_registerKo = $count_registerKo->where('date', '>=', $request->dayStart.' 00:00:00')
                                                    ->where('date', '<=', $request->dayEnd.' 23:59:59');
            }
            else{
                $count_registerKo = $count_registerKo->where('date','>=', Carbon::now()->subDays(29)->toDateString());
            }

            $count_registerKo = $count_registerKo->select(DB::raw('count(*) as registerKo'), DB::raw('DATE(date) as day'))
                                                 ->groupBy('day')
                                                 ->pluck('registerKo','day')
                                                 ->toArray();

            $keys = [];
            $registerKo = array_fill(0, count($days), 0);

            if(isset($count_registerKo) && count($count_registerKo) > 0){
                foreach($days as $dayKey => $select_day){
                    foreach ($count_registerKo as $day => $countRegisterKo) {
                        if (strcmp($select_day, $day) == 0)
                            array_push($keys, $dayKey);
                    }
                }
                foreach($days as $dayKey => $select_day){
                    foreach ($keys as $key => $value) {
                        if($dayKey == $value){
                            $registerKo[$dayKey] = $count_registerKo[$select_day];
                        }
                    }
                }
            }

            //password_recovery Ok
            if($request->entity_key == '0'){
                $count_password_recoveryOk = Access::where('action','=','password_recovery')
                                                    ->where('result','=','1');
            }
            else{
                $count_password_recoveryOk = Access::where('entity_key','=',$request->entity_key)
                                                    ->where('action','=','password_recovery')
                                                    ->where('result','=','1');
            }

            if(!empty($request->dayStart) && !empty($request->dayEnd) ){

                $count_password_recoveryOk = $count_password_recoveryOk->where('date', '>=', $request->dayStart.' 00:00:00')
                                                                        ->where('date', '<=', $request->dayEnd.' 23:59:59');
            }
            else{
                $count_password_recoveryOk = $count_password_recoveryOk->where('date','>=', Carbon::now()->subDays(29)->toDateString());
            }

            $count_password_recoveryOk = $count_password_recoveryOk->select(DB::raw('count(*) as password_recoveryOk'), DB::raw('DATE(date) as day'))
                                                                    ->groupBy('day')
                                                                    ->pluck('password_recoveryOk','day')
                                                                    ->toArray();

            $keys = [];
            $passwordRecoveryOk = array_fill(0, count($days), 0);

            if(isset($count_password_recoveryOk) && count($count_password_recoveryOk) > 0){
                foreach($days as $dayKey => $select_day){
                    foreach ($count_password_recoveryOk as $day => $countPasswordRecoveryOk) {
                        if (strcmp($select_day, $day) == 0)
                            array_push($keys, $dayKey);
                    }
                }
                foreach($days as $dayKey => $select_day){
                    foreach ($keys as $key => $value) {
                        if($dayKey == $value){
                            $passwordRecoveryOk[$dayKey] = $count_password_recoveryOk[$select_day];
                        }
                    }
                }
            }

            //password_recovery Ko
            if($request->entity_key == '0'){
                $count_password_recoveryKo = Access::where('action','=','password_recovery')
                                                    ->where('result','=','0');
            }
            else{
                $count_password_recoveryKo = Access::where('entity_key','=',$request->entity_key)
                                                    ->where('action','=','password_recovery')
                                                    ->where('result','=','0');
            }

            if(!empty($request->dayStart) && !empty($request->dayEnd) ){
                if($request->dayStart == $request->dayEnd){
                    $count_password_recoveryKo = $count_password_recoveryKo->where('date', '=', $request->dayStart.' 00:00:00');
                }
                $count_password_recoveryKo = $count_password_recoveryKo->whereBetween('date',[$request->dayStart.' 00:00:00', $request->dayEnd.' 23:59:59']);
            }
            else{
                $count_password_recoveryKo = $count_password_recoveryKo->where('date','>=', Carbon::now()->subDays(29)->toDateString());
            }

            $count_password_recoveryKo = $count_password_recoveryKo->select(DB::raw('count(*) as password_recoveryKo'), DB::raw('DATE(date) as day'))
                                                                    ->groupBy('day')
                                                                    ->pluck('password_recoveryKo','day')
                                                                    ->toArray();

            $keys = [];
            $passwordRecoveryKo = array_fill(0, count($days), 0);

            if(isset($count_password_recoveryKo) && count($count_password_recoveryKo) > 0){
                foreach($days as $dayKey => $select_day){
                    foreach ($count_password_recoveryKo as $day => $countPasswordRecoveryKo) {
                        if (strcmp($select_day, $day) == 0)
                            array_push($keys, $dayKey);
                    }
                }
                foreach($days as $dayKey => $select_day){
                    foreach ($keys as $key => $value) {
                        if($dayKey == $value){
                            $passwordRecoveryKo[$dayKey] = $count_password_recoveryKo[$select_day];
                        }
                    }
                }
            }

            //create topic Ok
            if($request->entity_key == '0'){
                $count_create_topicOk = Access::where('action','=','create_topic')
                                                ->where('result','=','1');
            }
            else{
                $count_create_topicOk = Access::where('entity_key','=',$request->entity_key)
                                            ->where('action','=','create_topic')
                                            ->where('result','=','1');
            }

            if(!empty($request->dayStart) && !empty($request->dayEnd) ){

                $count_create_topicOk = $count_create_topicOk->where('date', '>=', $request->dayStart.' 00:00:00')
                                                             ->where('date', '<=', $request->dayEnd.' 23:59:59');
            }
            else{
                $count_create_topicOk = $count_create_topicOk->where('date','>=', Carbon::now()->subDays(29)->toDateString());
            }

            $count_create_topicOk = $count_create_topicOk->select(DB::raw('count(*) as createTopicOk'), DB::raw('DATE(date) as day'))
                                                        ->groupBy('day')
                                                        ->pluck('createTopicOk','day')
                                                        ->toArray();
            $keys = [];
            $createTopicOk = array_fill(0, count($days), 0);

            if(isset($count_create_topicOk) && count($count_create_topicOk) > 0){
                foreach($days as $dayKey => $select_day){
                    foreach ($count_create_topicOk as $day => $countCreateTopicOk) {
                        if (strcmp($select_day, $day) == 0)
                            array_push($keys, $dayKey);
                    }
                }
                foreach($days as $dayKey => $select_day){
                    foreach ($keys as $key => $value) {
                        if($dayKey == $value){
                            $createTopicOk[$dayKey] = $count_create_topicOk[$select_day];
                        }
                    }
                }

            }
            //create topic Ko
            if($request->entity_key == '0'){
                $count_create_topicKo = Access::where('action','=','create_topic')
                                                ->where('result','=','0');
            }
            else{
                $count_create_topicKo = Access::where('entity_key','=',$request->entity_key)
                                                ->where('action','=','create_topic')
                                                ->where('result','=','0');
            }

            if(!empty($request->dayStart) && !empty($request->dayEnd) ){

                $count_create_topicKo = $count_create_topicKo->where('date', '>=', $request->dayStart.' 00:00:00')
                                                                ->where('date', '<=', $request->dayEnd.' 23:59:59');
            }
            else{
                $count_create_topicKo = $count_create_topicKo->where('date','>=', Carbon::now()->subDays(29)->toDateString());
            }

             $count_create_topicKo = $count_create_topicKo->select(DB::raw('count(*) as createTopicKo'), DB::raw('DATE(date) as day'))
                                                        ->groupBy('day')
                                                        ->pluck('createTopicKo','day')
                                                        ->toArray();

            $keys = [];
            $createTopicKo = array_fill(0, count($days), 0);

            if(isset($count_create_topicKo) && count($count_create_topicKo) > 0){
                foreach($days as $dayKey => $select_day){
                    foreach ($count_create_topicKo as $day => $countCreateTopicKo) {
                        if (strcmp($select_day, $day) == 0)
                            array_push($keys, $dayKey);
                    }
                }
                foreach($days as $dayKey => $select_day){
                    foreach ($keys as $key => $value) {
                        if($dayKey == $value){
                            $createTopicKo[$dayKey] = $count_create_topicKo[$select_day];
                        }
                    }
                }
            }

            //Show topic Ok
            if($request->entity_key == '0'){
                $count_show_topicOk = Access::where('action','=','topic_show')
                    ->where('result','=','1');
            }
            else{
                $count_show_topicOk = Access::where('entity_key','=',$request->entity_key)
                    ->where('action','=','topic_show')
                    ->where('result','=','1');
            }

            if(!empty($request->dayStart) && !empty($request->dayEnd) ){

                $count_show_topicOk = $count_show_topicOk->where('date', '>=', $request->dayStart.' 00:00:00')
                                                            ->where('date', '<=', $request->dayEnd.' 23:59:59');
            }
            else{
                $count_show_topicOk = $count_show_topicOk->where('date','>=', Carbon::now()->subDays(29)->toDateString());
            }

            $count_show_topicOk = $count_show_topicOk->select(DB::raw('count(*) as showTopicOk'), DB::raw('DATE(date) as day'))
                                                         ->groupBy('day')
                                                         ->pluck('showTopicOk','day')
                                                        ->toArray();

            $keys = [];
            $showTopicOk = array_fill(0, count($days), 0);

            if(isset($count_show_topicOk) && count($count_show_topicOk) > 0){
                foreach($days as $dayKey => $select_day){
                    foreach ($count_show_topicOk as $day => $countShowTopicOk) {
                        if (strcmp($select_day, $day) == 0)
                            array_push($keys, $dayKey);
                    }
                }
                foreach($days as $dayKey => $select_day){
                    foreach ($keys as $key => $value) {
                        if($dayKey == $value){
                            $showTopicOk[$dayKey] = $count_show_topicOk[$select_day];
                        }
                    }
                }
            }

            //Show topic Ko
            if($request->entity_key == '0'){
                $count_show_topicKo = Access::where('action','=','topic_show')
                                            ->where('result','=','0');
            }
            else{
                $count_show_topicKo = Access::where('entity_key','=',$request->entity_key)
                                            ->where('action','=','topic_show')
                                            ->where('result','=','0');
            }

            if(!empty($request->dayStart) && !empty($request->dayEnd) ){

                $count_show_topicKo = $count_show_topicKo->where('date', '>=', $request->dayStart.' 00:00:00')
                    ->where('date', '<=', $request->dayEnd.' 23:59:59');
            }
            else{
                $count_show_topicKo = $count_show_topicKo->where('date','>=', Carbon::now()->subDays(29)->toDateString());
            }

            $count_show_topicKo = $count_show_topicKo->select(DB::raw('count(*) as showTopicKo'), DB::raw('DATE(date) as day'))
                                                        ->groupBy('day')
                                                        ->pluck('showTopicKo','day')
                                                        ->toArray();

            $keys = [];
            $showTopicKo = array_fill(0, count($days), 0);

            if(isset($count_show_topicKo) && count($count_show_topicKo) > 0){
                foreach($days as $dayKey => $select_day){
                    foreach ($count_show_topicKo as $day => $countShowTopicKo) {
                        if (strcmp($select_day, $day) == 0)
                            array_push($keys, $dayKey);
                    }
                }
                foreach($days as $dayKey => $select_day){
                    foreach ($keys as $key => $value) {
                        if($dayKey == $value){
                            $showTopicKo[$dayKey] = $count_show_topicKo[$select_day];
                        }
                    }
                }
            }

            return response()->json(['days'=> $days , 'loginOk' => $loginOk, 'loginKo' => $loginKo, 'registerOk'=>$registerOk, 'registerKo'=>$registerKo,'passwordRecoveryOk'=>$passwordRecoveryOk, 'passwordRecoveryKo'=>$passwordRecoveryKo, 'createTopicOk'=>$createTopicOk, 'createTopicKo'=>$createTopicKo, 'showTopicKo'=>$showTopicKo, 'showTopicOk'=>$showTopicOk], 200);

        }
        catch(Exception $e) {
            return response()->json(['error' => 'Failed to retrieve the access list'], 500);
        }
        return response()->json(['error' => 'Unauthorized' ], 401);
    }

}

