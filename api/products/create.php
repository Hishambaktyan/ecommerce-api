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



$image = null;



if(isset($_FILES["image"]))
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



$db = new Database();

$conn = $db->connect();



$product = new Product($conn);



$result = $product->create([

    "category_id" =>
    $_POST["category_id"] ?? null,

    "name" =>
    $name,

    "description" =>
    $_POST["description"] ?? "",

    "price" =>
    $_POST["price"] ?? 0,

    "old_price" =>
    $_POST["old_price"] ?? null,

    "stock" =>
    $_POST["stock"] ?? 0,

    "image" =>
    $image

]);



if($result)
{

    response(
        true,
        "Product created"
    );

}



response(
    false,
    "Failed"
);

?>