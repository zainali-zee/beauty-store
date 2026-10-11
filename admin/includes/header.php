<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>

<?php echo $pageTitle; ?>

</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
rel="stylesheet">

<link
href="assets/css/admin.css"
rel="stylesheet">

<?php

if(isset($pageCSS)){

?>

<link

href="assets/css/<?php echo $pageCSS; ?>"

rel="stylesheet">

<?php

}

?>

</head>

<body>

<div class="admin-wrapper">