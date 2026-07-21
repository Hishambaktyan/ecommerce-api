<?php


class Category
{


private $db;



public function __construct($database)
{
    $this->db=$database;
}




public function all()
{

$stmt=$this->db->prepare(

"SELECT * FROM categories
ORDER BY id DESC"

);


$stmt->execute();


return $stmt->fetchAll();

}





public function create(
$name,
$image=null
)
{


$stmt=$this->db->prepare(

"INSERT INTO categories
(name,image)
VALUES
(?,?)"

);


return $stmt->execute([

$name,
$image

]);


}





public function find($id)
{


$stmt=$this->db->prepare(

"SELECT *
FROM categories
WHERE id=?"

);


$stmt->execute([$id]);


return $stmt->fetch();

}




public function delete($id)
{

$stmt=$this->db->prepare(

"DELETE FROM categories
WHERE id=?"

);


return $stmt->execute([$id]);

}


}


?>