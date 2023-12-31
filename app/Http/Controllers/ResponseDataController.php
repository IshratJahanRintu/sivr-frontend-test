<?php

namespace App\Http\Controllers;

class ResponseDataController
{
    private $errorCode;
    private $message;

    private function responseJsonData($response, $httpStatusCode, $data = null)
    {

        return $response->json([
            'errorCode' => $this->errorCode,
            'message' => $this->message,
            'data' => $data
        ], $httpStatusCode);
    }




    public function successLoginResponse($response, $token, $cacheKey)
    {
        $this->errorCode = 1002;
        $this->message = 'Logged In Successfully.';
        $data = array(
            'token' => $token,
            'key' => $cacheKey
        );
        return $this->responseJsonData($response, 200, $data);
    }

    public function failedLoginResponse($response)
    {
        $this->errorCode = 1003;
        $this->message = 'Unauthorized. User Not Found.';
        return $this->responseJsonData($response, 401);
    }


    public function errorLoginResponse($response)
    {
        $this->errorCode = 1004;
        $this->message = 'Login Failed.';
        return $this->responseJsonData($response, 401);
    }

}
