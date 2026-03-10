<body class="hold-transition login-page">
	<div class="login-box">
		<!-- /.login-logo -->
		<div class="card card-outline card-primary">
			<div class="card-header text-center">
				<a href="../" class="h1"><b><?= SISTEMA_PREFIJO ?></b> <?= SISTEMA_SUFIJO ?></a>
			</div>
			<div class="card-body">
				<p class="login-box-msg">Inicio de sesión</p>

				<form action="../login/ingresar" method="post">
					<div class="input-group mb-3">
						<input type="username" name="usuario" class="form-control focused" placeholder="Usuario" value="<?= $this->session->flashdata('usuario') ?>" required>
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-user"></span>
							</div>
						</div>
					</div>
					<div class="input-group mb-3">
						<input type="password" name="clave" class="form-control" placeholder="Contraseña" required>
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-lock"></span>
							</div>
						</div>
					</div>
					<div class="row justify-content-end">
						<div class="col-4">
							<button type="submit" class="btn btn-outline-primary btn-block btn-flat">Iniciar</button>
						</div>
					</div>
				</form>

			</div>
			<!-- /.card-body -->
		</div>
		<!-- /.card -->
	</div>
	<!-- /.login-box -->


	<!-- jQuery -->
	<script src="<?= load_asset_ext("jquery.min.js") ?>"></script>
	<!-- Bootstrap 4 -->
	<script src="<?= load_asset_ext("bootstrap.bundle.min.js") ?>"></script>
	<script>
		$(".focused").focus();
		<?php
		$err_login = $this->session->flashdata('err_login');
		if (!empty($err_login)) {
			echo 'toastr["error"]("' . $err_login . '")';
		}
		unset($_SESSION['err_login']);
		?>
	</script>
</body>

</html>