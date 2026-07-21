<?php


require_once "../../classes/Database.php";

require_once "../../classes/Auth.php";

require_once "../../helpers/response.php";

require_once "../../config/cors.php";


$auth=new Auth();



if(!$auth->check())
{

response(

false,

"Login required"

);

}



$db=new Database();

$conn=$db->connect();



$user_id=$auth->userId();





// بيانات المستخدم

$user=$conn->prepare(

"SELECT 

id,

name,

email,

role,

created_at

FROM users

WHERE id=?

"

);



$user->execute([

$user_id

]);



$userData=$user->fetch();





// العناوين

$addresses=$conn->prepare(

"SELECT *

FROM addresses

WHERE user_id=?

ORDER BY id DESC"

);



$addresses->execute([

$user_id

]);



$userData["addresses"]

=
$addresses->fetchAll();



response(

true,

"Profile",

$userData

);



?>