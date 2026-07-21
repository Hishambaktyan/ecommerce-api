<?php


require_once "auth.php";

require_once "../classes/Database.php";


$db=new Database();

$conn=$db->connect();


$categories=$conn->query(

"
SELECT

categories.id,

categories.NAME AS name,

categories.image,

categories.created_at,

COUNT(products.id) AS products_count

FROM categories

LEFT JOIN products

ON products.category_id = categories.id

GROUP BY categories.id

ORDER BY categories.id DESC

"

)->fetchAll();



?>


<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width,initial-scale=1">


<title>Categories</title>


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

display:flex;

justify-content:space-between;

align-items:center;

margin-bottom:25px;

}



.header h1{

margin:0;

}




.add-btn{

background:#2563eb;

color:white;

padding:12px 20px;

border-radius:12px;

text-decoration:none;

font-weight:600;

}



.box{

background:white;

border-radius:20px;

padding:25px;

overflow:auto;

}



table{

width:100%;

border-collapse:collapse;

}



th{

text-align:left;

padding:15px;

background:#f8fafc;

}



td{

padding:15px;

border-bottom:1px solid #eee;

}



.badge{

background:#dbeafe;

color:#1d4ed8;

padding:6px 12px;

border-radius:20px;

font-size:13px;

}





.actions{

display:flex;

gap:10px;

}



.btn{

padding:8px 15px;

border-radius:10px;

text-decoration:none;

font-weight:600;

font-size:14px;

}



.edit{

background:#2563eb;

color:white;

}



.delete{

background:#ef4444;

color:white;

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



.header{

flex-direction:column;

gap:15px;

}


table{

min-width:600px;

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
Categories
</h1>



<a href="category-create.php" class="add-btn">

+ Add Category

</a>


</div>





<div class="box">



<table>


<tr>

<th>Image</th>
<th>Name</th>
<th>Created At</th>
<th>Actions</th>

</tr>




<?php foreach($categories as $c): ?>


<tr>


<td>

<?php if($c["image"]): ?>

<img

src="<?= htmlspecialchars($c["image"], ENT_QUOTES, 'UTF-8') ?>"

style="
width:60px;
height:60px;
border-radius:12px;
object-fit:cover;
"

>

<?php else: ?>

No Image

<?php endif; ?>


</td>



<td>

<?= htmlspecialchars($c["name"], ENT_QUOTES, 'UTF-8') ?>

</td>




<td>

<?= htmlspecialchars($c["created_at"] ?? "-", ENT_QUOTES, 'UTF-8') ?>

</td>




<td>


<div class="actions">


<a class="btn edit"

href="category-edit.php?id=<?= htmlspecialchars($c["id"], ENT_QUOTES, 'UTF-8') ?>">

Edit

</a>


<a class="btn delete"

href="category-delete.php?id=<?= htmlspecialchars($c["id"], ENT_QUOTES, 'UTF-8') ?>"

onclick="return confirm('Delete category?')">

Delete

</a>



</div>


</td>


</tr>



<?php endforeach; ?>



</table>



</div>



</div>



</body>


</html>