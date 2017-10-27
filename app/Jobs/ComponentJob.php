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
use App\Component;


class ComponentJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $xModuleToken;

    public function __construct($token){

      $this->xModuleToken = $token;

    }

    public function handle(){
      Component::create([
          'name' => $this->xModelToken,

      ]);
    }
}
