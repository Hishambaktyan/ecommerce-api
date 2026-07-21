<?php

require_once "auth.php";

require_once "../classes/Database.php";

require_once "../classes/Cloudinary.php";



$db=new Database();

$conn=$db->connect();



// جلب التصنيفات

$categories=$conn->query(

"SELECT id,name FROM categories ORDER BY name"

)->fetchAll();



$error=null;



if($_POST)
{

verify_csrf();

$name=$_POST["name"];

$category=$_POST["category_id"];

$description=$_POST["description"];

$price=$_POST["price"];

$old_price=$_POST["old_price"] ?: null;

$stock=$_POST["stock"];

// Validate numeric fields
if(!is_numeric($price) || $price < 0)
{
    $error="Price must be a valid positive number";
}
elseif(!is_numeric($stock) || $stock < 0)
{
    $error="Stock must be a valid positive number";
}
elseif($old_price !== null && $old_price !== "" && (!is_numeric($old_price) || $old_price < 0))
{
    $error="Old price must be a valid positive number";
}



if($error)
{
    // Validation failed, don't process further
}
else
{

$image=null;



if(isset($_FILES["image"]) 
&& 
$_FILES["image"]["error"]===0)
{


$cloudinary=new Cloudinary();


$image=$cloudinary->upload(

$_FILES["image"]

);



}



$stmt=$conn->prepare(

"INSERT INTO products

(
category_id,
NAME,
description,
price,
old_price,
stock,
image
)

VALUES

(?,?,?,?,?,?,?)"

);



$result=$stmt->execute([

$category,

$name,

$description,

$price,

$old_price,

$stock,

$image

]);



if($result)
{

header(
"Location: products.php"
);

exit;

}
else
{

$error="Failed creating product";

}


} // end else (validation passed)
} // end if($_POST)
?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width,initial-scale=1">


<title>
Create Product
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

max-width:700px;

margin:40px auto;

padding:20px;

}



.card{

background:white;

border-radius:25px;

padding:35px;

box-shadow:
0 20px 50px rgba(0,0,0,.1);

}



h1{

margin-bottom:30px;

}



.field{

margin-bottom:20px;

}



label{

display:block;

margin-bottom:8px;

font-weight:600;

}



input,
textarea,
select{


width:100%;

padding:14px;

border-radius:12px;

border:1px solid #ddd;

font-size:16px;

}



textarea{

height:120px;

resize:none;

}



button{


width:100%;

padding:15px;

border:0;

border-radius:15px;

background:#2563eb;

color:white;

font-size:17px;

font-weight:bold;

cursor:pointer;

}



button:hover{

background:#1d4ed8;

}



.error{

background:#fee2e2;

color:#b91c1c;

padding:15px;

border-radius:12px;

margin-bottom:20px;

}



.back{

display:block;

margin-top:20px;

text-align:center;

text-decoration:none;

}



@media(max-width:600px){

.container{

margin:10px auto;

}


.card{

padding:20px;

}

}


</style>


</head>


<body>



<div class="container">


<div class="card">


<h1>

➕ Add Product

</h1>



<?php if($error): ?>

<div class="error">

<?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>

</div>

<?php endif; ?>



<form method="POST" enctype="multipart/form-data">



<div class="field">

<label>
Category
</label>


<select name="category_id" required>


<option value="">
Select Category
</option>


<?php foreach($categories as $c): ?>


<option value="<?= htmlspecialchars($c["id"], ENT_QUOTES, 'UTF-8') ?>">

<?= htmlspecialchars($c["name"], ENT_QUOTES, 'UTF-8') ?>

</option>


<?php endforeach; ?>


</select>


</div>





<div class="field">

<label>
Product Name
</label>


<input

name="name"

required

placeholder="iPhone 15 Pro"

>


</div>




<div class="field">


<label>
Description
</label>


<textarea

name="description"

placeholder="Product description"

></textarea>


</div>





<div class="field">


<label>
Price
</label>


<input

type="number"

step="0.01"

name="price"

required

>


</div>





<div class="field">


<label>
Old Price
</label>


<input

type="number"

step="0.01"

name="old_price"

>


</div>





<div class="field">


<label>
Stock
</label>


<input

type="number"

name="stock"

value="0"

>


</div>





<div class="field">


<label>
Product Image
</label>


<input

type="file"

name="image"

accept="image/*"

>


</div>


<input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">


<button>

Create Product

</button>



</form>



<a class="back" href="products.php">

← Back to Products

</a>



</div>


</div>



</body>

</html>