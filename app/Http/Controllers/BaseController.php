<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected $service;
    protected $resource;

    public function __construct($service, $resource)
    {
        $this->service = $service;
        $this->resource = $resource;
    }

    protected function success($message = 'success', $data = [], $code = 200){

        $response = [];
        $response += [
            'message' => $message
        ];

        if(!empty($data)){
            $response += [
                'data' => $data
            ];
        }

        return response()->json($response, $code);
    }
}
