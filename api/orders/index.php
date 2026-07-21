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



$stmt=$conn->prepare(

"SELECT *

FROM orders

WHERE user_id=?

ORDER BY id DESC"

);



$stmt->execute([

$auth->userId()

]);



$data=$stmt->fetchAll();



response(

true,

"Orders list",

$data

);



?>