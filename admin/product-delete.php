<?php


require_once "auth.php";

require_once "../classes/Database.php";

require_once "../helpers/response.php";



$id=$_GET["id"] ?? null;



if(!$id)
{
    response(
        false,
        "Product ID required"
    );
}



$db=new Database();

$conn=$db->connect();





// حذف صور المنتج الإضافية

$stmt=$conn->prepare(

"DELETE FROM product_images

WHERE product_id=?"

);


$stmt->execute([$id]);





// حذف المنتج

$stmt=$conn->prepare(

"DELETE FROM products

WHERE id=?"

);



$result=$stmt->execute([$id]);




if($result)
{

header(
"Location: products.php"
);

exit;

}



echo "Delete failed";
