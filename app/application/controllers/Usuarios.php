<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends Crud_Controller
{
    protected $modelClass = 'Usuario_model';
    protected $baseRoute = 'usuarios';
    protected $entityName = 'Usuario';

    protected $indexView = 'usuarios/index';
    protected $formView = 'usuarios/form';
    protected $detailView = 'usuarios/detail';

    protected $validationRules = [
        [
            'field' => 'nombre',
            'label' => 'Nombre corto',
            'rules' => 'trim|required|max_length[40]|callback_unique_nombre'
        ],
        [
            'field' => 'nombres',
            'label' => 'Nombres',
            'rules' => 'trim|required|max_length[60]'
        ],
        [
            'field' => 'apellidos',
            'label' => 'Apellidos',
            'rules' => 'trim|required|max_length[60]'
        ],
        [
            'field' => 'agencia',
            'label' => 'Agencia',
            'rules' => 'trim|required|integer'
        ],
        [
            'field' => 'dui',
            'label' => 'DUI',
            'rules' => 'trim|required|max_length[10]|callback_unique_dui'
        ],
        [
            'field' => 'activo',
            'label' => 'Estado',
            'rules' => 'required|in_list[0,1]'
        ],
        [
            'field' => 'reinicio_clave',
            'label' => 'Reinicio de clave',
            'rules' => 'required|in_list[0,1]'
        ],
    ];

    protected $validationRulesUpdate = [
        [
            'field' => 'nombre',
            'label' => 'Nombre corto',
            'rules' => 'trim|required|max_length[40]|callback_unique_nombre'
        ],
        [
            'field' => 'nombres',
            'label' => 'Nombres',
            'rules' => 'trim|required|max_length[60]'
        ],
        [
            'field' => 'apellidos',
            'label' => 'Apellidos',
            'rules' => 'trim|required|max_length[60]'
        ],
        [
            'field' => 'agencia',
            'label' => 'Agencia',
            'rules' => 'trim|required|integer'
        ],
        [
            'field' => 'dui',
            'label' => 'DUI',
            'rules' => 'trim|required|max_length[10]|callback_unique_dui'
        ],
        [
            'field' => 'activo',
            'label' => 'Estado',
            'rules' => 'required|in_list[0,1]'
        ],
        [
            'field' => 'reinicio_clave',
            'label' => 'Reinicio de clave',
            'rules' => 'required|in_list[0,1]'
        ],
    ];

    public function __construct()
    {
        parent::__construct();
    }

    protected function mapPostToData(?object $currentRow = null): array
    {
        $isUpdate = $currentRow !== null;

        $data = [
            'nombre' => trim((string) $this->input->post('nombre', true)),
            'nombres' => trim((string) $this->input->post('nombres', true)),
            'apellidos' => trim((string) $this->input->post('apellidos', true)),
            'agencia' => (int) $this->input->post('agencia', true),
            'dui' => trim((string) $this->input->post('dui', true)),
            'activo' => $this->input->post('activo', true) == '1',
            'reinicio_clave' => $this->input->post('reinicio_clave', true) == '1',
        ];

        if ($isUpdate) {
            $data['actualizado_por'] = $this->user();
            $data['fecha_actualizado'] = date('Y-m-d H:i:s');
        } else {
            $data['creado_por'] = $this->user();
        }

        return $data;
    }

    public function unique_dui($value)
    {
        $uuid = $this->uri->segment(3);

        if ($this->usuario_model->existsDui($value, $uuid ?: null)) {
            $this->form_validation->set_message('unique_dui', 'Ya existe un usuario con este DUI');
            return false;
        }

        return true;
    }

    public function unique_nombre($value)
    {
        $uuid = $this->uri->segment(3);

        if ($this->usuario_model->existsNombre($value, $uuid ?: null)) {
            $this->form_validation->set_message('unique_nombre', 'Ya existe un usuario con este nombre corto');
            return false;
        }

        return true;
    }
}