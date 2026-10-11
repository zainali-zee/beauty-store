<?php

include 'config/database.php';

$pageTitle = "Home | " . SITE_NAME;
$pageCSS   = "home.css";
$pageJS    = "home.js";

include 'includes/header.php';
include 'includes/navbar.php';

/*
|--------------------------------------------------------------------------
| Get Active Hero Slides
|--------------------------------------------------------------------------
*/

$sliderQuery = mysqli_query(
    $conn,
    "SELECT * FROM sliders
     WHERE status='Active'
     ORDER BY display_order ASC"
);

?>

<!-- ==========================================
                HERO SECTION
========================================== -->

<section class="hero">

    <div class="container">

        <div class="swiper heroSwiper">

            <div class="swiper-wrapper">

                <?php while($slide = mysqli_fetch_assoc($sliderQuery)){ ?>

                <div class="swiper-slide">

                    <div class="row align-items-center">

                        <div class="col-lg-6">

                            <div
                                class="hero-content"
                                data-aos="fade-right">

                                <h5>

                                    Welcome To

                                </h5>

                                <h1>

                                    <?php echo htmlspecialchars($slide['title']); ?>

                                </h1>

                                <p>

                                    <?php echo htmlspecialchars($slide['subtitle']); ?>

                                </p>

                                <div class="hero-buttons">

                                    <a
                                        href="<?php echo htmlspecialchars($slide['button_link']); ?>"
                                        class="btn-main">

                                        <?php echo htmlspecialchars($slide['button_text']); ?>

                                    </a>

                                </div>

                            </div>

                        </div>

                        <div class="col-lg-6 text-center">

                            <div
                                class="hero-image"
                                data-aos="fade-left">

                                <img
                                    src="assets/uploads/sliders/<?php echo htmlspecialchars($slide['image']); ?>"
                                    alt="<?php echo htmlspecialchars($slide['title']); ?>">

                            </div>

                        </div>

                    </div>

                </div>

                <?php } ?>

            </div>

            <!-- Slider Controls -->

            <div class="swiper-pagination"></div>

            <div class="swiper-button-next"></div>

            <div class="swiper-button-prev"></div>

        </div>

    </div>

</section>
<!-- ==========================================
        CATEGORIES SECTION
========================================== -->

<?php

$categoryQuery = mysqli_query(
    $conn,
    "SELECT * FROM categories
     WHERE status='Active'
     ORDER BY id ASC"
);

?>

<section class="categories-section">

    <div class="container">

        <div
            class="section-title"
            data-aos="fade-up">

            <span>

                Categories

            </span>

            <h2>

                Shop By Category

            </h2>

            <p>

                Explore our premium skincare and beauty collections.

            </p>

        </div>

        <div class="row">

            <?php

            while($category = mysqli_fetch_assoc($categoryQuery)){

            ?>

            <div
                class="col-lg-3 col-md-6 col-sm-6 mb-4"
                data-aos="zoom-in">

                <div class="category-card">

                    <img

                        src="assets/uploads/categories/<?php echo htmlspecialchars($category['category_image']); ?>"

                        alt="<?php echo htmlspecialchars($category['category_name']); ?>">

                    <div class="category-content">

                        <h4>

                            <?php echo htmlspecialchars($category['category_name']); ?>

                        </h4>

                        <a

                            href="category.php?id=<?php echo $category['id']; ?>"

                            class="btn-main">

                            View Products

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
        FEATURED PRODUCTS
========================================== -->

<?php

$featuredProducts = mysqli_query(
    $conn,
    "SELECT * FROM products
     WHERE featured='Yes'
     AND status='Active'
     ORDER BY id DESC
     LIMIT 8"
);

?>

<section class="featured-products">

    <div class="container">

        <div class="section-title" data-aos="fade-up">

            <span>Featured Products</span>

            <h2>Our Best Products</h2>

            <p>
                Discover our most popular skincare and beauty products.
            </p>

        </div>

        <div class="row">

            <?php while($product = mysqli_fetch_assoc($featuredProducts)){ ?>

            <div class="col-lg-3 col-md-6 mb-4">

                <div class="product-card" data-aos="zoom-in">

                    <div class="product-image">

                        <img
                        src="assets/uploads/products/<?php echo htmlspecialchars($product['product_image']); ?>"
                        alt="<?php echo htmlspecialchars($product['product_name']); ?>">

                    </div>

                    <div class="product-content">

                        <h5>

                            <?php echo htmlspecialchars($product['product_name']); ?>

                        </h5>

                        <div class="price">

                            <?php if(!empty($product['old_price'])){ ?>

                                <del>
                                    <?php echo CURRENCY . number_format($product['old_price'],2); ?>
                                </del>

                            <?php } ?>

                            <span>

                                <?php echo CURRENCY . number_format($product['price'],2); ?>

                            </span>

                        </div>

                        <a
                        href="product.php?id=<?php echo $product['id']; ?>"
                        class="btn-main">

                            View Details

                        </a>

                    </div>

                </div>

            </div>

            <?php } ?>

        </div>

    </div>

</section>
<!-- ==========================================
        WHY CHOOSE US
========================================== -->

<?php

$whyChooseQuery = mysqli_query(
    $conn,
    "SELECT *
     FROM why_choose_us
     WHERE status='Active'
     ORDER BY id ASC"
);

?>

<section class="why-choose">

    <div class="container">

        <div class="section-title" data-aos="fade-up">

            <span>Why Choose Us</span>

            <h2>Why Customers Love SCS Star Beauty</h2>

            <p>

                We are committed to providing premium-quality skincare and beauty products that deliver excellent results.

            </p>

        </div>

        <div class="row">

            <?php while($item = mysqli_fetch_assoc($whyChooseQuery)){ ?>

            <div
                class="col-lg-3 col-md-6 mb-4">

                <div
                    class="why-card"
                    data-aos="zoom-in">

                    <div class="why-icon">

                        <i class="<?php echo htmlspecialchars($item['icon']); ?>"></i>

                    </div>

                    <h4>

                        <?php echo htmlspecialchars($item['title']); ?>

                    </h4>

                    <p>

                        <?php echo htmlspecialchars($item['description']); ?>

                    </p>

                </div>

            </div>

            <?php } ?>

        </div>

    </div>

</section>
<!-- ==========================================
        TESTIMONIALS
========================================== -->

<?php

$testimonialQuery = mysqli_query(
    $conn,
    "SELECT *
     FROM testimonials
     WHERE status='Active'
     ORDER BY id DESC"
);

?>

<section class="testimonials-section">

    <div class="container">

        <div class="section-title" data-aos="fade-up">

            <span>Testimonials</span>

            <h2>What Our Customers Say</h2>

            <p>

                Trusted by customers who love our skincare and beauty products.

            </p>

        </div>

        <div class="swiper testimonialSwiper">

            <div class="swiper-wrapper">

                <?php while($testimonial = mysqli_fetch_assoc($testimonialQuery)){ ?>

                <div class="swiper-slide">

                    <div class="testimonial-card">

                        <img
                            src="assets/uploads/testimonials/<?php echo htmlspecialchars($testimonial['image']); ?>"
                            alt="<?php echo htmlspecialchars($testimonial['customer_name']); ?>"
                            class="testimonial-image">

                        <h4>

                            <?php echo htmlspecialchars($testimonial['customer_name']); ?>

                        </h4>

                        <small>

                            <?php echo htmlspecialchars($testimonial['designation']); ?>

                        </small>

                        <div class="stars">

                            <?php

                            for($i=1;$i<=5;$i++){

                                if($i <= $testimonial['rating']){

                                    echo '<i class="fa-solid fa-star"></i>';

                                }else{

                                    echo '<i class="fa-regular fa-star"></i>';

                                }

                            }

                            ?>

                        </div>

                        <p>

                            "<?php echo htmlspecialchars($testimonial['review']); ?>"

                        </p>

                    </div>

                </div>

                <?php } ?>

            </div>

            <div class="swiper-pagination"></div>

        </div>

    </div>

</section>
<!-- ==========================================
        NEWSLETTER SECTION
========================================== -->

<section class="newsletter-section">

    <div class="container">

        <div class="newsletter-box" data-aos="zoom-in">

            <div class="row align-items-center">

                <div class="col-lg-6">

                    <span class="newsletter-tag">

                        Stay Updated

                    </span>

                    <h2>

                        Get Beauty Tips & Exclusive Offers

                    </h2>

                    <p>

                        Subscribe to receive skincare tips, new product updates,
                        special discounts and exclusive offers directly in your inbox.

                    </p>

                </div>

                <div class="col-lg-6">

                    <form class="newsletter-form">

                        <div class="input-group">

                            <input
                                type="email"
                                class="form-control"
                                placeholder="Enter your email address"
                                required>

                            <button
                                type="submit"
                                class="btn btn-main">

                                Subscribe

                            </button>

                        </div>

                    </form>

                    <div class="contact-buttons mt-4">

                        <a
                            href="contact.php"
                            class="btn btn-outline-custom">

                            Contact Us

                        </a>

                        <a
                            href="#"
                            class="btn btn-success ms-2">

                            <i class="fab fa-whatsapp"></i>

                            WhatsApp

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>
<!-- ==========================================
        PAGE LOADER
========================================== -->

<div id="loader">

    <div class="loader-spinner"></div>

</div>

<!-- ==========================================
        SCROLL TO TOP BUTTON
========================================== -->

<button id="scrollTopBtn">

    <i class="fa-solid fa-arrow-up"></i>

</button>

<!-- ==========================================
        SCROLL PROGRESS BAR
========================================== -->

<div id="scrollProgress"></div>

<?php

include 'includes/footer.php';
include 'includes/scripts.php';

?>