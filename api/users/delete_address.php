<?php


require_once "../../classes/Database.php";

require_once "../../classes/Auth.php";

require_once "../../helpers/response.php";

require_once "../../config/cors.php";



$auth=new Auth();



if(!$auth->check())
{

response(false,"Login required");

}



$id=$_GET["id"] ?? null;



$db=new Database();

$conn=$db->connect();



$stmt=$conn->prepare(

"DELETE FROM addresses

WHERE id=?

AND user_id=?

"

);



$result=$stmt->execute([

$id,

$auth->userId()

]);



response(

$result,

$result
?
"Address deleted"
:
"Failed"

);


?>