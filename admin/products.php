<?php


require_once "auth.php";

require_once "../classes/Database.php";


$db=new Database();

$conn=$db->connect();


$products=$conn->query(

"SELECT 

products.id,

products.NAME AS name,

products.description,

products.price,

products.old_price,

products.stock,

products.image,

products.STATUS,

categories.name AS category

FROM products

LEFT JOIN categories

ON categories.id = products.category_id

ORDER BY products.id DESC"

)->fetchAll();


?>



<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>Products</title>



<style>


*{

box-sizing:border-box;

font-family:"Segoe UI",sans-serif;

}



body{

margin:0;

background:#f1f5f9;

}



/* layout */


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

margin-bottom:40px;

text-align:center;

}



.sidebar a{

display:block;

color:#cbd5e1;

text-decoration:none;

padding:14px;

border-radius:12px;

margin-bottom:10px;

}



.sidebar a:hover{

background:#2563eb;

color:white;

}



.main{

margin-left:250px;

padding:30px;

}



/* header */


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

}





/* table */


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



.product-img{

width:60px;

height:60px;

border-radius:12px;

object-fit:cover;

}



.badge{

padding:6px 12px;

border-radius:20px;

font-size:13px;

background:#dcfce7;

color:#166534;

}


.actions{
    display:flex;
    gap:10px;
    justify-content:center;
}


.btn{
    display:inline-flex;
    align-items:center;
    justify-content:center;

    padding:8px 15px;

    border-radius:10px;

    font-size:14px;

    font-weight:600;

    text-decoration:none;

    transition:.3s;

    cursor:pointer;
}


/* Edit button */

.btn-edit{

    background:#2563eb;

    color:white;

}


.btn-edit:hover{

    background:#1d4ed8;

    transform:translateY(-2px);

}



/* Delete button */

.btn-delete{

    background:#ef4444;

    color:white;

}


.btn-delete:hover{

    background:#dc2626;

    transform:translateY(-2px);

}




/* mobile */


@media(max-width:900px){


.sidebar{

position:relative;

width:100%;

height:auto;

}



.main{

margin:0;

}



.header{

flex-direction:column;

gap:15px;

}


table{

min-width:800px;

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
Products
</h1>



<a class="add-btn" href="product-create.php">

+ Add Product

</a>


</div>





<div class="box">


<table>


<tr>

<th>
Image
</th>

<th>
Name
</th>

<th>
Category
</th>

<th>
Price
</th>

<th>
Stock
</th>

<th>
Status
</th>

<th>
Actions
</th>


</tr>




<?php foreach($products as $p): ?>


<tr>


<td>


<?php if(!empty($p["image"])): ?>


<img
class="product-img"
src="<?= htmlspecialchars($p["image"], ENT_QUOTES, 'UTF-8') ?>">


<?php else: ?>


No Image


<?php endif; ?>


</td>




<td>

<?= htmlspecialchars($p["name"], ENT_QUOTES, 'UTF-8') ?>

</td>



<td>

<?= htmlspecialchars($p["category"] ?? "-", ENT_QUOTES, 'UTF-8') ?>

</td>




<td>

<?= htmlspecialchars($p["price"], ENT_QUOTES, 'UTF-8') ?>

</td>



<td>

<?= htmlspecialchars($p["stock"], ENT_QUOTES, 'UTF-8') ?>

</td>



<td>


<span class="badge">

<?= htmlspecialchars($p["STATUS"], ENT_QUOTES, 'UTF-8') ?>

</span>


</td>



<td>


<a class="btn btn-edit"

href="product-edit.php?id=<?= htmlspecialchars($p["id"], ENT_QUOTES, 'UTF-8') ?>">

Edit

</a>



<a class="btn btn-delete"

href="product-delete.php?id=<?= htmlspecialchars($p["id"], ENT_QUOTES, 'UTF-8') ?>"

onclick="return confirm('Delete product?')">

Delete

</a>


</td>


</tr>



<?php endforeach; ?>



</table>


</div>


</div>


</body>


</html>