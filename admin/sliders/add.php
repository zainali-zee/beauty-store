<?php
$pageTitle = "Add Slider";

include '../includes/header.php';
include '../includes/db.php';

if(isset($_POST['add_slider']))
{
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $subtitle = mysqli_real_escape_string($conn, $_POST['subtitle']);
    $button_text = mysqli_real_escape_string($conn, $_POST['button_text']);
    $button_link = mysqli_real_escape_string($conn, $_POST['button_link']);
    $display_order = (int)$_POST['display_order'];
    $status = $_POST['status'];

    $image = "";

    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0)
    {
        $image = time() . "_" . basename($_FILES['image']['name']);

        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            "../../assets/images/sliders/".$image
        );
    }

    $sql = "INSERT INTO sliders
    (
        title,
        subtitle,
        button_text,
        button_link,
        image,
        display_order,
        status
    )
    VALUES
    (
        '$title',
        '$subtitle',
        '$button_text',
        '$button_link',
        '$image',
        '$display_order',
        '$status'
    )";

    mysqli_query($conn,$sql);

    header("Location:index.php");
    exit;
}
?>

<div class="container mt-4">

    <div class="card">

        <div class="card-header">

            <h3>Add New Slider</h3>

        </div>

        <div class="card-body">

            <form method="POST"
                  enctype="multipart/form-data">

                <div class="mb-3">

                    <label class="form-label">
                        Slider Title
                    </label>

                    <input
                        type="text"
                        name="title"
                        class="form-control"
                        required>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Subtitle
                    </label>

                    <textarea
                        name="subtitle"
                        class="form-control"
                        rows="3"></textarea>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Button Text
                    </label>

                    <input
                        type="text"
                        name="button_text"
                        class="form-control">

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Button Link
                    </label>

                    <input
                        type="text"
                        name="button_link"
                        class="form-control">

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Slider Image
                    </label>

                    <input
                        type="file"
                        name="image"
                        class="form-control"
                        required>

                </div>

                <div class="row">

                    <div class="col-md-6">

                        <label class="form-label">
                            Display Order
                        </label>

                        <input
                            type="number"
                            name="display_order"
                            class="form-control"
                            value="1">

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select">

                            <option value="active">
                                Active
                            </option>

                            <option value="inactive">
                                Inactive
                            </option>

                        </select>

                    </div>

                </div>

                <br>

                <button
                    type="submit"
                    name="add_slider"
                    class="btn btn-success">

                    Save Slider

                </button>

                <a href="index.php"
                   class="btn btn-secondary">

                    Cancel

                </a>

            </form>

        </div>

    </div>

</div>

<?php include '../includes/footer.php'; ?>