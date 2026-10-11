<?php

include 'includes/auth.php';

/* ==========================================
        LOGOUT
========================================== */

$_SESSION = [];

/* Delete Session Cookie */

if(

    ini_get("session.use_cookies")

){

    $params = session_get_cookie_params();

    setcookie(

        session_name(),

        "",

        time() - 42000,

        $params["path"],

        $params["domain"],

        $params["secure"],

        $params["httponly"]

    );

}

/* Destroy Session */

session_destroy();

/* Redirect */

header("Location: login.php");

exit;

?>