<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><?= $titulo ?></h1>
            </div>
            <!-- <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Projects</li>
                    </ol>
                </div> -->
        </div>
    </div><!-- /.container-fluid -->
</section>


<section class="content">
<?php 
		// $hidden = array('controlador' => 'Creditos');
		echo form_open($action, array('id' => 'frm_rpts','target'=>'_blank' ));
    ?>
    <div class="card">
        <div class="card-body row">
            <div class="col-5 text-center align-items-center justify-content-center d-none d-xl-block">
                <img src="<?= load_img(FAVICON_HD) ?>" alt="SIG Logo" class="brand-image img-circle">
                <h2><strong><?= SISTEMA_PREFIJO ?></strong> <?= SISTEMA_SUFIJO ?></h2>
                <p class="lead mb-5"><?= SISTEMA_DESCRIPCION ?><br>
                    <i class="far fa-registered"></i>Todos los derechos reservados 2021
                </p>
            </div>