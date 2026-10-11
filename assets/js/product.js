document.addEventListener("DOMContentLoaded", function(){

    console.log("Product Details Ready");

});

function changeProductImage(image){

    const main = document.getElementById("mainProductImage");

    main.src = image.src;

    document.querySelectorAll(".gallery-thumb").forEach(function(item){

        item.classList.remove("active-thumb");

    });

    image.classList.add("active-thumb");

}