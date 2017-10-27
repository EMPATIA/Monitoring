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
use App\Server;


class ServerJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $ip;

    public function __construct($serverIp){

      $this->ip = $serverIp;

    }

    public function handle(){
      Server::create([
          'ip' => $this->ip,
      ]);
    }
}
