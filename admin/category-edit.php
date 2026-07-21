<?php


require_once "auth.php";

require_once "../classes/Database.php";

require_once "../classes/Cloudinary.php";

require_once "../helpers/response.php";



$db=new Database();

$conn=$db->connect();



$id=$_GET["id"] ?? null;



if(!$id)
{
    response(
        false,
        "Category ID required"
    );
}


$stmt=$conn->prepare(

"SELECT *

FROM categories

WHERE id=?"

);



$stmt->execute([$id]);


$category=$stmt->fetch();



if(!$category)
{
    response(
        false,
        "Category not found"
    );
}


$error=null;



if($_POST)
{

verify_csrf();

$name=$_POST["name"];



$image=$category["image"];




if(isset($_FILES["image"]) 
&& 
$_FILES["image"]["error"]===0)
{


$cloudinary=new Cloudinary();


$image=$cloudinary->upload(

$_FILES["image"]

);


}




$update=$conn->prepare(


"
UPDATE categories

SET

NAME=?,

image=?

WHERE id=?"

);



$result=$update->execute([

$name,

$image,

$id

]);



if($result)
{

header(

"Location: categories.php"

);

exit;

}


$error="Update failed";


}

?>


<!DOCTYPE html>

<html>

<head>


<meta charset="UTF-8">

<meta name="viewport" content="width=device-width,initial-scale=1">


<title>Edit Category</title>


<style>


*{

box-sizing:border-box;

font-family:"Segoe UI";

}


body{

background:#f1f5f9;

margin:0;

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



input{

width:100%;

padding:14px;

margin-bottom:20px;

border-radius:12px;

border:1px solid #ddd;

font-size:16px;

}



label{

font-weight:600;

display:block;

margin-bottom:8px;

}



img{

width:100px;

height:100px;

object-fit:cover;

border-radius:15px;

margin-bottom:20px;

}



button{

width:100%;

padding:15px;

background:#2563eb;

color:white;

border:0;

border-radius:15px;

font-size:17px;

font-weight:bold;

}


</style>


</head>


<body>


<div class="container">


<div class="card">


<h1>
✏ Edit Category
</h1>



<?php if($error): ?>

<div class="error" style="background:#fee2e2;color:#b91c1c;padding:15px;border-radius:12px;margin-bottom:20px;">
<?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
</div>

<?php endif; ?>



<form method="POST" enctype="multipart/form-data">


<label>
Category Name
</label>


<input

name="name"

value="<?= htmlspecialchars($category["NAME"], ENT_QUOTES, 'UTF-8') ?>"

required

>



<label>
Current Image
</label>


<?php if($category["image"]): ?>

<br>

<img src="<?= htmlspecialchars($category["image"], ENT_QUOTES, 'UTF-8') ?>">


<?php endif; ?>



<label>
Change Image
</label>


<input

type="file"

name="image"

accept="image/*"

>


<input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">


<button>

Save Changes
</button>


</form>



</div>


</div>


</body>

</html>