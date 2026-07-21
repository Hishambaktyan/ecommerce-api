<?php


require_once "../../classes/Database.php";

require_once "../../classes/Category.php";

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



$id=$_GET["id"] ?? null;



if(!$id)
{

response(

false,

"Category id required"

);

}



$db=new Database();

$conn=$db->connect();



$category=new Category($conn);



$result=$category->delete($id);



response(

$result,

$result
?
"Category deleted"
:
"Delete failed"

);


?>