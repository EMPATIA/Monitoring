<?php

namespace App\ComModules;

use App\One\One;
use Exception;
use Cache;
use App\Events\EventNotify;

class EMPATIA
{
    /**
     * @return array with all users
     * @throws Exception
     */
    public static function getUsers()
    {
        $response = ONE::post([
            'component' => 'empatia',
            'api'       => 'auth',
            'method'    => 'listNames',
            'params' => [
                'analytics'=> 1,
                ]
        ]);
        if($response->statusCode() != 200){
            throw new Exception(trans("comModulesEMPATIA.errorGetUsers"));
        }
        return $response->json()->users;
    }

    /**
     * @return array with all cbs
     * @throws Exception
     */
    public static function getCbs()
    {
        $response = ONE::get([
            'component' => 'empatia',
            'api'       => 'cb',
            'method'    => 'list',
            'params' => [
                'analytics'=> 1,
            ]
        ]);

        if($response->statusCode() != 200){
            throw new Exception(trans("comModulesEMPATIA.errorGetCbs"));
        }
        return $response->json()->cbs;
    }
    /**
     * @return array with all getSites
     * @throws Exception
     */
    public static function getSites()
    {
        $response = ONE::get([
            'component' => 'empatia',
            'api'       => 'site',
            'method'    => 'list',
            'params' => [
                'analytics'=> 1,
            ]
        ]);
        if($response->statusCode() != 200){
            throw new Exception(trans("comModulesEMPATIA.errorGetSites"));
        }
        return $response->json()->sites;
    }


}