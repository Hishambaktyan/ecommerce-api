<?php


class Jwt
{

    public static function encode($payload, $secret)
    {

        $header = [
            "alg" => "HS256",
            "typ" => "JWT"
        ];

        $headerEncoded = self::base64UrlEncode(
            json_encode($header)
        );

        $payloadEncoded = self::base64UrlEncode(
            json_encode($payload)
        );

        $signature = hash_hmac(
            "sha256",
            $headerEncoded . "." . $payloadEncoded,
            $secret,
            true
        );

        $signatureEncoded = self::base64UrlEncode($signature);

        return $headerEncoded . "." . $payloadEncoded . "." . $signatureEncoded;

    }


    public static function decode($token, $secret)
    {

        $parts = explode(".", $token);

        if (count($parts) !== 3) {
            return null;
        }

        list($headerEncoded, $payloadEncoded, $signatureEncoded) = $parts;

        $expectedSignature = hash_hmac(
            "sha256",
            $headerEncoded . "." . $payloadEncoded,
            $secret,
            true
        );

        $expectedSignatureEncoded = self::base64UrlEncode($expectedSignature);

        if (!hash_equals($expectedSignatureEncoded, $signatureEncoded)) {
            return null;
        }

        $payload = json_decode(
            self::base64UrlDecode($payloadEncoded),
            true
        );

        if (!$payload) {
            return null;
        }

        if (isset($payload["exp"]) && time() > $payload["exp"]) {
            return null;
        }

        return $payload;

    }


    private static function base64UrlEncode($data)
    {

        return rtrim(
            strtr(
                base64_encode($data),
                "+/",
                "-_"
            ),
            "="
        );

    }


    private static function base64UrlDecode($data)
    {

        return base64_decode(
            strtr($data, "-_", "+/")
        );

    }

}


?>