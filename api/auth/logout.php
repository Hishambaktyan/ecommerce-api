<?php


require_once "../../classes/Auth.php";

require_once "../../helpers/response.php";

require_once "../../config/cors.php";


$auth=new Auth();


$auth->logout();



response(

true,

"Logout successful"

);


?>