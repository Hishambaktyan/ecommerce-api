<?php


require_once "../../classes/Database.php";
require_once "../../classes/Category.php";
require_once "../../helpers/response.php";
require_once "../../config/cors.php";



$db = new Database();

$conn = $db->connect();



$category = new Category($conn);



$data = $category->all();



response(

true,

"Categories list",

$data

);


?>