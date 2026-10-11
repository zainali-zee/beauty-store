<?php

include '../config/database.php';
include '../config/settings.php';

include 'includes/auth.php';

redirectIfLoggedIn();
$pageTitle = "Admin Login | SCS Star Beauty";
$pageCSS = "login.css";
$pageJS = "login.js";

include 'includes/header.php';
$error = "";

if(isset($_POST['login'])){

    $email = trim($_POST['email']);

    $password = $_POST['password'];

    $email = mysqli_real_escape_string($conn,$email);

$query = mysqli_query(

    $conn,

    "SELECT *

     FROM admin

     WHERE

     email='$email'

     AND

     status='Active'

     LIMIT 1"

);
    if(mysqli_num_rows($query)==1){

        $admin = mysqli_fetch_assoc($query);

        if(password_verify($password,$admin['password'])){

            $_SESSION['admin_id'] = $admin['id'];

            $_SESSION['admin_name'] = $admin['full_name'];

            $_SESSION['admin_email'] = $admin['email'];

            header("Location: dashboard.php");

            exit;

        }

    }

    $error = "Invalid email or password.";

}

?>

<div class="login-page">

    <div class="login-card">

        <div class="login-logo">

            <img

                src="../assets/images/logo.png"

                alt="Logo">

        </div>

        <h2>
            <?php

if($error!=""){

?>

<div class="alert alert-danger">

    <?php echo $error; ?>

</div>

<?php

}

?>

            Admin Login

        </h2>

        <p>

            Welcome back! Please login to continue.

        </p>

        <form
            action=""
            method="POST">

            <!-- Email -->

            <div class="mb-3">

                <label class="form-label">

                    Email Address

                </label>

                <input

                    type="email"

                    name="email"

                    class="form-control"

                    placeholder="Enter your email"

                    required>

            </div>

            <!-- Password -->

            <div class="mb-3">

                <label class="form-label">

                    Password

                </label>

                <div class="password-box">

                    <input

                        type="password"

                        name="password"

                        id="password"

                        class="form-control"

                        placeholder="Enter password"

                        required>

                    <button

                        type="button"

                        id="togglePassword"

                        class="password-toggle">

                        <i class="fa-solid fa-eye"></i>

                    </button>

                </div>

            </div>

            <!-- Remember -->

            <div class="form-check mb-4">

                <input

                    class="form-check-input"

                    type="checkbox"

                    id="remember"

                    name="remember">

                <label

                    class="form-check-label"

                    for="remember">

                    Remember Me

                </label>

            </div>
<button

type="submit"

name="login"

class="btn btn-main w-100">
                <i class="fa-solid fa-right-to-bracket"></i>

                Login

            </button>

        </form>

    </div>

</div>

<?php

include 'includes/scripts.php';

?>