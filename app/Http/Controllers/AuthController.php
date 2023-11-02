<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\JwtTokenController;
class AuthController extends Controller
{
    /**
     * @var DataRequestController
     */

    private $cacheKey;
    private $cache;
    /**
     * @var DataRequestController
     */
    private $dataRequestContoller;
    /**
     * @var DataProviderController
     */
    private $dataProvider;
    /**
     * @var DataLogController
     */
    private $dataLogger;
    /**
     * @var ResponseDataController
     */
    private $responseManager;
    /**
     * @var \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    private $response;

    public function __construct()
    {
        $this->dataRequestContoller=new DataRequestController();
        $this->dataProvider=new DataProviderController();
        $this->dataLogger=new DataLogController();
        $this->cache=new CacheController();
        $this->responseManager = new ResponseDataController();
        $this->response=response();
    }


public function generateAuthLink(Request $request){
    $cli      = $request->input ( 'cli' );
    $params = json_encode([[$cli,'','Web', 'AE' , 'BN' ,'']],true);
    $responseData=$this->dataRequestContoller->getResponse(GET_LOGIN_GENERATE_CODE,$params);

    return $responseData['longcode'];
}

public function loginWithCode(Request $request){
    $this->validate ( $request , [
        'authCode' => 'required|alpha_num|size:12' ,
        'cli' => 'required|numeric|digits:11'
    ] );

    $authCode = $request->input ( 'authCode' );
  $cli=$request->input ( 'cli' );
    $params=json_encode(array($authCode,$cli));
    $responseData=$this->dataRequestContoller->getResponse(GET_USER_FROM_AUTH_CODE_FUNCTION,$params);
$userData=null;
    if ($responseData !== 0) {

        $userData= reset($responseData);

    }

    if ( $userData != null ) {

        return $this->generateTokenData ( $request , $userData , IVR_SOURCE );
    }
    return $this->responseManager->failedLoginResponse ( $this->response );




}
    private function generateTokenData ( $request , $userData , $source )
    {


        $this->jwtTokenObject = new JwtTokenController();
        $cli                  = substr ( $userData[ 'cli' ] , - 10 );
        $userData['session_id' ] = $cli . time ();
        $isRegistered             = $this->checkUserRegistered ( $cli , $userData[ 'session_id' ] );
        $isLogged                 = $this->dataLogger->createCustomerLogData ( $userData , $request->ip () , $source , $isRegistered );
        if ( $isLogged ) {
            $token          = $this->jwtTokenObject->getJwtAuthToken ( $userData );
            $this->cacheKey = rand ( 100000 , 999999 ) . $userData[ 'session_id' ];
            if ( $token != null ) {
                $this->setInitialCacheData ( $userData , $token );

                return $this->responseManager->successLoginResponse ( $this->response , $token , $this->cacheKey );
            }
        }
        return $this->responseManager->errorLoginResponse ( $this->response );
    }


    private function checkUserRegistered ( $cli , $sessionId )
    {


        $isRegistered = NO;
        $apiResult    = $this->dataProvider->getApiResult ( $cli , $sessionId , GET_USER_BY_PHONE_API );
        if ( is_array ( $apiResult ) ) {
            $apiResult = reset ( $apiResult );
            if ( ! empty( $apiResult[ 'responseCode' ] ) ) {
                if ( $apiResult[ 'responseCode' ] == 100 ) {
                    $isRegistered = YES;
                }
            }
        }
        return $isRegistered;
    }
    public function setInitialCacheData ( $userData , $token )
    {
        $key = $this->cacheKey;

        $this->cache->setCacheData ( "ivrId" . $key , $userData[ 'ivr_id' ] );
        $this->cache->setCacheData ( "cli" . $key , substr ( $userData[ 'cli' ] , - 10 ) );
        $this->cache->setCacheData ( "language" . $key , $userData[ 'language' ] );
        $this->cache->setCacheData ( "sessionId" . $key , $userData[ 'session_id' ] );
        $this->cache->setCacheData ( "sound" . $key , 'ON' );
        $this->cache->setCacheData ( "did" . $key , $userData[ 'did' ] );
        $this->cache->setCacheData ( "startTime" . $key , time () );
        $this->cache->setCacheData ( "action" . $key , DEFAULT_ACTION );
        $this->cache->setCacheData ( "firstGreeting" . $key , true );
        $this->cache->setCacheData ( "tokenData" . $key , $token );
        $this->cache->setCacheData ( LAST_REQUESTED_PAGE . $this->cacheKey , DEFAULT_PAGE );
        $this->cache->setCacheData ( REQUEST_COUNT . $this->cacheKey , "1" );
        $this->cache->setCacheData ( "requestAmount" . $this->cacheKey , "1" );
    }
}
