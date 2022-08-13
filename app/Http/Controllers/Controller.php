<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function errorJSON($message, $statusCode = 500, $errorCode = 'ERROR')
    {
        return response()->json(["error" => $message, 'code' => $errorCode], $statusCode);
    }

    public function errorInertia($message, $routeName = null, $routeParams = [])
    {
        if ($routeName) return redirect()->route($routeName,$routeParams)->with("error", $message);
        else return redirect()->back()->with("error", $message);
    }

    public function successInertia($message, $routeName = null, $routeParams = [], $data = null)
    {
        if ($routeName) return redirect()->route($routeName, $routeParams)->with("success", $message)->with("dados", $data);
        else return redirect()->back()->with("success", $message)->with("dados", $data);
    }

    public function responseJSON($data = [], $statusCode = 200)
    {
        return response()->json([
            "data" => $data
        ], $statusCode);
    }
}
