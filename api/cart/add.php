<?php


require_once "../../classes/Database.php";

require_once "../../classes/Cart.php";

require_once "../../classes/Auth.php";

require_once "../../helpers/response.php";

require_once "../../config/cors.php";



$auth = new Auth();



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



$product_id =
$data["product_id"] ?? null;


$quantity =
$data["quantity"] ?? 1;

if($quantity < 1)
{
    response(
        false,
        "Invalid quantity"
    );
}



if(!$product_id)
{

response(

false,

"Product id required"

);

}




$user_id=$auth->userId();



$db=new Database();

$conn=$db->connect();

// check product stock

$productCheck=$conn->prepare(

"SELECT stock 
 FROM products
 WHERE id=?"

);


$productCheck->execute([

$product_id

]);


$product=$productCheck->fetch();



if(!$product)
{

response(
false,
"Product not found"
);

}



if($quantity > $product["stock"])
{

response(
false,
"Not enough stock"
);

}



$check=$conn->prepare(

"SELECT *

FROM cart

WHERE user_id=?

AND product_id=?

"

);


$check->execute([

$user_id,

$product_id

]);



$existing=$check->fetch();



if($existing)
{


$stmt=$conn->prepare(

"UPDATE cart

SET quantity = quantity + ?

WHERE id=?

"

);



$result=$stmt->execute([

$quantity,

$existing["id"]

]);



}

else
{


$cart=new Cart($conn);



$result=$cart->add(

$user_id,

$product_id,

$quantity

);


}



response(

$result,

$result
?
"Added to cart"
:
"Failed"

);


?>