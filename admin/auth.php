<?php

require_once "../helpers/csrf.php";


session_start();



if(!isset($_SESSION["admin_id"]))
{

header(
"Location: login.php"
);

exit;

}


?>