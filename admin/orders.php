<?php


require_once "auth.php";

require_once "../classes/Database.php";



$db = new Database();

$conn = $db->connect();



$stmt = $conn->prepare(

"SELECT

orders.*,

users.name AS customer_name,

users.email


FROM orders


JOIN users

ON users.id = orders.user_id


ORDER BY orders.id DESC"

);



$stmt->execute();


$orders=$stmt->fetchAll();



?>



<!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">

<meta name="viewport" content="width=device-width,initial-scale=1">



<title>
Orders
</title>



<style>


*{

box-sizing:border-box;

font-family:"Segoe UI",sans-serif;

}



body{

margin:0;

background:#f1f5f9;

}



/* Sidebar */


.sidebar{

position:fixed;

width:250px;

height:100vh;

background:#0f172a;

padding:25px;

color:white;

}



.logo{

font-size:25px;

font-weight:bold;

text-align:center;

margin-bottom:40px;

}



.sidebar a{

display:block;

padding:14px;

margin-bottom:10px;

border-radius:12px;

color:#cbd5e1;

text-decoration:none;

}



.sidebar a:hover{

background:#2563eb;

color:white;

}



/* Main */


.main{

margin-left:250px;

padding:30px;

}




.header{

background:white;

padding:25px;

border-radius:20px;

margin-bottom:25px;

}



.header h1{

margin:0;

}



/* Box */


.box{

background:white;

padding:25px;

border-radius:20px;

overflow:auto;

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



.customer{

font-weight:600;

}



.email{

color:#64748b;

font-size:14px;

}



/* status */


.status{

padding:7px 14px;

border-radius:20px;

font-size:13px;

font-weight:600;

}



.Pending{

background:#fef3c7;

color:#92400e;

}



.Processing{

background:#dbeafe;

color:#1e40af;

}



.Shipped{

background:#e0e7ff;

color:#4338ca;

}



.Delivered{

background:#dcfce7;

color:#166534;

}



.Cancelled{

background:#fee2e2;

color:#991b1b;

}



/* button */


.btn{

background:#2563eb;

color:white;

padding:9px 16px;

border-radius:10px;

text-decoration:none;

font-weight:600;

font-size:14px;

}



.btn:hover{

background:#1d4ed8;

}





@media(max-width:900px){



.sidebar{

position:relative;

width:100%;

height:auto;

}



.main{

margin:0;

padding:15px;

}



table{

min-width:1000px;

}


}



</style>



</head>



<body>




<div class="sidebar">


<div class="logo">

E-Commerce

</div>



<a href="dashboard.php">

🏠 Dashboard

</a>


<a href="categories.php">

📂 Categories

</a>



<a href="products.php">

📦 Products

</a>



<a href="orders.php">

🛒 Orders

</a>



<a href="users.php">

👥 Users

</a>



<a href="settings.php">

⚙ Settings

</a>



<a href="logout.php">

🚪 Logout

</a>



</div>





<div class="main">


<div class="header">


<h1>

🛒 Orders Management

</h1>


</div>





<div class="box">



<table>


<tr>


<th>ID</th>

<th>Customer</th>

<th>Total</th>

<th>Payment</th>

<th>Address</th>

<th>Status</th>

<th>Date</th>

<th>Action</th>


</tr>





<?php foreach($orders as $o): ?>


<tr>



<td>

#<?= htmlspecialchars($o["id"], ENT_QUOTES, 'UTF-8') ?>

</td>




<td>


<div class="customer">

<?= htmlspecialchars($o["customer_name"], ENT_QUOTES, 'UTF-8') ?>

</div>


<div class="email">

<?= htmlspecialchars($o["email"], ENT_QUOTES, 'UTF-8') ?>

</div>


</td>




<td>

$<?= htmlspecialchars($o["total"], ENT_QUOTES, 'UTF-8') ?>

</td>



<td>

<?= htmlspecialchars($o["payment_method"] ?? "-", ENT_QUOTES, 'UTF-8') ?>

</td>
    


<td>

<?= htmlspecialchars($o["address"], ENT_QUOTES, 'UTF-8') ?>

</td>



<td>


<span class="status <?= htmlspecialchars($o["STATUS"], ENT_QUOTES, 'UTF-8') ?>">


<?= htmlspecialchars($o["STATUS"], ENT_QUOTES, 'UTF-8') ?>


</span>


</td>



<td>

<?= htmlspecialchars($o["created_at"], ENT_QUOTES, 'UTF-8') ?>

</td>



<td>


<a class="btn"

href="update-order.php?id=<?= htmlspecialchars($o["id"], ENT_QUOTES, 'UTF-8') ?>">

Update

</a>


</td>



</tr>



<?php endforeach; ?>




</table>



</div>



</div>



</body>


</html>