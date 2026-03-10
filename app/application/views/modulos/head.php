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
    <?= load_css_ext("font-awesome-all") ?>
    <?= load_css_ext("font-awesome-animation") ?>
    <!-- ION icon  -->
    <?= load_css_ext("ionicons") ?>
    <!-- Theme style -->
    <?= load_css_ext("adminlte") ?>
    <!-- Sig CSS -->
    <link rel="stylesheet" href="<?= load_css("sig.css") ?>">
    <link media="print" rel="stylesheet" href="<?= load_css("printing.css") ?>">
    <!-- Sig Printing -->
    <?= load_js_local("printing") ?>
    <!-- JQuery -->
    <?= load_js_ext("jquery") ?>
    <!-- SweetAlert -->
    <?= load_css_ext("sweetalert2") ?>
    <?= load_css_ext("swal-dark") ?>
    <?= load_js_ext("sweetalert2") ?>
    <!-- MSGbox -->
    <?= load_css_ext("toastr") ?>
    <?= load_js_ext("toastr") ?>
    <!-- SweetAlert Function-->
    <script>
        // var SuccessAlert = Swal.mixin({toast: true,icon: 'success',position: 'top-end',showConfirmButton: false,timer: 4000});
        // var ErrorAlert = Swal.mixin({toast: true,icon: 'error',position: 'top-end',showConfirmButton: false,timer: 8000});
        // var WarningAlert = Swal.mixin({toast: true,icon: 'warning',position: 'top-end',showConfirmButton: false,timer: 4000});
        var baseurlAJAX = "<?= base_url(); ?>";
    </script>

    <!-- DataTable css-->
    <?php
    if ($load_datatable) {
        echo load_css_ext("datatables/dataTables.bootstrap4");
        echo load_css_ext("datatables/responsive.bootstrap4");
        echo load_css_ext("datatables/buttons.bootstrap4");
    }
    ?>

</head>