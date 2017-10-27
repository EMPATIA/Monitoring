<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataCollection extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'data_collections';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     *
     */
    protected $fillable = ['id', 'component_server_id','memory_used', 'read_sector', 'read_byte','write_sector','write_byte'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at','updated_at','deleted_at'];

    public function cpus(){
        return $this->hasMany('App\Cpu');
    }
    public function componentServer(){
        return $this->belongsTo('App\ComponentServer');
    }
}
