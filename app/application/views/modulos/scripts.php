<!-- Bootstrap 4 -->
<?= load_js_ext("bootstrap.bundle") ?>
<!-- AdminLTE App -->
<?= load_js_ext("adminlte") ?>

<!-- dinamic JS from CTRLs var jsExtfile-->
<?php
if (!empty($jsExtfile)) {
    if (is_array($jsExtfile)) {
        foreach ($jsExtfile as $nameJs) {
            echo '<script src="' . load_asset_ext($nameJs) . '"></script>';
        }
    } else {
        echo '<script src="' . load_asset_ext($jsExtfile) . '"></script>';
    }
}
?>

<!-- DataTable js-->
<?php
if ($load_datatable) {
    echo load_js_ext('datatables/jquery.datatables');
    echo load_js_ext('datatables/dataTables.bootstrap4');
    echo load_js_ext('datatables/dataTables.responsive');
    echo load_js_ext('datatables/responsive.bootstrap4');
    echo load_js_ext('datatables/dataTables.buttons');
    echo load_js_ext('datatables/buttons.bootstrap4');
}
?>

<!-- SIG App -->
<?= load_js_local('sig') ?>


<!-- dinamic JS from CTRLs var jsfile-->
<?php
if (!empty($jsfile)) {
    if (is_array($jsfile)) {
        foreach ($jsfile as $nameJs) {
            echo load_js_local($nameJs);
        }
    } else {
        echo load_js_local($jsfile);
    }
}
?>

<?php
if (!empty($script_controller)) { ?>
    <!-- dinamic Function documentReady from LoadLayoutFooter -->
    <script>
        $(document).ready(function($) {
            toastr.options.timeOut = 6000;
            <?= $script_controller ?>
        });
    </script>
<?php } ?>

<?php
if (!empty($script_after_load)) { ?>
    <!-- dinamic Function documentReady from L6oadLayoutFooter -->
    <script>
        $(document).ready(function($) {
            <?= $script_after_load ?>
        });
    </script>
<?php } ?>

</body>

</html>