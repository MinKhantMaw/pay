<?php

namespace App\Helpers;

class ApiResponse
{
    public static function success($message, $data, $status)
    {
        return response()->json([
            'message' => $message,
            '$data' => $data,
            'status' => $status
        ]);
    }

    public static function fail($message, $data, $status)
    {
        return response()->json([
            'message' => $message,
            '$data' => $data,
            'status' => $status
        ]);
    }
}
