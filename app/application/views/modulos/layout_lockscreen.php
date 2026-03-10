<body class="hold-transition lockscreen">
<!-- Automatic element centering -->
<div class="lockscreen-wrapper">
  <div class="lockscreen-logo">
    <a href="../../index2.html"><b><?= SISTEMA_PREFIJO ?></b> <?= SISTEMA_SUFIJO ?></a>
  </div>
  <!-- User name -->
  <div class="lockscreen-name"><?= $nombre ?></div>

  <!-- START LOCK SCREEN ITEM -->
  <div class="lockscreen-item">
    <!-- lockscreen image -->
    <div class="lockscreen-image">
      <img src="<?= load_img("avatar5.png") ?>" alt="User Image">
    </div>
    <!-- /.lockscreen-image -->

    <!-- lockscreen credentials (contains the form) -->
    <form action="../login/ingresar" method="post" class="lockscreen-credentials">
      <div class="input-group">
        <input type="password" name="clave" class="form-control" placeholder="password" required>
        <input type="hidden" value="<?= $usuario ?>" name="usuario">
        <div class="input-group-append">
          <button type="submit" class="btn">
            <i class="fas fa-arrow-right text-muted"></i>
          </button>
        </div>
      </div>
    </form>
    <!-- /.lockscreen credentials -->

  </div>
  <!-- /.lockscreen-item -->
  <div class="help-block text-center">
    Ingresa tu clave para continuar con la sesión
  </div>
  <div class="text-center">
    <a href="<?= go_to("login") ?>">Cambiar de usuario</a>
  </div>
  <div class="lockscreen-footer text-center">
    Copyright &copy; 2014-2021 <b><a href="#" class="text-black"><?= SISTEMA ?></a></b><br>
    Todos los derechos reservados
  </div>
</div>
<!-- /.center -->

<!-- jQuery -->
<script src="<?= load_asset_ext("jquery.min.js") ?>"></script>
<!-- Bootstrap 4 -->
<script src="<?= load_asset_ext("bootstrap.bundle.min.js") ?>"></script>
</body>
</html>
