<?php

include 'includes/auth.php';
include '../config/database.php';
include '../config/settings.php';
requireAdminLogin();

$pageTitle = "Products | Admin";
$pageCSS = "products.css";
$pageJS = "products.js";
/* ==========================================
        ADD PRODUCT
========================================== */

$success = "";

$error = "";

if (isset($_SESSION['success'])) {

?>

    <div class="alert alert-success alert-dismissible fade show">

        <i class="fa-solid fa-circle-check"></i>

        <?php

        echo $_SESSION['success'];

        unset($_SESSION['success']);

        ?>

        <button

            class="btn-close"

            data-bs-dismiss="alert">

        </button>

    </div>

<?php

}


if (isset($_SESSION['error'])) {
?>

    <div class="alert alert-danger">

        <?php

        echo $_SESSION['error'];

        unset($_SESSION['error']);

        ?>

    </div>

<?php

}


if (isset($_POST['add_product'])) {

    /* -----------------------------
        GET FORM DATA
    ------------------------------ */

    $category_id = (int)$_POST['category_id'];

    $product_name = trim($_POST['product_name']);

    $brand = trim($_POST['brand']);

    $sku = trim($_POST['sku']);

    $weight = trim($_POST['weight']);

    $price = trim($_POST['price']);

    $old_price = trim($_POST['old_price']);

    $stock = (int)$_POST['stock'];

    $featured = $_POST['featured'];

    $status = $_POST['status'];

    $short_description = trim($_POST['short_description']);

    $description = trim($_POST['description']);

    $benefits = trim($_POST['benefits']);

    $ingredients = trim($_POST['ingredients']);

    $usage_method = trim($_POST['usage_method']);

    /* -----------------------------
        ESCAPE STRINGS
    ------------------------------ */

    $product_name = mysqli_real_escape_string($conn, $product_name);

    $brand = mysqli_real_escape_string($conn, $brand);

    $sku = mysqli_real_escape_string($conn, $sku);

    $weight = mysqli_real_escape_string($conn, $weight);

    $price = mysqli_real_escape_string($conn, $price);

    $old_price = mysqli_real_escape_string($conn, $old_price);

    $featured = mysqli_real_escape_string($conn, $featured);

    $status = mysqli_real_escape_string($conn, $status);

    $short_description = mysqli_real_escape_string($conn, $short_description);

    $description = mysqli_real_escape_string($conn, $description);

    $benefits = mysqli_real_escape_string($conn, $benefits);

    $ingredients = mysqli_real_escape_string($conn, $ingredients);

    $usage_method = mysqli_real_escape_string($conn, $usage_method);

    /* -----------------------------
        VALIDATION
    ------------------------------ */

    if (

        empty($product_name)

        ||

        empty($category_id)

        ||

        empty($price)

    ) {

        $error = "Please fill all required fields.";
    }

    /* -----------------------------
        GENERATE SLUG
    ------------------------------ */

    if ($error == "") {

        $slug = strtolower($product_name);

        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);

        $slug = trim($slug, '-');

        $checkSlug = mysqli_query(

            $conn,

            "SELECT id

             FROM products

             WHERE slug='$slug'

             LIMIT 1"

        );

        if (mysqli_num_rows($checkSlug) > 0) {

            $slug .= "-" . time();
        }
    }

    /* -----------------------------
        MAIN IMAGE DEFAULT
    ------------------------------ */

    $productImage = "";

    if ($error == "") {

        if (

            isset($_FILES['product_image'])

            &&

            $_FILES['product_image']['name'] != ""

        ) {

            $allowed = [

                "jpg",

                "jpeg",

                "png",

                "webp"

            ];

            $extension = strtolower(

                pathinfo(

                    $_FILES['product_image']['name'],

                    PATHINFO_EXTENSION

                )

            );

            if (

                !in_array(

                    $extension,

                    $allowed

                )

            ) {

                $error = "Only JPG, PNG and WEBP images are allowed.";
            }
        }
    }
    /* -----------------------------
        UPLOAD MAIN IMAGE
    ------------------------------ */

    if ($error == "") {

        if (

            isset($_FILES['product_image'])

            &&

            $_FILES['product_image']['name'] != ""

        ) {

            $extension = strtolower(

                pathinfo(

                    $_FILES['product_image']['name'],

                    PATHINFO_EXTENSION

                )

            );

            $productImage =

                uniqid("product_", true)

                . "." .

                $extension;

            $uploadPath =

                "uploads/products/"

                . $productImage;

            if (

                !move_uploaded_file(

                    $_FILES['product_image']['tmp_name'],

                    $uploadPath

                )

            ) {

                $error =

                    "Failed to upload product image.";
            }
        }
    }

    /* -----------------------------
        CHECK DUPLICATE PRODUCT
    ------------------------------ */

    if ($error == "") {

        $duplicate = mysqli_query(

            $conn,

            "SELECT id

             FROM products

             WHERE

             product_name='$product_name'

             LIMIT 1"

        );

        if (

            mysqli_num_rows(

                $duplicate

            ) > 0

        ) {

            $error =

                "A product with this name already exists.";
        }
    }

    /* -----------------------------
        PREPARE OPTIONAL VALUES
    ------------------------------ */

    if ($old_price == "") {

        $old_price = "NULL";
    } else {

        $old_price = "'$old_price'";
    }

    if ($brand == "") {

        $brand = "SCS Star Beauty";
    }

    if ($weight == "") {

        $weight = "";
    }

    if ($sku == "") {

        $sku = "";
    }
    /* -----------------------------
        INSERT PRODUCT
    ------------------------------ */

    if ($error == "") {

        $insert = mysqli_query(

            $conn,

            "INSERT INTO products
            (
                category_id,
                product_name,
                slug,
                short_description,
                description,
                benefits,
                ingredients,
                usage_method,
                sku,
                brand,
                weight,
                price,
                old_price,
                stock,
                featured,
                product_image,
                status
            )
            VALUES
            (
                '$category_id',
                '$product_name',
                '$slug',
                '$short_description',
                '$description',
                '$benefits',
                '$ingredients',
                '$usage_method',
                '$sku',
                '$brand',
                '$weight',
                '$price',
                $old_price,
                '$stock',
                '$featured',
                '$productImage',
                '$status'
            )"

        );

        if ($insert) {

            $productId = mysqli_insert_id($conn);

            /* -----------------------------
                GALLERY IMAGES
            ------------------------------ */

            if (

                isset($_FILES['gallery_images'])

                &&

                !empty($_FILES['gallery_images']['name'][0])

            ) {

                $totalImages = count(

                    $_FILES['gallery_images']['name']

                );

                for (

                    $i = 0;

                    $i < $totalImages;

                    $i++

                ) {

                    if (

                        $_FILES['gallery_images']['error'][$i] == 0

                    ) {

                        $extension = strtolower(

                            pathinfo(

                                $_FILES['gallery_images']['name'][$i],

                                PATHINFO_EXTENSION

                            )

                        );

                        $allowed = [

                            "jpg",

                            "jpeg",

                            "png",

                            "webp"

                        ];

                        if (

                            in_array(

                                $extension,

                                $allowed

                            )

                        ) {

                            $galleryImage =

                                uniqid("gallery_", true)

                                . "." .

                                $extension;

                            $galleryPath =

                                "uploads/gallery/"

                                . $galleryImage;

                            if (

                                move_uploaded_file(

                                    $_FILES['gallery_images']['tmp_name'][$i],

                                    $galleryPath

                                )

                            ) {

                                mysqli_query(

                                    $conn,

                                    "INSERT INTO product_gallery
                                    (
                                        product_id,
                                        image
                                    )
                                    VALUES
                                    (
                                        '$productId',
                                        '$galleryImage'
                                    )"

                                );
                            }
                        }
                    }
                }
            }

            header(

                "Location: products.php?success=1"

            );

            exit;
        } else {

            $error =

                "Failed to add product.";
        }
    }
}

/* ==========================================
        SEARCH & FILTER VARIABLES
========================================== */
/* ==========================================
        SEARCH & FILTER VARIABLES
========================================== */

$search = "";

$category = "";

$status = "";

$featured = "";

$sort = "latest";

/* ==========================================
        GET FILTER VALUES
========================================== */

if (isset($_GET['search'])) {

    $search = trim($_GET['search']);
}

if (isset($_GET['category'])) {

    $category = $_GET['category'];
}

if (isset($_GET['status'])) {

    $status = $_GET['status'];
}

if (isset($_GET['featured'])) {

    $featured = $_GET['featured'];
}

if (isset($_GET['sort'])) {

    $sort = $_GET['sort'];
}

/* ==========================================
        DASHBOARD STATISTICS
========================================== */

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

$activeProducts = mysqli_fetch_row(

    mysqli_query(

        $conn,

        "SELECT COUNT(*) FROM products
         WHERE status='Active'"

    )

)[0];

$outOfStock = mysqli_fetch_row(

    mysqli_query(

        $conn,

        "SELECT COUNT(*) FROM products
         WHERE stock<=0"

    )

)[0];

/* ==========================================
        CATEGORY DROPDOWN
========================================== */

$categoryQuery = mysqli_query(

    $conn,

    "SELECT id,category_name

     FROM categories

     WHERE status='Active'

     ORDER BY category_name ASC"

);

/* ==========================================
        BUILD WHERE CLAUSE
========================================== */

$where = "WHERE 1";

if ($search != "") {

    $search = mysqli_real_escape_string($conn, $search);

    $where .= "

    AND

    (

        products.product_name LIKE '%$search%'

        OR

        products.brand LIKE '%$search%'

        OR

        products.sku LIKE '%$search%'

    )";
}

if ($category != "") {

    $category = (int)$category;

    $where .= "

    AND

    products.category_id='$category'";
}

if ($status != "") {

    $status = mysqli_real_escape_string($conn, $status);

    $where .= "

    AND

    products.status='$status'";
}

if ($featured != "") {

    $featured = mysqli_real_escape_string($conn, $featured);

    $where .= "

    AND

    products.featured='$featured'";
}

/* ==========================================
        SORTING
========================================== */

$orderBy = "

ORDER BY

products.id DESC";

switch ($sort) {

    case "oldest":

        $orderBy = "

        ORDER BY

        products.id ASC";

        break;

    case "name":

        $orderBy = "

        ORDER BY

        products.product_name ASC";

        break;

    case "price_low":

        $orderBy = "

        ORDER BY

        products.price ASC";

        break;

    case "price_high":

        $orderBy = "

        ORDER BY

        products.price DESC";

        break;
}

/* ==========================================
        PAGINATION
========================================== */

$limit = 10;

$page = 1;

if (isset($_GET['page'])) {

    $page = max(1, (int)$_GET['page']);
}

$offset = ($page - 1) * $limit;

/* ==========================================
        TOTAL RECORDS
========================================== */

$countQuery = mysqli_query(

    $conn,

    "SELECT COUNT(*)

     FROM products

     $where"

);

$totalRows = mysqli_fetch_row(

    $countQuery

)[0];

$totalPages = ceil($totalRows / $limit);

/* ==========================================
        PRODUCT LIST
========================================== */

$productQuery = mysqli_query(

    $conn,

    "SELECT

        products.*,

        categories.category_name

     FROM products

     INNER JOIN categories

     ON

     products.category_id=categories.id

     $where

     $orderBy

     LIMIT

     $offset,

     $limit"

);

include 'includes/header.php';

include 'includes/navbar.php';

include 'includes/sidebar.php';

?>

<div class="dashboard-content">
    <!-- ==========================================
        PAGE HEADER
========================================== -->

    <div class="page-header">

        <div>

            <h2>

                <i class="fa-solid fa-box-open"></i>

                Product Management

            </h2>

            <p>

                Manage all beauty products from one place.

            </p>

        </div>

        <div>

            <button

                class="btn btn-primary"

                data-bs-toggle="modal"

                data-bs-target="#addProductModal">

                <i class="fa-solid fa-plus"></i>

                Add Product

            </button>

        </div>

    </div>

    <!-- ==========================================
        SUCCESS ALERTS
========================================== -->

    <?php if (isset($_GET['success'])) { ?>

        <div class="alert alert-success">

            ✅ Product added successfully.

        </div>

    <?php } ?>

    <?php if (isset($_GET['updated'])) { ?>

        <div class="alert alert-success">

            ✅ Product updated successfully.

        </div>

    <?php } ?>

    <?php if (isset($_GET['deleted'])) { ?>

        <div class="alert alert-success">

            🗑️ Product deleted successfully.

        </div>

    <?php } ?>

    <!-- ==========================================
        STATISTICS
========================================== -->

    <div class="row g-4 mb-4">

        <div class="col-lg-3 col-md-6">

            <div class="stats-card">

                <i class="fa-solid fa-box-open"></i>

                <h2>

                    <?php echo $totalProducts; ?>

                </h2>

                <p>

                    Total Products

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

                <i class="fa-solid fa-circle-check"></i>

                <h2>

                    <?php echo $activeProducts; ?>

                </h2>

                <p>

                    Active Products

                </p>

            </div>

        </div>

        <div class="col-lg-3 col-md-6">

            <div class="stats-card">

                <i class="fa-solid fa-triangle-exclamation"></i>

                <h2>

                    <?php echo $outOfStock; ?>

                </h2>

                <p>

                    Out Of Stock

                </p>

            </div>

        </div>

    </div>

    <!-- ==========================================
        PRODUCTS CONTAINER
========================================== -->

    <div class="dashboard-box">
        <!-- ==========================================
        SEARCH & FILTER
========================================== -->

        <form
            action=""
            method="GET"
            class="mb-4">

            <div class="row g-3">

                <!-- Search -->

                <div class="col-lg-3">

                    <input

                        type="text"

                        name="search"

                        class="form-control"

                        placeholder="Search product..."

                        value="<?php echo htmlspecialchars($search); ?>">

                </div>

                <!-- Category -->

                <div class="col-lg-2">

                    <select

                        name="category"

                        class="form-select">

                        <option value="">

                            All Categories

                        </option>

                        <?php

                        mysqli_data_seek(

                            $categoryQuery,

                            0

                        );

                        while (

                            $cat = mysqli_fetch_assoc(

                                $categoryQuery

                            )

                        ) {

                        ?>

                            <option

                                value="<?php echo $cat['id']; ?>"

                                <?php

                                if (

                                    $category == $cat['id']

                                )

                                    echo "selected";

                                ?>>

                                <?php

                                echo htmlspecialchars(

                                    $cat['category_name']

                                );

                                ?>

                            </option>

                        <?php } ?>

                    </select>

                </div>

                <!-- Featured -->

                <div class="col-lg-2">

                    <select

                        name="featured"

                        class="form-select">

                        <option value="">

                            Featured

                        </option>

                        <option

                            value="Yes"

                            <?php

                            if ($featured == "Yes")

                                echo "selected";

                            ?>>

                            Featured

                        </option>

                        <option

                            value="No"

                            <?php

                            if ($featured == "No")

                                echo "selected";

                            ?>>

                            Normal

                        </option>

                    </select>

                </div>

                <!-- Status -->

                <div class="col-lg-2">

                    <select

                        name="status"

                        class="form-select">

                        <option value="">

                            Status

                        </option>

                        <option

                            value="Active"

                            <?php

                            if ($status == "Active")

                                echo "selected";

                            ?>>

                            Active

                        </option>

                        <option

                            value="Inactive"

                            <?php

                            if ($status == "Inactive")

                                echo "selected";

                            ?>>

                            Inactive

                        </option>

                    </select>

                </div>

                <!-- Sort -->

                <div class="col-lg-2">

                    <select

                        name="sort"

                        class="form-select">

                        <option

                            value="latest"

                            <?php

                            if ($sort == "latest")

                                echo "selected";

                            ?>>

                            Latest

                        </option>

                        <option

                            value="oldest"

                            <?php

                            if ($sort == "oldest")

                                echo "selected";

                            ?>>

                            Oldest

                        </option>

                        <option

                            value="name"

                            <?php

                            if ($sort == "name")

                                echo "selected";

                            ?>>

                            Name A-Z

                        </option>

                        <option

                            value="price_low"

                            <?php

                            if ($sort == "price_low")

                                echo "selected";

                            ?>>

                            Price Low

                        </option>

                        <option

                            value="price_high"

                            <?php

                            if ($sort == "price_high")

                                echo "selected";

                            ?>>

                            Price High

                        </option>

                    </select>

                </div>

                <!-- Buttons -->

                <div class="col-lg-1 d-grid">

                    <button

                        class="btn btn-primary"

                        type="submit">

                        <i class="fa-solid fa-magnifying-glass"></i>

                    </button>

                </div>

            </div>

        </form>

        <!-- ==========================================
        RESET BUTTON
========================================== -->

        <div class="mb-4">

            <a

                href="products.php"

                class="btn btn-outline-secondary">

                <i class="fa-solid fa-rotate-right"></i>

                Reset Filters

            </a>

        </div>

        <!-- ==========================================
        PRODUCT TABLE
========================================== -->

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead>

                    <tr>

                        <th>Image</th>

                        <th>Product</th>

                        <th>Category</th>

                        <th>Price</th>

                        <th>Stock</th>

                        <th>Featured</th>

                        <th>Status</th>

                        <th>Created</th>

                        <th width="120">

                            Actions

                        </th>

                    </tr>

                </thead>

                <tbody>
                    <?php

                    if (mysqli_num_rows($productQuery) > 0) {

                        while ($product = mysqli_fetch_assoc($productQuery)) {

                    ?>

                            <tr id="productRow<?php echo $product['id']; ?>">
                                <!-- Product Image -->

                                <td>

                                    <?php

                                    if (!empty($product['product_image']) && file_exists("../assets/uploads/products/" . $product['product_image'])) {

                                    ?>

                                        <img

                                            src="../assets/uploads/products/<?php echo htmlspecialchars($product['product_image']); ?>"

                                            alt="<?php echo htmlspecialchars($product['product_name']); ?>"

                                            width="70"

                                            height="70"

                                            class="rounded border"

                                            style="object-fit:cover;">

                                    <?php

                                    } else {

                                    ?>

                                        <img

                                            src="../assets/images/no-image.png"

                                            alt="No Image"

                                            width="70"

                                            height="70"

                                            class="rounded border"

                                            style="object-fit:cover;">

                                    <?php

                                    }

                                    ?>

                                </td>

                                <!-- Product Details -->

                                <td>

                                    <strong>

                                        <?php echo htmlspecialchars($product['product_name']); ?>

                                    </strong>

                                    <br>

                                    <small class="text-muted">

                                        Brand:

                                        <?php echo htmlspecialchars($product['brand']); ?>

                                    </small>

                                    <br>

                                    <small class="text-muted">

                                        SKU:

                                        <?php

                                        echo !empty($product['sku'])

                                            ? htmlspecialchars($product['sku'])

                                            : "-";

                                        ?>

                                    </small>

                                </td>

                                <!-- Category -->

                                <td>

                                    <?php echo htmlspecialchars($product['category_name']); ?>

                                </td>

                                <!-- Price -->

                                <td>

                                    <strong>

                                        <?php

                                        echo CURRENCY .

                                            number_format($product['price'], 2);

                                        ?>

                                    </strong>

                                    <br>

                                    <?php

                                    if (

                                        !empty($product['old_price'])

                                        &&

                                        $product['old_price'] >

                                        $product['price']

                                    ) {

                                    ?>

                                        <small class="text-decoration-line-through text-muted">

                                            <?php

                                            echo CURRENCY .

                                                number_format($product['old_price'], 2);

                                            ?>

                                        </small>

                                    <?php } ?>

                                </td>

                                <!-- Stock -->

                                <td>

                                    <?php

                                    if ($product['stock'] <= 0) {

                                    ?>

                                        <span class="badge bg-danger">

                                            Out Of Stock

                                        </span>

                                        <br>

                                        <small>0 Qty</small>

                                    <?php

                                    } elseif ($product['stock'] <= 5) {

                                    ?>

                                        <span class="badge bg-warning text-dark">

                                            Low Stock

                                        </span>

                                        <br>

                                        <small>

                                            <?php echo $product['stock']; ?>

                                            Qty

                                        </small>

                                    <?php

                                    } else {

                                    ?>

                                        <span class="badge bg-success">

                                            In Stock

                                        </span>

                                        <br>

                                        <small>

                                            <?php echo $product['stock']; ?>

                                            Qty

                                        </small>

                                    <?php } ?>

                                </td>

                                <!-- Featured -->

                                <td>

                                    <?php

                                    if ($product['featured'] == "Yes") {

                                    ?>

                                        <span class="badge bg-success">

                                            Yes

                                        </span>

                                    <?php

                                    } else {

                                    ?>

                                        <span class="badge bg-secondary">

                                            No

                                        </span>

                                    <?php } ?>

                                </td>

                                <!-- Status -->

                                <td>

                                    <?php

                                    if ($product['status'] == "Active") {

                                    ?>

                                        <span class="badge bg-success">

                                            Active

                                        </span>

                                    <?php

                                    } else {

                                    ?>

                                        <span class="badge bg-danger">

                                            Inactive

                                        </span>

                                    <?php } ?>

                                </td>

                                <!-- Created Date -->

                                <td>

                                    <?php

                                    echo date(

                                        "d M Y",

                                        strtotime(

                                            $product['created_at']

                                        )

                                    );

                                    ?>

                                </td>

                                <!-- Actions -->

                                <td>

                                    <div class="action-buttons">
                                        <button

                                            type="button"

                                            class="btn btn-warning btn-sm editProductBtn"

                                            data-bs-toggle="modal"

                                            data-bs-target="#editProductModal"

                                            data-id="<?php echo $product['id']; ?>"

                                            title="Edit">

                                            <i class="fa-solid fa-pen"></i>

                                        </button>
                                        <button

                                            type="button"

                                            class="btn btn-danger btn-sm deleteProductBtn"

                                            data-bs-toggle="modal"

                                            data-bs-target="#deleteProductModal"

                                            data-id="<?php echo $product['id']; ?>"

                                            data-name="<?php echo htmlspecialchars($product['product_name']); ?>"

                                            title="Delete">

                                            <i class="fa-solid fa-trash"></i>

                                        </button>

                                    </div>

                                </td>

                            </tr>

                        <?php

                        }
                    } else {

                        ?>

                        <tr>

                            <td colspan="9" class="text-center py-5">

                                <i

                                    class="fa-solid fa-box-open"

                                    style="font-size:55px;color:#ccc;">

                                </i>

                                <br><br>

                                <strong>

                                    No products found.

                                </strong>

                            </td>

                        </tr>

                    <?php

                    }

                    ?>

                </tbody>

            </table>

        </div>

    </div>
    <!-- ==========================================
        PAGINATION
========================================== -->

    <?php

    if ($totalPages > 1) {

    ?>

        <nav class="mt-4">

            <ul class="pagination justify-content-center">

                <!-- Previous -->

                <?php

                if ($page > 1) {

                ?>

                    <li class="page-item">

                        <a

                            class="page-link"

                            href="?search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>&featured=<?php echo urlencode($featured); ?>&status=<?php echo urlencode($status); ?>&sort=<?php echo urlencode($sort); ?>&page=<?php echo $page - 1; ?>">

                            Previous

                        </a>

                    </li>

                <?php

                }

                ?>

                <!-- Page Numbers -->

                <?php

                for ($i = 1; $i <= $totalPages; $i++) {

                ?>

                    <li

                        class="page-item <?php if ($page == $i) echo 'active'; ?>">

                        <a

                            class="page-link"

                            href="?search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>&featured=<?php echo urlencode($featured); ?>&status=<?php echo urlencode($status); ?>&sort=<?php echo urlencode($sort); ?>&page=<?php echo $i; ?>">

                            <?php echo $i; ?>

                        </a>

                    </li>

                <?php

                }

                ?>

                <!-- Next -->

                <?php

                if ($page < $totalPages) {

                ?>

                    <li class="page-item">

                        <a

                            class="page-link"

                            href="?search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>&featured=<?php echo urlencode($featured); ?>&status=<?php echo urlencode($status); ?>&sort=<?php echo urlencode($sort); ?>&page=<?php echo $page + 1; ?>">

                            Next

                        </a>

                    </li>

                <?php

                }

                ?>

            </ul>

        </nav>

    <?php

    }

    ?>


    <!-- ==========================================
        ADD PRODUCT MODAL
========================================== -->

    <div
        class="modal fade"
        id="addProductModal"
        tabindex="-1">

        <div class="modal-dialog modal-xl modal-dialog-scrollable">

            <div class="modal-content">

                <form

                    method="POST"

                    enctype="multipart/form-data">

                    <div class="modal-header">

                        <h5 class="modal-title">

                            <i class="fa-solid fa-plus"></i>

                            Add New Product

                        </h5>

                        <button

                            type="button"

                            class="btn-close"

                            data-bs-dismiss="modal">

                        </button>

                    </div>

                    <div class="modal-body">

                        <?php

                        if ($error != "") {

                        ?>

                            <div class="alert alert-danger">

                                <?php echo $error; ?>

                            </div>

                        <?php

                        }

                        ?>

                        <div class="row">

                            <!-- Category -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Category

                                    <span class="text-danger">*</span>

                                </label>

                                <select

                                    name="category_id"

                                    class="form-select"

                                    required>

                                    <option value="">

                                        Select Category

                                    </option>

                                    <?php

                                    mysqli_data_seek($categoryQuery, 0);

                                    while ($cat = mysqli_fetch_assoc($categoryQuery)) {

                                    ?>

                                        <option

                                            value="<?php echo $cat['id']; ?>">

                                            <?php

                                            echo htmlspecialchars(

                                                $cat['category_name']

                                            );

                                            ?>

                                        </option>

                                    <?php

                                    }

                                    ?>

                                </select>

                            </div>

                            <!-- Product Name -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Product Name

                                    <span class="text-danger">*</span>

                                </label>

                                <input

                                    type="text"

                                    name="product_name"

                                    class="form-control"

                                    placeholder="Product Name"

                                    required>

                            </div>

                            <!-- Brand -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Brand

                                </label>

                                <input

                                    type="text"

                                    name="brand"

                                    class="form-control"

                                    value="SCS Star Beauty">

                            </div>

                            <!-- SKU -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    SKU

                                </label>

                                <input

                                    type="text"

                                    name="sku"

                                    class="form-control"

                                    placeholder="SKU">

                            </div>

                            <!-- Weight -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Weight

                                </label>

                                <input

                                    type="text"

                                    name="weight"

                                    class="form-control"

                                    placeholder="e.g. 100ml">

                            </div>

                            <!-- Stock -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Stock Quantity

                                </label>

                                <input

                                    type="number"

                                    name="stock"

                                    class="form-control"

                                    value="0"

                                    min="0">

                            </div>

                            <!-- Featured -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Featured Product

                                </label>

                                <select

                                    name="featured"

                                    class="form-select">

                                    <option value="No">

                                        No

                                    </option>

                                    <option value="Yes">

                                        Yes

                                    </option>

                                </select>

                            </div>

                            <!-- Status -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Status

                                </label>

                                <select

                                    name="status"

                                    class="form-select">

                                    <option value="Active">

                                        Active

                                    </option>

                                    <option value="Inactive">

                                        Inactive

                                    </option>

                                </select>

                            </div>

                            <hr class="my-4">

                            <h5 class="mb-4">

                                <i class="fa-solid fa-money-bill-wave"></i>

                                Pricing

                            </h5>
                            <div class="row">

                                <!-- Price -->

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">

                                        Price

                                        <span class="text-danger">*</span>

                                    </label>

                                    <input

                                        type="number"

                                        name="price"

                                        class="form-control"

                                        step="0.01"

                                        min="0"

                                        placeholder="0.00"

                                        required>

                                </div>

                                <!-- Old Price -->

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">

                                        Old Price

                                    </label>

                                    <input

                                        type="number"

                                        name="old_price"

                                        class="form-control"

                                        step="0.01"

                                        min="0"

                                        placeholder="0.00">

                                </div>

                            </div>

                            <hr class="my-4">

                            <h5 class="mb-4">

                                <i class="fa-solid fa-file-lines"></i>

                                Product Description

                            </h5>

                            <!-- Short Description -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Short Description

                                </label>

                                <textarea

                                    name="short_description"

                                    class="form-control"

                                    rows="3"

                                    placeholder="Write a short description..."></textarea>

                            </div>

                            <!-- Full Description -->

                            <div class="mb-4">

                                <label class="form-label">

                                    Full Description

                                </label>

                                <textarea

                                    name="description"

                                    class="form-control"

                                    rows="6"

                                    placeholder="Write complete product description..."></textarea>

                            </div>

                            <hr class="my-4">

                            <h5 class="mb-4">

                                <i class="fa-solid fa-heart"></i>

                                Product Benefits

                            </h5>

                            <div class="mb-4">

                                <textarea

                                    name="benefits"

                                    class="form-control"

                                    rows="5"

                                    placeholder="Enter product benefits..."></textarea>

                            </div>

                            <hr class="my-4">

                            <h5 class="mb-4">

                                <i class="fa-solid fa-flask"></i>

                                Ingredients

                            </h5>

                            <div class="mb-4">

                                <textarea

                                    name="ingredients"

                                    class="form-control"

                                    rows="5"

                                    placeholder="Enter ingredients..."></textarea>

                            </div>

                            <hr class="my-4">

                            <h5 class="mb-4">

                                <i class="fa-solid fa-hand-sparkles"></i>

                                Usage Method

                            </h5>

                            <div class="mb-4">

                                <textarea

                                    name="usage_method"

                                    class="form-control"

                                    rows="5"

                                    placeholder="Explain how to use this product..."></textarea>

                            </div>

                            <hr class="my-4">

                            <h5 class="mb-4">

                                <i class="fa-solid fa-image"></i>

                                Product Images

                            </h5>
                            <div class="row">

                                <!-- Main Product Image -->

                                <div class="col-lg-6 mb-4">

                                    <label class="form-label">

                                        Main Product Image

                                    </label>

                                    <input

                                        type="file"

                                        name="product_image"

                                        class="form-control"

                                        accept=".jpg,.jpeg,.png,.webp">

                                    <small class="text-muted">

                                        Supported formats: JPG, JPEG, PNG, WEBP

                                    </small>

                                </div>

                                <!-- Gallery Images -->

                                <div class="col-lg-6 mb-4">

                                    <label class="form-label">

                                        Gallery Images

                                    </label>

                                    <input

                                        type="file"

                                        name="gallery_images[]"

                                        class="form-control"

                                        accept=".jpg,.jpeg,.png,.webp"

                                        multiple>

                                    <small class="text-muted">

                                        You can select multiple images.

                                    </small>

                                </div>

                            </div>

                            <!-- Image Preview -->

                            <div class="row">

                                <div class="col-md-6">

                                    <div class="image-preview-box">

                                        <h6>

                                            Main Image Preview

                                        </h6>

                                        <img

                                            id="mainPreview"

                                            src="../assets/images/no-image.png"

                                            alt="Preview"

                                            class="img-fluid rounded border">

                                    </div>

                                </div>

                                <div class="col-md-6">

                                    <div class="gallery-preview-box">

                                        <h6>

                                            Gallery Preview

                                        </h6>

                                        <div

                                            id="galleryPreview"

                                            class="d-flex flex-wrap gap-2">

                                            <span class="text-muted">

                                                No images selected.

                                            </span>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <hr class="my-4">

                            <div class="alert alert-info">

                                <i class="fa-solid fa-circle-info"></i>

                                <strong>Tips:</strong>

                                <ul class="mb-0 mt-2">

                                    <li>

                                        Upload high-quality images.

                                    </li>

                                    <li>

                                        Use the same image dimensions for all products.

                                    </li>

                                    <li>

                                        Keep filenames simple.

                                    </li>

                                    <li>

                                        WEBP images are recommended for better performance.

                                    </li>

                                </ul>

                            </div>

                        </div>
                    </div>
                    <!-- Modal Footer -->

                    <div class="modal-footer">

                        <button

                            type="button"

                            class="btn btn-secondary"

                            data-bs-dismiss="modal">

                            <i class="fa-solid fa-xmark"></i>

                            Cancel

                        </button>

                        <button

                            type="reset"

                            class="btn btn-warning">

                            <i class="fa-solid fa-rotate"></i>

                            Reset

                        </button>

                        <button

                            type="submit"

                            name="add_product"

                            class="btn btn-primary">

                            <i class="fa-solid fa-floppy-disk"></i>

                            Save Product

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>
    <!-- ==========================================
        EDIT PRODUCT MODAL
========================================== -->

    <div

        class="modal fade"

        id="editProductModal"

        tabindex="-1"

        aria-hidden="true">

        <div

            class="modal-dialog modal-xl modal-dialog-scrollable">

            <div class="modal-content">

                <form

                    action="update-product.php"

                    method="POST"

                    enctype="multipart/form-data">

                    <input

                        type="hidden"

                        name="product_id"

                        id="edit_product_id">

                    <div class="modal-header">

                        <h5 class="modal-title">

                            <i class="fa-solid fa-pen"></i>

                            Edit Product

                        </h5>

                        <button

                            type="button"

                            class="btn-close"

                            data-bs-dismiss="modal">

                        </button>

                    </div>

                    <div class="modal-body">

                        <div class="row">

                            <!-- Category -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Category

                                    <span class="text-danger">*</span>

                                </label>

                                <select

                                    name="category_id"

                                    id="edit_category"

                                    class="form-select"

                                    required>

                                    <option value="">

                                        Select Category

                                    </option>

                                    <?php

                                    mysqli_data_seek(

                                        $categoryQuery,

                                        0

                                    );

                                    while (

                                        $cat = mysqli_fetch_assoc(

                                            $categoryQuery

                                        )

                                    ) {

                                    ?>

                                        <option

                                            value="<?php echo $cat['id']; ?>">

                                            <?php

                                            echo htmlspecialchars(

                                                $cat['category_name']

                                            );

                                            ?>

                                        </option>

                                    <?php

                                    }

                                    ?>

                                </select>

                            </div>

                            <!-- Product Name -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Product Name

                                </label>

                                <input

                                    type="text"

                                    name="product_name"

                                    id="edit_product_name"

                                    class="form-control"

                                    required>

                            </div>

                            <!-- Brand -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Brand

                                </label>

                                <input

                                    type="text"

                                    name="brand"

                                    id="edit_brand"

                                    class="form-control">

                            </div>

                            <!-- SKU -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    SKU

                                </label>

                                <input

                                    type="text"

                                    name="sku"

                                    id="edit_sku"

                                    class="form-control">

                            </div>

                            <!-- Weight -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Weight

                                </label>

                                <input

                                    type="text"

                                    name="weight"

                                    id="edit_weight"

                                    class="form-control">

                            </div>

                            <!-- Stock -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Stock

                                </label>

                                <input

                                    type="number"

                                    name="stock"

                                    id="edit_stock"

                                    class="form-control">

                            </div>

                            <hr class="my-4">

                            <h5>

                                Basic Information

                            </h5>
                            <div class="row">

                                <!-- Price -->

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">

                                        Price

                                        <span class="text-danger">*</span>

                                    </label>

                                    <input

                                        type="number"

                                        name="price"

                                        id="edit_price"

                                        class="form-control"

                                        step="0.01"

                                        min="0"

                                        required>

                                </div>

                                <!-- Old Price -->

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">

                                        Old Price

                                    </label>

                                    <input

                                        type="number"

                                        name="old_price"

                                        id="edit_old_price"

                                        class="form-control"

                                        step="0.01"

                                        min="0">

                                </div>

                                <!-- Featured -->

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">

                                        Featured Product

                                    </label>

                                    <select

                                        name="featured"

                                        id="edit_featured"

                                        class="form-select">

                                        <option value="Yes">

                                            Yes

                                        </option>

                                        <option value="No">

                                            No

                                        </option>

                                    </select>

                                </div>

                                <!-- Status -->

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">

                                        Status

                                    </label>

                                    <select

                                        name="status"

                                        id="edit_status"

                                        class="form-select">

                                        <option value="Active">

                                            Active

                                        </option>

                                        <option value="Inactive">

                                            Inactive

                                        </option>

                                    </select>

                                </div>

                            </div>

                            <hr class="my-4">

                            <h5>

                                Product Description

                            </h5>

                            <!-- Short Description -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Short Description

                                </label>

                                <textarea

                                    name="short_description"

                                    id="edit_short_description"

                                    class="form-control"

                                    rows="3"></textarea>

                            </div>

                            <!-- Full Description -->

                            <div class="mb-4">

                                <label class="form-label">

                                    Full Description

                                </label>

                                <textarea

                                    name="description"

                                    id="edit_description"

                                    class="form-control"

                                    rows="6"></textarea>

                            </div>

                            <hr class="my-4">

                            <h5>

                                Benefits

                            </h5>

                            <div class="mb-4">

                                <textarea

                                    name="benefits"

                                    id="edit_benefits"

                                    class="form-control"

                                    rows="5"></textarea>

                            </div>

                            <hr class="my-4">

                            <h5>

                                Ingredients

                            </h5>

                            <div class="mb-4">

                                <textarea

                                    name="ingredients"

                                    id="edit_ingredients"

                                    class="form-control"

                                    rows="5"></textarea>

                            </div>

                            <hr class="my-4">

                            <h5>

                                Usage Method

                            </h5>

                            <div class="mb-4">

                                <textarea

                                    name="usage_method"

                                    id="edit_usage_method"

                                    class="form-control"

                                    rows="5"></textarea>

                            </div>

                            <hr class="my-4">

                            <h5>

                                Product Images

                            </h5>
                            <div class="row">

                                <!-- Current Main Image -->

                                <div class="col-lg-6 mb-4">

                                    <label class="form-label">

                                        Current Product Image

                                    </label>

                                    <div class="text-center">

                                        <img

                                            id="edit_main_preview"

                                            src="../assets/images/no-image.png"

                                            alt="Product Image"

                                            class="img-fluid rounded border"

                                            style="max-width:220px; max-height:220px; object-fit:cover;">

                                    </div>

                                </div>

                                <!-- Upload New Main Image -->

                                <div class="col-lg-6 mb-4">

                                    <label class="form-label">

                                        Replace Product Image

                                    </label>

                                    <input

                                        type="file"

                                        name="product_image"

                                        id="edit_product_image"

                                        class="form-control"

                                        accept=".jpg,.jpeg,.png,.webp">

                                    <small class="text-muted">

                                        Leave empty if you don't want to change the current image.

                                    </small>

                                </div>

                            </div>

                            <hr class="my-4">

                            <h5>

                                Gallery Images

                            </h5>

                            <!-- Existing Gallery -->

                            <div class="mb-4">

                                <label class="form-label">

                                    Current Gallery

                                </label>

                                <div

                                    id="edit_gallery_preview"

                                    class="d-flex flex-wrap gap-3">

                                    <span class="text-muted">

                                        Gallery images will appear here.

                                    </span>

                                </div>

                            </div>

                            <!-- Upload New Gallery Images -->

                            <div class="mb-4">

                                <label class="form-label">

                                    Add More Gallery Images

                                </label>

                                <input

                                    type="file"

                                    name="gallery_images[]"

                                    id="edit_gallery_images"

                                    class="form-control"

                                    accept=".jpg,.jpeg,.png,.webp"

                                    multiple>

                                <small class="text-muted">

                                    Select one or more images to add to the existing gallery.

                                </small>

                            </div>

                            <hr class="my-4">

                            <div class="alert alert-info">

                                <i class="fa-solid fa-circle-info"></i>

                                <strong>Note:</strong>

                                Uploading a new main image will replace the existing one.

                                Gallery images selected here will be added to the existing gallery.

                            </div>

                        </div>
                    </div>

                    <!-- Modal Footer -->

                    <div class="modal-footer">

                        <button

                            type="button"

                            class="btn btn-secondary"

                            data-bs-dismiss="modal">

                            <i class="fa-solid fa-xmark"></i>

                            Cancel

                        </button>

                        <button

                            type="submit"

                            name="update_product"

                            id="updateProductBtn"

                            class="btn btn-primary">

                            <i class="fa-solid fa-floppy-disk"></i>

                            Update Product

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>
    <!-- ==========================================
        DELETE PRODUCT MODAL
========================================== -->

    <div

        class="modal fade"

        id="deleteProductModal"

        tabindex="-1"

        aria-hidden="true">

        <div

            class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header bg-danger text-white">

                    <h5 class="modal-title">

                        <i class="fa-solid fa-trash"></i>

                        Delete Product

                    </h5>

                    <button

                        type="button"

                        class="btn-close btn-close-white"

                        data-bs-dismiss="modal">

                    </button>

                </div>

                <div class="modal-body text-center">

                    <input

                        type="hidden"

                        id="delete_product_id">

                    <div class="mb-4">

                        <i

                            class="fa-solid fa-triangle-exclamation text-danger"

                            style="font-size:70px;">

                        </i>

                    </div>

                    <h4 class="mb-3">

                        Are you sure?

                    </h4>

                    <p class="text-muted">

                        You are about to permanently delete

                        <strong id="delete_product_name">

                            this product

                        </strong>.

                    </p>

                    <div class="alert alert-warning">

                        <i class="fa-solid fa-circle-exclamation"></i>

                        This action cannot be undone.

                    </div>

                </div>

                <div class="modal-footer">

                    <button

                        type="button"

                        class="btn btn-secondary"

                        data-bs-dismiss="modal">

                        <i class="fa-solid fa-xmark"></i>

                        Cancel

                    </button>

                    <button

                        type="button"

                        id="confirmDeleteProduct"

                        class="btn btn-danger">

                        <i class="fa-solid fa-trash"></i>

                        Delete Product

                    </button>

                </div>

            </div>

        </div>

    </div>
    <!-- ==========================================
        SUCCESS TOAST
========================================== -->

    <div

        class="toast-container">

        <div

            id="successToast"

            class="toast"

            role="alert">

            <div class="toast-header">

                <i

                    class="fa-solid fa-circle-check text-success me-2">

                </i>

                <strong class="me-auto">

                    Success

                </strong>

                <button

                    class="btn-close"

                    data-bs-dismiss="toast">

                </button>

            </div>

            <div

                class="toast-body"

                id="toastMessage">

                Operation completed successfully.

            </div>

        </div>

    </div>
    <?php

    include 'includes/footer.php';

    include 'includes/scripts.php';

    ?>