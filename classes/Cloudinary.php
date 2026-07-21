<?php

require_once __DIR__ . "/../config/env.php";

class Cloudinary
{

    private $cloud_name;

    private $api_key;

    private $api_secret;



    public function __construct()
    {

        $config = require __DIR__ . "/../config/cloudinary.php";


        $this->cloud_name = $config["cloud_name"];

        $this->api_key = $config["api_key"];

        $this->api_secret = $config["api_secret"];

    }

    public function upload($file)
{

  $allowed = [
        'image/jpeg',
        'image/png',
        'image/webp'
    ];


    if(!in_array($file["type"], $allowed))
    {
        throw new Exception(
            "Invalid image type"
        );
    }

    if($file["size"] > 5 * 1024 * 1024)
{
    throw new Exception(
        "Image size too large"
    );
}

    $url =
    "https://api.cloudinary.com/v1_1/"
    .$this->cloud_name
    ."/image/upload";


    $timestamp = time();


    $signatureString =
    "timestamp=".$timestamp;


    $signature =
    sha1(
        $signatureString .
        $this->api_secret
    );


    $data = [

        "file" =>
        new CURLFile(
            $file["tmp_name"]
        ),

        "api_key" =>
        $this->api_key,

        "timestamp" =>
        $timestamp,

        "signature" =>
        $signature

    ];


    $ch = curl_init();


    curl_setopt(
        $ch,
        CURLOPT_URL,
        $url
    );


    curl_setopt(
        $ch,
        CURLOPT_POST,
        true
    );


    curl_setopt(
        $ch,
        CURLOPT_POSTFIELDS,
        $data
    );


    curl_setopt(
        $ch,
        CURLOPT_RETURNTRANSFER,
        true
    );

    curl_setopt(
    $ch,
    CURLOPT_TIMEOUT,
    30
    );


    curl_setopt(
        $ch,
        CURLOPT_CONNECTTIMEOUT,
        10
        );


    $response =
    curl_exec($ch);


    if($response === false)
{

    throw new Exception(
        curl_error($ch)
    );

}


    curl_close($ch);


    $result =
    json_decode(
        $response,
        true
    );


    if(isset($result["secure_url"]))
    {

        return $result["secure_url"];

    }


    return null;

}



}

?>