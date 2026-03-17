<body class="hold-transition layout-fixed">
    <div class="wrapper" id="wrapper">

        <!-- Preloader -->
        <?php if (isset($preloader)) { echo $preloader; }   ?>
        <!-- Preloader -->

        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- LOCK SESION -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="<?= go_to("login", "lockscreen") ?>" class="nav-link"><?= $this->session->userdata('nombre') ?> </a>
                </li>
            </ul>
            <!-- FULL SCREEN -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <?= $timestamp  ?>
                    </a>
                </li>
            </ul>

        </nav>
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="<?= base_url("Bi") ?>" class="brand-link">
                <img src="<?= load_img(FAVICON) ?>" alt="SIG Logo" class="brand-image img-circle">
                <span class="brand-text font-weight-light"><?= SISTEMA ?></span>
            </a>