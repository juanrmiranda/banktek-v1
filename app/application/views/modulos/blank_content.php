<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-0">
			<div class="col-sm-6">
				<h1 style="color: #99a5b7;"><i class="fas fa-home"></i> Inicio</h1>
			</div>
		</div>
	</div>
</section>
<!-- Main content -->
<div class="content">
	<div class="container-fluid">
		<?php
		if ($this->session->userdata('reinicio_clave') == true) {
		?>
			<script>
				Swal.fire({
					title: 'Cambio de Contraseña',
					html: `	<input type="password" id="password" class="swal2-input" placeholder="Nueva Contraseña">
							<input type="password" id="password_confirm" class="swal2-input" placeholder="Confirme Contraseña">`,
					confirmButtonText: 'Cambiar contraseña',
					focusConfirm: false,
					allowOutsideClick: false,
					preConfirm: () => {
						const password_confirm = Swal.getPopup().querySelector('#password_confirm').value
						const password = Swal.getPopup().querySelector('#password').value
						if (!password) {
							Swal.showValidationMessage(`Debe ingresar una contraseña`)
						} else if (password_confirm !== password || (password).length < 6) {
							Swal.showValidationMessage(`Las contraseñas deben coincidir o mayor a 5 carácteres`)
						}
						return {
							password: password
						}
					}
				}).then((result) => {					
					CambiarPass(result.value.password);
				})
			</script>
		<?php
		}
		?>
		<!-- /.row -->
		<?php include 'bookmarks/bookmarks_list.php' ?>
	</div><!-- /.container-fluid -->
</div>
<!-- /.content -->