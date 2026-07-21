<?php


require_once "../../classes/Database.php";

require_once "../../classes/Auth.php";

require_once "../../helpers/response.php";

require_once "../../config/cors.php";



$auth=new Auth();



if(!$auth->isAdmin())
{

response(

false,

"Admin only"

);

}



$id=$_POST["id"] ?? null;



if(!$id)
{

response(

false,

"Category id required"

);

}



$db=new Database();

$conn=$db->connect();



$stmt=$conn->prepare(

"UPDATE categories

SET name=?

WHERE id=?

"

);



$result=$stmt->execute([

$_POST["name"],

$id

]);



response(

$result,

$result
?
"Category updated"
:
"Update failed"

);



?>