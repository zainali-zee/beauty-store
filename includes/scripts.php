<!-- ==========================================
        Bootstrap Bundle
========================================== -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>


<!-- ==========================================
        AOS Animation
========================================== -->

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>


<!-- ==========================================
        Swiper Slider
========================================== -->

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>


<!-- ==========================================
        Initialize AOS
========================================== -->

<script>

AOS.init({

    duration:1000,

    once:true,

    offset:80

});

</script>


<!-- ==========================================
        Global JavaScript
========================================== -->

<script src="assets/js/main.js"></script>


<!-- ==========================================
        Page JavaScript
========================================== -->

<?php

if(isset($pageJS) && !empty($pageJS)){

?>

<script src="assets/js/<?php echo htmlspecialchars($pageJS); ?>"></script>

<?php

}

?>


</body>

</html>