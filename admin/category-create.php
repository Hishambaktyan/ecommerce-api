<?php


require_once "auth.php";

require_once "../classes/Database.php";

require_once "../classes/Cloudinary.php";



$db=new Database();

$conn=$db->connect();



$error=null;



if($_POST)
{

verify_csrf();

$name=$_POST["name"];


$image=null;



if(isset($_FILES["image"]) && $_FILES["image"]["error"]===0)
{


$cloudinary=new Cloudinary();


$image=$cloudinary->upload(

$_FILES["image"]

);


}



$stmt=$conn->prepare(


"
INSERT INTO categories

(
NAME,
image
)

VALUES
(?,?)"

);



$result=$stmt->execute([

$name,

$image

]);



if($result)
{

header(
"Location: categories.php"
);

exit;

}


$error="Failed creating category";


}



?>


<!DOCTYPE html>

<html>


<head>


<meta charset="UTF-8">

<meta name="viewport" content="width=device-width,initial-scale=1">


<title>
Add Category
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

max-width:600px;

margin:40px auto;

padding:20px;

}



.card{

background:white;

padding:35px;

border-radius:25px;

box-shadow:0 20px 50px rgba(0,0,0,.1);

}



h1{

margin-bottom:30px;

}



label{

display:block;

margin-bottom:8px;

font-weight:600;

}



input{

width:100%;

padding:14px;

border-radius:12px;

border:1px solid #ddd;

margin-bottom:20px;

font-size:16px;

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


</style>


</head>


<body>


<div class="container">


<div class="card">


<h1>
➕ Add Category
</h1>



<?php if($error): ?>

<div class="error">

<?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>

</div>

<?php endif; ?>



<form method="POST" enctype="multipart/form-data">


<label>
Category Name
</label>


<input

name="name"

placeholder="Electronics"

required

>



<label>
Category Image
</label>


<input

type="file"

name="image"

accept="image/*"

>


<input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">


<button>

Create Category
</button>


</form>


</div>


</div>


</body>


</html>