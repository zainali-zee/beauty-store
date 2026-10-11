/* ==========================================================
   SCS STAR BEAUTY
   Global JavaScript
   Version: 1.0
========================================================== */

document.addEventListener("DOMContentLoaded", function () {

    /* ==========================================
       Sticky Navbar Shadow
    ========================================== */

    const navbar = document.querySelector(".custom-navbar");

    if (navbar) {

        window.addEventListener("scroll", function () {

            if (window.scrollY > 20) {

                navbar.classList.add("navbar-scrolled");

            } else {

                navbar.classList.remove("navbar-scrolled");

            }

        });

    }

    /* ==========================================
       Active Navigation Link
    ========================================== */

    const currentPage = window.location.pathname.split("/").pop();

    const navLinks = document.querySelectorAll(".navbar-nav .nav-link");

    navLinks.forEach(function (link) {

        const href = link.getAttribute("href");

        if (href === currentPage || (currentPage === "" && href === "index.php")) {

            link.classList.add("active");

        }

    });

    /* ==========================================
       Smooth Scroll
    ========================================== */

    document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {

        anchor.addEventListener("click", function (e) {

            const target = document.querySelector(this.getAttribute("href"));

            if (target) {

                e.preventDefault();

                target.scrollIntoView({

                    behavior: "smooth"

                });

            }

        });

    });

});