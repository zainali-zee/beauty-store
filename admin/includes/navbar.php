<nav class="admin-navbar">

    <div class="navbar-left">

        <h3>

            Admin Dashboard

        </h3>

    </div>

    <div class="navbar-right">

        <span class="today-date">

            <i class="fa-solid fa-calendar-days"></i>

            <?php echo date("d M Y"); ?>

        </span>

        <span class="admin-user">

            <i class="fa-solid fa-user"></i>

            <?php echo htmlspecialchars($_SESSION['admin_name']); ?>

        </span>

        <a

            href="logout.php"

            class="logout-btn"

            onclick="return confirm('Are you sure you want to logout?');">

            <i class="fa-solid fa-right-from-bracket"></i>

            Logout

        </a>

    </div>

</nav>