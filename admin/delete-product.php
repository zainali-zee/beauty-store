<?php

header('Content-Type: application/json');

include 'includes/auth.php';

include '../config/database.php';
include '../config/settings.php';

requireAdminLogin();

/* ==========================================
        VALIDATE REQUEST
========================================== */

if(

    !isset($_POST['product_id'])

    ||

    !is_numeric($_POST['product_id'])

){

    echo json_encode([

        "success"=>false,

        "message"=>"Invalid product."

    ]);

    exit;

}

$productId=(int)$_POST['product_id'];
/* ==========================================
        GET PRODUCT
========================================== */

$query=mysqli_query(

$conn,

"SELECT

id,

product_image

FROM products

WHERE id='$productId'

LIMIT 1"

);

if(mysqli_num_rows($query)==0){

echo json_encode([

"success"=>false,

"message"=>"Product not found."

]);

exit;

}

$product=mysqli_fetch_assoc($query);
/* ==========================================
        GET GALLERY
========================================== */

$gallery=mysqli_query(

$conn,

"SELECT

image

FROM product_gallery

WHERE product_id='$productId'"

);
/* ==========================================
        DELETE GALLERY IMAGES
========================================== */

while($row=mysqli_fetch_assoc($gallery)){

    $galleryImage =

        "uploads/gallery/"

        .$row['image'];

    if(

        file_exists($galleryImage)

    ){

        unlink($galleryImage);

    }

}

mysqli_query(

    $conn,

    "DELETE FROM product_gallery

     WHERE product_id='$productId'"

);

/* ==========================================
        DELETE MAIN IMAGE
========================================== */

if(

    $product['product_image']!=""

){

    $productImage =

        "uploads/products/"

        .$product['product_image'];

    if(

        file_exists($productImage)

    ){

        unlink($productImage);

    }

}
/* ==========================================
        DELETE PRODUCT
========================================== */

$delete = mysqli_query(

    $conn,

    "DELETE FROM products

     WHERE id='$productId'

     LIMIT 1"

);

if($delete){

    echo json_encode([

        "success"=>true,

        "message"=>"Product deleted successfully."

    ]);

}
else{

    echo json_encode([

        "success"=>false,

        "message"=>"Unable to delete product."

    ]);

}

exit;