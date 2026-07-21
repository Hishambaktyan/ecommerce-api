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



$id = $_GET["id"] ?? null;



if(!$id)
{

    response(
        false,
        "Order id required"
    );

}



$db = new Database();

$conn = $db->connect();



$user_id = $auth->userId();



// جلب الطلب

$stmt = $conn->prepare(

"SELECT *

FROM orders

WHERE id=?

AND user_id=?"

);



$stmt->execute([

$id,

$user_id

]);



$order = $stmt->fetch();



if(!$order)
{

    response(
        false,
        "Order not found"
    );

}



// جلب المنتجات داخل الطلب

$stmt = $conn->prepare(

"SELECT

order_items.quantity,

order_items.price,

products.name,

products.image


FROM order_items


JOIN products

ON products.id = order_items.product_id


WHERE order_items.order_id=?"

);



$stmt->execute([

$id

]);



$items = $stmt->fetchAll();



$order["items"] = $items;



response(

true,

"Order details",

$order

);


?>