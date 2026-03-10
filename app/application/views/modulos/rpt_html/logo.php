<?php
if (isset($logo)) {
?>
    <tr>
        <th colspan="12" style="border: none;">
            <table class="table-head table table-borderless ">
                <?php if ($logo) { ?>
                    <tr>
                        <td rowspan="7"> <img class="" src="<?= load_img("logo.jpg") ?>" alt="Logo" <?=  isset($horizontal) ?  'height="95" width="95"' :  'height="128" width="128"'; ?> > </td>
                        <td colspan="2" class="w-100"></td>
                    </tr>
                <?php } ?>
                <tr>
                    <?php if ($titulo) { ?>
                        <td colspan="2" class="w-100 text-center">
                            <h4><?= strtoupper(EMPRESA) ?></h4>
                        </td>
                    <?php } else {
                        echo "<td>&nbsp;</td>";
                    } ?>
                </tr>
                <?php if ($sub_titulo) { ?>
                    <tr>
                        <td colspan="2" class="w-100 text-center">
                            <h5><?= $sub_titulo_msj ?></h5>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="w-100 text-center">
                            <h5><?= $sub_titulo2_msj ?></h5>
                        </td>
                    </tr>
                <?php } else {
                    echo "<tr><td>&nbsp;</td></tr>";
                } ?>
                <tr>
                    <?php if ($usuario) { ?>
                        <td class="idreporte text-right"><?= strtolower($usuario_msj) ?>
                        <?php } ?>
                </tr>
                <tr>
                    <?php if ($rpt) { ?>
                        <td class="idreporte text-right"><?= strtolower($rpt_msj) ?>
                        <?php } ?>
                </tr>
                <tr>
                    <?php if ($timestamp) { ?>
                        <td class="idreporte text-right"> <?= $timestamp_msj ?> </td>
                    <?php } ?>
                </tr>

            </table>
        </th>
    </tr>
<?php
}
?>