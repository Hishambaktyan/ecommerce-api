<?php


require_once "../../classes/Database.php";

require_once "../../classes/Cart.php";

require_once "../../classes/Auth.php";

require_once "../../helpers/response.php";

require_once "../../config/cors.php";



$auth = new Auth();



if(!$auth->check())
{

    response(
        false,
        "Login required"
    );

}



$db = new Database();

$conn = $db->connect();



$cart = new Cart($conn);



$items = $cart->view(

    $auth->userId()

);



$total = 0;



foreach($items as $item)
{

    $total +=

    $item["price"] *

    $item["quantity"];

}



response(

    true,

    "Cart details",

    [

        "items"=>$items,

        "total"=>$total

    ]

);


?>