<nav class="navbar navbar-expand-lg custom-navbar sticky-top">

    <div class="container">

        <!-- Logo -->
        <a class="navbar-brand" href="index.php">

            <i class="fa-solid fa-spa"></i>

            <?php echo SITE_NAME; ?>

        </a>

        <!-- Mobile Menu Button -->
        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mainNavbar"
                aria-controls="mainNavbar"
                aria-expanded="false"
                aria-label="Toggle navigation">

            <span class="navbar-toggler-icon"></span>

        </button>

        <!-- Navigation Links -->
        <div class="collapse navbar-collapse"
             id="mainNavbar">

            <ul class="navbar-nav mx-auto">

                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="products.php">Products</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="category.php">Categories</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="about.php">About</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="contact.php">Contact</a>
                </li>

            </ul>

            <!-- Right Icons -->
            <div class="navbar-icons">

                <a href="search.php"
                   title="Search">

                    <i class="fa-solid fa-magnifying-glass"></i>

                </a>

                <a href="#"
                   title="WhatsApp">

                    <i class="fa-brands fa-whatsapp"></i>

                </a>

                <a href="admin/login.php"
                   title="Admin">

                    <i class="fa-solid fa-user-shield"></i>

                </a>

            </div>

        </div>

    </div>

</nav>