<?php


require_once "../../classes/Database.php";

require_once "../../classes/User.php";

require_once "../../classes/Auth.php";

require_once "../../helpers/response.php";

require_once "../../config/cors.php";

require_once "../../middleware/rate_limit.php";


$data=json_decode(
file_get_contents("php://input"),
true
);



$email=$data["email"] ?? "";

$password=$data["password"] ?? "";

$limitKey =
$_SERVER['REMOTE_ADDR']
."_"
.$email;


if(!rateLimit($limitKey))
{

    response(
        false,
        "Too many login attempts. Try again later"
    );

}


$db=new Database();

$conn=$db->connect();



$userModel=new User($conn);



$user=$userModel->findByEmail($email);




if(
!$user ||
!password_verify(
$password,
$user["password"]
)
)
{

response(
false,
"Invalid login information"
);

}



$auth=new Auth();


$token = $auth->login($user);



response(

true,

"Login successful",

[

"token"=>$token,

"id"=>$user["id"],

"name"=>$user["name"],

"role"=>$user["role"]

]

);


?>