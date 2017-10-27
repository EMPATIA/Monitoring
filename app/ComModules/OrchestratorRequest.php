<?php

namespace App\ComModules;

use App\One\One;
use Exception;
use Cache;
use App\Events\EventNotify;

class OrchestratorRequest
{

    public static function askCheckToken($xModuleToken)
    {

        $response = one::get(
            [
                "component" => "orchestrator",
                "api" => "module",
                "method" => "checkToken",
            ]
        );

        if ($response->statusCode() != 200) {
            throw new Exception("Failed to ask to check token .");
        }


        return $response->content();

    }


}