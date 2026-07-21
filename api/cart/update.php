<?php


require_once "../../classes/Database.php";

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



$cart_id =
$data["cart_id"] ?? null;


$quantity =
$data["quantity"] ?? null;



if(!$cart_id || !$quantity)
{

    response(
        false,
        "Cart id and quantity required"
    );

}



if($quantity < 1)
{

    response(
        false,
        "Invalid quantity"
    );

}




$db=new Database();

$conn=$db->connect();



// جلب المنتج والتأكد من المخزون

$stmt=$conn->prepare(

"SELECT 

products.stock

FROM cart

JOIN products

ON products.id = cart.product_id

WHERE cart.id=?

AND cart.user_id=?"

);



$stmt->execute([

$cart_id,

$auth->userId()

]);



$item=$stmt->fetch();



if(!$item)
{

    response(
        false,
        "Cart item not found"
    );

}



if($quantity > $item["stock"])
{

    response(
        false,
        "Not enough stock"
    );

}




$update=$conn->prepare(

"UPDATE cart

SET quantity=?

WHERE id=?

AND user_id=?"

);



$result=$update->execute([

$quantity,

$cart_id,

$auth->userId()

]);




response(

$result,

$result
?
"Cart updated"
:
"Update failed"

);


?>