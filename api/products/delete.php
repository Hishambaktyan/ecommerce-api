<?php


require_once "../../classes/Database.php";

require_once "../../classes/Product.php";

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
"Product id required"
);

}



$db=new Database();

$conn=$db->connect();



$product=new Product($conn);



$result=$product->delete($id);



response(

$result,

$result
?
"Product deleted"
:
"Delete failed"

);


?>