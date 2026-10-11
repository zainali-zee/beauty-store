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
    !isset($_POST['gallery_id']) ||
    !is_numeric($_POST['gallery_id'])
) {

    echo json_encode([
        "success" => false,
        "message" => "Invalid gallery image."
    ]);

    exit;
}

$galleryId = (int)$_POST['gallery_id'];

/* ==========================================
        GET IMAGE
========================================== */

$query = mysqli_query(

    $conn,

    "SELECT
        id,
        image
     FROM product_gallery
     WHERE id='$galleryId'
     LIMIT 1"

);

if(mysqli_num_rows($query)==0){

    echo json_encode([

        "success"=>false,

        "message"=>"Gallery image not found."

    ]);

    exit;

}

$image = mysqli_fetch_assoc($query);

/* ==========================================
        DELETE IMAGE FILE
========================================== */

$imagePath =

    "uploads/gallery/"

    .$image["image"];

if(

    file_exists($imagePath)

){

    unlink($imagePath);

}

/* ==========================================
        DELETE DATABASE RECORD
========================================== */

$delete = mysqli_query(

    $conn,

    "DELETE FROM product_gallery

     WHERE id='$galleryId'

     LIMIT 1"

);

if($delete){

    echo json_encode([

        "success"=>true

    ]);

}
else{

    echo json_encode([

        "success"=>false,

        "message"=>"Unable to delete image."

    ]);

}