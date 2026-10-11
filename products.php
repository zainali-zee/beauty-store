<?php

include 'config/database.php';

$pageTitle = "Products | " . SITE_NAME;
$pageCSS   = "products.css";
$pageJS    = "products.js";

include 'includes/header.php';
include 'includes/navbar.php';

/* ===================================================
   PRODUCT SEARCH, FILTER & SORT
=================================================== */

$search   = "";
$category = 0;
$sort     = "newest";

if(isset($_GET['search'])){
    $search = trim($_GET['search']);
}

if(isset($_GET['category'])){
    $category = (int)$_GET['category'];
}

if(isset($_GET['sort'])){

    $allowedSort = [

        "newest",
        "oldest",
        "price_low",
        "price_high",
        "name_asc",
        "name_desc"

    ];

    if(in_array($_GET['sort'], $allowedSort)){
        $sort = $_GET['sort'];
    }

}

$search = mysqli_real_escape_string($conn, $search);

/* ===================================================
   LOAD CATEGORIES
=================================================== */

$categoryQuery = mysqli_query(

    $conn,

    "SELECT id, category_name
     FROM categories
     WHERE status='Active'
     ORDER BY category_name ASC"

);

/* ===================================================
   PRODUCTS QUERY
=================================================== */

$sql = "

SELECT
    p.*,
    c.category_name

FROM products p

INNER JOIN categories c

ON p.category_id = c.id

WHERE p.status='Active'

";

/* Search */

if(!empty($search)){

    $sql .= "

    AND (

        p.product_name LIKE '%$search%'

        OR p.brand LIKE '%$search%'

        OR p.short_description LIKE '%$search%'

    )

    ";

}

/* Category */

if($category > 0){

    $sql .= "

    AND p.category_id = $category

    ";

}

/* Sorting */

switch($sort){

    case "oldest":
        $sql .= " ORDER BY p.id ASC";
        break;

    case "price_low":
        $sql .= " ORDER BY p.price ASC";
        break;

    case "price_high":
        $sql .= " ORDER BY p.price DESC";
        break;

    case "name_asc":
        $sql .= " ORDER BY p.product_name ASC";
        break;

    case "name_desc":
        $sql .= " ORDER BY p.product_name DESC";
        break;

    default:
        $sql .= " ORDER BY p.id DESC";

}

/* ===================================================
   PAGINATION
=================================================== */

$limit = 12;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if($page < 1){

    $page = 1;

}

$offset = ($page - 1) * $limit;

/* Total Products */

$totalQuery = mysqli_query($conn, $sql);

$totalProducts = mysqli_num_rows($totalQuery);

$totalPages = ceil($totalProducts / $limit);

/* Final Query */

$sql .= " LIMIT $offset, $limit";

$productQuery = mysqli_query($conn, $sql);

?>

<section class="products-page">

    <div class="container">

        <div class="section-title">

            <!-- ==========================
                 SEARCH FORM
            =========================== -->

            <form
                action="products.php"
                method="GET"
                class="search-form mb-5">

                <div class="row g-3 justify-content-center">

                    <!-- Search -->

                    <div class="col-lg-4">

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search products..."
                            value="<?php echo htmlspecialchars($search); ?>">

                    </div>

                    <!-- Category -->

                    <div class="col-lg-3">

                        <select
                            name="category"
                            class="form-select">

                            <option value="0">

                                All Categories

                            </option>

                            <?php

                            mysqli_data_seek($categoryQuery,0);

                            while($cat = mysqli_fetch_assoc($categoryQuery)){

                            ?>

                            <option
                                value="<?php echo $cat['id']; ?>"

                                <?php

                                if($category == $cat['id']){

                                    echo "selected";

                                }

                                ?>

                            >

                                <?php

                                echo htmlspecialchars($cat['category_name']);

                                ?>

                            </option>

                            <?php } ?>

                        </select>

                    </div>

                    <!-- Sort -->

                    <div class="col-lg-3">

                        <select
                            name="sort"
                            class="form-select">

                            <option value="newest" <?php if($sort=="newest") echo "selected"; ?>>

                                Newest

                            </option>

                            <option value="oldest" <?php if($sort=="oldest") echo "selected"; ?>>

                                Oldest

                            </option>

                            <option value="price_low" <?php if($sort=="price_low") echo "selected"; ?>>

                                Price: Low → High

                            </option>

                            <option value="price_high" <?php if($sort=="price_high") echo "selected"; ?>>

                                Price: High → Low

                            </option>

                            <option value="name_asc" <?php if($sort=="name_asc") echo "selected"; ?>>

                                A → Z

                            </option>

                            <option value="name_desc" <?php if($sort=="name_desc") echo "selected"; ?>>

                                Z → A

                            </option>

                        </select>

                    </div>

                    <!-- Search Button -->

                    <div class="col-lg-2 d-grid">

                        <button
                            class="btn btn-main"
                            type="submit">

                            <i class="fa-solid fa-magnifying-glass"></i>

                            Search

                        </button>

                    </div>

                </div>

            </form>
                        <span>

                Our Collection

            </span>

            <h2>

                All Products

            </h2>

            <p>

                Explore our complete skincare and beauty collection.

            </p>

        </div>

        <?php

        if(mysqli_num_rows($productQuery) == 0){

        ?>

        <div class="alert alert-warning text-center">

            No products found.

        </div>

        <?php

        }

        ?>

        <div class="row">

            <?php

            while($product = mysqli_fetch_assoc($productQuery)){

            ?>

            <div class="col-lg-3 col-md-6 mb-4">

                <div
                    class="product-card"
                    data-aos="fade-up">

                    <!-- Featured Badge -->

                    <?php

                    if($product['featured'] == "Yes"){

                    ?>

                    <div class="featured-badge">

                        Featured

                    </div>

                    <?php

                    }

                    ?>

                    <!-- Product Image -->

                    <div class="product-image">

                        <?php

                        if(

                            !empty($product['old_price']) &&

                            $product['old_price'] > $product['price']

                        ){

                            $discount = round(

                                (

                                    ($product['old_price'] - $product['price'])

                                    /

                                    $product['old_price']

                                ) * 100

                            );

                        ?>

                        <span class="discount-badge">

                            -<?php echo $discount; ?>%

                        </span>

                        <?php

                        }

                        ?>

                        <!-- Product Icons -->

                        <div class="product-icons">

                            <a
                                href="#"
                                title="Wishlist">

                                <i class="fa-regular fa-heart"></i>

                            </a>

                            <a
                                href="product.php?id=<?php echo $product['id']; ?>"
                                title="Quick View">

                                <i class="fa-regular fa-eye"></i>

                            </a>

                        </div>

                        <img

                            src="assets/uploads/products/<?php echo htmlspecialchars($product['product_image']); ?>"

                            alt="<?php echo htmlspecialchars($product['product_name']); ?>">

                    </div>

                    <!-- Product Content -->

                    <div class="product-content">
                                                <h5>

                            <?php echo htmlspecialchars($product['product_name']); ?>

                        </h5>

                        <!-- Product Category -->

                        <p class="product-category">

                            <i class="fa-solid fa-tag"></i>

                            <?php echo htmlspecialchars($product['category_name']); ?>

                        </p>

                        <!-- Stock Status -->

                        <?php

                        if($product['stock'] > 0){

                        ?>

                        <div class="stock available">

                            <i class="fa-solid fa-circle-check"></i>

                            In Stock

                        </div>

                        <?php

                        }else{

                        ?>

                        <div class="stock unavailable">

                            <i class="fa-solid fa-circle-xmark"></i>

                            Out of Stock

                        </div>

                        <?php

                        }

                        ?>

                        <!-- Product Price -->

                        <div class="price">

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

                        </div>

                        <!-- View Details -->

                        <a

                            href="product.php?id=<?php echo $product['id']; ?>"

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

        <!-- ==========================
             PAGINATION
        =========================== -->

        <?php

        if($totalPages > 1){

        ?>

        <nav class="mt-5">

            <ul class="pagination justify-content-center">

                <!-- Previous -->

                <?php

                if($page > 1){

                ?>

                <li class="page-item">

                    <a

                        class="page-link"

                        href="?search=<?php echo urlencode($search); ?>&category=<?php echo $category; ?>&sort=<?php echo urlencode($sort); ?>&page=<?php echo $page-1; ?>">

                        Previous

                    </a>

                </li>

                <?php

                }

                ?>

                <!-- Page Numbers -->

                <?php

                for($i = 1; $i <= $totalPages; $i++){

                ?>

                <li class="page-item <?php if($page == $i) echo 'active'; ?>">

                    <a

                        class="page-link"

                        href="?search=<?php echo urlencode($search); ?>&category=<?php echo $category; ?>&sort=<?php echo urlencode($sort); ?>&page=<?php echo $i; ?>">

                        <?php echo $i; ?>

                    </a>

                </li>

                <?php

                }

                ?>

                <!-- Next -->

                <?php

                if($page < $totalPages){

                ?>

                <li class="page-item">

                    <a

                        class="page-link"

                        href="?search=<?php echo urlencode($search); ?>&category=<?php echo $category; ?>&sort=<?php echo urlencode($sort); ?>&page=<?php echo $page+1; ?>">

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

    </div>

</section>
<?php

include 'includes/footer.php';

include 'includes/scripts.php';

?>