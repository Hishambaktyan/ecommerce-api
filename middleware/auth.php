<?php


require_once __DIR__ .
"/../classes/Auth.php";


require_once __DIR__ .
"/../helpers/response.php";



$auth = new Auth();



if(!$auth->check())
{

    response(
        false,
        "Unauthorized"
    );

}


?>