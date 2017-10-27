<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComponentServer extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'component_servers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'component_id', 'server_id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at','updated_at','deleted_at'];

    public function component(){
        return $this->belongsTo('App\Component');
    }
    public function server(){
        return $this->belongsTo('App\Server');
    }
    public function datasCollections(){
        return $this->hasMany('App\DataCollection');
    }

}
