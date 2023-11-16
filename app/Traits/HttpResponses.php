<?php

namespace App\Traits;

class HttpResponses
{
    protected function success($code = 200, $message = null, $data)
    {
        return response()->json([
            'status' => 'Request was successful.',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function error($code, $message = null, $data)
    {
        return response()->json([
            'status' => 'Error was occurred.',
            'message' => $message,
            'data' => $data
        ], $code);
    }
}
