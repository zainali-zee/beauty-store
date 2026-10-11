<div class="admin-sidebar">

    <!-- Logo -->

    <div class="sidebar-logo">

        <img
            src="../assets/images/logo.png"
            alt="Logo">

        <h4>

            SCS Star Beauty

        </h4>

    </div>

    <!-- Admin -->

    <div class="admin-profile">

        <i class="fa-solid fa-user-shield"></i>

        <h6>

            <?php echo htmlspecialchars($_SESSION['admin_name']); ?>

        </h6>

        <small>

            Administrator

        </small>

    </div>

    <!-- Menu -->

    <ul class="sidebar-menu">

        <li>

            <a
                href="dashboard.php"
                class="<?php echo basename($_SERVER['PHP_SELF'])=='dashboard.php' ? 'active' : ''; ?>">

                <i class="fa-solid fa-gauge"></i>

                Dashboard

            </a>

        </li>

        <li>
<a
    href="categories.php"
    class="<?php echo basename($_SERVER['PHP_SELF'])=='categories.php' ? 'active' : ''; ?>">

                <i class="fa-solid fa-layer-group"></i>

                Categories

            </a>

        </li>

        <li>
<a
    href="products.php"
    class="<?php echo basename($_SERVER['PHP_SELF'])=='products.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-box-open"></i>

                Products

            </a>

        </li>

        <li>
<a
    href="messages.php"
    class="<?php echo basename($_SERVER['PHP_SELF'])=='messages.php' ? 'active' : ''; ?>">

                <i class="fa-solid fa-envelope"></i>

                Messages

            </a>

        </li>

       <li>

    <a

        href="settings.php"

        class="<?php echo basename($_SERVER['PHP_SELF'])=="settings.php" ? "active" : ""; ?>">

        <i class="fa-solid fa-gear"></i>

        Website Settings

    </a>

</li>
<li>
    <a href="sliders/index.php">
        <i class="fas fa-images"></i>
        <span>Sliders</span>
    </a>
</li>
        <li>

            <a
                href="logout.php"
                class="logout-link">

                <i class="fa-solid fa-right-from-bracket"></i>

                Logout

            </a>

        </li>

    </ul>

</div>