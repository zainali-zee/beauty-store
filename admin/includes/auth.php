<?php

if(session_status() === PHP_SESSION_NONE){

    session_start();

}

/* ==========================================
        CHECK LOGIN
========================================== */

function isAdminLoggedIn(){

    return isset($_SESSION['admin_id']);

}

/* ==========================================
        PROTECT PAGE
========================================== */

function requireAdminLogin(){

    if(!isAdminLoggedIn()){

        header("Location: login.php");

        exit;

    }

}

/* ==========================================
        REDIRECT IF ALREADY LOGGED IN
========================================== */

function redirectIfLoggedIn(){

    if(isAdminLoggedIn()){

        header("Location: dashboard.php");

        exit;

    }

}

?>