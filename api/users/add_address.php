<?php


require_once "../../classes/Database.php";

require_once "../../classes/Auth.php";

require_once "../../helpers/response.php";

require_once "../../helpers/validator.php";

require_once "../../config/cors.php";



$auth=new Auth();



if(!$auth->check())
{

response(false,"Login required");

}



$data=json_decode(

file_get_contents("php://input"),

true

);



// Validate required fields
$title = clean($data["title"] ?? "");
$address = clean($data["address"] ?? "");
$city = clean($data["city"] ?? "");
$country = clean($data["country"] ?? "");
$phone = clean($data["phone"] ?? "");

if(
!required($title) ||
!required($address) ||
!required($city) ||
!required($country)
)
{

response(false, "Title, address, city, and country are required");

}


$db=new Database();

$conn=$db->connect();


$stmt=$conn->prepare(

"INSERT INTO addresses

(
user_id,
title,
address,
city,
country,
phone
)

VALUES
(?,?,?,?,?,?)"

);


$result=$stmt->execute([

$auth->userId(),

$title,

$address,

$city,

$country,

$phone

]);


response(

$result,
$result ? "Address added" : "Failed"

);


?>