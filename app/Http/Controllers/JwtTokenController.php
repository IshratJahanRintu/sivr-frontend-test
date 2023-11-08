<?php

namespace App\Http\Controllers;

use Firebase\JWT\JWT;

class JwtTokenController extends Controller
{


    public function createJwtAuthToken($user)
    {
        $plan = null;
        $cli = $user['cli'];
        $key = env('JWT_SECRET');
        $createTime = time();
        $notBeforeTime = time();
        $expireTime = time() + LOGIN_DURATION;
        $payload = array(
            "iss" => VIVR_TOKEN_ISSUER,
            "aud" => VIVR_TOKEN_ISSUER,
            "iat" => $createTime,
            "nbf" => $notBeforeTime,
            "exp" => $expireTime,
            "cli" => $cli,
            "plan" => $plan,
            "uid" => md5($cli . $plan . $createTime)
        );
        return JWT::encode($payload,$key,'HS256');
    }



}
