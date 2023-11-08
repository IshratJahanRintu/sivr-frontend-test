<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataRequestController extends Controller
{



    public function getResponse($method, $params)
    {

        $requestData = array('method' => $method, 'params' => $params);
        $responseData = $this->dataRequest($requestData);
        return json_decode($responseData, true);
    }

    private function dataRequest($postData = [])
    {

        $url = DATA_PROVIDER_URL;


        $requestHeaders = [];
        $headers = ["cache-control: no-cache"];
        $headers = array_merge($headers, $requestHeaders);

        $curlHandler = curl_init();
        curl_setopt($curlHandler, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curlHandler, CURLOPT_TIMEOUT, 15);
        curl_setopt($curlHandler, CURLOPT_URL, $url);
        curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandler, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($curlHandler, CURLOPT_POST, true);
        curl_setopt($curlHandler, CURLOPT_POSTFIELDS, $postData);


        curl_setopt($curlHandler, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curlHandler, CURLOPT_SSL_VERIFYPEER, false);

        $response = trim(curl_exec($curlHandler));

        $err = curl_error($curlHandler);
        return !empty($err) ? null : $response;

    }


}
