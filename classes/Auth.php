<?php

require_once __DIR__ . "/Jwt.php";
require_once __DIR__ . "/../config/env.php";


class Auth
{

    private $secret;
    private $payload = null;


    public function __construct()
    {

        $this->secret = getenv("JWT_SECRET");

        $token = $this->getTokenFromHeader();

        if ($token) {
            $this->payload = Jwt::decode($token, $this->secret);
        }
    }


    private function getTokenFromHeader()
    {

        $headers = function_exists('getallheaders')
            ? getallheaders()
            : [
                'Authorization' => $_SERVER['HTTP_AUTHORIZATION']
                    ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
                    ?? null
            ];

        $authHeader = $headers["Authorization"]
            ?? $headers["authorization"]
            ?? null;

        if (!$authHeader) {
            return null;
        }

        if (!str_starts_with($authHeader, "Bearer ")) {
            return null;
        }

        return trim(
            substr($authHeader, 7)
        );
    }


    public function login($user)
    {

        $payload = [

            "user_id" => $user["id"],

            "role" => $user["role"],

            "iat" => time(),

            "exp" => time() + (60 * 60 * 24 * 7)

        ];

        return Jwt::encode($payload, $this->secret);
    }


    public function logout()
    {

        // JWT عديم الحالة (stateless) — مفيش حاجة يعملها السيرفر فعليًا
        // فلاتر بتمسح الـ token المحفوظ عندها محليًا وخلاص
        return true;
    }


    public function check()
    {

        return $this->payload !== null
            && isset($this->payload["user_id"]);
    }


    public function userId()
    {

        return $this->payload["user_id"] ?? null;
    }


    public function isAdmin()
    {

        return isset($this->payload["role"])
            && $this->payload["role"] === "admin";
    }
}
