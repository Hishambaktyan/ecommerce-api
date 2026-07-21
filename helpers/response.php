<?php


function response(
    $status,
    $message,
    $data = null
)
{

    http_response_code(
        detectHttpCode($status, $message)
    );

    header(
        "Content-Type: application/json"
    );


    echo json_encode([

        "status"=>$status,

        "message"=>$message,

        "data"=>$data

    ]);


    exit;

}


function detectHttpCode($status, $message)
{

    if ($status) {
        return 200;
    }

    $message = strtolower($message);

    $map = [
        "login required"          => 401,
        "unauthorized"             => 401,
        "invalid login"            => 401,
        "invalid token"            => 401,
        "admin access required"    => 403,
        "admin only"                => 403,
        "not found"                => 404,
        "too many"                 => 429,
        "already exists"           => 409,
        "already taken"            => 409,
    ];

    foreach ($map as $keyword => $code) {
        if (str_contains($message, $keyword)) {
            return $code;
        }
    }

    return 400;

}


?>