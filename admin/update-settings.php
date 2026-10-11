<?php

include '../config/database.php';

include 'includes/auth.php';

requireAdminLogin();

/* ==========================================
        VALIDATE REQUEST
========================================== */

if($_SERVER['REQUEST_METHOD']!="POST"){

    header("Location: settings.php");

    exit;

}

/* ==========================================
        LOAD CURRENT SETTINGS
========================================== */

$query=mysqli_query(

    $conn,

    "SELECT *

     FROM settings

     WHERE id=1

     LIMIT 1"

);

if(mysqli_num_rows($query)==0){

    die("Settings record not found.");

}

$current=mysqli_fetch_assoc($query);

/* ==========================================
        SANITIZE INPUT
========================================== */

$website_name=mysqli_real_escape_string(

    $conn,

    trim($_POST['website_name'])

);

$email=mysqli_real_escape_string(

    $conn,

    trim($_POST['email'])

);

$phone=mysqli_real_escape_string(

    $conn,

    trim($_POST['phone'])

);

$whatsapp=mysqli_real_escape_string(

    $conn,

    trim($_POST['whatsapp'])

);

$address=mysqli_real_escape_string(

    $conn,

    trim($_POST['address'])

);

$business_hours=mysqli_real_escape_string(

    $conn,

    trim($_POST['business_hours'])

);

$currency=mysqli_real_escape_string(

    $conn,

    trim($_POST['currency'])

);

$facebook=mysqli_real_escape_string(

    $conn,

    trim($_POST['facebook'])

);

$instagram=mysqli_real_escape_string(

    $conn,

    trim($_POST['instagram'])

);

$youtube=mysqli_real_escape_string(

    $conn,

    trim($_POST['youtube'])

);

$tiktok=mysqli_real_escape_string(

    $conn,

    trim($_POST['tiktok'])

);

$linkedin=mysqli_real_escape_string(

    $conn,

    trim($_POST['linkedin'])

);

$twitter=mysqli_real_escape_string(

    $conn,

    trim($_POST['twitter'])

);

$meta_title=mysqli_real_escape_string(

    $conn,

    trim($_POST['meta_title'])

);

$meta_description=mysqli_real_escape_string(

    $conn,

    trim($_POST['meta_description'])

);

$meta_keywords=mysqli_real_escape_string(

    $conn,

    trim($_POST['meta_keywords'])

);

$google_maps=mysqli_real_escape_string(

    $conn,

    trim($_POST['google_maps'])

);

$footer_text=mysqli_real_escape_string(

    $conn,

    trim($_POST['footer_text'])

);

/* ==========================================
        KEEP CURRENT FILES
========================================== */

$logo=$current['logo'];

$favicon=$current['favicon'];
/* ==========================================
        LOGO UPLOAD
========================================== */

if(

    isset($_FILES['logo']) &&

    $_FILES['logo']['error']==0

){

    $allowedTypes=[

        "jpg",

        "jpeg",

        "png",

        "webp",

        "svg"

    ];

    $extension=strtolower(

        pathinfo(

            $_FILES['logo']['name'],

            PATHINFO_EXTENSION

        )

    );

    if(

        in_array(

            $extension,

            $allowedTypes

        )

    ){

        $newLogo=

            "logo_".

            time().

            ".".

            $extension;

        $uploadPath=

            "../assets/uploads/logo/"

            .$newLogo;

        if(

            move_uploaded_file(

                $_FILES['logo']['tmp_name'],

                $uploadPath

            )

        ){

            if(

                !empty($logo)

                &&

                $logo!="logo.png"

                &&

                file_exists(

                    "../assets/uploads/logo/"

                    .$logo

                )

            ){

                unlink(

                    "../assets/uploads/logo/"

                    .$logo

                );

            }

            $logo=$newLogo;

        }

    }

}
/* ==========================================
        FAVICON UPLOAD
========================================== */

if(

    isset($_FILES['favicon']) &&

    $_FILES['favicon']['error']==0

){

    $allowedTypes=[

        "ico",

        "png",

        "svg"

    ];

    $extension=strtolower(

        pathinfo(

            $_FILES['favicon']['name'],

            PATHINFO_EXTENSION

        )

    );

    if(

        in_array(

            $extension,

            $allowedTypes

        )

    ){

        $newFavicon=

            "favicon_".

            time().

            ".".

            $extension;

        $uploadPath=

            "../assets/uploads/favicon/"

            .$newFavicon;

        if(

            move_uploaded_file(

                $_FILES['favicon']['tmp_name'],

                $uploadPath

            )

        ){

            if(

                !empty($favicon)

                &&

                $favicon!="favicon.ico"

                &&

                file_exists(

                    "../assets/uploads/favicon/"

                    .$favicon

                )

            ){

                unlink(

                    "../assets/uploads/favicon/"

                    .$favicon

                );

            }

            $favicon=$newFavicon;

        }

    }

}
/* ==========================================
        UPDATE SETTINGS
========================================== */

$update=mysqli_query(

    $conn,

    "UPDATE settings SET

    website_name='$website_name',

    logo='$logo',

    favicon='$favicon',

    email='$email',

    phone='$phone',

    whatsapp='$whatsapp',

    address='$address',

    business_hours='$business_hours',

    currency='$currency',

    facebook='$facebook',

    instagram='$instagram',

    youtube='$youtube',

    tiktok='$tiktok',

    linkedin='$linkedin',

    twitter='$twitter',

    meta_title='$meta_title',

    meta_description='$meta_description',

    meta_keywords='$meta_keywords',

    google_maps='$google_maps',

    footer_text='$footer_text',

    updated_at=NOW()

    WHERE id=1"

);
/* ==========================================
        RESPONSE
========================================== */

if($update){

    $_SESSION['success']="Website settings updated successfully.";

}
else{

    $_SESSION['error']="Unable to update website settings.";

}

header("Location: settings.php");

exit;