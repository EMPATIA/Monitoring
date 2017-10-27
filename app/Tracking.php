<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Tracking extends Model
{


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'trackings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'is_logged','auth_token','user_key','ip','url','site_key','method','session_id','table_key','time_start','time_end', 'message'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at','updated_at'];

    /**
     * The servers that belongs to the component.
     */

    public function trackingRequest(){
        return $this->hasMany('App\TrackingRequest');
    }

}
