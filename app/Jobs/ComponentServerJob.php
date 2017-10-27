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
use App\ComponentServer;


class ComponentServerJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $server;
    protected $componentId;

    public function __construct($server, $componentId){

      $this->server = $server;
      $this->componentId = $componentId;

    }

    public function handle(){

      $compServModel = new ComponentServer(['component_id' => $this->componentId]);
      $this->server->componentServers()->save($compServModel);

    }
}
