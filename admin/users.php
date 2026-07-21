<?php


require_once "auth.php";

require_once "../classes/Database.php";


$db=new Database();

$conn=$db->connect();



$users=$conn->query(

"
SELECT 

id,

name,

email,

role,

created_at

FROM users

ORDER BY id DESC

"

)->fetchAll();



?>



<!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">

<meta name="viewport" content="width=device-width,initial-scale=1">


<title>
Users
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

padding:15px;

background:#f8fafc;

text-align:left;

}



td{

padding:15px;

border-bottom:1px solid #eee;

}




.user{

font-weight:700;

}



.email{

color:#64748b;

}





.role{

padding:7px 14px;

border-radius:20px;

font-size:13px;

font-weight:600;

}



.admin{

background:#fee2e2;

color:#b91c1c;

}



.user-role{

background:#dcfce7;

color:#166534;

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

min-width:700px;

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

👥 Users Management

</h1>


</div>





<div class="box">


<table>


<tr>

<th>ID</th>

<th>Name</th>

<th>Email</th>

<th>Role</th>

<th>Created</th>

</tr>




<?php foreach($users as $u): ?>


<tr>


<td>

#<?= htmlspecialchars($u["id"], ENT_QUOTES, 'UTF-8') ?>

</td>



<td>


<div class="user">

<?= htmlspecialchars($u["name"], ENT_QUOTES, 'UTF-8') ?>

</div>


</td>




<td>


<div class="email">

<?= htmlspecialchars($u["email"], ENT_QUOTES, 'UTF-8') ?>

</div>


</td>




<td>



<?php if($u["role"]=="admin"): ?>


<span class="role admin">

Admin

</span>


<?php else: ?>


<span class="role user-role">

User

</span>


<?php endif; ?>



</td>



<td>

<?= $u["created_at"] ?? "-" ?>

</td>



</tr>



<?php endforeach; ?>



</table>



</div>




</div>



</body>


</html>