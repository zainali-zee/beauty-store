/* ==========================================
        PRODUCTS.JS
        SCS STAR BEAUTY
========================================== */

document.addEventListener("DOMContentLoaded", function () {

    /* ==========================================
            COMMON ELEMENTS
    ========================================== */

    const productImage =
        document.querySelector('input[name="product_image"]');

    const mainPreview =
        document.getElementById("mainPreview");

    const galleryInput =
        document.querySelector('input[name="gallery_images[]"]');

    const galleryPreview =
        document.getElementById("galleryPreview");

    const editImage =
        document.getElementById("edit_product_image");

    const editMainPreview =
        document.getElementById("edit_main_preview");

    const editGalleryPreview =
        document.getElementById("edit_gallery_preview");

    const updateForm =
        document.querySelector("#editProductModal form");



    /* ==========================================
            TOAST FUNCTION
    ========================================== */

    function showToast(message, type = "success") {

        const toastElement =
            document.getElementById("successToast");

        const toastMessage =
            document.getElementById("toastMessage");

        if (!toastElement || !toastMessage) {

            alert(message);

            return;

        }

        let icon = "fa-circle-check text-success";

        if (type === "error") {

            icon = "fa-circle-xmark text-danger";

        }

        if (type === "warning") {

            icon = "fa-circle-exclamation text-warning";

        }

        toastMessage.innerHTML =

            `<i class="fa-solid ${icon}"></i> ${message}`;

        const toast = new bootstrap.Toast(toastElement);

        toast.show();

    }



    /* ==========================================
            MAIN IMAGE PREVIEW
    ========================================== */

    if (productImage && mainPreview) {

        productImage.addEventListener("change", function () {

            const file = this.files[0];

            if (!file) {

                mainPreview.src = "";

                return;

            }

            mainPreview.src = URL.createObjectURL(file);

        });

    }



    /* ==========================================
            GALLERY PREVIEW
    ========================================== */

    if (galleryInput && galleryPreview) {

        galleryInput.addEventListener("change", function () {

            galleryPreview.innerHTML = "";

            if (this.files.length === 0) {

                galleryPreview.innerHTML =

                    "<span class='text-muted'>No images selected.</span>";

                return;

            }

            Array.from(this.files).forEach(function (file) {

                const img = document.createElement("img");

                img.src = URL.createObjectURL(file);

                img.className = "gallery-thumb";

                galleryPreview.appendChild(img);

            });

        });

    }



    /* ==========================================
            EDIT MAIN IMAGE PREVIEW
    ========================================== */

    if (editImage && editMainPreview) {

        editImage.addEventListener("change", function () {

            const file = this.files[0];

            if (!file) {

                return;

            }

            editMainPreview.src = URL.createObjectURL(file);

        });

    }



    /* ==========================================
            UPDATE BUTTON LOADING
    ========================================== */

    if (updateForm) {

        updateForm.addEventListener("submit", function () {

            const btn =

                document.getElementById(

                    "updateProductBtn"

                );

            if (!btn) {

                return;

            }

            btn.disabled = true;

            btn.innerHTML =

                '<i class="fa-solid fa-spinner fa-spin"></i> Updating...';

        });

    }



    /* ==========================================
            RENDER GALLERY
    ========================================== */

    function renderGallery(images) {

        if (!editGalleryPreview) {

            return;

        }

        editGalleryPreview.innerHTML = "";

        if (images.length === 0) {

            editGalleryPreview.innerHTML =

                `<div class="text-center w-100">

                    <i class="fa-regular fa-images"
                       style="font-size:45px;color:#bbb;">
                    </i>

                    <p class="text-muted mt-2">

                        No Gallery Images

                    </p>

                </div>`;

            return;

        }

        images.forEach(function (image) {

            editGalleryPreview.innerHTML +=

            `<div class="gallery-item">

                <img

                    src="uploads/gallery/${image.image}"

                    class="gallery-thumb">

                <button

                    type="button"

                    class="btn btn-danger btn-sm deleteGalleryImage"

                    data-id="${image.id}">

                    <i class="fa-solid fa-trash"></i>

                </button>

            </div>`;

        });

    }
        /* ==========================================
            LOAD PRODUCT (AJAX)
    ========================================== */

    const editButtons =
        document.querySelectorAll(".editProductBtn");

    editButtons.forEach(function (button) {

        button.addEventListener("click", function () {

            const productId = this.dataset.id;

            fetch(

                "get-product.php?id=" +

                encodeURIComponent(productId)

            )

            .then(response => response.json())

            .then(data => {

                if (!data.success) {

                    showToast(

                        data.message,

                        "error"

                    );

                    return;

                }

                const product = data.product;

                document.getElementById(

                    "edit_product_id"

                ).value = product.id;

                document.getElementById(

                    "edit_category"

                ).value = product.category_id;

                document.getElementById(

                    "edit_product_name"

                ).value = product.product_name;

                document.getElementById(

                    "edit_brand"

                ).value = product.brand;

                document.getElementById(

                    "edit_sku"

                ).value = product.sku;

                document.getElementById(

                    "edit_weight"

                ).value = product.weight;

                document.getElementById(

                    "edit_price"

                ).value = product.price;

                document.getElementById(

                    "edit_old_price"

                ).value = product.old_price;

                document.getElementById(

                    "edit_stock"

                ).value = product.stock;

                document.getElementById(

                    "edit_featured"

                ).value = product.featured;

                document.getElementById(

                    "edit_status"

                ).value = product.status;

                document.getElementById(

                    "edit_short_description"

                ).value = product.short_description;

                document.getElementById(

                    "edit_description"

                ).value = product.description;

                document.getElementById(

                    "edit_benefits"

                ).value = product.benefits;

                document.getElementById(

                    "edit_ingredients"

                ).value = product.ingredients;

                document.getElementById(

                    "edit_usage_method"

                ).value = product.usage_method;

                if (

                    editMainPreview

                ) {

                    if (

                        product.product_image

                    ) {

                        editMainPreview.src =

                            "uploads/products/" +

                            product.product_image;

                    }

                    else {

                        editMainPreview.src =

                            "../assets/images/no-image.png";

                    }

                }

                renderGallery(

                    data.gallery

                );

            })

            .catch(function () {

                showToast(

                    "Unable to load product.",

                    "error"

                );

            });

        });

    });



    /* ==========================================
            DELETE GALLERY IMAGE
    ========================================== */

    document.addEventListener(

        "click",

        function (event) {

            const btn =

                event.target.closest(

                    ".deleteGalleryImage"

                );

            if (!btn) {

                return;

            }

            if (

                !confirm(

                    "Delete this gallery image?"

                )

            ) {

                return;

            }

            fetch(

                "delete-gallery-image.php",

                {

                    method: "POST",

                    headers: {

                        "Content-Type":

                        "application/x-www-form-urlencoded"

                    },

                    body:

                    "gallery_id=" +

                    encodeURIComponent(

                        btn.dataset.id

                    )

                }

            )

            .then(response => response.json())

            .then(data => {

                if (

                    data.success

                ) {

                    const item =

                        btn.closest(

                            ".gallery-item"

                        );

                    if (

                        item

                    ) {

                        item.remove();

                    }

                    if (

                        editGalleryPreview

                    ) {

                        if (

                            editGalleryPreview.querySelectorAll(

                                ".gallery-item"

                            ).length === 0

                        ) {

                            renderGallery([]);

                        }

                    }

                    showToast(

                        "Gallery image deleted."

                    );

                }

                else {

                    showToast(

                        data.message,

                        "error"

                    );

                }

            })

            .catch(function(){

                showToast(

                    "Unable to delete gallery image.",

                    "error"

                );

            });

        }

    );
        /* ==========================================
            DELETE PRODUCT
    ========================================== */

    let selectedProductId = null;

    const deleteButtons =
        document.querySelectorAll(".deleteProductBtn");

    deleteButtons.forEach(function (button) {

        button.addEventListener("click", function () {

            selectedProductId = this.dataset.id;

            const hiddenId =

                document.getElementById(

                    "delete_product_id"

                );

            const productName =

                document.getElementById(

                    "delete_product_name"

                );

            if(hiddenId){

                hiddenId.value = selectedProductId;

            }

            if(productName){

                productName.textContent =

                    this.dataset.name;

            }

        });

    });



    /* ==========================================
            CONFIRM DELETE
    ========================================== */

    const confirmDelete =

        document.getElementById(

            "confirmDeleteProduct"

        );

    if(confirmDelete){

        confirmDelete.addEventListener(

            "click",

            function(){

                if(

                    selectedProductId===null

                ){

                    return;

                }

                confirmDelete.disabled = true;

                confirmDelete.innerHTML =

                    '<i class="fa-solid fa-spinner fa-spin"></i> Deleting...';

                fetch(

                    "delete-product.php",

                    {

                        method:"POST",

                        headers:{

                            "Content-Type":

                            "application/x-www-form-urlencoded"

                        },

                        body:

                        "product_id="+

                        encodeURIComponent(

                            selectedProductId

                        )

                    }

                )

                .then(response=>response.json())

                .then(data=>{

                    if(data.success){

                        const row =

                        document.getElementById(

                            "productRow"+

                            selectedProductId

                        );

                        const modal =

                        bootstrap.Modal.getInstance(

                            document.getElementById(

                                "deleteProductModal"

                            )

                        );

                        if(modal){

                            modal.hide();

                        }

                        if(row){

                            row.classList.add(

                                "product-removing"

                            );

                            setTimeout(function(){

                                row.remove();

                            },350);

                        }

                        showToast(

                            data.message,

                            "success"

                        );

                    }

                    else{

                        showToast(

                            data.message,

                            "error"

                        );

                    }

                })

                .catch(function(){

                    showToast(

                        "Unable to delete product.",

                        "error"

                    );

                })

                .finally(function(){

                    confirmDelete.disabled = false;

                    confirmDelete.innerHTML =

                    '<i class="fa-solid fa-trash"></i> Delete Product';

                });

            }

        );

    }



    /* ==========================================
            RESET EDIT MODAL
    ========================================== */

    const editModal =

        document.getElementById(

            "editProductModal"

        );

    if(editModal){

        editModal.addEventListener(

            "hidden.bs.modal",

            function(){

                if(updateForm){

                    updateForm.reset();

                }

                if(editGalleryPreview){

                    editGalleryPreview.innerHTML = "";

                }

                if(editMainPreview){

                    editMainPreview.src =

                    "../assets/images/no-image.png";

                }

            }

        );

    }



    /* ==========================================
            END
    ========================================== */

});