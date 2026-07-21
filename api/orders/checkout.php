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



$data=json_decode(

file_get_contents("php://input"),

true

);



$address =
$data["address"] ?? "";


$payment =
$data["payment_method"] ?? "Cash";



if(!$address)
{

response(
false,
"Address required"
);

}



$db=new Database();

$conn=$db->connect();



$user_id=$auth->userId();



try
{


$conn->beginTransaction();



/*
جلب منتجات السلة
*/

$stmt=$conn->prepare(

"SELECT

cart.product_id,

cart.quantity,

products.price

FROM cart

JOIN products

ON products.id = cart.product_id

WHERE cart.user_id=?

"

);



$stmt->execute([

$user_id

]);



$cart=$stmt->fetchAll();



if(!$cart)
{

response(
false,
"Cart empty"
);

}



$total=0;



foreach($cart as $item)
{


$stockCheck=$conn->prepare(

"SELECT stock

FROM products

WHERE id=?"

);



$stockCheck->execute([

$item["product_id"]

]);



$product=$stockCheck->fetch();



if(
$item["quantity"] > $product["stock"]
)
{

response(

false,

"Not enough stock"

);

}



$total +=

$item["price"]

*

$item["quantity"];


}



/*
إنشاء الطلب
*/


$order=$conn->prepare(

"INSERT INTO orders

(
user_id,
total,
address,
payment_method
)

VALUES
(?,?,?,?)

"

);



$order->execute([

$user_id,

$total,

$address,

$payment

]);



$order_id =
$conn->lastInsertId();





/*
إضافة تفاصيل الطلب
*/


$itemInsert=$conn->prepare(

"INSERT INTO order_items

(
order_id,
product_id,
quantity,
price
)

VALUES
(?,?,?,?)

"

);



foreach($cart as $item)
{


$itemInsert->execute([

$order_id,

$item["product_id"],

$item["quantity"],

$item["price"]

]);



$stock=$conn->prepare(

"UPDATE products

SET stock = stock - ?

WHERE id=?

AND stock >= ?"

);



$stock->execute([

$item["quantity"],

$item["product_id"],

$item["quantity"]

]);


if($stock->rowCount() === 0)
{

    throw new Exception(

        "Stock changed for product ID "
        . $item["product_id"]

    );

}



}




/*
تفريغ السلة
*/


$clear=$conn->prepare(

"DELETE FROM cart

WHERE user_id=?

"

);


$clear->execute([

$user_id

]);



$conn->commit();



response(

true,

"Order created",

[

"order_id"=>$order_id,

"total"=>$total

]

);



}

catch(Exception $e)
{


$conn->rollBack();



response(

false,

"Order failed"

);


}



?>