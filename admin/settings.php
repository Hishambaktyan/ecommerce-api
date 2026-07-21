<?php


require_once "auth.php";

require_once "../classes/Database.php";


$db=new Database();

$conn=$db->connect();



$message=null;



if($_POST)
{

verify_csrf();

$stmt=$conn->prepare(

"
UPDATE settings

SET

store_name=?,

currency=?

WHERE id=1

"

);



$stmt->execute([

$_POST["store_name"],

$_POST["currency"]

]);



$message="Settings updated successfully";



}



$data=$conn->query(

"
SELECT *

FROM settings

WHERE id=1

"

)->fetch();


?>


<!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">

<meta name="viewport" content="width=device-width,initial-scale=1">


<title>
Settings
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



.card{

background:white;

max-width:700px;

padding:35px;

border-radius:25px;

box-shadow:0 15px 40px rgba(0,0,0,.08);

}



.success{

background:#dcfce7;

color:#166534;

padding:15px;

border-radius:12px;

margin-bottom:25px;

font-weight:600;

}



.field{

margin-bottom:25px;

}



label{

display:block;

margin-bottom:8px;

font-weight:600;

color:#334155;

}



input{

width:100%;

padding:15px;

border-radius:14px;

border:1px solid #cbd5e1;

font-size:16px;

outline:none;

}



input:focus{

border-color:#2563eb;

box-shadow:0 0 0 4px rgba(37,99,235,.15);

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



.info{

margin-top:25px;

background:#f8fafc;

padding:20px;

border-radius:15px;

color:#64748b;

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


.card{

padding:25px;

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

⚙ Store Settings
</h1>

</div>



<div class="card">



<?php if($message): ?>


<div class="success">

<?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
</div>


<?php endif; ?>




<form method="POST">



<div class="field">

<label>

Store Name
</label>

<input

name="store_name"

value="<?= htmlspecialchars($data["store_name"] ?? "", ENT_QUOTES, 'UTF-8') ?>"

required

>


</div>



<div class="field">

<label>

Currency
</label>

<input

name="currency"

value="<?= htmlspecialchars($data["currency"] ?? "", ENT_QUOTES, 'UTF-8') ?>"

required

>


</div>


<input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">


<button>

💾 Save Settings
</button>


</form>



<div class="info">

<h3>

Current Configuration
</h3>

<p>

Store:
<b>
<?= htmlspecialchars($data["store_name"] ?? "", ENT_QUOTES, 'UTF-8') ?>
</b>

</p>

<p>

Currency:
<b>
<?= htmlspecialchars($data["currency"] ?? "", ENT_QUOTES, 'UTF-8') ?>
</b>

</p>

</div>



</div>




</div>



</body>

</html>