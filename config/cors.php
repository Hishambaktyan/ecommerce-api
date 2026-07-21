<?php

// Restrict CORS to specific origins
$allowed_origins = [
    "http://localhost",
    "http://localhost:3000",
    "http://localhost:5173",
    "https://yourdomain.com"
];

$origin = $_SERVER["HTTP_ORIGIN"] ?? "";

if(in_array($origin, $allowed_origins))
{
    header("Access-Control-Allow-Origin: " . $origin);
}
else
{
    header("Access-Control-Allow-Origin: http://localhost");
}

header(
"Access-Control-Allow-Headers: Content-Type, Authorization"
);

header(
"Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"
);

header("Access-Control-Allow-Credentials: true");


if($_SERVER["REQUEST_METHOD"]=="OPTIONS")
{
    http_response_code(204);
    exit;
}

// Security headers
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Permissions-Policy: geolocation=(), microphone=(), camera=()");

?>