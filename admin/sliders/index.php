<?php
$pageTitle = "Manage Sliders";

include '../includes/header.php';
include '../config/database.php';

$query = mysqli_query($conn, "
SELECT *
FROM sliders
ORDER BY display_order ASC
");
?>

<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h3>Homepage Sliders</h3>

        <a href="add.php" class="btn btn-primary">
            Add New Slider
        </a>

    </div>

    <table class="table table-bordered table-hover">

        <thead class="table-dark">

        <tr>

            <th>ID</th>

            <th>Image</th>

            <th>Title</th>

            <th>Status</th>

            <th>Order</th>

            <th>Actions</th>

        </tr>

        </thead>

        <tbody>

        <?php while($slider=mysqli_fetch_assoc($query)): ?>

        <tr>

            <td><?= $slider['id']; ?></td>

            <td>

                <img
                src="../../assets/images/sliders/<?= $slider['image']; ?>"
                width="180">

            </td>

            <td><?= htmlspecialchars($slider['title']); ?></td>

            <td>

                <?php if($slider['status']=="active"): ?>

                    <span class="badge bg-success">Active</span>

                <?php else: ?>

                    <span class="badge bg-danger">Inactive</span>

                <?php endif; ?>

            </td>

            <td><?= $slider['display_order']; ?></td>

            <td>

                <a href="edit.php?id=<?= $slider['id']; ?>"
                class="btn btn-warning btn-sm">

                    Edit

                </a>

                <a href="delete.php?id=<?= $slider['id']; ?>"
                class="btn btn-danger btn-sm"
                onclick="return confirm('Delete this slider?')">

                    Delete

                </a>

            </td>

        </tr>

        <?php endwhile; ?>

        </tbody>

    </table>

</div>

<?php include '../includes/footer.php'; ?>