<?php


require_once "../../classes/Database.php";

require_once "../../classes/Category.php";

require_once "../../classes/Auth.php";

require_once "../../helpers/response.php";

require_once "../../helpers/validator.php";

require_once "../../config/cors.php";

require_once "../../classes/Cloudinary.php";



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

"Category name required"

);

}



$image=null;



if(isset($_FILES["image"]))
{


$file=$_FILES["image"];



$allowed=[

"image/jpeg",

"image/png",

"image/webp"

];



$finfo = finfo_open(FILEINFO_MIME_TYPE);

$type = finfo_file(
    $finfo,
    $file["tmp_name"]
);


if(!in_array($type,$allowed))
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



$db=new Database();

$conn=$db->connect();



$category=new Category($conn);



$result=$category->create(

$name,

$image

);



response(

$result,

$result
?
"Category created"
:
"Failed"

);


?>