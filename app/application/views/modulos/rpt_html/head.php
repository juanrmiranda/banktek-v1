<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= SISTEMA ?></title>
    <link rel="icon" href="<?= load_img("fav.jpg") ?>" type="image/jpg">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?= load_asset_ext("font-awesome-all.min.css") ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= load_asset_ext("adminlte.min.css") ?>">
    <!-- Sig CSS -->
    <link media="print" rel="stylesheet" href="<?= load_css("printing.css") ?>">
    <?php
    if (isset($horizontal)) {
        echo '<link media="print" rel="stylesheet" href="' . load_css("printing.h.css") . '">';
    }
    ?>
</head>

<body onafterprint="self.close()">
    <div class="wrapper">
        <section class="invoice">