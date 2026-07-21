<?php


require_once "../../classes/Database.php";

require_once "../../classes/User.php";

require_once "../../helpers/response.php";

require_once "../../helpers/validator.php";

require_once "../../config/cors.php";

require_once "../../middleware/rate_limit.php";


$data=json_decode(
file_get_contents("php://input"),
true
);



$name = clean($data["name"] ?? "");

$email = clean($data["email"] ?? "");

$password =$data["password"] ?? "";



if(
!required($name)
||
!required($email)
||
!required($password)
)
{

response(
false,
"Required fields missing"
);

}

if(strlen($password) < 8)
{

response(
false,
"Password must be at least 8 characters"
);

}

if(!preg_match('/[A-Z]/', $password))
{

response(
false,
"Password must contain at least one uppercase letter"
);

}

if(!preg_match('/[a-z]/', $password))
{

response(
false,
"Password must contain at least one lowercase letter"
);

}

if(!preg_match('/[0-9]/', $password))
{

response(
false,
"Password must contain at least one number"
);

}



if(!validEmail($email))
{

response(
false,
"Invalid email"
);

}

// Rate limiting
$limitKey = $_SERVER['REMOTE_ADDR'] . "_register";

if(!rateLimit($limitKey, 3, 3600))
{

response(
false,
"Too many registration attempts. Try again later"
);

}


$db=new Database();

$conn=$db->connect();



$user=new User($conn);



if($user->findByEmail($email))
{

response(
false,
"Email already exists"
);

}



$result=$user->create(

$name,

$email,

$password

);



if($result)
{

response(
true,
"Account created"
);

}



response(
false,
"Registration failed"
);


?>