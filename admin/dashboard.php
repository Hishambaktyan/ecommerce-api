<?php


require_once "auth.php";


require_once "../classes/Database.php";


$db=new Database();

$conn=$db->connect();




$users=$conn->query(

"SELECT COUNT(*) FROM users"

)->fetchColumn();



$products=$conn->query(

"SELECT COUNT(*) FROM products"

)->fetchColumn();



$orders=$conn->query(

"SELECT COUNT(*) FROM orders"

)->fetchColumn();



$sales=$conn->query(

"SELECT COALESCE(SUM(total),0) FROM orders"

)->fetchColumn();



?>



<!DOCTYPE html>

<html lang="en">

<head>


<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>Admin Dashboard</title>



<style>


*{

margin:0;

padding:0;

box-sizing:border-box;

font-family:"Segoe UI",sans-serif;

}



body{

background:#f1f5f9;

display:flex;

min-height:100vh;

}



/* Sidebar */


.sidebar{


width:260px;

background:#0f172a;

color:white;

padding:25px;

position:fixed;

height:100vh;

}



.logo{

font-size:25px;

font-weight:bold;

margin-bottom:40px;

text-align:center;

}



.menu a{


display:block;

padding:15px;

margin-bottom:10px;

border-radius:12px;

color:#cbd5e1;

text-decoration:none;

transition:.3s;

}



.menu a:hover{


background:#2563eb;

color:white;

}




/* Main */


.main{

margin-left:260px;

width:calc(100% - 260px);

padding:30px;

}



/* Navbar */


.navbar{


background:white;

padding:20px;

border-radius:20px;

display:flex;

justify-content:space-between;

align-items:center;

margin-bottom:30px;

box-shadow:

0 10px 30px rgba(0,0,0,.05);

}



.navbar h1{

color:#0f172a;

}



.admin{

background:#2563eb;

color:white;

padding:10px 18px;

border-radius:20px;

}



/* Cards */


.cards{


display:grid;

grid-template-columns:

repeat(4,1fr);

gap:20px;

}



.card{


background:white;

padding:30px;

border-radius:22px;

box-shadow:

0 15px 35px rgba(0,0,0,.08);

}



.card h3{


color:#64748b;

font-size:15px;

margin-bottom:15px;

}



.card .number{


font-size:35px;

font-weight:bold;

color:#0f172a;

}



.blue{

border-left:5px solid #2563eb;

}


.green{

border-left:5px solid #16a34a;

}


.orange{

border-left:5px solid #f97316;

}


.red{

border-left:5px solid #dc2626;

}




/* Quick actions */


.section{


margin-top:35px;

background:white;

padding:30px;

border-radius:22px;

}



.section h2{

margin-bottom:20px;

}



.actions{


display:flex;

gap:15px;

flex-wrap:wrap;

}



.actions a{


padding:15px 25px;

background:#2563eb;

color:white;

text-decoration:none;

border-radius:12px;

}



/* Mobile */


@media(max-width:900px){


.sidebar{


position:relative;

width:100%;

height:auto;

}



body{

display:block;

}



.main{

margin-left:0;

width:100%;

}



.cards{


grid-template-columns:

repeat(2,1fr);

}



}



@media(max-width:500px){


.cards{


grid-template-columns:1fr;

}


.navbar{


flex-direction:column;

gap:15px;

}


}



</style>



</head>


<body>




<div class="sidebar">


<div class="logo">

E-Commerce

</div>



<div class="menu">


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


</div>





<div class="main">



<div class="navbar">


<h1>
Dashboard
</h1>


<div class="admin">

Admin

</div>


</div>





<div class="cards">



<div class="card blue">

<h3>
Total Users
</h3>


<div class="number">

<?= $users ?>

</div>

</div>





<div class="card green">


<h3>
Products
</h3>


<div class="number">

<?= $products ?>

</div>


</div>






<div class="card orange">


<h3>
Orders
</h3>


<div class="number">

<?= $orders ?>

</div>


</div>






<div class="card red">


<h3>
Total Sales
</h3>


<div class="number">

<?= $sales ?>

</div>


</div>



</div>







<div class="section">


<h2>

Quick Management

</h2>



<div class="actions">


<a href="categories.php">

Manage Categories

</a>



<a href="products.php">

Manage Products

</a>



<a href="orders.php">

Manage Orders

</a>



<a href="users.php">

Manage Users

</a>



</div>



</div>






</div>



</body>


</html>