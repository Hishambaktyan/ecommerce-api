<?php

header("Content-Type: application/json");
echo bin2hex(random_bytes(32));

echo json_encode([
    "status" => true,
    "message" => "E-commerce API Running"
]);

?>