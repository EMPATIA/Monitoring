<?php
/**
 * Created by PhpStorm.
 * User: Vitor Fonseca
 * Date: 18/04/2017
 * Time: 14:12
 */

namespace App\Jobs;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\TrackingRequest;
use App\Tracking;

class TrackingRequestJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $params;

    public function __construct($data){

      $this->params = $data;

    }

    public function handle(){

      $tableKey=$this->params["table_key"];

      $tracking= Tracking::where('table_key',$tableKey)->get()->last();

      $trackingRequestModel = new TrackingRequest(['table_key' => $tableKey,
                              'method' => $this->params['method'],
                              'module_token' => $this->params['module_token'],
                              'url' => $this->params['url'],
                              'result' => $this->params['result'],
                              'time_start' => $this->params['time_start']

      ]);

      $trackingRequest = $tracking->trackingRequest()->save($trackingRequestModel);
    }
}
