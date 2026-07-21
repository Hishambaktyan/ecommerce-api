<?php


require_once "../../classes/Database.php";
require_once "../../classes/Product.php";
require_once "../../helpers/response.php";
require_once "../../config/cors.php";



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



$data=$product->find($id);



if(!$data)
{

response(
false,
"Product not found"
);

}



response(

true,

"Product details",

$data

);


?>