<?php


session_start();


require_once "../classes/Database.php";

require_once "../helpers/csrf.php";


$error = "";



if(isset($_POST["login"]))
{

verify_csrf();

$email = trim($_POST["email"]);

$password = $_POST["password"];



$db = new Database();

$conn = $db->connect();



$stmt = $conn->prepare(

"SELECT

id,

name,

email,

PASSWORD AS password,

role

FROM users

WHERE email=?

AND role='admin'

LIMIT 1"

);



$stmt->execute([

$email

]);



$user = $stmt->fetch();



if(!$user)
{

$error = "Email not found or you are not an admin";

}

elseif(!password_verify($password,$user["password"]))
{

$error = "Incorrect password";

}

else
{

session_regenerate_id(true);

$_SESSION["admin_id"] = $user["id"];


header(
"Location: dashboard.php"
);

exit;


}


}

?>
<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>Admin Login</title>


<style>


*{

margin:0;

padding:0;

box-sizing:border-box;

font-family:"Inter","Segoe UI",sans-serif;

}



body{

height:100vh;

display:flex;

align-items:center;

justify-content:center;

background:

radial-gradient(circle at top,#3b82f6,#0f172a 60%);

}



.login-wrapper{

width:100%;

max-width:430px;

padding:20px;

}



.login-card{

background:rgba(255,255,255,.95);

border-radius:28px;

padding:45px 35px;

box-shadow:

0 25px 70px rgba(0,0,0,.35);

backdrop-filter:blur(10px);

}



.logo{

text-align:center;

margin-bottom:35px;

}



.logo .icon{

width:70px;

height:70px;

margin:auto;

border-radius:20px;

background:#2563eb;

color:white;

display:flex;

align-items:center;

justify-content:center;

font-size:32px;

font-weight:bold;

}

.logo h1{

margin-top:20px;

font-size:30px;

color:#111827;

}

.logo p{

margin-top:8px;

color:#64748b;

}

.alert{

background:#fee2e2;

color:#b91c1c;

padding:14px;

border-radius:14px;

margin-bottom:20px;

text-align:center;

font-size:15px;

font-weight:600;

}

.field{

margin-bottom:20px;

}

.field label{

display:block;

margin-bottom:8px;

font-weight:600;

color:#334155;

}

.field input{


width:100%;

height:52px;

padding:0 16px;

border-radius:14px;

border:1px solid #cbd5e1;

font-size:16px;

outline:none;

transition:.3s;

}

.field input:focus{

border-color:#2563eb;

box-shadow:

0 0 0 4px rgba(37,99,235,.15);

}

button{


width:100%;

height:55px;

border:none;

border-radius:15px;

background:

linear-gradient(

135deg,

#2563eb,

#1d4ed8

);


color:white;

font-size:17px;

font-weight:bold;

cursor:pointer;

transition:.3s;


}

button:hover{


transform:translateY(-3px);

box-shadow:

0 10px 25px rgba(37,99,235,.4);


}

.footer{


text-align:center;

margin-top:30px;

color:#94a3b8;

font-size:13px;

}

@media(max-width:480px){


.login-card{

padding:35px 22px;

}

.logo h1{

font-size:25px;

}


}


</style>


</head>


<body>


<div class="login-wrapper">


<div class="login-card">


<div class="logo">


<div class="icon">

A

</div>


<h1>

Admin Panel

</h1>


<p>

Manage your store easily

</p>


</div>



<?php if($error): ?>


<div class="alert">

<?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>

</div>


<?php endif; ?>




<form method="POST">


<div class="field">

<label>
Email
</label>


<input

type="email"

name="email"

placeholder="admin@example.com"

required>



</div>



<div class="field">


<label>
Password
</label>


<input

type="password"

name="password"

placeholder="********"

required>


</div>


<input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">


<button name="login">

Sign In
</button>


</form>



<div class="footer">

E-Commerce Management System
</div>



</div>


</div>



</body>


</html>