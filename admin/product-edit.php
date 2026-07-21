<?php

require_once "auth.php";

require_once "../classes/Database.php";

require_once "../classes/Cloudinary.php";

require_once "../helpers/response.php";


$db = new Database();

$conn = $db->connect();



$id = $_GET["id"] ?? null;


if (!$id) {
    response(
        false,
        "Product ID required"
    );
}



// جلب المنتج

$stmt = $conn->prepare(

"SELECT

id,

category_id,

NAME AS name,

description,

price,

old_price,

stock,

image,

STATUS

FROM products

WHERE id=?"

);


$stmt->execute([$id]);


$product = $stmt->fetch();


if (!$product) {
    response(
        false,
        "Product not found"
    );
}



// جلب التصنيفات

$categories = $conn->query(

"SELECT id,name

FROM categories

ORDER BY name"

)->fetchAll();




$error = null;



if ($_POST) {

    verify_csrf();

    $name = $_POST["name"];

    $category = $_POST["category_id"];

    $description = $_POST["description"];

    $price = $_POST["price"];

    $old_price = $_POST["old_price"] ?: null;

    $stock = $_POST["stock"];

    $status = $_POST["status"];



    $image = $product["image"];



    // صورة جديدة

    if (
        isset($_FILES["image"])
        &&
        $_FILES["image"]["error"] === 0
    ) {


        $cloudinary = new Cloudinary();


        $newImage = $cloudinary->upload(

            $_FILES["image"]

        );


        if ($newImage) {

            $image = $newImage;

        }

    }




    $update = $conn->prepare(

        "UPDATE products SET

        category_id=?,

        NAME=?,

        description=?,

        price=?,

        old_price=?,

        stock=?,

        image=?,

        STATUS=?

        WHERE id=?"

    );



    $result = $update->execute([


        $category,

        $name,

        $description,

        $price,

        $old_price,

        $stock,

        $image,

        $status,

        $id


    ]);




    if ($result) {

        header(
            "Location: products.php"
        );

        exit;

    }


    $error = "Update failed";


}

?>

<!DOCTYPE html>

<html>

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width,initial-scale=1">

    <title>Edit Product</title>


    <style>
        * {

            box-sizing: border-box;
            font-family: "Segoe UI";

        }


        body {

            margin: 0;

            background: #f1f5f9;

        }


        .container {

            max-width: 700px;

            margin: 40px auto;

            padding: 20px;

        }



        .card {

            background: white;

            padding: 35px;

            border-radius: 25px;

            box-shadow: 0 20px 50px rgba(0, 0, 0, .1);

        }



        h1 {

            margin-bottom: 30px;

        }



        .field {

            margin-bottom: 20px;

        }



        label {

            display: block;

            font-weight: 600;

            margin-bottom: 8px;

        }



        input,
        textarea,
        select {

            width: 100%;

            padding: 14px;

            border-radius: 12px;

            border: 1px solid #ddd;

        }



        textarea {

            height: 120px;

        }



        img {

            width: 120px;

            height: 120px;

            object-fit: cover;

            border-radius: 15px;

            margin-bottom: 15px;

        }



        button {

            width: 100%;

            padding: 15px;

            border: 0;

            border-radius: 15px;

            background: #2563eb;

            color: white;

            font-size: 17px;

            font-weight: bold;

            cursor: pointer;

        }



        .error {

            background: #fee2e2;

            padding: 15px;

            color: #b91c1c;

            border-radius: 12px;

        }
    </style>


</head>

<body>


    <div class="container">

        <div class="card">


            <h1>
                ✏ Edit Product
            </h1>



            <?php if ($error): ?>

                <div class="error">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </div>

            <?php endif; ?>



            <form method="POST" enctype="multipart/form-data">



                <div class="field">

                    <label>Category</label>

                    <select name="category_id">


                        <?php foreach ($categories as $c): ?>


                            <option

                                value="<?= htmlspecialchars($c["id"], ENT_QUOTES, 'UTF-8') ?>"

                                <?=

                                $c["id"] == $product["category_id"]

                                ?
                                "selected"
                                :
                                ""

                                ?>

                                >


                                <?= htmlspecialchars($c["name"], ENT_QUOTES, 'UTF-8') ?>


                            </option>


                        <?php endforeach; ?>


                    </select>

                </div>




                <div class="field">

                    <label>Name</label>

                    <input

                        name="name"

                        value="<?= htmlspecialchars($product["name"], ENT_QUOTES, 'UTF-8') ?>">

                </div>




                <div class="field">

                    <label>Description</label>


                    <textarea name="description">

                        <?= htmlspecialchars($product["description"], ENT_QUOTES, 'UTF-8') ?>
                    </textarea>


                </div>




                <div class="field">

                    <label>Price</label>

                    <input

                        type="number"

                        step="0.01"

                        name="price"

                        value="<?= htmlspecialchars($product["price"], ENT_QUOTES, 'UTF-8') ?>">


                </div>




                <div class="field">

                    <label>Old Price</label>

                    <input

                        type="number"

                        step="0.01"

                        name="old_price"

                        value="<?= htmlspecialchars($product["old_price"], ENT_QUOTES, 'UTF-8') ?>">


                </div>




                <div class="field">

                    <label>Stock</label>

                    <input

                        type="number"

                        name="stock"

                        value="<?= htmlspecialchars($product["stock"], ENT_QUOTES, 'UTF-8') ?>">


                </div>




                <div class="field">

                    <label>Status</label>


                    <select name="status">


                        <option

                            value="active"

                            <?= $product["STATUS"] == "active" ? "selected" : "" ?>>active</option>


                        <option

                            value="inactive"

                            <?= $product["STATUS"] == "inactive" ? "selected" : "" ?>>inactive</option>


                    </select>


                </div>




                <div class="field">

                    <label>Current Image</label>


                    <?php if ($product["image"]): ?>

                        <br>

                        <img src="<?= htmlspecialchars($product["image"], ENT_QUOTES, 'UTF-8') ?>">


                    <?php endif; ?>


                    <input

                        type="file"

                        name="image"

                        accept="image/*">


                </div>


                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">


                <button>

                    Save Changes
                </button>



            </form>



        </div>

    </div>


</body>

</html>