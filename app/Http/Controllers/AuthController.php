<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\JwtTokenController;

class AuthController extends Controller
{

    private $cache;

    private $dataRequestController;

    private $responseManager;

    private $response;

    public function __construct()
    {
        $this->dataRequestController = new DataRequestController();
        $this->cache = new CacheController();
        $this->responseManager = new ResponseDataController();
        $this->response = response();
    }


    public function generateAuthLink(Request $request)
    {
        $cli = $request->input('cli');
        $params = json_encode([[$cli, '', 'Web', 'AE', 'BN', '']], true);
        $responseData = $this->dataRequestController->getResponse(GET_LOGIN_GENERATE_CODE, $params);

        return $responseData['longcode'];
    }

    public function loginWithCode(Request $request)
    {

        $this->validate($request, [
            'authCode' => 'required|alpha_num|size:12',
            'cli' => 'required|numeric|digits:11'
        ]);

        $authCode = $request->input('authCode');
        $cli = $request->input('cli');
        $params = json_encode(array($authCode, $cli));
        $responseData = $this->dataRequestController->getResponse(GET_USER_FROM_AUTH_CODE_FUNCTION, $params);
        $userData = $responseData !== 0 ? reset($responseData) : null;

        if ($userData != null) {

            return $this->generateTokenData($userData);
        }
        return $this->responseManager->failedLoginResponse($this->response);

    }

    private function generateTokenData($userData)
    {

        $this->jwtTokenObject = new JwtTokenController();
        $cli = substr($userData['cli'], -10);
        $userData['session_id'] = $cli . time();

        $token = $this->jwtTokenObject->createJwtAuthToken($userData);
        $cacheKey = rand(100000, 999999) . $userData['session_id'];
        if ($token != null) {
            $this->setInitialCacheData($userData, $token, $cacheKey);
            return $this->responseManager->successLoginResponse($this->response, $token, $cacheKey);
        }
        return $this->responseManager->errorLoginResponse($this->response);
    }


    public function setInitialCacheData($userData, $token,$cacheKey)
    {


        $this->cache->setCacheData("ivrId" . $cacheKey, $userData['ivr_id']);
        $this->cache->setCacheData("cli" . $cacheKey, substr($userData['cli'], -10));
        $this->cache->setCacheData("language" . $cacheKey, $userData['language']);
        $this->cache->setCacheData("sessionId" . $cacheKey, $userData['session_id']);
        $this->cache->setCacheData("sound" . $cacheKey, 'ON');
        $this->cache->setCacheData("did" . $cacheKey, $userData['did']);
        $this->cache->setCacheData("startTime" . $cacheKey, time());
        $this->cache->setCacheData("action" . $cacheKey, DEFAULT_ACTION);
        $this->cache->setCacheData("firstGreeting" . $cacheKey, true);
        $this->cache->setCacheData("tokenData" . $cacheKey, $token);
        $this->cache->setCacheData(LAST_REQUESTED_PAGE . $cacheKey, DEFAULT_PAGE);
        $this->cache->setCacheData(REQUEST_COUNT . $cacheKey, "1");
        $this->cache->setCacheData("requestAmount" . $cacheKey, "1");
    }
}
