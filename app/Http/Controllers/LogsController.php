<?php

namespace App\Http\Controllers;

use App\Log;
use App\One\One;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Exception;

/**
 * Class LogsController
 * @package App\Http\Controllers
 */
class LogsController extends Controller
{

    protected $keysRequired = [
        'component',
        'type',
        'ip',
        'message',
        'url'
    ];

    /**
     * Requests a list of logs.
     * Returns the list of logs.
     *
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {

        ONE::verifyToken($request);

        try {
            $logs = Log::get();
            return response()->json(['data' => $logs], 200);
        }
        catch(Exception $e) {
            return response()->json(['error' => 'Failed to retrieve the log list'], 500);
        }

        return response()->json(['error' => 'Unauthorized' ], 401);
    }

    /**
     * Request a specific log.
     * Returns the details of the requested log.
     *
     * @param $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        ONE::verifyToken($request);

        try {
            $log = Log::findOrFail($id);
            return response()->json($log, 200);
        }
        catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Log not Found'], 404);
        }

        return response()->json(['error' => 'Unauthorized' ], 401);
    }

    /**
     * Store a newly created log in storage.
     * Returns the details of the newly created log.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $userKey = ONE::verifyLogin($request);

        ONE::verifyKeysRequest($this->keysRequired, $request);

        try {
            $log = Log::create(['component' => $request->json('component'),
                                'type' => $request->json('type'),
                                'user' => $userKey ?? "anonymous",
                                'ip' => $request->json('ip'),
                                'message' => $request->json('message'),
                                'url' => $request->json('url')]);
            return response()->json($log, 201);
        }
        catch(QueryException $e){
            return response()->json(['error' => 'Failed to store new log'], 500);
        }

        return response()->json(['error' => 'Unauthorized' ], 401);
    }

}
