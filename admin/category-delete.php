<?php


require_once "auth.php";

require_once "../classes/Database.php";

require_once "../helpers/response.php";



$id=$_GET["id"] ?? null;



if(!$id)
{
    response(
        false,
        "Category ID required"
    );
}



$db=new Database();

$conn=$db->connect();




/*
فحص هل يوجد منتجات
*/


$check=$conn->prepare(

"SELECT COUNT(*)

FROM products

WHERE category_id=?"

);



$check->execute([$id]);



$count=$check->fetchColumn();




if($count>0)
{


echo "

<script>

alert('Cannot delete category. It contains products');

window.location='categories.php';

</script>

";


exit;

}





/*
الحذف
*/


$stmt=$conn->prepare(

"DELETE FROM categories

WHERE id=?"

);



$result=$stmt->execute([$id]);




if($result)
{


header(

"Location: categories.php"

);


exit;


}



echo "Delete failed";