<?php


require_once "../../classes/Database.php";

require_once "../../classes/Cart.php";

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



$id=$_GET["id"] ?? null;



if(!$id)
{

response(

false,

"Cart id required"

);

}



$db=new Database();

$conn=$db->connect();



$cart=new Cart($conn);



$result=$cart->remove(

$id,

$auth->userId()

);



response(

$result,

$result
?
"Removed from cart"
:
"Failed"

);



?>