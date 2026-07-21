<?php


require_once "../../classes/Database.php";

require_once "../../classes/Product.php";

require_once "../../classes/Auth.php";

require_once "../../classes/Cloudinary.php";

require_once "../../helpers/response.php";

require_once "../../helpers/validator.php";

require_once "../../config/cors.php";



$auth = new Auth();



if(!$auth->isAdmin())
{

    response(
        false,
        "Admin access required"
    );

}



$id = $_POST["id"] ?? null;



if(!$id)
{

    response(
        false,
        "Product ID required"
    );

}



$name = clean(
    $_POST["name"] ?? ""
);



if(!$name)
{

    response(
        false,
        "Product name required"
    );

}



$db = new Database();

$conn = $db->connect();



$product = new Product($conn);

$existing = $product->find($id);



if(!$existing)
{

    response(
        false,
        "Product not found"
    );

}



// الصورة الافتراضية = الصورة الحالية، تتغيّر بس لو المستخدم رفع صورة جديدة
$image = $existing["image"];



if(isset($_FILES["image"]) && $_FILES["image"]["error"] === 0)
{

    $file = $_FILES["image"];

    $allowed = [

        "image/jpeg",

        "image/png",

        "image/webp"

    ];

    $finfo = finfo_open(FILEINFO_MIME_TYPE);

    $type = finfo_file(
        $finfo,
        $file["tmp_name"]
    );

    if(!in_array($type, $allowed))
    {

        response(
            false,
            "Invalid image"
        );

    }

    $cloudinary = new Cloudinary();

    try {

        $image = $cloudinary->upload($file);

    } catch (Exception $e) {

        response(
            false,
            "Image upload failed"
        );

    }

    if (!$image) {

        response(
            false,
            "Image upload failed"
        );

    }

}



$stmt = $conn->prepare(

"
UPDATE products SET

category_id=?,

name=?,

description=?,

price=?,

old_price=?,

stock=?,

image=?

WHERE id=?

"

);



$result = $stmt->execute([


    $_POST["category_id"] ?? null,


    $name,


    $_POST["description"] ?? "",


    $_POST["price"] ?? 0,


    $_POST["old_price"] ?? null,


    $_POST["stock"] ?? 0,


    $image,


    $id


]);




response(

    $result,

    $result
    ?
    "Product updated"
    :
    "Update failed"

);


?>