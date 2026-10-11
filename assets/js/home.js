document.addEventListener("DOMContentLoaded",function(){

    /* ==========================================
       LOADER
    ========================================== */

    window.addEventListener("load",function(){

        const loader=document.getElementById("loader");

        if(loader){

            loader.style.opacity="0";

            setTimeout(function(){

                loader.style.display="none";

            },400);

        }

    });

    /* ==========================================
       SCROLL TO TOP
    ========================================== */

    const scrollBtn=document.getElementById("scrollTopBtn");

    window.addEventListener("scroll",function(){

        if(window.scrollY>300){

            scrollBtn.style.display="block";

        }

        else{

            scrollBtn.style.display="none";

        }

    });

    scrollBtn.addEventListener("click",function(){

        window.scrollTo({

            top:0,

            behavior:"smooth"

        });

    });

    /* ==========================================
       PROGRESS BAR
    ========================================== */

    window.addEventListener("scroll",function(){

        const winScroll=document.documentElement.scrollTop;

        const height=document.documentElement.scrollHeight-document.documentElement.clientHeight;

        const progress=(winScroll/height)*100;

        document.getElementById("scrollProgress").style.width=progress+"%";

    });

});