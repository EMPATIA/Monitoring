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
use App\Tracking;

class TrackingJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $params;

    public function __construct($data){

        $this->params = $data;
    }

    public function handle(){

      Tracking::create([
          'is_logged' => $this->params["is_logged"],
          'auth_token' => $this->params["auth_token"],
          'user_key' => $this->params["user_key"],
          'ip' => $this->params["ip"],
          'url' => $this->params["url"],
          'site_key' => $this->params["site_key"],
          'method' => $this->params["method"],
          'session_id' => $this->params["session_id"],
          'table_key' => $this->params["table_key"],
          'time_start' => $this->params["time_start"],
          'time_end' => $this->params["time_end"] ?? null,
          'message' => $this->params["message"] ?? '',
      ]);
    }
}
