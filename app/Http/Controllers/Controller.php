<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function sendResponse($result, $message, $code)
    {
        $response = [
            "responseCode" => $code,
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];


        return response()->json($response, 200);
    }


    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            "responseCode" => $code,
            'success' => false,
            'message' => $error,

        ];


        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }
}
