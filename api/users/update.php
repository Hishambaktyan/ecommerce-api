<?php


require_once "../../classes/Database.php";

require_once "../../classes/Auth.php";

require_once "../../helpers/response.php";

require_once "../../helpers/validator.php";

require_once "../../config/cors.php";



$auth=new Auth();



if(!$auth->check())
{

response(

false,

"Login required"

);

}



$data=json_decode(

file_get_contents("php://input"),

true

);



$name=clean(

$data["name"] ?? ""

);


$password=

$data["password"] ?? null;



$user_id=$auth->userId();




$db=new Database();

$conn=$db->connect();



if($password)
{


$hash=password_hash(

$password,

PASSWORD_DEFAULT

);



$stmt=$conn->prepare(

"UPDATE users

SET

name=?,

password=?

WHERE id=?

"

);



$result=$stmt->execute([


$name,

$hash,

$user_id


]);



}

else
{


$stmt=$conn->prepare(

"UPDATE users

SET

name=?

WHERE id=?

"

);



$result=$stmt->execute([


$name,

$user_id


]);


}




response(

$result,

$result
?
"Profile updated"
:
"Update failed"

);


?>