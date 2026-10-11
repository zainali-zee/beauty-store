<?php

include 'config/database.php';

$pageTitle = "Product Details | " . SITE_NAME;
$pageCSS   = "product.css";
$pageJS    = "product.js";

include 'includes/header.php';
include 'includes/navbar.php';

/* ==========================================
        PRODUCT ID
========================================== */

$productID = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($productID <= 0){

    echo "<div class='container py-5'>
            <div class='alert alert-danger'>
                Invalid Product.
            </div>
          </div>";

    include 'includes/footer.php';
    include 'includes/scripts.php';
    exit;
}

/* ==========================================
        LOAD PRODUCT
========================================== */

$productQuery = mysqli_query(

    $conn,

    "SELECT

        p.*,
        c.category_name

    FROM products p

    INNER JOIN categories c

    ON p.category_id = c.id

    WHERE

        p.id = $productID

    AND

        p.status='Active'

    LIMIT 1"

);

if(mysqli_num_rows($productQuery) == 0){

    echo "<div class='container py-5'>
            <div class='alert alert-warning'>
                Product not found.
            </div>
          </div>";

    include 'includes/footer.php';
    include 'includes/scripts.php';
    exit;
}

$product = mysqli_fetch_assoc($productQuery);
/* ==========================================
        RELATED PRODUCTS
========================================== */

$relatedQuery = mysqli_query(

    $conn,

    "SELECT *

     FROM products

     WHERE

        category_id = {$product['category_id']}

     AND

        id != {$product['id']}

     AND

        status = 'Active'

     ORDER BY RAND()

     LIMIT 4"

);
/* ==========================================
        PRODUCT GALLERY
========================================== */

$galleryQuery = mysqli_query(

    $conn,

    "SELECT *

     FROM product_gallery

     WHERE product_id = {$product['id']}

     ORDER BY id ASC"

);

?>

<!-- ==========================================
        BREADCRUMB
========================================== -->

<section class="breadcrumb-section">

    <div class="container">

        <nav>

            <ol class="breadcrumb">

                <li class="breadcrumb-item">

                    <a href="index.php">

                        Home

                    </a>

                </li>

                <li class="breadcrumb-item">

                    <a href="products.php">

                        Products

                    </a>

                </li>

                <li class="breadcrumb-item active">

                    <?php echo htmlspecialchars($product['product_name']); ?>

                </li>

            </ol>

        </nav>

    </div>

</section>

<section class="product-details">

<div class="container">

<div class="row g-5">

<!-- LEFT -->

<div class="col-lg-5">

<div class="product-image-box">

    <img

        id="mainProductImage"

        src="assets/uploads/products/<?php echo htmlspecialchars($product['product_image']); ?>"

        class="img-fluid"

        alt="<?php echo htmlspecialchars($product['product_name']); ?>">

</div>

<!-- Gallery -->

<div class="product-gallery mt-3">

    <!-- Main Image Thumbnail -->

    <img

        src="assets/uploads/products/<?php echo htmlspecialchars($product['product_image']); ?>"

        class="gallery-thumb active-thumb"

        onclick="changeProductImage(this)"

        alt="Main Image">

    <?php

    while($gallery = mysqli_fetch_assoc($galleryQuery)){

    ?>

    <img

        src="assets/uploads/gallery/<?php echo htmlspecialchars($gallery['image']); ?>"

        class="gallery-thumb"

        onclick="changeProductImage(this)"

        alt="Gallery Image">

    <?php

    }

    ?>

</div>

</div>

<!-- RIGHT -->

<div class="col-lg-7">

    <div class="product-info">

        <!-- Category -->

        <span class="product-category-badge">

            <?php echo htmlspecialchars($product['category_name']); ?>

        </span>

        <!-- Product Name -->

        <h1 class="product-title">

            <?php echo htmlspecialchars($product['product_name']); ?>

        </h1>

        <!-- Brand -->

        <?php if(!empty($product['brand'])){ ?>

        <p class="product-brand">

            <strong>Brand:</strong>

            <?php echo htmlspecialchars($product['brand']); ?>

        </p>

        <?php } ?>

        <!-- Price -->

        <div class="product-price">

            <?php

            if(

                !empty($product['old_price']) &&

                $product['old_price'] > 0

            ){

            ?>

                <del>

                    <?php

                    echo CURRENCY .

                    number_format($product['old_price'],2);

                    ?>

                </del>

            <?php

            }

            ?>

            <span>

                <?php

                echo CURRENCY .

                number_format($product['price'],2);

                ?>

            </span>

            <?php

            if(

                !empty($product['old_price']) &&

                $product['old_price'] > $product['price']

            ){

                $discount = round(

                    (

                        ($product['old_price']-$product['price'])

                        /

                        $product['old_price']

                    )*100

                );

            ?>

                <span class="discount-text">

                    Save <?php echo $discount; ?>%

                </span>

            <?php

            }

            ?>

        </div>

        <!-- Stock -->

        <div class="product-stock">

            <?php

            if($product['stock'] > 0){

            ?>

                <span class="stock available">

                    <i class="fa-solid fa-circle-check"></i>

                    In Stock

                </span>

            <?php

            }else{

            ?>

                <span class="stock unavailable">

                    <i class="fa-solid fa-circle-xmark"></i>

                    Out of Stock

                </span>

            <?php

            }

            ?>

        </div>

        <!-- Short Description -->

        <?php if(!empty($product['short_description'])){ ?>

        <div class="short-description">

            <p>

                <?php

                echo nl2br(

                    htmlspecialchars($product['short_description'])

                );

                ?>

            </p>

        </div>

        <?php } ?>

        <!-- Product Information -->

        <div class="product-meta">

            <div>

                <strong>Product ID:</strong>

                #<?php echo $product['id']; ?>

            </div>

            <div>

                <strong>Category:</strong>

                <?php echo htmlspecialchars($product['category_name']); ?>

            </div>

            <?php if(!empty($product['sku'])){ ?>

            <div>

                <strong>SKU:</strong>

                <?php echo htmlspecialchars($product['sku']); ?>

            </div>

            <?php } ?>

        </div>

        <!-- Buttons -->

        <div class="product-buttons">
            <div class="share-buttons">

    <span>

        Share :

    </span>

    <a href="#">

        <i class="fab fa-facebook-f"></i>

    </a>

    <a href="#">

        <i class="fab fa-instagram"></i>

    </a>

    <a href="#">

        <i class="fab fa-whatsapp"></i>

    </a>

</div>

            <a

                href="products.php?category=<?php echo $product['category_id']; ?>"

                class="btn btn-outline-primary">

                <i class="fa-solid fa-layer-group"></i>

                View Category

            </a>

            <a

                href="#"

                class="btn btn-success">

                <i class="fab fa-whatsapp"></i>

                Order on WhatsApp

            </a>

        </div>

    </div>

</div>

</div>

</div>

<!-- ==========================================
        RELATED PRODUCTS
========================================== -->

<section class="related-products">

    <div class="container">

        <div class="section-title">

            <span>

                Similar Products

            </span>

            <h2>

                You May Also Like

            </h2>

            <p>

                Discover more products from the same category.

            </p>

        </div>

        <div class="row">

            <?php

            while($related = mysqli_fetch_assoc($relatedQuery)){

            ?>

            <div class="col-lg-3 col-md-6 mb-4">

                <div class="product-card">

                    <div class="product-image">

                        <img

                        src="assets/uploads/products/<?php echo htmlspecialchars($related['product_image']); ?>"

                        alt="<?php echo htmlspecialchars($related['product_name']); ?>">

                    </div>

                    <div class="product-content">

                        <h5>

                            <?php

                            echo htmlspecialchars($related['product_name']);

                            ?>

                        </h5>

                        <div class="price">

                            <?php

                            if(

                                !empty($related['old_price']) &&

                                $related['old_price'] > 0

                            ){

                            ?>

                            <del>

                                <?php

                                echo CURRENCY .

                                number_format($related['old_price'],2);

                                ?>

                            </del>

                            <?php

                            }

                            ?>

                            <span>

                                <?php

                                echo CURRENCY .

                                number_format($related['price'],2);

                                ?>

                            </span>

                        </div>

                        <a

                        href="product.php?id=<?php echo $related['id']; ?>"

                        class="btn-main">

                            View Details

                        </a>

                    </div>

                </div>

            </div>

            <?php

            }

            ?>

        </div>

    </div>

</section>

<!-- ==========================================
        MISSION & VISION
========================================== -->

<section class="mission-vision">

    <div class="container">

        <div class="row g-4">

            <!-- Mission -->

            <div class="col-lg-6">

                <div
                    class="info-card"
                    data-aos="fade-right">

                    <div class="icon-box">

                        <i class="fa-solid fa-bullseye"></i>

                    </div>

                    <h3>

                        Our Mission

                    </h3>

                    <p>

                        Our mission is to provide premium-quality skincare, haircare, and beauty products that are safe, effective, and affordable. We strive to help people achieve healthy skin and hair by combining quality ingredients with trusted formulations.

                    </p>

                </div>

            </div>

            <!-- Vision -->

            <div class="col-lg-6">

                <div
                    class="info-card"
                    data-aos="fade-left">

                    <div class="icon-box">

                        <i class="fa-solid fa-eye"></i>

                    </div>

                    <h3>

                        Our Vision

                    </h3>

                    <p>

                        Our vision is to become one of Asia's most trusted beauty and skincare brands by consistently delivering innovative products, exceptional customer satisfaction, and long-term value.

                    </p>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- ==========================================
        WHY CHOOSE US
========================================== -->

<section class="why-choose">

    <div class="container">

        <div class="section-title">

            <span>

                Why Choose Us

            </span>

            <h2>

                Why Customers Trust SCS Star Beauty

            </h2>

            <p>

                We are committed to delivering premium beauty products with quality, care, and customer satisfaction.

            </p>

        </div>

        <div class="row">

            <!-- Card 1 -->

            <div class="col-lg-4 col-md-6 mb-4">

                <div
                    class="choose-card"
                    data-aos="zoom-in">

                    <div class="choose-icon">

                        <i class="fa-solid fa-leaf"></i>

                    </div>

                    <h4>

                        Premium Ingredients

                    </h4>

                    <p>

                        Carefully selected ingredients designed to nourish your skin and hair safely.

                    </p>

                </div>

            </div>

            <!-- Card 2 -->

            <div class="col-lg-4 col-md-6 mb-4">

                <div
                    class="choose-card"
                    data-aos="zoom-in"
                    data-aos-delay="150">

                    <div class="choose-icon">

                        <i class="fa-solid fa-award"></i>

                    </div>

                    <h4>

                        Trusted Quality

                    </h4>

                    <p>

                        Every product is created with a strong focus on quality, consistency, and customer satisfaction.

                    </p>

                </div>

            </div>

            <!-- Card 3 -->

            <div class="col-lg-4 col-md-6 mb-4">

                <div
                    class="choose-card"
                    data-aos="zoom-in"
                    data-aos-delay="300">

                    <div class="choose-icon">

                        <i class="fa-solid fa-heart"></i>

                    </div>

                    <h4>

                        Customer First

                    </h4>

                    <p>

                        We believe in building long-term relationships by providing excellent products and support.

                    </p>

                </div>

            </div>

        </div>

    </div>

</section>

<?php

include 'includes/footer.php';

include 'includes/scripts.php';

?>

