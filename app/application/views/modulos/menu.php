<div class="sidebar">
    <div class="user-panel mt-2 pb-2 mb-2 pl-2 d-flex">
        <div class="info">
            <a href="<?= base_url() ?>" class="d-block"> <i class="fas fa-home"></i> Inicio</a>
        </div>
    </div>
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu" data-accordion="false">
            <?= $this->session->userdata('reinicio_clave') == false ? $menu_dinamico : '' ?>
        </ul>
    </nav>
</div>
</aside>
<div class="content-wrapper">
