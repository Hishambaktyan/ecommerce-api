<?php


require_once "../../classes/Database.php";

require_once "../../classes/Auth.php";

require_once "../../classes/Cloudinary.php";

require_once "../../helpers/response.php";

require_once "../../config/cors.php";



$auth = new Auth();



if (!$auth->isAdmin()) {

    response(
        false,
        "Admin access required"
    );
}




$product_id = $_POST["product_id"] ?? null;



if (!$product_id) {

    response(
        false,
        "Product ID required"
    );
}




if (!isset($_FILES["images"])) {

    response(
        false,
        "Images required"
    );
}




// تأكد أن المستخدم أرسل images[]
if (!is_array($_FILES["images"]["tmp_name"])) {

    response(
        false,
        "Send multiple images using images[]"
    );
}




$db = new Database();

$conn = $db->connect();



$cloudinary = new Cloudinary();



$uploaded = [];




foreach ($_FILES["images"]["tmp_name"] as $key => $tmp) {


    $file = [

        "tmp_name" => $tmp,

        "name" => $_FILES["images"]["name"][$key],

        "type" => $_FILES["images"]["type"][$key],

        "size" => $_FILES["images"]["size"][$key]

    ];




    $url = $cloudinary->upload($file);



    if ($url) {


        $stmt = $conn->prepare(

            "
            INSERT INTO product_images
            (
                product_id,
                image
            )
            VALUES
            (
                ?,
                ?
            )
            "

        );



        $stmt->execute([

            $product_id,

            $url

        ]);



        $uploaded[] = $url;
    }
}




if (count($uploaded) == 0) {

    response(
        false,
        "No images uploaded"
    );
}




response(

    true,

    "Images uploaded",

    $uploaded

);
