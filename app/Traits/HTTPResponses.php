<?php

namespace App\Traits;

trait HTTPResponses
{
    public function error($message, $code = 400)
    {
        return response()->json([
            'message' => $message,
        ], $code);
    }

    protected function success($data, $message = null, $code = 200)
    {
        return response()->json([
            'message' => $message,
            'data' => $data
        ], $code);
    }
}
