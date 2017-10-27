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
use App\DataCollection;
use App\Cpu;


class DataCollectionJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $io;
    protected $memory;
    protected $cpus;
    protected $compServerId;

    public function __construct($io,$memory,$cpus,$compServerId){

      $this->io = $io;
      $this->memory = $memory;
      $this->compServerId = $compServerId;
      $this->cpus = $cpus;

    }

    public function handle(){

      $dataModel = DataCollection::create([
          'component_server_id' => $this->compServerId,
          'memory_used' => $this->memory,
          'read_sector' => $this->io["readSector"],
          'read_byte'  => $this->io["readByte"],
          'write_sector' => $this->io["writeSector"],
          'write_byte' =>  $this->io["writeByte"]
      ]);
      foreach ($this->cpus as $cpu) {
          //create and store new cpu to database
          $cpuModel = new Cpu(['value' => $cpu["value"]]);
          $cpu = $dataModel->cpus()->save($cpuModel);
      }

    }
}
