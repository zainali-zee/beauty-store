<?php

include 'config/database.php';

$pageTitle = "Contact Us | " . SITE_NAME;
$pageCSS   = "contact.css";
$pageJS    = "contact.js";

include 'includes/header.php';
include 'includes/navbar.php';

/* ==========================================
        WEBSITE SETTINGS
========================================== */

$settingsQuery = mysqli_query(

    $conn,

    "SELECT *
     FROM settings
     LIMIT 1"

);

$settings = mysqli_fetch_assoc($settingsQuery);

/* ==========================================
        CONTACT FORM
========================================== */

$success = "";
$error   = "";

$name = "";
$email = "";
$subject = "";
$message = "";

if(isset($_POST['send_message'])){

    $name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    $name = mysqli_real_escape_string($conn,$name);
    $email = mysqli_real_escape_string($conn,$email);
    $subject = mysqli_real_escape_string($conn,$subject);
    $message = mysqli_real_escape_string($conn,$message);

    if(

        empty($name) ||

        empty($email) ||

        empty($subject) ||

        empty($message)

    ){

        $error = "Please fill in all required fields.";

    }

    elseif(!filter_var($email,FILTER_VALIDATE_EMAIL)){

        $error = "Please enter a valid email address.";

    }

    else{

        $insert = mysqli_query(

            $conn,

            "INSERT INTO contact_messages
            (
                full_name,
                email,
                subject,
                message
            )
            VALUES
            (
                '$name',
                '$email',
                '$subject',
                '$message'
            )"

        );

        if($insert){

            $success = "Thank you! Your message has been sent successfully.";

            $name = "";
            $email = "";
            $subject = "";
            $message = "";

        }else{

            $error = "Something went wrong. Please try again.";

        }

    }

}

?>
<!-- ==========================================
        PAGE BANNER
========================================== -->

<section class="page-banner">

    <div class="container">

        <div class="banner-content">

            <h1 data-aos="fade-up">

                Contact Us

            </h1>

            <p
                data-aos="fade-up"
                data-aos-delay="200">

                We'd love to hear from you. Contact us anytime.

            </p>

        </div>

    </div>

</section>

<!-- ==========================================
        CONTACT SECTION
========================================== -->

<section class="contact-section">

    <div class="container">

        <div class="row g-5">

            <!-- LEFT SIDE -->

            <div class="col-lg-5">

                <div
                    class="contact-info"
                    data-aos="fade-right">

                    <h2>

                        Get In Touch

                    </h2>

                    <p>

                        Have questions about our products? Our team is here to help you.

                    </p>

                    <!-- Online Store -->

                    <div class="contact-item">

                        <div class="contact-icon">

                            <i class="fa-solid fa-store"></i>

                        </div>

                        <div>

                            <h5>

                                Store

                            </h5>

                            <p>

                                Online Store

                            </p>

                        </div>

                    </div>

                    <!-- Phone -->

                    <div class="contact-item">

                        <div class="contact-icon">

                            <i class="fa-solid fa-phone"></i>

                        </div>

                        <div>

                            <h5>

                                Phone

                            </h5>

                            <p>

                                <?php echo htmlspecialchars($settings['phone']); ?>

                            </p>

                        </div>

                    </div>

                    <!-- WhatsApp -->

                    <div class="contact-item">

                        <div class="contact-icon">

                            <i class="fa-brands fa-whatsapp"></i>

                        </div>

                        <div>

                            <h5>

                                WhatsApp

                            </h5>

                            <p>

                                <?php echo htmlspecialchars($settings['whatsapp']); ?>

                            </p>

                        </div>

                    </div>

                    <!-- Email -->

                    <div class="contact-item">

                        <div class="contact-icon">

                            <i class="fa-solid fa-envelope"></i>

                        </div>

                        <div>

                            <h5>

                                Email

                            </h5>

                            <p>

                                <?php echo htmlspecialchars($settings['email']); ?>

                            </p>

                        </div>

                    </div>

                    <!-- Business Hours -->

                    <div class="contact-item">

                        <div class="contact-icon">

                            <i class="fa-solid fa-clock"></i>

                        </div>

                        <div>

                            <h5>

                                Business Hours

                            </h5>

                            <p>

                                <?php

                                echo nl2br(

                                    htmlspecialchars(

                                        $settings['business_hours']

                                    )

                                );

                                ?>

                            </p>

                        </div>

                    </div>

                </div>

            </div>

            <!-- RIGHT SIDE -->
             <!-- RIGHT SIDE -->

<div class="col-lg-7">

    <div
        class="contact-form-card"
        data-aos="fade-left">

        <h2>

            Send Us A Message

        </h2>

        <!-- Success Message -->

        <?php

        if($success != ""){

        ?>

        <div class="alert alert-success">

            <?php echo $success; ?>

        </div>

        <?php

        }

        ?>

        <!-- Error Message -->

        <?php

        if($error != ""){

        ?>

        <div class="alert alert-danger">

            <?php echo $error; ?>

        </div>

        <?php

        }

        ?>

        <form
            action=""
            method="POST">

            <div class="row">

                <!-- Full Name -->

                <div class="col-md-6 mb-3">

                    <input

                        type="text"

                        name="full_name"

                        class="form-control"

                        placeholder="Full Name"

                        value="<?php echo htmlspecialchars($name); ?>"

                        required>

                </div>

                <!-- Email -->

                <div class="col-md-6 mb-3">

                    <input

                        type="email"

                        name="email"

                        class="form-control"

                        placeholder="Email Address"

                        value="<?php echo htmlspecialchars($email); ?>"

                        required>

                </div>

            </div>

            <!-- Subject -->

            <div class="mb-3">

                <input

                    type="text"

                    name="subject"

                    class="form-control"

                    placeholder="Subject"

                    value="<?php echo htmlspecialchars($subject); ?>"

                    required>

            </div>

            <!-- Message -->

            <div class="mb-4">

                <textarea

                    name="message"

                    class="form-control"

                    rows="6"

                    placeholder="Write your message..."

                    required><?php echo htmlspecialchars($message); ?></textarea>

            </div>

            <!-- Submit -->

            <button

                type="submit"

                name="send_message"

                class="btn btn-main">

                <i class="fa-solid fa-paper-plane"></i>

                Send Message

            </button>

        </form>
                <!-- ==========================================
                SOCIAL LINKS
        =========================================== -->

        <div class="social-links mt-5">

            <h4>

                Follow Us

            </h4>

            <a

                href="<?php echo htmlspecialchars($settings['facebook']); ?>"

                target="_blank"

                rel="noopener noreferrer"

                aria-label="Facebook">

                <i class="fab fa-facebook-f"></i>

            </a>

            <a

                href="<?php echo htmlspecialchars($settings['instagram']); ?>"

                target="_blank"

                rel="noopener noreferrer"

                aria-label="Instagram">

                <i class="fab fa-instagram"></i>

            </a>

            <a

                href="<?php echo htmlspecialchars($settings['tiktok']); ?>"

                target="_blank"

                rel="noopener noreferrer"

                aria-label="TikTok">

                <i class="fab fa-tiktok"></i>

            </a>

            <a

                href="<?php echo htmlspecialchars($settings['youtube']); ?>"

                target="_blank"

                rel="noopener noreferrer"

                aria-label="YouTube">

                <i class="fab fa-youtube"></i>

            </a>

        </div>

    </div>

</div>

        </div>

    </div>

</section>

<?php

include 'includes/footer.php';

include 'includes/scripts.php';

?>