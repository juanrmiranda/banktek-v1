<!-- modalBookmark -->
<a href="#" data-url="<?= base_url(uri_string()) ?>" data-keyboard="true" data-toggle="modal" data-target="#modalAddBookmark"><i class="far fa-star text-warning"></i></a>
<div class="modal fade" id="modalAddBookmark" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalAddBookmarkLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="color: #6c757d ;">
        <div class="modal-content">
            <div class="modal-header py-1">
                <h5 class="modal-title" id="modalAddBookmarkLabel">Nuevo Bookmark</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body py-1">
                <div class="form-group">
                    <label for="bookmark-grupo" class="col-form-label">Defina el Grupo:</label>
                    <input type="text" class="form-control" id="bookmark-grupo" autocomplete=off>
                </div>
                <div class="form-group">
                    <label for="bookmark-nombre" class="col-form-label">Asigne un Nombre:</label>
                    <input type="text" class="form-control" id="bookmark-nombre">
                </div>
                <input type="hidden" class="form-control" id="bookmark-url" autocomplete=off>
            </div>
            <div class="modal-footer py-1">
            <button type="button" class="btn btn-outline-danger btn-flat" id="btnBookmarksDelete" data-dismiss="modal">Eliminar</button>
                <button type="button" class="btn btn-outline-secondary btn-flat" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-outline-success btn-flat" id="btnBookmarksCreate" data-dismiss="modal">Crear Bookmark</button>
            </div>
        </div>
    </div>
</div>
<!-- modalBookmark -->