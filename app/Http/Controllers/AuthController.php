<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * @var DataRequestController
     */


    public function __construct()
    {
        $this->dataRequestContoller=new DataRequestController();
    }


public function generateAuthLink(Request $request){
    $cli      = $request->input ( 'cli' );
    $params = json_encode([$cli,'','Web', 'AE' , 'BN' ,''],true);

    $responseData=$this->dataRequestContoller->getResponse('getIVRGeneratedLink',$params);
    return $responseData['longcode'];
}
}
