<div class="row">
	<div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
		<div class="card rounded-0">
		<div class="card-header">
                <h3 class="card-title text-muted">
                  <i class="fas fa-store-alt mr-1"></i>
                  Ventas
                </h3>
              </div>
			<div class="card-body py-1">
				<div class="d-flex justify-content-between align-items-center border-bottom mb-1">                 
                  <p class="d-flex flex-column text-right py-1 mb-1">                 
                    <a href="<?= go_to("ventas", "nuevo") ?>" class="text-muted">Nueva venta</a>
                  </p>
                </div>
				<div class="d-flex justify-content-between align-items-center border-bottom mb-1">
					<p class="d-flex flex-column text-right py-1 mb-1">
					<a href="<?= go_to("clientes", "nuevo") ?>" class="text-muted">Nuevo cliente</a>
					</p>
				</div>
				<div class="d-flex justify-content-between align-items-center border-bottom mb-1">				
					<p class="d-flex flex-column text-right py-1 mb-1">				
					<a href="<?= go_to("ventas", "tramites") ?>" class="text-muted">Trámites</a>
					</p>
				</div>
				<div class="d-flex justify-content-between align-items-center mb-0">
					<p class="d-flex flex-column text-right py-1 mb-1">
					<a href="<?= go_to("ventas", "listado") ?>" class="text-muted">Listado</a>
					</p>
				</div>
			</div>
		</div>
	</div>
</div>		