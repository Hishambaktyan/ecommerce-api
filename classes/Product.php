<?php


class Product
{


private $db;



public function __construct($database)
{

$this->db=$database;

}


public function all(
$limit=10,
$offset=0
)
{

$stmt=$this->db->prepare(

"SELECT 
products.*,
categories.name AS category

FROM products

LEFT JOIN categories

ON categories.id = products.category_id

ORDER BY products.id DESC

LIMIT ?
OFFSET ?"

);



$stmt->bindValue(
1,
(int)$limit,
PDO::PARAM_INT
);


$stmt->bindValue(
2,
(int)$offset,
PDO::PARAM_INT
);


$stmt->execute();


$products = $stmt->fetchAll();



foreach($products as &$product)
{

    $images = $this->db->prepare(

        "SELECT image
         FROM product_images
         WHERE product_id=?"

    );


    $images->execute([

        $product["id"]

    ]);


    $product["images"] =
    $images->fetchAll(PDO::FETCH_COLUMN);


}



return $products;


}



public function find($id)
{

    $stmt = $this->db->prepare(

    "SELECT 
        products.*,
        categories.name AS category

    FROM products

    LEFT JOIN categories

    ON categories.id = products.category_id

    WHERE products.id = ?"

    );


    $stmt->execute([$id]);


    $product = $stmt->fetch();



    if(!$product)
    {
        return null;
    }



    $images = $this->db->prepare(

        "SELECT image
         FROM product_images
         WHERE product_id = ?"

    );


    $images->execute([$id]);



    $product["images"] = 
    $images->fetchAll(PDO::FETCH_COLUMN);



    return $product;

}





public function create($data)
{


$stmt=$this->db->prepare(

"INSERT INTO products

(
category_id,
name,
description,
price,
old_price,
stock,
image
)

VALUES
(?,?,?,?,?,?,?)

"

);



return $stmt->execute([

$data["category_id"],

$data["name"],

$data["description"],

$data["price"],

$data["old_price"],

$data["stock"],

$data["image"]

]);


}




public function delete($id)
{

$stmt=$this->db->prepare(

"DELETE FROM products
WHERE id=?"

);


return $stmt->execute([$id]);


}

public function update($id,$data)
{

$stmt=$this->db->prepare(

"UPDATE products

SET

category_id=?,

NAME=?,

description=?,

price=?,

old_price=?,

stock=?,

image=?,

STATUS=?

WHERE id=?

"

);


return $stmt->execute([

$data["category_id"],

$data["name"],

$data["description"],

$data["price"],

$data["old_price"],

$data["stock"],

$data["image"],

$data["status"],

$id

]);


}


}


?>