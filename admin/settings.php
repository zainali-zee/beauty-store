<?php

include '../config/database.php';

include 'includes/auth.php';

requireAdminLogin();

/* ==========================================
        PAGE SETTINGS
========================================== */

$pageTitle = "Website Settings | SCS Star Beauty";

$pageCSS = "settings.css";

$pageJS = "settings.js";

include 'includes/header.php';

if(isset($_SESSION['success'])){

?>

<div class="container-fluid mt-3">

    <div class="alert alert-success alert-dismissible fade show">

        <i class="fa-solid fa-circle-check"></i>

        <?php

        echo $_SESSION['success'];

        unset($_SESSION['success']);

        ?>

        <button

            type="button"

            class="btn-close"

            data-bs-dismiss="alert">

        </button>

    </div>

</div>

<?php

}

?>

<?php

if(isset($_SESSION['error'])){

?>

<div class="container-fluid mt-3">

    <div class="alert alert-danger alert-dismissible fade show">

        <i class="fa-solid fa-circle-xmark"></i>

        <?php

        echo $_SESSION['error'];

        unset($_SESSION['error']);

        ?>

        <button

            type="button"

            class="btn-close"

            data-bs-dismiss="alert">

        </button>

    </div>

</div>

<?php

}

?>

?>

<div class="dashboard-content">

    <!-- ==========================================
            PAGE HEADER
    ========================================== -->

    <div class="page-header">

        <div>

            <h2>

                <i class="fa-solid fa-gear"></i>

                Website Settings

            </h2>

            <p>

                Manage your website information.

            </p>

        </div>

    </div>

    <!-- ==========================================
            SETTINGS FORM
    ========================================== -->

    <div class="dashboard-box">

        <form

            id="settingsForm"

            action="update-settings.php"

            method="POST"

            enctype="multipart/form-data">

            <!-- Form Sections will be added
                 in upcoming parts -->
            <!-- ==========================================
        BRANDING
========================================== -->

            <div class="settings-section">

                <h4>

                    <i class="fa-solid fa-palette"></i>

                    Branding

                </h4>

                <div class="row">

                    <!-- Website Name -->

                    <div class="col-lg-12 mb-4">

                        <label class="form-label">

                            Website Name

                        </label>

                        <input

                            type="text"

                            name="website_name"

                            class="form-control"

                            value="<?php echo htmlspecialchars($settings['website_name']); ?>"

                            placeholder="Enter website name"

                            required>

                    </div>

                    <!-- Logo -->

                    <div class="col-lg-6">

                        <label class="form-label">

                            Website Logo

                        </label>

                        <input

                            type="file"

                            name="logo"

                            id="logo"

                            class="form-control"

                            accept=".png,.jpg,.jpeg,.svg,.webp">
                        <small class="text-muted">

                            Recommended size:
                            250 × 80 pixels.

                        </small>

                    </div>

                    <!-- Favicon -->

                    <div class="col-lg-6">

                        <label class="form-label">

                            Website Favicon

                        </label>

                        <input

                            type="file"

                            name="favicon"

                            id="favicon"

                            class="form-control"

                            accept=".ico,.png,.svg">

                    </div>

                </div>

                <div class="row mt-4">

                    <!-- Logo Preview -->

                    <div class="col-md-6 text-center">

                        <label class="form-label d-block">

                            Current Logo

                        </label>

                        <div class="image-preview">

                            <img

                                id="logoPreview"

                                src="<?php

                                        if (

                                            !empty($settings['logo']) &&

                                            file_exists(

                                                "../assets/uploads/logo/" . $settings['logo']

                                            )

                                        ) {

                                            echo "../assets/uploads/logo/" . $settings['logo'];
                                        } else {

                                            echo "../assets/images/no-image.png";
                                        }

                                        ?>"

                                alt="Website Logo">
                        </div>

                    </div>

                    <!-- Favicon Preview -->

                    <div class="col-md-6 text-center">

                        <label class="form-label d-block">

                            Current Favicon

                        </label>

                        <div class="image-preview">

                            <img

                                id="faviconPreview"

                                src="<?php

                                        if (

                                            !empty($settings['favicon']) &&

                                            file_exists(

                                                "../assets/uploads/favicon/" . $settings['favicon']

                                            )

                                        ) {

                                            echo "../assets/uploads/favicon/" . $settings['favicon'];
                                        } else {

                                            echo "../assets/images/no-image.png";
                                        }

                                        ?>"

                                alt="Website Favicon">

                        </div>

                    </div>

                </div>

            </div>
            <!-- ==========================================
        CONTACT INFORMATION
========================================== -->

<div class="settings-section">

    <h4>

        <i class="fa-solid fa-address-book"></i>

        Contact Information

    </h4>

    <div class="row">

        <!-- Email -->

        <div class="col-lg-6 mb-4">

            <label class="form-label">

                Email Address

            </label>

            <input

                type="email"

                name="email"

                class="form-control"

                value="<?php echo htmlspecialchars($settings['email']); ?>"

                placeholder="Enter email address">

        </div>

        <!-- Phone -->

        <div class="col-lg-6 mb-4">

            <label class="form-label">

                Phone Number

            </label>

            <input

                type="text"

                name="phone"

                class="form-control"

                value="<?php echo htmlspecialchars($settings['phone']); ?>"

                placeholder="Enter phone number">

        </div>

        <!-- WhatsApp -->

        <div class="col-lg-6 mb-4">

            <label class="form-label">

                WhatsApp Number

            </label>

            <input

                type="text"

                name="whatsapp"

                class="form-control"

                value="<?php echo htmlspecialchars($settings['whatsapp']); ?>"

                placeholder="Enter WhatsApp number">

        </div>

        <!-- Business Hours -->

        <div class="col-lg-6 mb-4">

            <label class="form-label">

                Business Hours

            </label>

            <input

                type="text"

                name="business_hours"

                class="form-control"

                value="<?php echo htmlspecialchars($settings['business_hours']); ?>"

                placeholder="Monday - Saturday | 9:00 AM - 8:00 PM">

        </div>

        <!-- Address -->

        <div class="col-lg-12">

            <label class="form-label">

                Business Address

            </label>

            <textarea

                name="address"

                class="form-control"

                rows="4"

                placeholder="Enter business address"><?php echo htmlspecialchars($settings['address']); ?></textarea>

        </div>

    </div>

</div>
<!-- ==========================================
        SOCIAL MEDIA
========================================== -->

<div class="settings-section">

    <h4>

        <i class="fa-solid fa-share-nodes"></i>

        Social Media

    </h4>

    <div class="row">

        <!-- Facebook -->

        <div class="col-lg-6 mb-4">

            <label class="form-label">

                <i class="fa-brands fa-facebook text-primary"></i>

                Facebook

            </label>

            <input

                type="url"

                name="facebook"

                class="form-control"

                value="<?php echo htmlspecialchars($settings['facebook']); ?>"

                placeholder="https://facebook.com/yourpage">

        </div>

        <!-- Instagram -->

        <div class="col-lg-6 mb-4">

            <label class="form-label">

                <i class="fa-brands fa-instagram text-danger"></i>

                Instagram

            </label>

            <input

                type="url"

                name="instagram"

                class="form-control"

                value="<?php echo htmlspecialchars($settings['instagram']); ?>"

                placeholder="https://instagram.com/yourpage">

        </div>

        <!-- YouTube -->

        <div class="col-lg-6 mb-4">

            <label class="form-label">

                <i class="fa-brands fa-youtube text-danger"></i>

                YouTube

            </label>

            <input

                type="url"

                name="youtube"

                class="form-control"

                value="<?php echo htmlspecialchars($settings['youtube']); ?>"

                placeholder="https://youtube.com/@yourchannel">

        </div>

        <!-- TikTok -->

        <div class="col-lg-6 mb-4">

            <label class="form-label">

                <i class="fa-brands fa-tiktok"></i>

                TikTok

            </label>

            <input

                type="url"

                name="tiktok"

                class="form-control"

                value="<?php echo htmlspecialchars($settings['tiktok']); ?>"

                placeholder="https://tiktok.com/@yourpage">

        </div>

        <!-- LinkedIn -->

        <div class="col-lg-6 mb-4">

            <label class="form-label">

                <i class="fa-brands fa-linkedin text-primary"></i>

                LinkedIn

            </label>

            <input

                type="url"

                name="linkedin"

                class="form-control"

                value="<?php echo htmlspecialchars($settings['linkedin']); ?>"

                placeholder="https://linkedin.com/company/yourcompany">

        </div>

        <!-- Twitter / X -->

        <div class="col-lg-6 mb-4">

            <label class="form-label">

                <i class="fa-brands fa-x-twitter"></i>

                X (Twitter)

            </label>

            <input

                type="url"

                name="twitter"

                class="form-control"

                value="<?php echo htmlspecialchars($settings['twitter']); ?>"

                placeholder="https://x.com/yourpage">

        </div>

    </div>

</div>
<!-- ==========================================
        SEO & WEBSITE SETTINGS
========================================== -->

<div class="settings-section">

    <h4>

        <i class="fa-solid fa-globe"></i>

        SEO & Website Settings

    </h4>

    <div class="row">

        <!-- Currency -->

        <div class="col-lg-6 mb-4">

            <label class="form-label">

                Currency Symbol

            </label>

            <input

                type="text"

                name="currency"

                class="form-control"

                value="<?php echo htmlspecialchars($settings['currency']); ?>"

                placeholder="Rs.">

        </div>

        <!-- Meta Title -->

        <div class="col-lg-6 mb-4">

            <label class="form-label">

                Meta Title

            </label>

            <input

                type="text"

                name="meta_title"

                class="form-control"

                maxlength="255"

                value="<?php echo htmlspecialchars($settings['meta_title']); ?>"

                placeholder="Website Meta Title">

        </div>

        <!-- Meta Description -->

        <div class="col-lg-12 mb-4">

            <label class="form-label">

                Meta Description

            </label>

            <textarea

                name="meta_description"

                class="form-control"

                rows="4"

                placeholder="Enter meta description"><?php echo htmlspecialchars($settings['meta_description']); ?></textarea>

            <small class="text-muted">

                Recommended: 150–160 characters.

            </small>

        </div>

        <!-- Meta Keywords -->

        <div class="col-lg-12 mb-4">

            <label class="form-label">

                Meta Keywords

            </label>

            <textarea

                name="meta_keywords"

                class="form-control"

                rows="3"

                placeholder="beauty, skincare, cosmetics"><?php echo htmlspecialchars($settings['meta_keywords']); ?></textarea>

            <small class="text-muted">

                Separate keywords using commas.

            </small>

        </div>

        <!-- Google Maps -->

        <div class="col-lg-12 mb-4">

            <label class="form-label">

                Google Maps Embed Code / URL

            </label>

            <textarea

                name="google_maps"

                class="form-control"

                rows="5"

                placeholder="Paste Google Maps embed code or URL"><?php echo htmlspecialchars($settings['google_maps']); ?></textarea>

        </div>

        <!-- Footer -->

        <div class="col-lg-12">

            <label class="form-label">

                Footer Text

            </label>

            <textarea

                name="footer_text"

                class="form-control"

                rows="3"

                placeholder="© 2026 SCS Star Beauty. All Rights Reserved."><?php echo htmlspecialchars($settings['footer_text']); ?></textarea>

        </div>

    </div>

</div>

<!-- ==========================================
        SAVE BUTTON
========================================== -->

<div class="text-end">

   <button

type="submit"

id="saveSettingsBtn"

class="btn btn-primary save-btn">

        Save Settings

    </button>

</div>

        </form>

    </div>

</div>

<?php

include 'includes/footer.php';

include 'includes/scripts.php';

?>