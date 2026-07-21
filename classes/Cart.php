<?php


class Cart
{


private $db;



public function __construct($database)
{

$this->db=$database;

}





public function add(
$user,
$product,
$qty
)
{


$stmt=$this->db->prepare(

"INSERT INTO cart
(user_id,product_id,quantity)

VALUES
(?,?,?)

"

);


return $stmt->execute([

$user,

$product,

$qty

]);


}




public function view($user)
{


$stmt=$this->db->prepare(

"SELECT 

cart.*,

products.name,

products.price,

products.image


FROM cart


JOIN products

ON products.id=
cart.product_id


WHERE user_id=?


"

);


$stmt->execute([$user]);


return $stmt->fetchAll();


}





public function remove($id,$user)
{

$stmt=$this->db->prepare(

"DELETE FROM cart

WHERE id=?

AND user_id=?"

);


return $stmt->execute([

$id,

$user

]);


}


}


?>