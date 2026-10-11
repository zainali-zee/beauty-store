<?php

include 'config/database.php';

$pageTitle = "About Us | " . SITE_NAME;
$pageCSS   = "about.css";
$pageJS    = "about.js";

include 'includes/header.php';
include 'includes/navbar.php';

?>

<!-- ==========================================
        PAGE HEADER
========================================== -->

<section class="page-banner">

    <div class="container">

        <div class="banner-content">

            <h1 data-aos="fade-up">

                About SCS Star Beauty

            </h1>

            <p data-aos="fade-up" data-aos-delay="200">

                Premium skincare and beauty products designed to help you look and feel your best.

            </p>

        </div>

    </div>

</section>

<!-- ==========================================
        ABOUT SECTION
========================================== -->

<section class="about-section">

    <div class="container">

        <div class="row align-items-center">

            <div class="col-lg-6">

                <div
                    class="about-image"
                    data-aos="fade-right">

                    <img

                        src="assets/images/about.jpg"

                        class="img-fluid"

                        alt="About SCS Star Beauty">

                </div>

            </div>

            <div class="col-lg-6">

                <div
                    class="about-content"
                    data-aos="fade-left">

                    <span class="section-subtitle">

                        Who We Are

                    </span>

                    <h2>

                        Your Trusted Beauty Partner

                    </h2>

                    <p>

                        SCS Star Beauty is committed to providing premium-quality skincare, haircare, and beauty products made with carefully selected ingredients. Our mission is to deliver products that help people achieve healthier skin and hair while maintaining affordability and quality.

                    </p>

                    <p>

                        We continuously work to improve our product range, ensuring every customer receives effective, safe, and reliable beauty solutions.

                    </p>

                    <a

                        href="products.php"

                        class="btn btn-main">

                        Explore Products

                    </a>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- ==========================================
        COMPANY STATS
========================================== -->

<section class="company-stats">

    <div class="container">

        <div class="row text-center">

            <div class="col-lg-3 col-md-6 mb-4">

                <div class="stat-card">

                    <h2>45+</h2>

                    <p>Premium Products</p>

                </div>

            </div>

            <div class="col-lg-3 col-md-6 mb-4">

                <div class="stat-card">

                    <h2>1000+</h2>

                    <p>Happy Customers</p>

                </div>

            </div>

            <div class="col-lg-3 col-md-6 mb-4">

                <div class="stat-card">

                    <h2>12+</h2>

                    <p>Product Categories</p>

                </div>

            </div>

            <div class="col-lg-3 col-md-6 mb-4">

                <div class="stat-card">

                    <h2>100%</h2>

                    <p>Quality Focused</p>

                </div>

            </div>

        </div>

    </div>

</section>

<?php

include 'includes/footer.php';

include 'includes/scripts.php';

?>
