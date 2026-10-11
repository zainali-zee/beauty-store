<?php
/**
 * ==========================================================
 * SCS Star Beauty
 * Shared Header
 * ==========================================================
 */

include_once __DIR__ . '/config.php';

if (!isset($pageTitle)) {
    $pageTitle = SITE_NAME;
}

if (!isset($pageCSS)) {
    $pageCSS = '';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <!-- ==========================================
         Basic Meta
    =========================================== -->

    <meta charset="UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <!-- ==========================================
         SEO
    =========================================== -->

    <title><?php echo htmlspecialchars($pageTitle); ?></title>

    <meta name="description"
          content="Premium Skincare, Haircare and Beauty Products.">

    <meta name="keywords"
          content="Beauty, Skincare, Serum, Facewash, Cosmetics">

    <meta name="author"
          content="<?php echo SITE_NAME; ?>">

    <meta name="theme-color"
          content="#d63384">

    <!-- ==========================================
         Google Fonts
    =========================================== -->

    <link rel="preconnect"
          href="https://fonts.googleapis.com">

    <link rel="preconnect"
          href="https://fonts.gstatic.com"
          crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap"
          rel="stylesheet">

    <!-- ==========================================
         Bootstrap
    =========================================== -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- ==========================================
         Font Awesome
    =========================================== -->

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- ==========================================
         AOS
    =========================================== -->

    <link rel="stylesheet"
          href="https://unpkg.com/aos@2.3.4/dist/aos.css">

    <!-- ==========================================
         Swiper
    =========================================== -->

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

    <!-- ==========================================
         Global CSS
    =========================================== -->

    <link rel="stylesheet"
          href="assets/css/style.css">

    <link rel="stylesheet"
          href="assets/css/navbar.css">

    <link rel="stylesheet"
          href="assets/css/footer.css">

    <!-- <link rel="stylesheet"
          href="assets/css/animations.css">

    <link rel="stylesheet"
          href="assets/css/responsive.css"> -->

    <!-- ==========================================
         Page CSS
    =========================================== -->

    <?php
    if (!empty($pageCSS)) {
        echo '<link rel="stylesheet" href="assets/css/' . htmlspecialchars($pageCSS) . '">';
    }
    ?>

</head>

<body>