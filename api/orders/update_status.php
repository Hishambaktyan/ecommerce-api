<?php


require_once "../../classes/Database.php";

require_once "../../classes/Auth.php";

require_once "../../helpers/response.php";

require_once "../../config/cors.php";


$auth=new Auth();



if(!$auth->isAdmin())
{

response(

false,

"Admin only"

);

}



$data=json_decode(

file_get_contents("php://input"),

true

);



$id=$data["id"] ?? null;


$status=$data["status"] ?? null;



$allowed=[

"Pending",

"Processing",

"Shipped",

"Delivered",

"Cancelled"

];



if(
!in_array(
$status,
$allowed
)
)
{

response(

false,

"Invalid status"

);

}



$db=new Database();

$conn=$db->connect();



$stmt=$conn->prepare(

"UPDATE orders

SET status=?

WHERE id=?

"

);



$result=$stmt->execute([

$status,

$id

]);



response(

$result,

$result
?
"Status updated"
:
"Failed"

);


?>