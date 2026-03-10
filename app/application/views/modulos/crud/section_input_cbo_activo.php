<?php
if ($crud == 'create') {
    echo form_hidden('activo',true);
} else {
?>
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-12">
            <?= frm_select('activo', 'Estado', unserialize(OPT_ACTIVO_INACTIVO), $row, $status) ?>
        </div>
    </div>
<?php
}
?>