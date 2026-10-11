<link rel="stylesheet" href="assets/css/categories.css">
<?php

include 'includes/auth.php';
include '../config/database.php';
include '../config/settings.php';
requireAdminLogin();
/* ==========================================
        SEARCH & FILTER
========================================== */

$search = "";

$statusFilter = "";

if(isset($_GET['search'])){

    $search = trim($_GET['search']);

}

if(isset($_GET['status'])){

    $statusFilter = trim($_GET['status']);

}

$search = mysqli_real_escape_string($conn,$search);

$statusFilter = mysqli_real_escape_string($conn,$statusFilter);
/* ==========================================
        ADD CATEGORY
========================================== */

$success = "";

$error = "";

if(isset($_POST['add_category'])){

    $categoryName = trim($_POST['category_name']);

    $status = $_POST['status'];

    $categoryName = mysqli_real_escape_string($conn,$categoryName);

    $imageName = "";

    if(isset($_FILES['category_image']) && $_FILES['category_image']['error']==0){

        $extension = strtolower(

            pathinfo(

                $_FILES['category_image']['name'],

                PATHINFO_EXTENSION

            )

        );

        $allowed = [

            "jpg",

            "jpeg",

            "png",

            "webp"

        ];

        if(in_array($extension,$allowed)){

            $imageName = time()."_".uniqid().".".$extension;

            move_uploaded_file(

                $_FILES['category_image']['tmp_name'],

                "../assets/uploads/categories/".$imageName

            );

        }

    }

    if(empty($categoryName)){

        $error = "Category name is required.";

    }else{

        $insert = mysqli_query(

            $conn,

            "INSERT INTO categories

            (

                category_name,

                category_image,

                status

            )

            VALUES

            (

                '$categoryName',

                '$imageName',

                '$status'

            )"

        );

        if($insert){

            header("Location: categories.php?success=1");

            exit;

        }else{

            $error = "Unable to save category.";

        }

    }

}

$pageTitle = "Categories | Admin";
$pageCSS = "categories.css";
$pageJS = "categories.js";

include 'includes/header.php';
/* ==========================================
        LOAD CATEGORIES
========================================== */

$sql = "

SELECT *

FROM categories

WHERE 1

";

if(!empty($search)){

    $sql .= "

    AND category_name

    LIKE '%$search%'

    ";

}

if(!empty($statusFilter)){

    $sql .= "

    AND status='$statusFilter'

    ";

}

$sql .= "

ORDER BY id DESC

";
/* ==========================================
        PAGINATION
========================================== */

$limit = 10;

$page = isset($_GET['page']) ?

(int)$_GET['page']

: 1;

if($page < 1){

    $page = 1;

}

$offset = ($page-1)*$limit;

/* Total Rows */

$totalQuery = mysqli_query(

    $conn,

    $sql

);

$totalRows = mysqli_num_rows($totalQuery);

$totalPages = ceil($totalRows/$limit);

/* Final Query */

$sql .= "

LIMIT

$offset,

$limit

";

$categories = mysqli_query(

    $conn,

    $sql

);
include 'includes/navbar.php';
include 'includes/sidebar.php';


?>
<?php

if(isset($_GET['success'])){

?>

<div class="container-fluid mt-3">

    <div class="alert alert-success">

        Category added successfully.

    </div>

</div>
<?php

if(isset($_GET['updated'])){

?>

<div class="container-fluid mt-3">

    <div class="alert alert-success">

        Category updated successfully.

    </div>

</div>
<?php

if(isset($_GET['deleted'])){

?>

<div class="container-fluid mt-3">

    <div class="alert alert-success">

        Category deleted successfully.

    </div>

</div>

<?php

}

?>
<?php

}

?>
<?php

}

?>
<div class="dashboard-content">
<form

method="GET"

class="row g-3 mb-4">

<div class="col-md-5">

<input

type="text"

name="search"

class="form-control"

placeholder="Search category..."

value="<?php echo htmlspecialchars($search); ?>">

</div>

<div class="col-md-3">

<select

name="status"

class="form-select">

<option value="">

All Status

</option>

<option

value="Active"

<?php if($statusFilter=="Active") echo "selected"; ?>>

Active

</option>

<option

value="Inactive"

<?php if($statusFilter=="Inactive") echo "selected"; ?>>

Inactive

</option>

</select>

</div>

<div class="col-md-2">

<button

class="btn btn-primary w-100">

Search

</button>

</div>

<div class="col-md-2">

<a

href="categories.php"

class="btn btn-secondary w-100">

Reset

</a>

</div>

</form>
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2>

                Category Management

            </h2>

            <p class="text-muted">

                Manage all product categories.

            </p>

        </div>

       <button

class="btn btn-primary"

data-bs-toggle="modal"

data-bs-target="#addCategoryModal">

            <i class="fa-solid fa-plus"></i>

            Add Category

        </button>

    </div>

    <div class="dashboard-box">

        <div class="table-responsive">

            <table class="table table-bordered table-hover align-middle">

                <thead>

                    <tr>

                        <th width="80">

                            ID

                        </th>

                        <th width="120">

                            Image

                        </th>

                        <th>

                            Category Name

                        </th>

                        <th width="140">

                            Status

                        </th>

                        <th width="180">

                            Created

                        </th>

                        <th width="180">

                            Actions

                        </th>

                    </tr>

                </thead>

             <tbody>

<?php

if(mysqli_num_rows($categories)>0){

    while($category=mysqli_fetch_assoc($categories)){

?>

<tr>

    <td>

        <?php echo $category['id']; ?>

    </td>

    <td>

        <?php

        if(!empty($category['category_image'])){

        ?>

        <img

        src="../assets/uploads/categories/<?php echo htmlspecialchars($category['category_image']); ?>"

        alt="<?php echo htmlspecialchars($category['category_name']); ?>">

        <?php

        }else{

        ?>

        <span class="text-muted">

            No Image

        </span>

        <?php

        }

        ?>

    </td>

    <td>

        <?php echo htmlspecialchars($category['category_name']); ?>

    </td>

    <td>

        <?php

        if($category['status']=="Active"){

        ?>

        <span class="badge bg-success">

            Active

        </span>

        <?php

        }else{

        ?>

        <span class="badge bg-danger">

            Inactive

        </span>

        <?php

        }

        ?>

    </td>

    <td>

        <?php

        echo date(

            "d M Y",

            strtotime($category['created_at'])

        );

        ?>

    </td>
<td>

<div class="action-buttons">

<button

class="btn btn-warning btn-sm"

data-bs-toggle="modal"

data-bs-target="#editCategory<?php echo $category['id']; ?>">

<i class="fa-solid fa-pen"></i>

</button>

<a

href="categories.php?delete=<?php echo $category['id']; ?>"

class="btn btn-danger btn-sm"

onclick="return confirm('Are you sure you want to delete this category?');">

<i class="fa-solid fa-trash"></i>

</a>

</div>

</td>
</tr>
<!-- Edit Category Modal -->

<div

class="modal fade"

id="editCategory<?php echo $category['id']; ?>"

tabindex="-1">

<div class="modal-dialog">

<div class="modal-content">

<form

method="POST"

enctype="multipart/form-data">

<div class="modal-header">

<h5>

Edit Category

</h5>

<button

type="button"

class="btn-close"

data-bs-dismiss="modal">

</button>

</div>

<div class="modal-body">

<input

type="hidden"

name="category_id"

value="<?php echo $category['id']; ?>">

<input

type="hidden"

name="old_image"

value="<?php echo htmlspecialchars($category['category_image']); ?>">

<div class="mb-3">

<label>

Category Name

</label>

<input

type="text"

name="category_name"

class="form-control"

value="<?php echo htmlspecialchars($category['category_name']); ?>"

required>

</div>

<div class="mb-3">

<label>

Current Image

</label>

<br>

<?php

if(!empty($category['category_image'])){

?>

<img

src="../assets/uploads/categories/<?php echo htmlspecialchars($category['category_image']); ?>"

style="width:90px;height:90px;object-fit:cover;border-radius:8px;">

<?php

}else{

?>

<p class="text-muted">

No Image

</p>

<?php

}

?>

</div>

<div class="mb-3">

<label>

Change Image

</label>

<input

type="file"

name="category_image"

class="form-control"

accept=".jpg,.jpeg,.png,.webp">

</div>

<div class="mb-3">

<label>

Status

</label>

<select

name="status"

class="form-select">

<option

value="Active"

<?php if($category['status']=="Active") echo "selected"; ?>>

Active

</option>

<option

value="Inactive"

<?php if($category['status']=="Inactive") echo "selected"; ?>>

Inactive

</option>

</select>

</div>

</div>

<div class="modal-footer">

<button

type="button"

class="btn btn-secondary"

data-bs-dismiss="modal">

Cancel

</button>

<button

type="submit"

name="update_category"

class="btn btn-warning">

Update Category

</button>

</div>

</form>

</div>

</div>

</div>

<?php

    }

}else{

?>

<tr>

<td

colspan="6"

class="text-center py-5">

No categories found.

</td>

</tr>

<?php

}

?>

</tbody>

            </table>
            <?php

if($totalPages>1){

?>

<nav class="mt-4">

<ul class="pagination justify-content-center">

<?php

for($i=1;$i<=$totalPages;$i++){

?>

<li

class="page-item

<?php

if($page==$i)

echo 'active';

?>">

<a

class="page-link"

href="?search=<?php

echo urlencode($search);

?>&status=<?php

echo urlencode($statusFilter);

?>&page=<?php

echo $i;

?>">

<?php

echo $i;

?>

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

    </div>

</div>
<!-- /* ==========================================
        UPDATE CATEGORY
========================================== */ -->
<?php
if(isset($_POST['update_category'])){

    $categoryId = (int)$_POST['category_id'];

    $categoryName = trim($_POST['category_name']);

    $status = $_POST['status'];

    $oldImage = $_POST['old_image'];

    $categoryName = mysqli_real_escape_string($conn,$categoryName);

    $imageName = $oldImage;

    if(

        isset($_FILES['category_image']) &&

        $_FILES['category_image']['error']==0

    ){

        $extension = strtolower(

            pathinfo(

                $_FILES['category_image']['name'],

                PATHINFO_EXTENSION

            )

        );

        $allowed = [

            "jpg",

            "jpeg",

            "png",

            "webp"

        ];

        if(in_array($extension,$allowed)){

            $imageName = time()."_".uniqid().".".$extension;

            move_uploaded_file(

                $_FILES['category_image']['tmp_name'],

                "../assets/uploads/categories/".$imageName

            );

            if(

                !empty($oldImage) &&

                file_exists("../assets/uploads/categories/".$oldImage)

            ){

                unlink("../assets/uploads/categories/".$oldImage);

            }

        }

    }

    $update = mysqli_query(

        $conn,

        "UPDATE categories

        SET

            category_name='$categoryName',

            category_image='$imageName',

            status='$status'

        WHERE id=$categoryId"

    );

    if($update){

        header("Location: categories.php?updated=1");

        exit;

    }

}
/* ==========================================
        DELETE CATEGORY
========================================== */

if(isset($_GET['delete'])){

    $categoryId = (int)$_GET['delete'];

    /* Get Image */

    $query = mysqli_query(

        $conn,

        "SELECT category_image

         FROM categories

         WHERE id=$categoryId

         LIMIT 1"

    );

    if(mysqli_num_rows($query)==1){

        $row = mysqli_fetch_assoc($query);

        if(

            !empty($row['category_image']) &&

            file_exists("../assets/uploads/categories/".$row['category_image'])

        ){

            unlink("../assets/uploads/categories/".$row['category_image']);

        }

    }

    /* Delete Category */

    mysqli_query(

        $conn,

        "DELETE FROM categories

         WHERE id=$categoryId"

    );

    header("Location: categories.php?deleted=1");

    exit;

}
?>
<!-- ==========================================
        ADD CATEGORY MODAL
========================================== -->

<div

class="modal fade"

id="addCategoryModal"

tabindex="-1">

<div class="modal-dialog">

<div class="modal-content">

<form

method="POST"

enctype="multipart/form-data">

<div class="modal-header">

<h5>

Add Category

</h5>

<button

type="button"

class="btn-close"

data-bs-dismiss="modal">

</button>

</div>

<div class="modal-body">

<?php

if($error!=""){

?>

<div class="alert alert-danger">

<?php echo $error; ?>

</div>

<?php

}

?>

<div class="mb-3">

<label>

Category Name

</label>

<input

type="text"

name="category_name"

class="form-control"

required>

</div>

<div class="mb-3">

<label>

Category Image

</label>

<input

type="file"

name="category_image"

class="form-control"

accept=".jpg,.jpeg,.png,.webp">

</div>

<div class="mb-3">

<label>

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

</div>

<div class="modal-footer">

<button

type="button"

class="btn btn-secondary"

data-bs-dismiss="modal">

Cancel

</button>

<button

type="submit"

name="add_category"

class="btn btn-primary">

Save Category

</button>

</div>

</form>

</div>

</div>

</div>
<?php

include 'includes/footer.php';
include 'includes/scripts.php';

?>