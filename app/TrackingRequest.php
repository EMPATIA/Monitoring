<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class TrackingRequest extends Model
{


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tracking_requests';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'table_key','method','module_token','url','result', 'tracking_id', 'time_start', 'time_end', 'message'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at','updated_at'];

    /**
     * The servers that belongs to the component.
     */

    public function tracking(){
        return $this->hasMany('App\Tracking');
    }

}
