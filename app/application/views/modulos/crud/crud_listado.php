    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?=  $titulo ?></h1>
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

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Listado <?=  $titulo ?></h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i> 
                    </button>                    
                    <?php include 'modalAddBookmark.php'; ?>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-striped text-nowarp projects">
                    <thead>
                        <tr>
                            <?=  $heading ?>
                        </tr>
                    </thead>
                    <tbody>
                            <?=  $filas ?>
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </section>