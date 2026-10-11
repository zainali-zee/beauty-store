<?php

header('Content-Type: application/json');

include 'includes/auth.php';
include '../config/database.php';
include '../config/settings.php';

requireAdminLogin();

/* ==========================================
        VALIDATE REQUEST
========================================== */

if (
    !isset($_GET['id']) ||
    !is_numeric($_GET['id'])
) {

    echo json_encode([
        "success" => false,
        "message" => "Invalid product ID."
    ]);

    exit;
}

$productId = (int)$_GET['id'];

/* ==========================================
        GET PRODUCT
========================================== */

$productQuery = mysqli_query(

    $conn,

    "SELECT
        p.*,
        c.category_name
     FROM products p
     LEFT JOIN categories c
     ON p.category_id = c.id
     WHERE p.id='$productId'
     LIMIT 1"

);

if(mysqli_num_rows($productQuery)==0){

    echo json_encode([

        "success"=>false,

        "message"=>"Product not found."

    ]);

    exit;

}

$product = mysqli_fetch_assoc($productQuery);

/* ==========================================
        GET GALLERY
========================================== */

$gallery = [];

$galleryQuery = mysqli_query(

    $conn,

    "SELECT
        id,
        image
     FROM product_gallery
     WHERE product_id='$productId'
     ORDER BY id ASC"

);

while($row=mysqli_fetch_assoc($galleryQuery)){

    $gallery[] = [

        "id"=>$row["id"],

        "image"=>$row["image"]

    ];

}

/* ==========================================
        RESPONSE
========================================== */

$response = [

    "success"=>true,

    "product"=>[

        "id"=>$product["id"],

        "category_id"=>$product["category_id"],

        "category_name"=>$product["category_name"],

        "product_name"=>$product["product_name"],

        "slug"=>$product["slug"],

        "short_description"=>$product["short_description"],

        "description"=>$product["description"],

        "benefits"=>$product["benefits"],

        "ingredients"=>$product["ingredients"],

        "usage_method"=>$product["usage_method"],

        "sku"=>$product["sku"],

        "brand"=>$product["brand"],

        "weight"=>$product["weight"],

        "price"=>$product["price"],

        "old_price"=>$product["old_price"],

        "stock"=>$product["stock"],

        "featured"=>$product["featured"],

        "status"=>$product["status"],

        "product_image"=>$product["product_image"]

    ],

    "gallery"=>$gallery

];

echo json_encode(

    $response,

    JSON_UNESCAPED_UNICODE

);

exit;