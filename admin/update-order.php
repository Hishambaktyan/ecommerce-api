<?php


require_once "auth.php";

require_once "../classes/Database.php";

require_once "../helpers/response.php";


$db=new Database();

$conn=$db->connect();


$id=$_GET["id"] ?? null;


if(!$id)
{
    response(
        false,
        "Order ID required"
    );
}


/*
جلب بيانات الطلب
*/


$stmt=$conn->prepare(


"
SELECT

orders.*,

users.name AS customer_name,

users.email

FROM orders


JOIN users

ON users.id = orders.user_id


WHERE orders.id=?"

);


$stmt->execute([$id]);

$order=$stmt->fetch();


if(!$order)
{

response(
    false,
    "Order not found"
);

}




/*
تحديث الحالة
*/


if($_POST)
{


$status=$_POST["status"];

verify_csrf();

$update=$conn->prepare(


"
UPDATE orders

SET STATUS=?

WHERE id=?"

);


$update->execute([

$status,

$id

]);


header(

"Location:update-order.php?id=".$id

);

exit;


}




/*
جلب منتجات الطلب
*/


$items=$conn->prepare(


"
SELECT

order_items.*,

products.name,

products.image


FROM order_items


JOIN products

ON products.id=order_items.product_id


WHERE order_items.order_id=?"

);


$items->execute([$id]);

$products=$items->fetchAll();




?>


<!DOCTYPE html>

<html lang="en">

<head>


<meta charset="UTF-8">

<meta name="viewport" content="width=device-width,initial-scale=1">


<title>
Update Order
</title>



<style>


*{

box-sizing:border-box;

font-family:"Segoe UI";

}


body{

margin:0;

background:#f1f5f9;

}


.container{

max-width:1000px;

margin:40px auto;

padding:20px;

}


.card{

background:white;

padding:30px;

border-radius:25px;

margin-bottom:25px;

box-shadow:0 15px 40px rgba(0,0,0,.08);

}


h1{

margin-top:0;

}



.info{

display:grid;

grid-template-columns:repeat(2,1fr);

gap:20px;

}


.box{

background:#f8fafc;

padding:15px;

border-radius:15px;

}


.label{

color:#64748b;

font-size:14px;

}


.value{

font-weight:700;

margin-top:5px;

}




select{

width:100%;

padding:14px;

border-radius:12px;

border:1px solid #ddd;

font-size:16px;

}


button{

margin-top:15px;

width:100%;

padding:15px;

border:0;

border-radius:15px;

background:#2563eb;

color:white;

font-size:17px;

font-weight:bold;

}




table{

width:100%;

border-collapse:collapse;

}


th{

background:#f8fafc;

padding:15px;

text-align:left;

}


td{

padding:15px;

border-bottom:1px solid #eee;

}


.product-img{

width:60px;

height:60px;

object-fit:cover;

border-radius:12px;

}




@media(max-width:600px){


.info{

grid-template-columns:1fr;

}


}


</style>


</head>



<body>



<div class="container">



<div class="card">


<h1>

🛒 Order #<?= htmlspecialchars($order["id"], ENT_QUOTES, 'UTF-8') ?>
</h1>



<div class="info">


<div class="box">

<div class="label">
Customer
</div>

<div class="value">

<?= htmlspecialchars($order["customer_name"], ENT_QUOTES, 'UTF-8') ?>
</div>

</div>



<div class="box">

<div class="label">
Email
</div>

<div class="value">

<?= htmlspecialchars($order["email"], ENT_QUOTES, 'UTF-8') ?>
</div>

</div>



<div class="box">

<div class="label">
Total
</div>

<div class="value">

$<?= htmlspecialchars($order["total"], ENT_QUOTES, 'UTF-8') ?>
</div>

</div>



<div class="box">

<div class="label">
Address
</div>

<div class="value">

<?= htmlspecialchars($order["address"], ENT_QUOTES, 'UTF-8') ?>
</div>

</div>


</div>


</div>





<div class="card">


<h2>
Change Status
</h2>


<form method="POST">


<select name="status">


<option value="Pending" <?= $order["STATUS"]=="Pending"?"selected":"" ?>>
Pending
</option>


<option value="Processing" <?= $order["STATUS"]=="Processing"?"selected":"" ?>>
Processing
</option>


<option value="Shipped" <?= $order["STATUS"]=="Shipped"?"selected":"" ?>>
Shipped
</option>


<option value="Delivered" <?= $order["STATUS"]=="Delivered"?"selected":"" ?>>
Delivered
</option>


<option value="Cancelled" <?= $order["STATUS"]=="Cancelled"?"selected":"" ?>>
Cancelled
</option>


</select>


<input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">


<button>

Save Status
</button>


</form>


</div>


</div>


</body>

</html>