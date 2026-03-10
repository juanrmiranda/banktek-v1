</div>
<!-- /.card-body -->
<div class="card-footer">
  <div class="text-muted small">
    Complete los campos y presione el botón <strong>Agregar registro!</strong>
  </div>
</div>
</div>
<!-- /.card -->
<div class="row">
    <div class="col-12 mb-3">
        <!-- <input type="submit" value="Guardar cambios" class="btn btn-success float-right"> -->
        <button type="submit" class="btn btn-outline-success btn-flat float-right"><i class="fas fa-pencil-alt"></i> Agregar registro!</button>
    </div>
</div>
</form>
</section>
<!-- views from My_Controller -->
<?php
// los trozos de codigo que estaran fuera del form para agregar extras en Modales
// en la variable del My_Controller $load_view_footer
  if (! empty($load_view_footer)) {
    $this->load->view($load_view_footer);
  }
?>
<!-- views from My_Controller -->