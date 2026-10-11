<?php

include 'includes/auth.php';
include '../config/database.php';
include '../config/settings.php';
requireAdminLogin();

if(!isset($_POST['update_product'])){

    header("Location: products.php");

    exit;

}

$error = "";
$success = "";
/* ==========================================
        GET FORM DATA
========================================== */

$productId = (int)$_POST['product_id'];

$category_id = (int)$_POST['category_id'];

$product_name = trim($_POST['product_name']);

$brand = trim($_POST['brand']);

$sku = trim($_POST['sku']);

$weight = trim($_POST['weight']);

$price = trim($_POST['price']);

$old_price = trim($_POST['old_price']);

$stock = (int)$_POST['stock'];

$featured = $_POST['featured'];

$status = $_POST['status'];

$short_description = trim($_POST['short_description']);

$description = trim($_POST['description']);

$benefits = trim($_POST['benefits']);

$ingredients = trim($_POST['ingredients']);

$usage_method = trim($_POST['usage_method']);

/* ==========================================
        ESCAPE STRINGS
========================================== */

$product_name = mysqli_real_escape_string($conn,$product_name);

$brand = mysqli_real_escape_string($conn,$brand);

$sku = mysqli_real_escape_string($conn,$sku);

$weight = mysqli_real_escape_string($conn,$weight);

$price = mysqli_real_escape_string($conn,$price);

$old_price = mysqli_real_escape_string($conn,$old_price);

$featured = mysqli_real_escape_string($conn,$featured);

$status = mysqli_real_escape_string($conn,$status);

$short_description = mysqli_real_escape_string($conn,$short_description);

$description = mysqli_real_escape_string($conn,$description);

$benefits = mysqli_real_escape_string($conn,$benefits);

$ingredients = mysqli_real_escape_string($conn,$ingredients);

$usage_method = mysqli_real_escape_string($conn,$usage_method);
/* ==========================================
        EXISTING PRODUCT
========================================== */

$productQuery = mysqli_query(

    $conn,

    "SELECT *

     FROM products

     WHERE id='$productId'

     LIMIT 1"

);

if(mysqli_num_rows($productQuery)==0){

    header("Location: products.php");

    exit;

}

$product = mysqli_fetch_assoc($productQuery);

$currentImage = $product['product_image'];
/* ==========================================
        GENERATE SLUG
========================================== */

$slug = strtolower($product_name);

$slug = preg_replace('/[^a-z0-9]+/','-',$slug);

$slug = trim($slug,'-');

$checkSlug = mysqli_query(

    $conn,

    "SELECT id

     FROM products

     WHERE

     slug='$slug'

     AND

     id!='$productId'

     LIMIT 1"

);

if(mysqli_num_rows($checkSlug)>0){

    $slug .= "-" . time();

}
/* ==========================================
        MAIN IMAGE UPDATE
========================================== */

$productImage = $currentImage;

if(

    isset($_FILES['product_image'])

    &&

    $_FILES['product_image']['name']!=""

){

    $allowed = [

        "jpg",

        "jpeg",

        "png",

        "webp"

    ];

    $extension = strtolower(

        pathinfo(

            $_FILES['product_image']['name'],

            PATHINFO_EXTENSION

        )

    );

    if(

        !in_array(

            $extension,

            $allowed

        )

    ){

        $error =

        "Only JPG, JPEG, PNG and WEBP images are allowed.";

    }

    else{

        $productImage =

            uniqid(

                "product_",

                true

            )

            .".".

            $extension;

        $uploadPath =

            "uploads/products/"

            .$productImage;

        if(

            !move_uploaded_file(

                $_FILES['product_image']['tmp_name'],

                $uploadPath

            )

        ){

            $error =

            "Failed to upload new product image.";

        }

        else{

            if(

                $currentImage!=""

                &&

                file_exists(

                    "uploads/products/"

                    .$currentImage

                )

            ){

                unlink(

                    "uploads/products/"

                    .$currentImage

                );

            }

        }

    }

}

/* ==========================================
        OPTIONAL VALUES
========================================== */

if($old_price==""){

    $old_price = "NULL";

}

else{

    $old_price = "'".$old_price."'";

}

if($brand==""){

    $brand = "SCS Star Beauty";

}

if($weight==""){

    $weight = "";

}

if($sku==""){

    $sku = "";

}
/* ==========================================
        UPDATE PRODUCT
========================================== */

if($error==""){

    $update = mysqli_query(

        $conn,

        "UPDATE products
        SET

            category_id='$category_id',

            product_name='$product_name',

            slug='$slug',

            short_description='$short_description',

            description='$description',

            benefits='$benefits',

            ingredients='$ingredients',

            usage_method='$usage_method',

            sku='$sku',

            brand='$brand',

            weight='$weight',

            price='$price',

            old_price=$old_price,

            stock='$stock',

            featured='$featured',

            product_image='$productImage',

            status='$status'

        WHERE

            id='$productId'

        LIMIT 1"

    );

    if($update){
        /* ==========================================
                ADD NEW GALLERY IMAGES
        ========================================== */

        if(

            isset($_FILES['gallery_images'])

            &&

            !empty($_FILES['gallery_images']['name'][0])

        ){

            $totalImages = count(

                $_FILES['gallery_images']['name']

            );

            for(

                $i = 0;

                $i < $totalImages;

                $i++

            ){

                if(

                    $_FILES['gallery_images']['error'][$i] == 0

                ){

                    $extension = strtolower(

                        pathinfo(

                            $_FILES['gallery_images']['name'][$i],

                            PATHINFO_EXTENSION

                        )

                    );

                    $allowed = [

                        "jpg",

                        "jpeg",

                        "png",

                        "webp"

                    ];

                    if(

                        in_array(

                            $extension,

                            $allowed

                        )

                    ){

                        $galleryImage =

                            uniqid(

                                "gallery_",

                                true

                            )

                            .".".

                            $extension;

                        $uploadPath =

                            "uploads/gallery/"

                            .$galleryImage;

                        if(

                            move_uploaded_file(

                                $_FILES['gallery_images']['tmp_name'][$i],

                                $uploadPath

                            )

                        ){

                            mysqli_query(

                                $conn,

                                "INSERT INTO product_gallery
                                (
                                    product_id,
                                    image
                                )
                                VALUES
                                (
                                    '$productId',
                                    '$galleryImage'
                                )"

                            );

                        }

                    }

                }

            }

        }
      $_SESSION['success'] = "Product updated successfully.";

header("Location: products.php");

exit;
    }

    else{

        $error =

        "Failed to update product.";

    }

}

/* ==========================================
        SHOW ERROR
========================================== */

if($error!=""){

    $_SESSION['error'] = $error;

    header(

        "Location: products.php"

    );

    exit;

}

?>