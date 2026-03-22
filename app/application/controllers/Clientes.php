<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clientes extends MY_Controller
{

    protected $table_name_crud = 'clientes.clientes';
    protected $name_controller = __CLASS__;
    protected $campo1 = 'cliente';
    protected $campo2 = 'tipo_persona';
    protected $campo3 = 'codigo_cliente';
    protected $header1 = 'Cliente';
    protected $header2 = 'Residencia';
    protected $header3 = 'Trabajo';
    protected $jsfile = "clientes";
    protected $jsExtfile = "jquery.inputmask.min.js";

    protected $rules = array(
        array(
            'field' => 'dui',
            'label' => 'Dui',
            'rules' => 'trim|required|exact_length[10]'
        ), array(
            'field' => 'nombres',
            'label' => 'Nombres',
            'rules' => 'trim|required|max_length[150]|nombre'
        ), array(
            'field' => 'apellidos',
            'label' => 'Apellidos',
            'rules' => 'trim|required|max_length[150]|nombre'
        ), array(
            'field' => 'activo',
            'label' => 'Estado',
            'rules' => 'required'
        ), array(
            'field' => 'sexo',
            'label' => 'Sexo',
            'rules' => 'required'
        ), array(
            'field' => 'fecha_nacimiento',
            'label' => 'Nacimiento',
            'rules' => 'required'
        ), array(
            'field' => 'estado_civil',
            'label' => 'Estado Civil',
            'rules' => 'required'
        ), array(
            'field' => 'celular1',
            'label' => 'Celular',
            'rules' => 'exact_length[9]|telefono|required'
        ), array(
            'field' => 'celular2',
            'label' => 'Celular secundario',
            'rules' => 'exact_length[9]|telefono'
        ), array(
            'field' => 'telefono_fijo',
            'label' => 'Teléfono fijo',
            'rules' => 'max_length[20]|telefono'
        ), array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'valid_email|max_length[70]'
        ), array(
            'field' => 'departamento',
            'label' => 'Departamento',
            'rules' => 'trim'
        ), array(
            'field' => 'municipio',
            'label' => 'Municipio',
            'rules' => 'trim'
        ), array(
            'field' => 'direccion',
            'label' => 'Dirección',
            'rules' => 'max_length[200]'
        ), array(
            'field' => 'punto_referencia',
            'label' => 'Punto referencia',
            'rules' => 'max_length[200]'
        ), array(
            'field' => 'departamento_trabajo',
            'label' => 'Departamento laboral',
            'rules' => 'trim'
        ), array(
            'field' => 'municipio_trabajo',
            'label' => 'Municipio laboral',
            'rules' => 'trim'
        ), array(
            'field' => 'direccion_trabajo',
            'label' => 'Dirección laboral',
            'rules' => 'max_length[200]'
        ), array(
            'field' => 'punto_referencia_trabajo',
            'label' => 'Punto referencia trabajo',
            'rules' => 'max_length[200]'
        ), array(
            'field' => 'lugar_trabajo',
            'label' => 'Lugar trabajo',
            'rules' => 'max_length[150]'
        ), array(
            'field' => 'puesto_trabajo',
            'label' => 'Puesto',
            'rules' => 'max_length[100]|nombre'
        ), array(
            'field' => 'antiguedad_laboral',
            'label' => 'Antiguedad laboral',
            'rules' => 'max_length[20]|direccion|trim'
        ), array(
            'field' => 'telefono_trabajo',
            'label' => 'Teléfono trabajo',
            'rules' => 'max_length[20]|telefono'
        ), array(
            'field' => 'conyuge_nombre',
            'label' => 'Nombre conyuge',
            'rules' => 'max_length[300]|nombre'
        ), array(
            'field' => 'conyuge_telefono',
            'label' => 'Teléfono conyuge',
            'rules' => 'max_length[20]|telefono'
        ), array(
            'field' => 'conyuge_direccion_trabajo',
            'label' => 'Lugar trabajo conyuge',
            'rules' => 'max_length[300]'
        ), array(
            'field' => 'conyuge_telefono_dos',
            'label' => 'Teléfono conyuge dos',
            'rules' => 'max_length[30]|trim'
        ), array(
            'field' => 'profesion',
            'label' => 'Profesión',
            'rules' => 'trim|max_length[75]|nombre'
        ), array(
            'field' => 'ocupacion',
            'label' => 'Ocupación',
            'rules' => 'trim|max_length[75]|nombre'
        ), array(
            'field' => 'historial_telefonos',
            'label' => 'Historial telefonos',
            'rules' => 'trim|max_length[50]|alpha_numeric_spaces'
        ), array(
            'field' => 'departamento_expedicion',
            'label' => 'Departamento expedición',
            'rules' => 'trim'
        ), array(
            'field' => 'municipio_expedicion',
            'label' => 'Municipio expedición',
            'rules' => 'trim'
        ), array(
            'field' => 'ingresos',
            'label' => 'Ingresos',
            'rules' => 'required'
        ), array(
            'field' => 'tipo_vivienda',
            'label' => 'Tipo vivienda',
            'rules' => 'required'
        ), array(
            'field' => 'tiempo_vivienda',
            'label' => 'Tiempo residir',
            'rules' => 'trim'
        ), array(
            'field' => 'nit',
            'label' => 'NIT',
            'rules' => 'trim'
        ), array(
            'field' => 'nrc',
            'label' => 'NRC',
            'rules' => 'trim'
        ), array(
            'field' => 'giro',
            'label' => 'Giro',
            'rules' => 'trim'
        )
    );


}