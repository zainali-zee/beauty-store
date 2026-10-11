/* ==========================================
        WEBSITE SETTINGS
========================================== */

document.addEventListener("DOMContentLoaded", function () {

    /* ==========================================
            IMAGE PREVIEW
    ========================================== */

    function previewImage(inputId, previewId) {

        const input = document.getElementById(inputId);

        const preview = document.getElementById(previewId);

        if (!input || !preview) {

            return;

        }

        input.addEventListener("change", function () {

            if (!this.files.length) {

                return;

            }

            const file = this.files[0];

            if (!file.type.startsWith("image/")) {

                alert("Please select a valid image.");

                this.value = "";

                return;

            }

            preview.src = URL.createObjectURL(file);

        });

    }

    previewImage(

        "logo",

        "logoPreview"

    );

    previewImage(

        "favicon",

        "faviconPreview"

    );



    /* ==========================================
            SAVE BUTTON LOADING
    ========================================== */

    const form =

        document.getElementById(

            "settingsForm"

        );

    if(form){

        form.addEventListener(

            "submit",

            function(){

                const btn =

                    form.querySelector(

                        'button[type="submit"]'

                    );

                if(!btn){

                    return;

                }

                btn.disabled = true;

                btn.innerHTML =

                '<i class="fa-solid fa-spinner fa-spin"></i> Saving Settings...';

            }

        );

    }



    /* ==========================================
            AUTO HIDE ALERTS
    ========================================== */

    const alerts =

        document.querySelectorAll(

            ".alert"

        );

    alerts.forEach(function(alert){

        setTimeout(function(){

            const bsAlert =

                bootstrap.Alert.getOrCreateInstance(

                    alert

                );

            bsAlert.close();

        },4000);

    });

});