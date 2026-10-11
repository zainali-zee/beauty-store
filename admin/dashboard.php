<link rel="stylesheet" href="assets/css/dashboard.css">
<?php

include 'includes/auth.php';
include '../config/database.php';
include '../config/settings.php';

requireAdminLogin();

include 'includes/header.php';

include 'includes/navbar.php';

include 'includes/sidebar.php';
/* ==========================================
        DASHBOARD STATISTICS
========================================== */

$totalCategories = mysqli_fetch_row(

    mysqli_query(

        $conn,

        "SELECT COUNT(*) FROM categories"

    )

)[0];

$totalProducts = mysqli_fetch_row(

    mysqli_query(

        $conn,

        "SELECT COUNT(*) FROM products"

    )

)[0];

$featuredProducts = mysqli_fetch_row(

    mysqli_query(

        $conn,

        "SELECT COUNT(*) FROM products
         WHERE featured='Yes'"

    )

)[0];

$totalMessages = mysqli_fetch_row(

    mysqli_query(

        $conn,

        "SELECT COUNT(*) FROM contact_messages"

    )

)[0];
$pageTitle = "Dashboard | Admin";
$pageCSS = "dashboard.css";
$pageJS = "dashboard.js";
/* ==========================================
        RECENT DATA
========================================== */

/* Recent Messages */

$recentMessages = mysqli_query(

    $conn,

    "SELECT
        full_name,
        subject,
        created_at
     FROM contact_messages
     ORDER BY id DESC
     LIMIT 5"

);

/* Latest Products */

$latestProducts = mysqli_query(

    $conn,

    "SELECT
        product_name,
        price,
        stock
     FROM products
     ORDER BY id DESC
     LIMIT 5"

);


?><div class="dashboard-content">

    <!-- Welcome Banner -->

    <div class="welcome-banner">

        <div>

            <h2>

                Welcome Back,
                <?php echo htmlspecialchars($_SESSION['admin_name']); ?> 👋

            </h2>

            <p>

                Manage your products, categories, website settings and customer messages from one place.

            </p>

        </div>

        <div class="welcome-date">

            <h5>

                <?php echo date("l"); ?>

            </h5>

            <p>

                <?php echo date("d F Y"); ?>

            </p>

        </div>

    </div>
    <!-- ==========================================
        STATISTICS
========================================== -->


<div class="row g-4 mb-4">

    <div class="col-lg-3 col-md-6">

        <div class="stats-card">

            <i class="fa-solid fa-layer-group"></i>

            <h2>

                <?php echo $totalCategories; ?>

            </h2>

            <p>

                Categories

            </p>

        </div>

    </div>

    <div class="col-lg-3 col-md-6">

        <div class="stats-card">

            <i class="fa-solid fa-box-open"></i>

            <h2>

                <?php echo $totalProducts; ?>

            </h2>

            <p>

                Products

            </p>

        </div>

    </div>

    <div class="col-lg-3 col-md-6">

        <div class="stats-card">

            <i class="fa-solid fa-star"></i>

            <h2>

                <?php echo $featuredProducts; ?>

            </h2>

            <p>

                Featured Products

            </p>

        </div>

    </div>

    <div class="col-lg-3 col-md-6">

        <div class="stats-card">

            <i class="fa-solid fa-envelope"></i>

            <h2>

                <?php echo $totalMessages; ?>

            </h2>

            <p>

                Messages

            </p>

        </div>

    </div>

</div>

<div class="row">

    <!-- Recent Messages -->

    <div class="col-lg-6">

        <div class="dashboard-box">

            <h3>

                <i class="fa-solid fa-envelope"></i>

                Recent Messages

            </h3>

            <div class="table-responsive">

                <table class="table table-hover">

                    <thead>

                        <tr>

                            <th>Name</th>

                            <th>Subject</th>

                            <th>Date</th>

                        </tr>

                    </thead>

                    <tbody>

                    <?php

                    if(mysqli_num_rows($recentMessages)>0){

                        while($row=mysqli_fetch_assoc($recentMessages)){

                    ?>

                        <tr>

                            <td>

                                <?php echo htmlspecialchars($row['full_name']); ?>

                            </td>

                            <td>

                                <?php echo htmlspecialchars($row['subject']); ?>

                            </td>

                            <td>

                                <?php echo date("d M",strtotime($row['created_at'])); ?>

                            </td>

                        </tr>

                    <?php

                        }

                    }else{

                    ?>

                        <tr>

                            <td colspan="3" class="text-center">

                                No messages found.

                            </td>

                        </tr>

                    <?php

                    }

                    ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <!-- Latest Products -->

    <div class="col-lg-6">

        <div class="dashboard-box">

            <h3>

                <i class="fa-solid fa-box-open"></i>

                Latest Products

            </h3>

            <div class="table-responsive">

                <table class="table table-hover">

                    <thead>

                        <tr>

                            <th>Product</th>

                            <th>Price</th>

                            <th>Stock</th>

                        </tr>

                    </thead>

                    <tbody>

                    <?php

                    if(mysqli_num_rows($latestProducts)>0){

                        while($row=mysqli_fetch_assoc($latestProducts)){

                    ?>

                        <tr>

                            <td>

                                <?php echo htmlspecialchars($row['product_name']); ?>

                            </td>

                            <td>

                                <?php echo CURRENCY.number_format($row['price'],2); ?>

                            </td>

                            <td>

                                <?php echo $row['stock']; ?>

                            </td>

                        </tr>

                    <?php

                        }

                    }else{

                    ?>

                        <tr>

                            <td colspan="3" class="text-center">

                                No products available.

                            </td>

                        </tr>

                    <?php

                    }

                    ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<!-- Quick Actions -->

<div class="dashboard-box">

    <h3>

        <i class="fa-solid fa-bolt"></i>

        Quick Actions

    </h3>

    <div class="quick-actions">

        <a href="categories.php" class="btn btn-primary">

            <i class="fa-solid fa-plus"></i>

            Manage Categories

        </a>

        <a href="products.php" class="btn btn-success">

            <i class="fa-solid fa-plus"></i>

            Manage Products

        </a>

        <a href="messages.php" class="btn btn-warning">

            <i class="fa-solid fa-envelope"></i>

            View Messages

        </a>

        <a href="settings.php" class="btn btn-dark">

            <i class="fa-solid fa-gear"></i>

            Website Settings

        </a>

    </div>

</div>

</div>

<?php

include 'includes/footer.php';

include 'includes/scripts.php';

?>