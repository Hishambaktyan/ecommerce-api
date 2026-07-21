<?php


require_once "../../classes/Database.php";
require_once "../../classes/Product.php";
require_once "../../helpers/response.php";
require_once "../../config/cors.php";



$db = new Database();

$conn = $db->connect();



$product = new Product($conn);



$page = $_GET["page"] ?? 1;

$limit = 10;

$offset = ($page - 1) * $limit;



$search = $_GET["search"] ?? "";



if($search)
{


$stmt = $conn->prepare(

"SELECT *

FROM products

WHERE name LIKE ?

ORDER BY id DESC

LIMIT ?

OFFSET ?"

);



$stmt->bindValue(
1,
"%".$search."%",
PDO::PARAM_STR
);


$stmt->bindValue(
2,
$limit,
PDO::PARAM_INT
);


$stmt->bindValue(
3,
$offset,
PDO::PARAM_INT
);


$stmt->execute();



$data=$stmt->fetchAll();



}

else
{

$data=$product->all($limit,$offset);

}

response(true,"Products list",$data);


?>