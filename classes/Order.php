<?php


class Order
{


private $db;


public function __construct($database)
{

$this->db=$database;

}





public function create(
$user,
$total,
$address,
$payment
)
{


$stmt=$this->db->prepare(

"INSERT INTO orders

(
user_id,
total,
address,
payment_method
)

VALUES
(?,?,?,?)

"

);


$stmt->execute([

$user,

$total,

$address,

$payment

]);



return $this->db->lastInsertId();


}





public function all($user)
{


$stmt=$this->db->prepare(

"SELECT *

FROM orders

WHERE user_id=?

ORDER BY id DESC"

);


$stmt->execute([$user]);


return $stmt->fetchAll();


}





public function updateStatus(
$id,
$status
)
{


$stmt=$this->db->prepare(

"UPDATE orders

SET status=?

WHERE id=?

"

);


return $stmt->execute([

$status,

$id

]);


}



}


?>