<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

	// VARIABLES CRUD
	public $headingList	= array();
	public $fieldList	= array();
	public $data = null;
	protected $table_name_crud = '';
	protected $table_name_view = '';
	protected $table_name_listado = '';
	protected $name_controller = '';
	protected $tabla_crud = null;
	protected $row_crud = null;
	protected $campo1 = null;
	protected $rules = null;
	protected $rules_edit = null;
	protected $rules_new = null;
	protected $campo2 = null;
	protected $campo3 = null;
	protected $header1 = null;
	protected $header2 = null;
	protected $header3 = null;
	protected $_field_data = array();
	protected $_param_rpt = array();

	// VARIABLES SweetAlert 
	protected $alerta = FALSE;
	protected $alerta_icono = 'info';
	protected $alerta_mensaje = '';

	// LOAD JS 
	protected $jsfile = null;
	protected $jsExtfile = null;

	// LOAD DATATABLES
	protected $load_datatable = false;

	// PARA PASAR VARIABLES DEL CONTROLLER HIJO AL PADRE EN LA FUNCION __LoadCrudUpdate
	protected $variables_hijo = null;
	protected $load_view_footer = null;

	// PROPIEDADES EXTRAS
	protected $vista_rpt = null;

	public function __construct()
	{
		parent::__construct();
		$this->Model('crud');
		$this->VerifyLogin();
		$this->load->helper('date');
		$datestring = '%d/%m/%Y - %H:%i:%s';
		$time = time();
		$timestamp = mdate($datestring, $time);
		$this->_param_rpt["logo"] = true;
		$this->_param_rpt["titulo"] = true;
		$this->_param_rpt["titulo_msj"] = EMPRESA;
		$this->_param_rpt["sub_titulo"] = true;
		$this->_param_rpt["sub_titulo_msj"] = SUB_TITULO_RPT;
		$this->_param_rpt["sub_titulo2_msj"] = SUB_TITULO2_RPT;
		$this->_param_rpt["rpt"] = true;
		$this->_param_rpt["rpt_msj"] = SISTEMA;
		$this->_param_rpt["usuario"] = true;
		$this->_param_rpt["usuario_msj"] = $this->usuario();
		$this->_param_rpt["timestamp"] = true;
		$this->_param_rpt["timestamp_msj"] = $timestamp;
	}

	// ----------------------------------------------------------------------------------
	// CREACIÓN DE MENU 
	// ----------------------------------------------------------------------------------
	protected  function create_menu($class)
	{
		// if ($this->usuario() == 'jramirezmi') {
			// $menu_sistema = $this->sql->read("generales.view_menu_sistema")->result();
		// } else {
			$menu_sistema = $this->sql->query("select * from generales.view_menu_sistema a
				where exists(select correlativo_opcion from generales.auth_usuarios_modulos_opciones
					where a.correlativo_opcion=correlativo_opcion
					and correlativo_usuario=" . $this->VariableSesion("correlativo") . ")
				order by 1,5")->result();
		// }
		$modulo = '';
		$controller = '';
		$function = '';
		$menu = '';
		foreach ($menu_sistema as $row) {
			$controller = $row->controller;
			$function = $row->method;

			if (empty($menu)) { // es el primer registro del listado
				$modulo = $row->descripcion_modulo;
				$menu = $this->create_modulo($class, $controller, $row->icono, $modulo);
				if (empty($function) === FALSE) {
					$menu .= $this->create_opcion($class, $function, $row->descripcion_opcion, $row->go_to);
				}
			} elseif ($modulo <> $row->descripcion_modulo) {
				$menu .= $this->generate_html_menu(6);
				$modulo = $row->descripcion_modulo;
				$menu .= $this->create_modulo($class, $controller, $row->icono, $modulo);
				if (empty($function) === FALSE) {
					$menu .= $this->create_opcion($class, $function, $row->descripcion_opcion, $row->go_to);
				}
			} else {
				if (empty($function) === FALSE) {
					$menu .= $this->create_opcion($class, $function, $row->descripcion_opcion, $row->go_to);
				}
			}
		}

		return $menu;
	}
	private function create_opcion($class, $function, $descripcion, $go_to)
	{
		$menu = '';
		if (strpos($class, $function) === FALSE) {
			$menu = $this->generate_html_menu(5, $descripcion, $go_to);
		} else {
			$menu = $this->generate_html_menu(4, $descripcion, $go_to);
		}
		return $menu;
	}
	private function create_modulo($class, $controller, $icono, $modulo)
	{
		$menu = '';
		if (stripos($class, $controller) === FALSE) {
			$menu = $this->generate_html_menu(1);
		} else {
			$menu = $this->generate_html_menu(0);
		}
		$menu .= $this->generate_html_menu(2, $icono);
		$menu .= $this->generate_html_menu(3, $modulo);
		return $menu;
	}
	private function generate_html_menu($opcion, $dato = null, $dato2 = null)
	{
		$txt = '';
		switch ($opcion) {
			case '0':
				# inicia un módulo seleccionado
				$txt = '<li class="nav-item menu-open">';
				$txt .= '<a href="#" class="nav-link active">';
				break;
			case '1':
				# inicia un módulo
				$txt = '<li class="nav-item">';
				$txt .= '<a href="#" class="nav-link ">';
				break;
			case '2':
				# icono del modulo
				$txt = '<i class="nav-icon ' . $dato . '"></i> ';
				break;
			case '3':
				# Nombre del modulo
				$txt = '<p>' . $dato . '<i class="right fas fa-angle-left"></i></p>';
				$txt .= '</a><ul class="nav nav-treeview">';
				break;
			case '4':
				# crea una opción Seleccionada
				$txt = '<li class="nav-item"><a href="' . go_to($dato2) . '" class="nav-link active"><i class="far fa-circle nav-icon"></i>';
				$txt .= '<p>' . $dato . '</p></a></li>';
				break;
			case '5':
				# crea una opción normal
				$txt = '<li class="nav-item"><a href="' . go_to($dato2) . '" class="nav-link"><i class="far fa-circle nav-icon"></i>';
				$txt .= '<p>' . $dato . '</p></a></li>';
				break;
			case '6':
				# cierra el modulo
				$txt = '</ul></li>';
				break;
			default:
				# code...
				break;
		}
		return $txt;
	}


	// ----------------------------------------------------------------------------------
	// FUNCIONES PUBLICAS
	// ----------------------------------------------------------------------------------

	public function rpt()
	{
		if (empty($this->vista_rpt)) {
			throw new Exception("No hay vista de Reportes para este Módulo (this->vista_rpt)", 1);
		}
		$this->LoadLayoutHeaderRpt($this->name_controller . "::rpt");
		$this->load->view(strtolower($this->name_controller) . "/" . $this->vista_rpt);
		$this->LoadLayoutFooterRpt();
	}
	protected function __RptSinInformacion()
	{
		$this->load->view('errors/html/rpt_sin_informacion');
	}
	protected function __RptSinAcceso($reporte = null)
	{
		$this->Log("Acceso restringido a reporte $reporte");
		$this->load->view('errors/html/rpt_sin_permiso');
	}
	protected function __SinAcceso($opcion = null)
	{
		$this->Log("Acceso restringido a esta opción $opcion");
		$this->load->view('errors/html/acceso_restringido');
	}

	public function LoadLayoutHeaderRpt($class = '', $titulo_ = null, $action_ = null)
	{
		// crea fecha del sistema
		$datestring = '%d/%m/%Y - %H:%i:%s';
		$time = time();
		$timestamp = mdate($datestring, $time);
		$var["timestamp"] = $timestamp;

		// crea menu del sistema
		$menu = "";
		if ($this->isLogin()) {
			$menu = $this->create_menu($class);
		}
		$var2["menu_dinamico"] = $menu;
		$var2["titulo"] = empty($titulo_) ? "Reportes" : $titulo_;
		$var2["action"] = empty($action_) ? $this->name_controller . "/rpt_do" : $action_;

		$var3["load_datatable"] = $this->load_datatable;

		$this->load->view('modulos\head', $var3);
		$this->load->view('modulos\layout_app', $var);
		$this->load->view('modulos\menu', $var2);
		$this->load->view('modulos\layout_site_rpt_header', $var2);
	}
	public function LoadLayoutFooterRpt($script_after_load = null)
	{
		$this->load->view('modulos/layout_site_rpt_footer');
		$this->load->view('modulos/footer');
		$script_controller = "";

		$alerta = (bool) $this->getFlashData('show_alert');
		if ($alerta) {
			$script_controller = 'toastr["' . $this->getFlashData('icono') . '"](' . "'";
			$msj =  $this->__optimizar_msj_error_PG($this->getFlashData('mensaje'));
			$script_controller .= $msj . "');";
		}

		if (!empty($script_after_load)) $var["script_after_load"] = $script_after_load;
		$var["script_controller"] = $script_controller;
		$var["jsfile"] = $this->jsfile;
		$var["jsExtfile"] = $this->jsExtfile;
		$var["load_datatable"] = $this->load_datatable;
		$this->jsfile = null;
		$this->jsExtfile = null;
		$this->load_datatable = false;

		$this->load->view('modulos/scripts', $var);
	}

	public function LoadLayoutHeader($class = '')
	{
		// crea fecha del sistema
		$datestring = '%d/%m/%Y - %H:%i:%s';
		$time = time();
		$timestamp = mdate($datestring, $time);
		$var["timestamp"] = $timestamp;
		
		// crea menu del sistema
		$menu = "";
		if ($this->isLogin()) {
			$menu = $this->create_menu($class);
		}
		$var2["menu_dinamico"] = $menu;
		
		$var3["load_datatable"] = $this->load_datatable;
		
		$this->load->view('modulos/head', $var3);
		$this->load->view('modulos/layout_app', $var);
		$this->load->view('modulos/menu', $var2);
	}
	public function LoadLayoutFooter($script_after_load = null)
	{
		$this->load->view('modulos/footer');
		$script_controller = "";

		$alerta = (bool) $this->getFlashData('show_alert');
		if ($alerta) {
			$script_controller = 'toastr["' . $this->getFlashData('icono') . '"](' . "'";
			$msj =  $this->__optimizar_msj_error_PG($this->getFlashData('mensaje'));
			$script_controller .= $msj . "');";
		}

		if (!empty($script_after_load)) $var["script_after_load"] = $script_after_load;
		$var["script_controller"] = $script_controller;
		$var["jsfile"] = $this->jsfile;
		$var["jsExtfile"] = $this->jsExtfile;
		$var["load_datatable"] = $this->load_datatable;
		$this->jsfile = null;
		$this->jsExtfile = null;
		$this->load_datatable = false;

		$this->load->view('modulos/scripts', $var);
	}
	public function VariableSesion($variable)
	{
		return $this->session->userdata($variable);
	}
	public function usuario()
	{
		return $this->session->userdata('usuario');
	}
	public function Model($nombre)
	{
		$this->load->model($nombre);
	}
	public function View($nombre, $data = null)
	{
		$this->load->view($nombre, $data);
	}
	public function Post($variable)
	{
		return $this->input->post($variable, TRUE);
	}
	public function isLogin()
	{
		if ($this->session->userdata('login')) {
			return true;
		}
		return false;
	}
	public function VerifyLogin()
	{
		if (!$this->session->userdata('login')) {
			header("Location:" . go_to('login'));
			return;
		}
		$appname = 'sig-pyme';
		if (!empty($this->usuario())) {
			$appname = $this->usuario();
		}
		// $this->crud->simple_query("SET ROLE TO '" . $appname . "'");
		$this->crud->simple_query("SET application_name TO '" . SISTEMA . "'");

		return true;
	}
	public function Read($tabla, $uuid = NULL, $order = TRUE)
	{
		$this->data = $this->crud->crud_read($tabla, $uuid, $order);
		return $this->data;
	}
	public function setMessage($mensaje, $icono = 'info')
	{
		$this->session->set_flashdata('show_alert', 'true');
		$this->session->set_flashdata('icono', $icono);
		$mensaje = strtok($mensaje, "\n");
		$this->session->set_flashdata('mensaje', $mensaje);
	}
	public function getFlashData($name)
	{
		$obj = $this->session->flashdata($name);
		unset($_SESSION[$name]);
		return $obj;
	}
	public function Log($mensaje)
	{
		log_message('error', 'Usuario: {' . $this->usuario() . '} CONTROLLER{' . $this->name_controller . '} ' . $mensaje);
	}

	// ---------------------------------------------------------------------------------------
	// MANEJO DEL CRUD 
	// ---------------------------------------------------------------------------------------
	protected function LoadLayoutFooterCrudErrores($var)
	{
		$this->load->view('modulos/crud/crud_ver_errores', $var);
		$this->load->view('modulos/footer');
		$this->load->view('modulos/scripts');
	}
	protected function LoadLayoutFooterCrudEmpty()
	{
		$this->load->view('modulos/crud/crud_ver_sinregistros');
		$this->load->view('modulos/footer');
		$this->load->view('modulos/scripts');
	}
	protected function __CrudRead($tabla, $uuid = NULL, $order = TRUE)
	{
		$this->data = $this->crud->crud_read($tabla, $uuid, $order);
		return $this->data;
	}
	private function __LoadCrudListado($tabla = null, $titulo, $controller, $class)
	{
		$var["heading"] = $this->__TablaCrud_CrearHeader();
		if (empty($var["heading"])) {
			throw new Exception('No se han seteado el Heading');
		}
		if (empty($this->campo1)) {
			throw new Exception('No se han seteado el Campo1');
		}
		if ($tabla === null) {
			throw new Exception('No se han seteado el nombre de la tabla');
		}

		if (empty($this->table_name_listado)) {
			$this->table_name_listado = $tabla . '_listado';
		}

		$this->tabla_crud = $this->__CrudRead($this->table_name_listado);
		$var["filas"] = $this->__TablaCrud_CrearRows($controller);
		$var["titulo"] = $titulo;
		$this->LoadLayoutHeader($class);
		$this->load->view('modulos/crud/crud_listado', $var);
		$this->LoadLayoutFooter();
	}
	protected function __CreateFooterCrud()
	{
		$msg="";
		if (!empty($this->row_crud["rows"]->creado_por)) {
			$msg = '<strong>Creado: </strong>' . $this->row_crud["rows"]->creado_por . ' ' . fecha_larga($this->row_crud["rows"]->fecha_creacion) . ' <strong> Estado: </strong>';
		}
		if (!empty($this->row_crud["rows"]->activo)) {
			$msg .= $this->row_crud["rows"]->activo ? ' ACTIVO' : ' INACTIVO';
		}
		if (!empty($this->row_crud["rows"]->actualizado_por)) {
			$msg .= ' <strong>Modificado: </strong>' . $this->row_crud["rows"]->actualizado_por . ' ' . fecha_larga($this->row_crud["rows"]->fecha_actualizado);
		}
		return $msg;
	}
	private function __LoadVisualizar($tabla = null, $uuid, $titulo, $controller, $vista)
	{
		$var["titulo"] = $titulo;
		$this->__LoadLayoutVisualizar();
		$this->load->view('modulos/crud/visualizar', $var);
		$this->row_crud = $this->__CrudRead($tabla, $uuid);

		if ($this->row_crud["error"] === true) {
			$var["error"] = $this->row_crud["mensaje"];
			$this->LoadLayoutFooterCrudErrores($var);
			return;
		}
		if ($this->row_crud["rows"] === NULL) {
			$this->LoadLayoutFooterCrudEmpty();
			return;
		}
		$var["row"] = $this->row_crud["rows"];
		$var["crud"] = 'visualizar';
		$var["footer"] = $this->__CreateFooterCrud();

		if ($this->variables_hijo) $var += $this->variables_hijo;
		
		$this->load->view($vista, $var);

		$this->load->view('modulos/crud/crud_ver_footer', $var);
		$this->__LoadFooterVisualizar();
	}
	private function __LoadLayoutVisualizar()
	{
		$var3["load_datatable"] = $this->load_datatable;
		$this->load->view('modulos\head', $var3);
		$this->load->view('modulos\layout_app_visualizar');
	}
	private function __LoadFooterVisualizar()
	{
		$script_controller = "";

		$alerta = (bool) $this->getFlashData('show_alert');
		if ($alerta) {
			$icon = $this->getFlashData('icono');
			if ($icon == 'success') {
				$script_controller = 'toastr["success"](' . "'";
			} elseif ($icon == 'error') {
				$script_controller = 'toastr["error"](' . "'";
			} elseif ($icon = 'warning') {
				$script_controller = 'toastr["warning"](' . "'";
			}
			$msj =  $this->__optimizar_msj_error_PG($this->getFlashData('mensaje'));
			$script_controller .= $msj . "');";
		}

		$var["script_controller"] = $script_controller;
		$var["jsfile"] = $this->jsfile;
		$var["jsExtfile"] = $this->jsExtfile;
		$var["load_datatable"] = $this->load_datatable;
		$this->jsfile = null;
		$this->jsExtfile = null;
		$this->load_datatable = false;

		$this->load->view('modulos/scripts', $var);
	}
	private function __LoadCrudView($tabla = null, $uuid, $titulo, $controller, $vista)
	{
		$var["titulo"] = $titulo;
		$this->LoadLayoutHeader($controller);
		$this->load->view('modulos/crud/crud_ver_header', $var);
		$this->row_crud = $this->__CrudRead($tabla, $uuid);

		if ($this->row_crud["error"] === true) {
			$var["error"] = $this->row_crud["mensaje"];
			$this->LoadLayoutFooterCrudErrores($var);
			return;
		}
		if ($this->row_crud["rows"] === NULL) {
			$this->LoadLayoutFooterCrudEmpty();
			return;
		}
		$var["row"] = $this->row_crud["rows"];

		$var["footer"] = $this->__CreateFooterCrud();


		$this->load->view($vista, $var);

		$this->load->view('modulos/crud/crud_ver_footer', $var);
		$this->LoadLayoutFooter();
	}
	private function __LoadCrudDelete($tabla = null, $uuid, $titulo, $controller, $vista, $action_ = 'delete')
	{
		$var["titulo"] = $titulo;
		$this->LoadLayoutHeader($controller);
		$var["uuid"] = $uuid;

		$hidden = array('uuid' => $uuid, 'action' => $action_);
		$frm_open = form_open($controller . '/' . $action_,  array('class' => "sigCrudForm"), $hidden);
		$var["frm_open"] = $frm_open;

		$this->load->view('modulos/crud/crud_delete_header', $var);
		$this->row_crud = $this->__CrudRead($tabla, $uuid);

		if ($this->row_crud["error"] === true) {
			$var["error"] = $this->row_crud["mensaje"];
			$this->LoadLayoutFooterCrudErrores($var);
			return;
		}
		if ($this->row_crud["rows"] === NULL) {
			$this->LoadLayoutFooterCrudEmpty();
			return;
		}
		$var["row"] = $this->row_crud["rows"];
		$var["crud"] = 'delete';

		$var["footer"] = $this->__CreateFooterCrud();

		$this->load->view($vista, $var);

		$this->load->view('modulos/crud/crud_delete_footer', $var);
		$this->LoadLayoutFooter();
	}
	private function __LoadCrudUpdate($tabla = null, $uuid, $titulo, $controller, $vista, $validando = FALSE, $action_ = 'update')
	{
		$var["titulo"] = $titulo;
		$this->LoadLayoutHeader($controller);
		$var["uuid"] = $uuid;
		$var["status"] = $validando;

		$hidden = array('uuid' => $uuid, 'action' => $action_);
		$frm_open = form_open($controller . '/' . $action_,  array('class' => "sigCrudForm"), $hidden);
		$var["frm_open"] = $frm_open;
		$var["card_type"] = 'info';
		$var["card_title"] = 'Actualizar información';

		$this->load->view('modulos/crud/crud_openform_header', $var);
		$this->row_crud = $this->__CrudRead($tabla, $uuid);

		if ($this->row_crud["error"] === true) {
			$var["error"] = $this->row_crud["mensaje"];
			$this->LoadLayoutFooterCrudErrores($var);
			return;
		}
		if ($this->row_crud["rows"] === NULL) {
			$this->LoadLayoutFooterCrudEmpty();
			return;
		}
		$var["row"] = $this->row_crud["rows"];
		$var["crud"] = $action_;
		$var["load_view_footer"] = $this->load_view_footer;
		$var["footer"] = $this->__CreateFooterCrud();
		$this->load_view_footer = null;

		if ($this->variables_hijo) $var += $this->variables_hijo;

		$this->load->view($vista, $var);

		$this->load->view('modulos/crud/crud_edit_footer', $var);
		$this->LoadLayoutFooter();
	}
	private function __LoadCrudNuevo($controller, $vista, $validando_ = FALSE)
	{
		$var["titulo"] = $controller;
		$this->LoadLayoutHeader($controller . '::nuevo');
		$var["status"] = $validando_;

		$hidden = array('action' => 'new');
		$frm_open = form_open($controller . '/create', array('class' => "sigCrudForm"), $hidden);
		$var["frm_open"] = $frm_open;
		$var["card_type"] = 'success';
		$var["row"] = null;
		$var["card_title"] = 'Agregar un nuevo registro';

		$this->load->view('modulos/crud/crud_openform_header', $var);
		$var["crud"] = 'create';
		$var["load_view_footer"] = $this->load_view_footer;
		$this->load_view_footer = null;

		$this->load->view($vista, $var);

		$this->load->view('modulos/crud/crud_new_footer');
		$this->LoadLayoutFooter();
	}
	public function __TablaCrud_CrearHeader()
	{
		$out = '<th class="fit"> # </th>';
		$out .= '<th>' . $this->header1 . '</th>';
		$out .= '<th>' . $this->header2 . '</th>';
		$out .= '<th>' . $this->header3 . '</th>';
		$out .= '<th class="fit">Estado</th>';
		$out .= '<th class="fit"></th>';

		return $out;
	}
	public function __TablaCrud_CrearRows($controller, $ver = 'ver', $editar = 'editar', $eliminar = 'eliminar')
	{
		if ($this->tabla_crud["error"] === TRUE) {
			// clean message
			// $str = strtok($this->tabla_crud["mensaje"], "\n");
			$this->setMessage($this->tabla_crud["mensaje"], 'warning');
			return '<tr><td colspan=6 class="text-center text-danger"><i class="fas fa-exclamation-triangle text-danger"></i> ' . MSJ_CRUD_VIEW_LISTADO_NOT_EXISTS . ' </td></tr>';
		}
		if ($this->tabla_crud["rows"] === NULL) {
			return '<tr><td colspan=6 class="text-center text-warning"><i class="fas fa-exclamation-triangle text-warning"></i> Sin registros </td></tr>';
		}
		$in  = '<td>';
		$out = '</td>';
		$filas = '';
		// $btnVer = '';
		$btnEditar = '';
		$btnEliminar = '';
		$campoA = $this->campo1;
		$campoB = $this->campo2;
		$campoC = $this->campo3;
		foreach ($this->tabla_crud["rows"] as $row) {
			// $filas .= '<tr><td>' . $row->correlativo . '</td>';
			$filas .= '<tr><td><a href="' . go_to($controller . '/' . $ver . '/' . $row->uuid) . '">' . $row->correlativo . '</a></td>';
			$filas .= $in . '<a>' . $row->$campoA . '</a>';
			$filas .= '<br><small>' . $row->creado_por . ' : ' . $row->fecha_creacion_descripcion . '</small>' . $out;
			$filas .= $in . $row->$campoB . $out;
			// por si el último campo no lo ocupo
			if (empty($campoC)) {
				$filas .= $in . '' . $out;
			} else {
				$filas .= $in . $row->$campoC . $out;
			}
			$filas .= $in;
			if ($row->activo === TRUE) {
				$filas .= '<span class="badge badge-success">';
			} elseif ($row->activo === NULL) {
				$filas .= '<span class="badge badge-warning">';
			} else {
				$filas .= '<span class="badge badge-danger">';
			}
			$filas .=  $row->estado_descripcion . '</span>' . $out;

			if (!empty($editar)) $btnEditar = '<a class="btn btn-outline-info btn-flat btn-sm" href="' . go_to($controller . '/' . $editar . '/' . $row->uuid) . '"><i class="fas fa-pencil-alt"></i> Editar</a> ';
			if (!empty($eliminar)) $btnEliminar = '<a class="btn btn-outline-danger btn-flat btn-sm" href="' . go_to($controller . '/' . $eliminar . '/' . $row->uuid) . '"><i class="fas fa-trash"></i> Eliminar</a> ';

			$filas .= '<td class="project-actions text-right fit">' . $btnEditar . $btnEliminar . '</td>';
			$filas .= '</tr>';
		}
		return $filas;
	}

	private function __setValuesCrud($tipo_)
	{
		$tmp_rules = $this->__loadRules($tipo_);
		foreach ($tmp_rules as $row) {
			// Houston, we have a problem...
			if (!isset($row['field'])) {
				continue;
			}
			// magic
			$this->_field_data[$row['field']] = $this->Post($row['field']);
		}
	}
	private function __cleanValuesCrud()
	{
		$this->_field_data = array();
	}

	// ----------------------------------------------------------------------------------
	// FUNCIONES PUBLICAS DEL CRUD
	// ----------------------------------------------------------------------------------
	public function visualizar($uuid, $vista_name_ = null, $titulo_ = null)
	{
		$vista_name = (empty($vista_name_)) ? strtolower($this->name_controller) . '/v_' . strtolower($this->name_controller) . '_view' : $vista_name_;
		$tabla = (empty($this->table_name_view) ? $this->table_name_crud : $this->table_name_view);
		$titulo = (empty($titulo_)) ? $this->name_controller : $titulo_;
		$this->__LoadVisualizar($tabla, $uuid, $titulo, $this->name_controller, $vista_name);
	}
	public function ver($uuid, $vista_name_ = null, $tabla_ = null, $titulo_ = null)
	{
		$vista_name = (empty($vista_name_)) ? strtolower($this->name_controller) . '/v_' . strtolower($this->name_controller) . '_view' : $vista_name_;
		$tabla = (empty($tabla_)) ? (empty($this->table_name_view) ? $this->table_name_crud : $this->table_name_view) : $tabla_;
		$titulo = (empty($titulo_)) ? $this->name_controller : $titulo_;
		$this->__LoadCrudView($tabla, $uuid, $titulo, $this->name_controller, $vista_name);
	}
	public function editar($uuid, $vista_name_ = null, $tabla_ = null, $titulo_ = null, $action_ = 'update')
	{
		if (empty($this->rules) && empty($this->rules_edit)) {
			throw new Exception('Las polítias del CRUD no están establecidas empty($form_validation->rules) or empty($form_validation->rules_edit)');
		}
		$vista_name = (empty($vista_name_)) ? strtolower($this->name_controller) . '/v_' . strtolower($this->name_controller) . '_edit' : $vista_name_;
		$tabla = (empty($tabla_)) ? $this->table_name_crud : $tabla_;
		$titulo = (empty($titulo_)) ? $this->name_controller : $titulo_;
		$this->load->helper('form');
		$this->__LoadCrudUpdate($tabla, $uuid, $titulo, $this->name_controller, $vista_name, FALSE, $action_);
	}
	public function eliminar($uuid, $vista_name_ = null, $tabla_ = null, $titulo_ = null, $action_ = 'delete')
	{
		// $vista_name = strtolower($this->name_controller) . '/v_' . strtolower($this->name_controller) . '_view';
		$this->load->helper('form');
		// $tabla = (empty($this->table_name_view) ? $this->table_name_crud : $this->table_name_view);
		$vista_name = (empty($vista_name_)) ? strtolower($this->name_controller) . '/v_' . strtolower($this->name_controller) . '_view' : $vista_name_;
		$tabla = (empty($tabla_)) ? (empty($this->table_name_view) ? $this->table_name_crud : $this->table_name_view) : $tabla_;
		$titulo = (empty($titulo_)) ? $this->name_controller : $titulo_;
		$this->__LoadCrudDelete($tabla, $uuid, $titulo, $this->name_controller, $vista_name, $action_);
	}
	public function nuevo()
	{
		if (empty($this->rules) && empty($this->rules_new)) {
			throw new Exception('Las polítias del CRUD no están establecidas empty($form_validation->rules) or empty($form_validation->rules_new)');
		}
		$vista_name = strtolower($this->name_controller) . '/v_' . strtolower($this->name_controller) . '_edit';
		$this->load->helper('form');
		$this->__LoadCrudNuevo($this->name_controller, $vista_name);
	}
	public function listado()
	{
		// $rr= $this->get_parent_controller($this, __CLASS__);
		$this->__LoadCrudListado($this->table_name_crud, $this->name_controller, $this->name_controller, $this->name_controller . '::listado');
	}
	// ----------------------------------------------------------------------------------
	// FUNCIONES PUBLICAS DEL CRUD - METODOS POST
	// ----------------------------------------------------------------------------------	
	public function create()
	{
		if ($this->input->post()) {
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('', '');
			$this->form_validation->set_rules($this->__loadRules('new'));
			if ($this->form_validation->run() == FALSE) {
				// TODO	ADD CONSTANT FOR LOGGIN OR NOT LOGGIN THIS FEATURE
				// HAY QUE HACER UNA FUNCIÓN PARA QUE TOME ESTE Y EL DE UPDATE TB
				$buffer = validation_errors();
				$buffer = str_replace(array("\r", "\n"), '|', $buffer);
				$this->Log($buffer);
				// ---------------------------------------------------
				$this->__cleanValuesCrud();
				$this->setMessage(MSJ_CRUD_FORM_VALIDATION_ERR, 'warning');
				$vista_name = strtolower($this->name_controller) . '/v_' . strtolower($this->name_controller) . '_edit';
				$this->__LoadCrudNuevo($this->name_controller, $vista_name, TRUE);
			} else {

				$this->__setValuesCrud("new");
				$this->data = $this->crud->crud_create($this->table_name_crud, $this->_field_data);
				if ($this->data["error"] === true) {
					$this->setMessage($this->data["mensaje"], 'error');
					$this->__cleanValuesCrud();
					$vista_name = strtolower($this->name_controller) . '/v_' . strtolower($this->name_controller) . '_edit';
					$this->__LoadCrudNuevo($this->name_controller, $vista_name, TRUE);
				} else {
					$this->setMessage(MSJ_CRUD_INSERT, 'success');
					header("Location:listado");
					return;
				}
			}
		} else {
			throw new Exception('{' . __METHOD__ . '} Debe llegar hasta aquí con un POST METHOD');
		}
	}
	public function update($vista_name_ = null, $tabla_ = null, $titulo_ = null, $action_ = 'update', $redirect_ = null, $tabla_error_ = null)
	{
		if ($this->input->post()) {
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('', '');
			$uuid = $this->Post('uuid');
			if (empty($uuid)) {
				throw new Exception('Debe especificar el UUID');
			}

			$vista_name = (empty($vista_name_)) ? strtolower($this->name_controller) . '/v_' . strtolower($this->name_controller) . '_edit' : $vista_name_;
			$tabla = (empty($tabla_)) ? $this->table_name_crud : $tabla_;
			$titulo = (empty($titulo_)) ? $this->name_controller : $titulo_;
			$redirect = (empty($redirect_)) ? 'listado' : $redirect_;

			// cuando es una vista especial la que consulto cuando da error no consulta la tabla base sino una vista especial
			$tabla_error = (empty($tabla_error_)) ? $tabla : $tabla_error_;

			$this->form_validation->set_rules($this->__loadRules('edit'));
			if ($this->form_validation->run() == FALSE) {
				// TODO	ADD CONSTANT FOR LOGGIN OR NOT LOGGIN THIS FEATURE
				$buffer = validation_errors();
				$buffer = str_replace(array("\r", "\n"), '|', $buffer);
				$this->Log($buffer);
				// ---------------------------------------------------
				$this->__cleanValuesCrud();
				$this->setMessage(MSJ_CRUD_FORM_VALIDATION_ERR, 'warning');
				// $vista_name = strtolower($this->name_controller) . '/v_' . strtolower($this->name_controller) . '_edit';
				$this->__LoadCrudUpdate($tabla_error, $uuid, $titulo, $this->name_controller, $vista_name, TRUE, $action_);
			} else {
				$this->__setValuesCrud("edit");
				$this->data = $this->crud->crud_update($tabla, $this->_field_data, $uuid);
				if ($this->data["error"] === true) {
					$this->setMessage($this->data["mensaje"], 'error');
					$this->__cleanValuesCrud();
					// $vista_name = strtolower($this->name_controller) . '/v_' . strtolower($this->name_controller) . '_edit';
					$this->__LoadCrudUpdate($tabla_error, $uuid, $titulo, $this->name_controller, $vista_name, TRUE, $action_);
				} else {
					$this->setMessage(MSJ_CRUD_UPDATE, 'success');
					header("Location:" . $redirect);
					return;
				}
			}
		} else {
			throw new Exception('{' . __METHOD__ . '} Debe llegar hasta aquí con un POST METHOD');
		}
	}
	public function delete()
	{
		if ($this->input->post()) {
			$this->load->helper('form');

			$uuid = $this->Post('uuid');
			if (empty($uuid)) {
				throw new Exception('Debe especificar el UUID');
			}

			$this->data = $this->crud->crud_delete($this->table_name_crud, $uuid);
			if ($this->data["error"] === true) {
				$this->setMessage($this->data["mensaje"], 'error');
				$this->__cleanValuesCrud();
				$vista_name = strtolower($this->name_controller) . '/v_' . strtolower($this->name_controller) . '_view';
				$tabla = (empty($this->table_name_view) ? $this->table_name_crud : $this->table_name_view);
				$this->__LoadCrudDelete($tabla, $uuid, $this->name_controller, $this->name_controller, $vista_name);
			} else {
				$this->setMessage(MSJ_CRUD_DELETE, 'success');
				header("Location:listado");
				return;
			}
		} else {
			throw new Exception('Debe llegar hasta aquí con un POST METHOD');
		}
	}

	// ----------------------------------------------------------------------------------
	// FUNCIONES PRIVADAS
	// ----------------------------------------------------------------------------------	
	private function __loadRules($tipo = '')
	{
		if (empty($tipo)) {
			return $this->rules;
		}
		if ($tipo == 'new') {
			if (empty($this->rules_new)) {
				return $this->rules;
			}
			return $this->rules_new;
		}
		if ($tipo == 'edit') {
			if (empty($this->rules_edit)) {
				return $this->rules;
			}
			return $this->rules_edit;
		}
	}
	private function __optimizar_msj_error_PG($mensaje)
	{
		$output = str_replace("llave duplicada viola restricción de unicidad", "", $mensaje);
		return $output;
	}
	private function get_parent_controller($instance, $classname) {
		$class = $classname;
		$t = get_class($instance);
		while (($p = get_parent_class($t)) !== false) {
			if ($p == $class) return $t;
			$t = $p;
		}
		return null;
	}	


}
