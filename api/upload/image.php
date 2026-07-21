<?php


require_once "../../classes/Auth.php";

require_once "../../classes/Cloudinary.php";

require_once "../../helpers/response.php";

require_once "../../config/cors.php";



$auth = new Auth();



if(!$auth->isAdmin())
{

    response(
        false,
        "Admin access required"
    );

}




if(!isset($_FILES["image"]))
{

    response(
        false,
        "Image required"
    );

}



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
        "Invalid image type"
    );
}



$cloudinary = new Cloudinary();



$url =
$cloudinary->upload($file);



if(!$url)
{

    response(
        false,
        "Upload failed"
    );

}



response(

true,

"Image uploaded",

[

"url"=>$url

]

);


?>