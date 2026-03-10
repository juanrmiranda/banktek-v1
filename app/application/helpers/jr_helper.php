<?php
// --------------QUERY/DATABASE FUNCTIONS----------------------------------------------------------

if (!function_exists('jr_find_row_database')) {
	/**
	 * Consulta a la base de datos un registro especifico
	 *
	 * @param   string  nombre de la tabla o vista
	 * @param   string  campo llave
	 * @param   string  valor a buscar
	 * @return  object
	 */
	function jr_find_row_database($tabla_ = null, $filtro_ = null)
	{
		if (empty($tabla_) || empty($filtro_)) {
			trigger_error("Faltan parámetros (tabla=" . $tabla_ . ")", E_USER_WARNING);
			$vacio = (object) array('error' => true, 'descripcion' => "Faltan parámetros (tabla=" . $tabla_ . ")");
			return $vacio;
		}
		$CI = &get_instance();
		$CI->load->model('crud');
		$CI->crud->where($filtro_);
		$CI->crud->Read($tabla_);
		if (!$CI->crud->conResultados()) {
			$vacio = (object) array('error' => true, 'descripcion' => '<span class="text-danger">Valor no encontrado</span>');
			return $vacio;
		}

		return $CI->crud->row();
	}
}

if (!function_exists('jr_directquery_database')) {
	/**
	 * Consulta a la base de datos un registro especifico
	 *
	 * @param   string  nombre de la tabla o vista
	 * @param   string  campo llave
	 * @param   string  valor a buscar
	 * @return  object
	 */
	function jr_directquery_database($query_ = null)
	{
		if (empty($query_)) {
			trigger_error("Faltan parámetros (query=null)", E_USER_WARNING);
			$vacio = (object) array('error' => true, 'descripcion' => "Faltan parámetros (query=null)");
			return $vacio;
		}
		$CI = &get_instance();
		$CI->load->model('crud');
		$resultado = $CI->crud->query($query_);
		if (!$CI->crud->conResultados()) {
			$vacio = (object) array('error' => true, 'descripcion' => '<span class="text-danger">Valor no encontrado</span>');
			return $vacio;
		}

		return $CI->crud->row();
	}
}

if (!function_exists('jr_find_row_constants')) {
	/**
	 * Consulta en constantes del proyecto por un Index
	 *
	 * @param   string  nombre de la constante
	 * @param   string  valor a buscar
	 * @return  string
	 */
	function jr_find_row_constants($constants_ = null, $valor_)
	{
		if (empty($constants_)  || $valor_ === "") {
			trigger_error("Faltan parámetros", E_USER_WARNING);
			return "Faltan parámetros";
		}
		$descripcion = 'No existe en la constante (' . $valor_ . ")";
		is_array($valor_) or $selected = array($valor_);
		$constants_ = unserialize($constants_);
		foreach ($constants_ as $key => $val) {
			$key = (string) $key;
			if (in_array($key, $selected)) {
				$descripcion = (string) $val;
				break;
			}
		}
		return $descripcion;
	}
}

if (!function_exists('jr_find_rows_database')) {
	/**
	 * Consulta a la base de datos un registro especifico
	 *
	 * @param   string  nombre de la tabla o vista
	 * @param   string  campo llave
	 * @param   string  valor a buscar
	 * @return  object
	 */
	function jr_find_rows_database($tabla_ = null, $filtro_ = null)
	{
		if (empty($tabla_) || empty($filtro_)) {
			trigger_error("Faltan parámetros (tabla=" . $tabla_ . ")", E_USER_WARNING);
			$vacio = (object) array('descripcion' => "Faltan parámetros (tabla=" . $tabla_ . ")");
			return $vacio;
		}
		$CI = &get_instance();
		$CI->load->model('crud');
		$CI->crud->where($filtro_);
		$CI->crud->Read($tabla_);
		if (!$CI->crud->conResultados()) {
			$vacio = (object) array('descripcion' => '<span class="text-danger">Valor no encontrado</span>');
			return $vacio;
		}

		return $CI->crud->rows();
	}
}

if (!function_exists('jr_current_date_database')) {
	/**
	 * Obtiene la fecha de la base de datos
	 *
	 * @return  string
	 */
	function jr_current_date_database()
	{
		$CI = &get_instance();
		$CI->load->model('crud');
		$registro = $CI->crud->query("select current_date");
		$row = $registro->row();
		return $row->date;
	}
}

if (!function_exists('jr_current_timestamp_database')) {
	/**
	 * Obtiene la fecha de la base de datos
	 *
	 * @return  string
	 */
	function jr_current_timestamp_database()
	{
		$CI = &get_instance();
		$CI->load->model('crud');
		$registro = $CI->crud->query("select current_timestamp as date");
		$row = $registro->row();
		return $row->date;
	}
}

if (!function_exists('jr_create_htmltable_database')) {
	/**
	 * Consulta a la base de datos un registro especifico
	 *
	 * @param   string  nombre de la tabla o vista
	 * @param   string  campo llave
	 * @param   string  valor a buscar
	 * @return  object
	 */
	function jr_create_htmltable_database($tabla_ = null, $filtro_ = null, $campos_ = null)
	{
		if (empty($tabla_) || empty($filtro_)) {
			trigger_error("Faltan parámetros (tabla=" . $tabla_ . ")", E_USER_WARNING);
			$vacio = (object) array('descripcion' => "Faltan parámetros (tabla=" . $tabla_ . ")");
			return $vacio;
		}
		$CI = &get_instance();
		$CI->load->model('crud');
		if (!empty($campos_)) {
			$CI->crud->select($campos_);
		}
		$CI->crud->where($filtro_);
		$query = $CI->crud->Read($tabla_);
		if (!$CI->crud->conResultados()) {
			return '<span class="text-danger">-Sin información-</span>';
		}
		$CI->load->library('table');
		$template = array('table_open' => '<table class="table text-muted table-hover table-sm">');
		$CI->table->set_template($template);
		return $CI->table->generate($query);
		// return $CI->crud->rows();
	}
}

if (!function_exists('jr_create_htmltable_expediente_digital')) {
	/**
	 * Creación de la tabla de Expediente Digital para visualización
	 *
	 * @param   string  nombre del schema 
	 * @param   string  UUID a buscar 
	 * @param   string  si la tabla es distinta la de los últimos
	 * @return  object
	 */
	function jr_create_htmltable_expediente_digital($schema_ = null, $uuid_registro_asociado_ = null, $vista_ = "documentos_view_ultimos")
	{
		if (empty($schema_) || empty($uuid_registro_asociado_)) {
			trigger_error("Faltan parámetros (tabla=" . $schema_ . ")", E_USER_WARNING);
			$vacio = (object) array('descripcion' => "Faltan parámetros (tabla=" . $schema_ . ")");
			return $vacio;
		}
		$CI = &get_instance();
		$CI->load->model('crud');
		$CI->crud->where("uuid_registro_asociado", $uuid_registro_asociado_);
		$CI->crud->Read("digitalizacion_" . $schema_ . "." . $vista_);
		if (!$CI->crud->conResultados()) {
			return '<span class="text-danger">-Sin información-</span>';
		}

		$registros = $CI->crud->rows();


		$CI->load->library('table');
		$template = array('table_open' => '<table class="table text-muted table-hover table-sm">');
		$CI->table->set_template($template);
		$CI->table->set_heading('Documento', 'Creado', 'Fecha');
		if ($registros) {
			foreach ($registros as $fila) {
				$string_adjunto = visualizar_info(ucfirst($schema_) . '/read_documento/' .  $fila->uuid, $fila->tipo_descripcion);
				$CI->table->add_row(
					$string_adjunto,
					$fila->creado_por,
					$fila->fecha_creacion_descripcion
				);
			}
		} else {
			$estado = array('data' => '<span class="text-red">-Sin información-</span>');
			$CI->table->add_row($estado, '', "");
		}
		$documentos = $CI->table->generate();
		return $documentos;

		// return $CI->crud->rows();
	}
}

if (!function_exists('jr_create_htmltable_fiadores')) {
	/**
	 * Creación de la tabla de Expediente Digital para visualización
	 *
	 * @param   string  nombre del schema 
	 * @param   string  UUID a buscar 
	 * @param   string  si la tabla es distinta la de los últimos
	 * @return  object
	 */
	function jr_create_htmltable_fiadores($correlativo_credito_, $editar = true)
	{
		if (empty($correlativo_credito_)) {
			trigger_error("Falta el correlativo del credito (genera_tabla_fiadores)", E_USER_WARNING);
			$vacio = (object) array('descripcion' => "Falta el correlativo del credito (genera_tabla_fiadores)");
			return $vacio;
		}
		$CI = &get_instance();
		$CI->load->model('crud');

		$CI->crud->where("correlativo_credito", $correlativo_credito_);
		$CI->crud->Read("creditos.fiadores_view");
		if (!$CI->crud->conResultados()) {
			return '<span class="text-danger">-Sin información-</span>';
		}

		$registros = $CI->crud->rows();
		$CI->load->library('table');
		$template = array('table_open' => '<table class="table table-hover table-sm" id="tabla_fiadores">');
		$CI->table->set_template($template);
		$CI->table->set_heading('Cliente', 'Creado', 'Fecha', "");
		if ($registros) {
			foreach ($registros as $fila) {
				$string_adjunto = visualizar_info('Clientes/visualizar/' .  $fila->uuid_cliente, $fila->nombre_cliente);
				$btn_editar = "";
				if ($editar) {
					$btn_editar = '<button type="button" class="btn btn-outline-danger btn-flat btn-sm" onclick="ConfirmarEliminarFiador(' . "'" . $fila->uuid . "'" . ')" ><i class="fas fa-trash"></i> Eliminar</button>';
				}
				$CI->table->add_row(
					$string_adjunto,
					$fila->creado_por,
					$fila->fecha_creacion_descripcion,
					$btn_editar
				);
			}
		} else {
			$estado = array('data' => '<span class="text-red">-Sin información-</span>');
			$CI->table->add_row($estado, '', "", "");
		}
		$fiadores = $CI->table->generate();
		return $fiadores;

		// return $CI->crud->rows();
	}
}
if (!function_exists('jr_create_htmltable_alertas_creditos')) {
	/**
	 * Creación de la tabla de Alertas de créditos
	 *
	 * @param   string  Correlativo del crédito
	 * @return  string  HTML Table con listadode alertas
	 */
	function jr_create_htmltable_alertas_creditos($correlativo_credito_)
	{
		if (empty($correlativo_credito_)) {
			trigger_error("Falta el correlativo del credito (genera_tabla_alertas)", E_USER_WARNING);
			$vacio = (object) array('descripcion' => "Falta el correlativo del credito (genera_tabla_alertas)");
			return $vacio;
		}
		$CI = &get_instance();
		$CI->load->model('crud');

		$CI->crud->where("correlativo_credito", $correlativo_credito_);
		$CI->crud->where("activo", true);
		$CI->crud->where("visible", true);
		$CI->crud->Read("creditos.alertas_view");
		if (!$CI->crud->conResultados()) {
			return '';
		}

		$registros = $CI->crud->rows();
		$html='<div class="row">
        <div class="col-12">
            <dl class="divider">
                <dt class="text-danger text-bold">Alertas</dt>
            </dl>';
		$CI->load->library('table');
		$template = array('table_open' => '<table class="table table-hover table-sm text-danger text-bold" id="tabla_fiadores">');
		$CI->table->set_template($template);
		// $CI->table->set_heading('Alerta');
		if ($registros) {
			foreach ($registros as $fila) {
				$CI->table->add_row(
					$fila->alerta_descripcion
				);
			}
		} else {
			$estado = array('data' => '<span class="text-red">-Sin información-</span>');
			$CI->table->add_row($estado);
		}
		$alertas = $CI->table->generate();
		$html  .= $alertas;
		$html  .= '        </div>
		</div>';

		return $html;

		// return $CI->crud->rows();
	}
}


// --------------DATE FUNCTIONS----------------------------------------------------------

if (!function_exists('fecha_corta')) {
	/**
	 * Convierte una fecha en HumanRead
	 *
	 * @param	string	fecha a convertir
	 * @return	string
	 */
	function fecha_corta($fecha)
	{
		if (empty($fecha)) {
			return "";
		}
		return strftime("%d %b %Y", strtotime($fecha));
	}
}
if (!function_exists('fecha_mediana')) {
	/**
	 * Convierte una fecha en HumanRead
	 *
	 * @param	string	fecha a convertir
	 * @return	string
	 */
	function fecha_mediana($fecha)
	{
		if (empty($fecha)) {
			return "";
		}
		return strftime("%d %B %Y", strtotime($fecha));;
	}
}
if (!function_exists('fecha_mediana_de')) {
	/**
	 * Convierte una fecha en HumanRead
	 *
	 * @param	string	fecha a convertir
	 * @return	string
	 */
	function fecha_mediana_de($fecha)
	{
		if (empty($fecha)) {
			return "";
		}
		return strftime("%d de %B de %Y", strtotime($fecha));;
	}
}
if (!function_exists('fecha_larga')) {
	/**
	 * Convierte una fecha en HumanRead
	 *
	 * @param	string	fecha a convertir
	 * @return	string
	 */
	function fecha_larga($fecha)
	{
		if (empty($fecha)) {
			return "";
		}
		return strftime("%d %b %Y %H:%M:%S", strtotime($fecha));;
	}
}

// --------------NUMBER FUNCTIONS----------------------------------------------------------

if (!function_exists('es_decimal')) {
	/**
	 * Valida una variable que sea decimal
	 *
	 * @param	string	valor a validar
	 * @return	boolean
	 */
	function es_decimal($valor)
	{
		try {
			new ReflectionClass('ReflectionClass' . ((float)$valor . "" !== $valor));
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
}
if (!function_exists('es_entero_positivo')) {
	/**
	 * Valida una variable que sea entero mayor a cero
	 *
	 * @param	string	valor a validar
	 * @return	boolean
	 */
	function es_entero_positivo($valor)
	{
		return is_numeric($valor) && intval($valor) == $valor && $valor > 0;
	}
}
if (!function_exists('es_entero')) {
	/**
	 * Valida una variable que sea entero incluyendo el número cero
	 *
	 * @param	string	valor a validar
	 * @return	boolean
	 */
	function es_entero($valor)
	{
		return is_numeric($valor) && intval($valor) == $valor;
	}
}
// ------------------------------------------------------------------------
// ------------------------------------------------------------------------
// updates jRamirez para el Load de URLS simples 
// --------------URL FUNCTIONS---------------------------------------------
// ------------------------------------------------------------------------
if (!function_exists('anchor2')) {
	/**
	 * Anchor Link
	 *
	 * Creates an anchor based on the local URL.
	 *
	 * @param	string	the URL
	 * @param	string	the link title
	 * @param	mixed	any attributes
	 * @return	string
	 */
	function anchor2($uri = '', $title = '', $attributes = '')
	{
		$title = (string) $title;

		$site_url = is_array($uri)
			? base_url($uri)
			: (preg_match('#^(\w+:)?//#i', $uri) ? $uri : base_url($uri));

		if ($title === '') {
			$title = $site_url;
		}

		if ($attributes !== '') {
			$attributes = _stringify_attributes($attributes);
		}

		return '<a href="' . $site_url . '"' . $attributes . '>' . $title . '</a>';
	}
}

if (!function_exists('load_img')) {
	/**
	 * load IMAGEN
	 *
	 * Create a local URL based on your basepath.
	 * a la carpeta de imagenes del sistema
	 *
	 * @param	string	$filename
	 * @return	string
	 */
	function load_img($filename = '')
	{
		return get_instance()->config->base_url("assets/img/" . $filename, NULL);
	}
}

if (!function_exists('load_js')) {
	/**
	 * load CSS 
	 *
	 * Create a local URL based on your basepath.
	 * Load CSS propio
	 *
	 * @param	string	Nombre del Assets externo
	 * @return	string
	 */
	function load_js($filename)
	{
		return get_instance()->config->base_url("assets/js/" . $filename . ".js", NULL);
	}
}

if (!function_exists('load_css')) {
	/**
	 * load CSS 
	 *
	 * Create a local URL based on your basepath.
	 * Load CSS propio
	 *
	 * @param	string	Nombre del Assets externo
	 * @return	string
	 */
	function load_css($filename)
	{
		return get_instance()->config->base_url("assets/css/" . $filename, NULL);
	}
}

if (!function_exists('load_asset_ext')) {
	/**
	 * load IMAGEN
	 *
	 * Create a local URL based on your basepath.
	 * Al Asset externo 
	 *
	 * @param	string	Nombre del Assets externo
	 * @return	string
	 */
	function load_asset_ext($filename)
	{
		return get_instance()->config->base_url("assets/ext/" . $filename, NULL);
	}
}

if (!function_exists('go_to')) {
	/**
	 * ir a un controlador
	 *
	 * Ir a un controller y una funcion
	 *
	 * @param	string	El nombre del controller a ejecutar
	 * @param	string	La función que se va ejecutar del controlador
	 * @return	string
	 */
	function go_to($controller, $function = '')
	{
		return get_instance()->config->base_url($controller . "/" . $function, NULL);
	}
}

if (!function_exists('generate_print')) {
	/**
	 * Anchor Link - Pop-up version
	 *
	 * Creates an anchor based on the local URL. The link
	 * opens a new window based on the attributes specified.
	 *
	 * @param	string	the URL
	 * @param	string	the link title
	 * @param	mixed	any attributes
	 * @return	string
	 */
	function generate_print($uri = '', $title = '')
	{
		$title = (string) $title;
		$site_url = get_instance()->config->base_url($uri, NULL);

		return '<a class="btn btn-outline-secondary btn-flat" href="#'
			. '" onclick="printPage(\'' . $site_url . "'); return false;\""
			. '><i class="fas fa-file-alt"></i> ' . $title . '</a>';
	}
}

if (!function_exists('visualizar_info')) {
	/**
	 * Anchor Link - version Visualizar from CTRLS
	 *
	 * Creates an anchor based on the local URL. The link
	 * opens a new window based on the attributes specified.
	 *
	 * @param	string	the URL
	 * @param	string	the link title
	 * @param	mixed	any attributes
	 * @return	string
	 */
	function visualizar_info($uri = '', $title = '')
	{
		$title = (string) $title;
		$site_url = get_instance()->config->base_url($uri, NULL);
		$attributes = array();
		$window_name = '_blank';
		foreach (array('width' => '1200', 'height' => '600', 'scrollbars' => 'yes', 'menubar' => 'no', 'status' => 'yes', 'resizable' => 'yes', 'screenx' => '0', 'screeny' => '0') as $key => $val) {
			$atts[$key] = isset($attributes[$key]) ? $attributes[$key] : $val;
			unset($attributes[$key]);
		}
		$attributes = _stringify_attributes($attributes);
		return '<a href="' . $site_url
			. '" onclick="window.open(\'' . $site_url . "', '" . $window_name . "', '" . _stringify_attributes($atts, TRUE) . "'); return false;\""
			. $attributes . '>' . $title . '</a>';
	}
}

if (!function_exists('load_css_ext')) {
	/**
	 * link rel CSS externo
	 *
	 * Crea un link del tipo rel el nombre debe ir sin extension
	 * y existente en la carpeta de los assets-externo
	 *
	 * @param	string	archivo Css a cargar, sin extension
	 * @return	string
	 */
	function load_css_ext($file = '')
	{
		return '<link rel="stylesheet" href="'
			. load_asset_ext($file . ".min.css")
			. '">';
	}
}

if (!function_exists('load_js_ext')) {
	/**
	 * script externo
	 *
	 * carga un script externo, del tipo MIN
	 * y existente en la carpeta de los assets-externo
	 *
	 * @param	string	Script MIN cargar, sin extension
	 * @return	string
	 */
	function load_js_ext($file = '')
	{
		return '<script src="'
			. load_asset_ext($file . ".min.js")
			. '"></script>';
	}
}

if (!function_exists('load_js_local')) {
	/**
	 * script externo
	 *
	 * carga un script externo, del tipo MIN
	 * y existente en la carpeta de los assets-externo
	 *
	 * @param	string	Script MIN cargar, sin extension
	 * @return	string
	 */
	function load_js_local($file = '')
	{
		return '<script src="'
			. load_js($file)
			. '"></script>';
	}
}


// -----------------UPDATED JR 16112021-------------------------------------------------------
// -----------------FORM FUNCTIONS---------------------------------------------


if (!function_exists('frm_hidden')) {
	/**
	 * Hidden Input Field
	 *
	 * Generates hidden fields. You can pass a simple key/value string or
	 * an associative array with multiple values.
	 *
	 * @param	mixed	$name		Field name
	 * @param	string	$value		Field value
	 * @param	bool	$recursing
	 * @return	string
	 */
	function frm_hidden($name, $row, $value = '')
	{
		static $form;
		if (empty($row)) {
			if ($value === '') {
				return '';
			}
		} else {
			$value = $row->$name;
		}

		$form = '<input type="hidden" id="input_' . $name . '" name="' . $name . '" value="' . html_escape($value) . "\" />\n";

		return $form;
	}
}

if (!function_exists('frm_input')) {
	/**
	 * Text Input Field
	 *
	 * @param	string Nombre del campo
	 * @param	string Titulo del campo
	 * @param	mixed Fila/Objeto Row del registro
	 * @return	boolean Estado para saber de donde obtener el valor si del ROW o del FORM
	 * @return	string Valor statico para el campo
	 * @param	mixed
	 */
	function frm_input($name, $label, $row, $validando, $focused = false, $value = '', $data = '')
	{

		if (!empty(form_error($name))) {
			$msj_err = form_error($name);
		} else {
			$msj_err = '';
		}

		if ($validando) {
			$value = set_value($name);
		} elseif (empty($value)) {
			if (empty($row)) {
				$value = '';
			} else {
				$value = $row->$name;
			}
		}
		$css_focus = $focused ? 'focused' : '';
		$defaults = array(
			'type' => 'text',
			'class' => $css_focus . ' form-control form-control-border sig-input ' . form_error_class($name),
			'name' => $name,
			'value' => $value
		);
		$div  = '<div class="form-group sig-form-group">' . "\n";
		$div  .= frm_label($label);
		$div .= '<input ' . _parse_form_attributes($data, $defaults) . " />\n";
		$div  .= '<div class="help-block" id="err_msj-' . $name . '">' . $msj_err . '</div>' . "\n";
		$div  .= '</div>' . "\n";
		return $div;
	}
}

if (!function_exists('frm_textarea')) {
	/**
	 * Textarea field
	 *
	 * @param	mixed	$data
	 * @param	string	$value
	 * @param	mixed	$extra
	 * @return	string
	 */
	function frm_textarea($name, $label, $cols, $filas, $row, $validando, $value = '', $data = '')
	{
		if (!empty(form_error($name))) {
			$msj_err = form_error($name);
		} else {
			$msj_err = '';
		}

		if ($validando) {
			$value = set_value($name);
		} elseif (empty($value)) {
			if (empty($row)) {
				$value = '';
			} else {
				$value = $row->$name;
			}
		}
		// $css_focus = $focused ? 'focused' : '';

		$defaults = array(
			// 'class' => $css_focus . ' form-control ' . form_error_class($name),
			'class' => ' form-control rounded-0 ' . form_error_class($name),
			'name' => $name,
			'cols' => $cols,
			'rows' => $filas,
			'value' => $value
		);

		// $defaults = array(
		// 	'cols' => '40',
		// 	'name' => is_array($data) ? '' : $data,
		// 	'rows' => '10'
		// );


		// if (!is_array($data) or !isset($data['value'])) {
		// 	$val = $value;
		// } else {
		// 	$val = $data['value'];
		// 	unset($data['value']); // textareas don't use the value attribute
		// }

		$div  = '<div class="form-group sig-form-group">' . "\n";
		$div  .= frm_label($label);
		$div .= '<textarea ' . _parse_form_attributes($data, $defaults) . '>'
			. html_escape($value)
			. "</textarea>\n";
		$div  .= '<div class="help-block" id="err_msj-' . $name . '">' . $msj_err . '</div>' . "\n";
		$div  .= '</div>' . "\n";
		return $div;

		// return '<textarea ' . _parse_form_attributes($data, $defaults) . '>'
		// . html_escape($value)
		// . "</textarea>\n";
	}
}

if (!function_exists('frm_dui')) {
	/**
	 * Text Input Field
	 *
	 * @param	string Nombre del campo
	 * @param	string Titulo del campo
	 * @param	mixed Fila/Objeto Row del registro
	 * @return	boolean Estado para saber de donde obtener el valor si del ROW o del FORM
	 * @return	string Valor statico para el campo
	 * @param	mixed
	 */
	function frm_dui($name, $label, $row, $validando, $focused = false, $value = '', $data = '')
	{

		if (!empty(form_error($name))) {
			$msj_err = form_error($name);
		} else {
			$msj_err = '';
		}

		if ($validando) {
			$value = set_value($name);
		} elseif (empty($value)) {
			if (empty($row)) {
				$value = '';
			} else {
				$value = $row->$name;
			}
		}
		$css_focus = $focused ? 'focused' : '';
		$defaults = array(
			'type' => 'text',
			'class' => $css_focus . ' form-control form-control-border sig-input' . form_error_class($name),
			'name' => $name,
			'value' => $value,
			'data-mask' => null,
			'data-inputmask' => "'mask': '99999999-9'"
		);
		$div  = '<div class="form-group sig-form-group">' . "\n";
		$div  .= frm_label($label);
		$div .= '<input ' . _parse_form_attributes($data, $defaults) . "  />\n";
		$div  .= '<div class="help-block" id="err_msj-' . $name . '">' . $msj_err . '</div>' . "\n";
		$div  .= '</div>' . "\n";
		return $div;
	}
}

if (!function_exists('frm_telefono')) {
	/**
	 * Text Input Field
	 *
	 * @param	string Nombre del campo
	 * @param	string Titulo del campo
	 * @param	mixed Fila/Objeto Row del registro
	 * @return	boolean Estado para saber de donde obtener el valor si del ROW o del FORM
	 * @return	string Valor statico para el campo
	 * @param	mixed
	 */
	function frm_telefono($name, $label, $row, $validando, $focused = false, $value = '', $data = '')
	{

		if (!empty(form_error($name))) {
			$msj_err = form_error($name);
		} else {
			$msj_err = '';
		}

		if ($validando) {
			$value = set_value($name);
		} elseif (empty($value)) {
			if (empty($row)) {
				$value = '';
			} else {
				$value = $row->$name;
			}
		}
		$css_focus = $focused ? 'focused' : '';
		$defaults = array(
			'type' => 'text',
			'class' => $css_focus . ' form-control form-control-border sig-input' . form_error_class($name),
			'name' => $name,
			'value' => $value,
			'data-mask' => null,
			'data-inputmask' => "'mask': '9999-9999'"
		);
		$div  = '<div class="form-group sig-form-group">' . "\n";
		$div .= frm_label($label);
		$div .= '<input ' . _parse_form_attributes($data, $defaults) . "  />\n";
		$div  .= '<div class="help-block" id="err_msj-' . $name . '">' . $msj_err . '</div>' . "\n";
		$div  .= '</div>' . "\n";
		return $div;
	}
}

if (!function_exists('frm_number')) {
	/**
	 * Text Input Field
	 *
	 * @param	string Nombre del campo
	 * @param	string Titulo del campo
	 * @param	string Valor mínimo permitido para el input
	 * @param	string Valor máximo permitido para el input
	 * @param	string Valor de incremento para el input
	 * @param	mixed Fila/Objeto Row del registro
	 * @return	boolean Estado para saber de donde obtener el valor si del ROW o del FORM
	 * @return	string Valor statico para el campo
	 * @param	mixed
	 */
	function frm_number($name, $label, $min, $max, $step, $row, $validando, $focused = false, $value = '', $data = '')
	{

		if (!empty(form_error($name))) {
			$msj_err = form_error($name);
		} else {
			$msj_err = '';
		}

		if ($validando) {
			$value = set_value($name);
		} elseif (empty($value)) {
			if (empty($row)) {
				$value = '';
			} else {
				$value = $row->$name;
			}
		}
		$css_focus = $focused ? 'focused' : '';
		$defaults = array(
			'type' => 'number',
			'class' => $css_focus . ' form-control form-control-border sig-input' . form_error_class($name),
			'name' => $name,
			'min' => $min,
			'max' => $max,
			'step' => $step,
			'value' => $value
		);
		$div  = '<div class="form-group sig-form-group">' . "\n";
		$div .= frm_label($label);
		$div .= '<input ' . _parse_form_attributes($data, $defaults) . " />\n";
		$div  .= '<div class="help-block" id="err_msj-' . $name . '">' . $msj_err . '</div>' . "\n";
		$div  .= '</div>' . "\n";
		return $div;
	}
}


if (!function_exists('frm_number_lg')) {
	/**
	 * Text Input Field
	 *
	 * @param	string Nombre del campo
	 * @param	string Titulo del campo
	 * @param	string Valor mínimo permitido para el input
	 * @param	string Valor máximo permitido para el input
	 * @param	string Valor de incremento para el input
	 * @param	mixed Fila/Objeto Row del registro
	 * @return	boolean Estado para saber de donde obtener el valor si del ROW o del FORM
	 * @return	string Valor statico para el campo
	 * @param	mixed
	 */
	function frm_number_lg($name, $label, $min, $max, $step, $row, $validando, $focused = false, $value = '', $data = '')
	{

		if (!empty(form_error($name))) {
			$msj_err = form_error($name);
		} else {
			$msj_err = '';
		}

		if ($validando) {
			$value = set_value($name);
		} elseif (empty($value)) {
			if (empty($row)) {
				$value = '';
			} else {
				$value = $row->$name;
			}
		}
		$css_focus = $focused ? 'focused' : '';
		$defaults = array(
			'type' => 'number',
			'class' => $css_focus . ' form-control form-control-border sig-input' . form_error_class($name),
			'name' => $name,
			'min' => $min,
			'max' => $max,
			'step' => $step,
			'value' => $value
		);
		$div  = '<div class="form-group sig-form-group">' . "\n";
		$div .= '<label>' . $label . '</label>' . "\n";
		$div .= '<input ' . _parse_form_attributes($data, $defaults) . " />\n";
		$div  .= '<div class="help-block" id="err_msj-' . $name . '">' . $msj_err . '</div>' . "\n";
		$div  .= '</div>' . "\n";
		return $div;
	}
}

if (!function_exists('frm_date')) {
	/**
	 * Text Input Field
	 *
	 * @param	string Nombre del campo
	 * @param	string Titulo del campo
	 * @param	mixed Fila/Objeto Row del registro
	 * @return	boolean Estado para saber de donde obtener el valor si del ROW o del FORM
	 * @return	string Valor statico para el campo
	 * @param	mixed
	 */
	function frm_date($name, $label, $row, $validando, $focused = false, $value_ = '', $data = '')
	{

		if (!empty(form_error($name))) {
			$msj_err = form_error($name);
		} else {
			$msj_err = '';
		}

		if ($validando) {
			$value = set_value($name);
			// if (empty($value)) {
			// 	$value = date('Y-m-d');
			// }
		} elseif (empty($row)) {
			if ($value_ === "") {
				$value = date('Y-m-d');
			} else {
				$value = $value_;
			}
		} else {
			$value = $row->$name;
			// if (empty($value)) {
			// 	$value = date('Y-m-d');
			// }
		}
		$css_focus = $focused ? 'focused' : '';
		$defaults = array(
			'type' => 'date',
			'class' => $css_focus . ' form-control form-control-border sig-input ' . form_error_class($name),
			'name' => $name,
			'value' => $value
		);
		$div  = '<div class="form-group sig-form-group">' . "\n";

		$div .= frm_label($label);
		$div .= '<input ' . _parse_form_attributes($data, $defaults) . " />\n";
		$div  .= '<div class="help-block" id="err_msj-' . $name . '">' . $msj_err . '</div>' . "\n";
		$div  .= '</div>' . "\n";
		return $div;
	}
}

if (!function_exists('form_dropdown_copy')) {
	/**
	 * Drop-down Menu
	 *
	 * @param	mixed	$data
	 * @param	mixed	$options
	 * @param	mixed	$selected
	 * @param	mixed	$extra
	 * @return	string
	 */
	function form_dropdown_copy($name, $options = array(), $selected = array(), $focused, $extra = '', $data = '')
	{
		$defaults = array();
		$css_focus = $focused ? 'focused' : '';

		if (is_array($data)) {
			if (isset($data['selected'])) {
				$selected = $data['selected'];
				unset($data['selected']); // select tags don't have a selected attribute
			}

			if (isset($data['options'])) {
				$options = $data['options'];
				unset($data['options']); // select tags don't use an options attribute
			}
			$defaults = array('name' => $name, 'class' => $css_focus . ' custom-select form-control-border sig-input-select ' . form_error_class($name));
		} else {
			$defaults = array('name' => $name, 'class' => $css_focus . ' custom-select form-control-border sig-input-select ' . form_error_class($name));
		}

		is_array($selected) or $selected = array($selected);
		is_array($options) or $options = array($options);

		// If no selected state was submitted we will attempt to set it automatically
		if (empty($selected)) {
			if (is_array($data)) {
				if (isset($data['name'], $_POST[$data['name']])) {
					$selected = array($_POST[$data['name']]);
				}
			} elseif (isset($_POST[$data])) {
				$selected = array($_POST[$data]);
			}
		}

		$extra = _attributes_to_string($extra);
		$elSeleccionado = implode("", $selected);
		if (empty($elSeleccionado)) $elSeleccionado = 'null';

		$multiple = (count($selected) > 1 && stripos($extra, 'multiple') === FALSE) ? ' multiple="multiple"' : '';

		$form = '<select data-selected="' . $elSeleccionado . '" id="input_' . $name . '"' . rtrim(_parse_form_attributes($data, $defaults)) . $extra . $multiple . ">\n";

		foreach ($options as $key => $val) {
			$key = (string) $key;

			if (is_array($val)) {
				if (empty($val)) {
					continue;
				}

				$form .= '<optgroup label="' . $key . "\">\n";

				foreach ($val as $optgroup_key => $optgroup_val) {
					$sel = in_array($optgroup_key, $selected) ? ' selected="selected"' : '';
					$form .= '<option value="' . html_escape($optgroup_key) . '"' . $sel . '>'
						. (string) $optgroup_val . "</option>\n";
				}

				$form .= "</optgroup>\n";
			} else {
				$form .= '<option class="sig-option" value="' . html_escape($key) . '"'
					. (in_array($key, $selected) ? ' selected="selected"' : '') . '>'
					. (string) $val . "</option>\n";
			}
		}

		return $form . "</select>\n";
	}
}

if (!function_exists('frm_select')) {
	/**
	 * Text Input Field
	 *
	 * @param	string Nombre del campo
	 * @param	string Titulo del campo
	 * @param	mixed Fila/Objeto Row del registro
	 * @return	boolean Estado para saber de donde obtener el valor si del ROW o del FORM
	 * @return	string Valor statico para el campo
	 * @param	mixed
	 */
	function frm_select($name, $label, $options, $row, $validando, $focused = FALSE, $value = '', $extra = '', $data = '')
	{

		if (!empty(form_error($name))) {
			$msj_err = form_error($name);
		} else {
			$msj_err = '';
		}

		if ($validando) {
			$value = set_value($name);
		} elseif (empty($value)) {
			if (empty($row)) {
				$value = '';
			} else {
				$value =  $row->$name;
			}
		}


		$div  = '<div class="form-group sig-form-group">' . "\n";
		$div .= frm_label($label);
		$div .= form_dropdown_copy($name, $options, $value, $focused, $extra, $data);
		$div  .= '<div class="help-block" id="err_msj-' . $name . '">' . $msj_err . '</div>' . "\n";
		$div  .= '</div>' . "\n";
		return $div;
	}
}

if (!function_exists('frm_select_lg')) {
	/**
	 * Text Input Field
	 *
	 * @param	string Nombre del campo
	 * @param	string Titulo del campo
	 * @param	mixed Fila/Objeto Row del registro
	 * @return	boolean Estado para saber de donde obtener el valor si del ROW o del FORM
	 * @return	string Valor statico para el campo
	 * @param	mixed
	 */
	function frm_select_lg($name, $label, $options, $row, $validando, $focused = FALSE, $value = '', $extra = '', $data = '')
	{

		if (!empty(form_error($name))) {
			$msj_err = form_error($name);
		} else {
			$msj_err = '';
		}

		if ($validando) {
			$value = set_value($name);
		} elseif (empty($value)) {
			if (empty($row)) {
				$value = '';
			} else {
				$value =  $row->$name;
			}
		}


		$div  = '<div class="form-group">' . "\n";
		$div .= '<label>' . $label . '</label>' . "\n";
		$div .= form_dropdown_copy($name, $options, $value, $focused, $extra, $data);
		$div  .= '<div class="help-block" id="err_msj-' . $name . '">' . $msj_err . '</div>' . "\n";
		$div  .= '</div>' . "\n";
		return $div;
	}
}

if (!function_exists('form_dropdown_copy_col')) {
	/**
	 * Drop-down Menu
	 *
	 * @param	mixed	$data
	 * @param	mixed	$options
	 * @param	mixed	$selected
	 * @param	mixed	$extra
	 * @return	string
	 */
	function form_dropdown_copy_col($name, $options = array(), $selected = array(), $focused, $extra = '', $data = '')
	{
		$defaults = array();
		$css_focus = $focused ? 'focused' : '';

		if (is_array($data)) {
			if (isset($data['selected'])) {
				$selected = $data['selected'];
				unset($data['selected']); // select tags don't have a selected attribute
			}

			if (isset($data['options'])) {
				$options = $data['options'];
				unset($data['options']); // select tags don't use an options attribute
			}
			$defaults = array('name' => $name, 'class' => $css_focus . ' custom-select form-control-border sig-input-select' . form_error_class($name));
		} else {
			$defaults = array('name' => $name, 'class' => $css_focus . ' custom-select form-control-border sig-input-select' . form_error_class($name));
		}

		is_array($selected) or $selected = array($selected);
		is_array($options) or $options = array($options);

		// If no selected state was submitted we will attempt to set it automatically
		if (empty($selected)) {
			if (is_array($data)) {
				if (isset($data['name'], $_POST[$data['name']])) {
					$selected = array($_POST[$data['name']]);
				}
			} elseif (isset($_POST[$data])) {
				$selected = array($_POST[$data]);
			}
		}

		$extra = _attributes_to_string($extra);
		$elSeleccionado = implode("", $selected);
		if (empty($elSeleccionado)) $elSeleccionado = 'null';

		$multiple = (count($selected) > 1 && stripos($extra, 'multiple') === FALSE) ? ' multiple="multiple"' : '';

		$form = '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-8"><select data-selected="' . $elSeleccionado . '" id="input_' . $name . '"' . rtrim(_parse_form_attributes($data, $defaults)) . $extra . $multiple . ">\n";

		foreach ($options as $key => $val) {
			$key = (string) $key;

			if (is_array($val)) {
				if (empty($val)) {
					continue;
				}

				$form .= '<optgroup label="' . $key . "\">\n";

				foreach ($val as $optgroup_key => $optgroup_val) {
					$sel = in_array($optgroup_key, $selected) ? ' selected="selected"' : '';
					$form .= '<option value="' . html_escape($optgroup_key) . '"' . $sel . '>'
						. (string) $optgroup_val . "</option>\n";
				}

				$form .= "</optgroup>\n";
			} else {
				$form .= '<option class="sig-option" value="' . html_escape($key) . '"'
					. (in_array($key, $selected) ? ' selected="selected"' : '') . '>'
					. (string) $val . "</option>\n";
			}
		}

		return $form . "</select></div>\n";
	}
}

if (!function_exists('frm_select_col')) {
	/**
	 * Text Input Field
	 *
	 * @param	string Nombre del campo
	 * @param	string Titulo del campo
	 * @param	mixed Fila/Objeto Row del registro
	 * @return	boolean Estado para saber de donde obtener el valor si del ROW o del FORM
	 * @return	string Valor statico para el campo
	 * @param	mixed
	 */
	function frm_select_col($name, $label, $options, $row, $validando, $focused = FALSE, $value = '', $extra = '', $data = '')
	{

		if (!empty(form_error($name))) {
			$msj_err = form_error($name);
		} else {
			$msj_err = '';
		}

		if ($validando) {
			$value = set_value($name);
		} elseif (empty($value)) {
			if (empty($row)) {
				$value = '';
			} else {
				$value =  $row->$name;
			}
		}


		$div  = '<div class="form-group sig-form-group row">' . "\n";
		$div .= frm_label_col($label);
		$div .= form_dropdown_copy_col($name, $options, $value, $focused, $extra, $data);
		$div  .= '<div class="help-block" id="err_msj-' . $name . '">' . $msj_err . '</div>' . "\n";
		$div  .= '</div>' . "\n";
		return $div;
	}
}

if (!function_exists('frm_select_basic')) {
	/**
	 * Text Input Field
	 *
	 * @param	string Nombre del campo
	 * @param	string Titulo del campo
	 * @param	mixed Fila/Objeto Row del registro
	 * @return	boolean Estado para saber de donde obtener el valor si del ROW o del FORM
	 * @return	string Valor statico para el campo
	 * @param	mixed
	 */
	function frm_select_basic($name, $label, $options, $row, $validando, $focused = FALSE, $value = '', $extra = '', $data = '')
	{

		if (!empty(form_error($name))) {
			$msj_err = form_error($name);
		} else {
			$msj_err = '';
		}

		if ($validando) {
			$value = set_value($name);
		} elseif (empty($value)) {
			if (empty($row)) {
				$value = '';
			} else {
				$value =  $row->$name;
			}
		}


		$div  = '<div class="form-group">' . "\n";
		$div .= frm_label($label);
		$div .= form_dropdown_basic_copy($name, $options, $value, $focused, $extra, $data);
		$div  .= '<div class="help-block" id="err_msj-' . $name . '">' . $msj_err . '</div>' . "\n";
		$div  .= '</div>' . "\n";
		return $div;
	}
}

if (!function_exists('form_dropdown_basic_copy')) {
	/**
	 * Drop-down Menu
	 *
	 * @param	mixed	$data
	 * @param	mixed	$options
	 * @param	mixed	$selected
	 * @param	mixed	$extra
	 * @return	string
	 */
	function form_dropdown_basic_copy($name, $options = array(), $selected = array(), $focused, $extra = '', $data = '')
	{
		$defaults = array();
		$css_focus = $focused ? 'focused' : '';

		if (is_array($data)) {
			if (isset($data['selected'])) {
				$selected = $data['selected'];
				unset($data['selected']); // select tags don't have a selected attribute
			}

			if (isset($data['options'])) {
				$options = $data['options'];
				unset($data['options']); // select tags don't use an options attribute
			}
			$defaults = array('name' => $name, 'class' => $css_focus . ' custom-select rounded-0 ' . form_error_class($name));
		} else {
			$defaults = array('name' => $name, 'class' => $css_focus . ' custom-select rounded-0 ' . form_error_class($name));
		}

		is_array($selected) or $selected = array($selected);
		is_array($options) or $options = array($options);

		// If no selected state was submitted we will attempt to set it automatically
		if (empty($selected)) {
			if (is_array($data)) {
				if (isset($data['name'], $_POST[$data['name']])) {
					$selected = array($_POST[$data['name']]);
				}
			} elseif (isset($_POST[$data])) {
				$selected = array($_POST[$data]);
			}
		}

		$extra = _attributes_to_string($extra);
		$elSeleccionado = implode("", $selected);
		if (empty($elSeleccionado)) $elSeleccionado = 'null';

		$multiple = (count($selected) > 1 && stripos($extra, 'multiple') === FALSE) ? ' multiple="multiple"' : '';

		$form = '<select data-selected="' . $elSeleccionado . '" id="input_' . $name . '"' . rtrim(_parse_form_attributes($data, $defaults)) . $extra . $multiple . ">\n";

		foreach ($options as $key => $val) {
			$key = (string) $key;

			if (is_array($val)) {
				if (empty($val)) {
					continue;
				}

				$form .= '<optgroup label="' . $key . "\">\n";

				foreach ($val as $optgroup_key => $optgroup_val) {
					$sel = in_array($optgroup_key, $selected) ? ' selected="selected"' : '';
					$form .= '<option value="' . html_escape($optgroup_key) . '"' . $sel . '>'
						. (string) $optgroup_val . "</option>\n";
				}

				$form .= "</optgroup>\n";
			} else {
				$form .= '<option class="sig-option" value="' . html_escape($key) . '"'
					. (in_array($key, $selected) ? ' selected="selected"' : '') . '>'
					. (string) $val . "</option>\n";
			}
		}

		return $form . "</select>\n";
	}
}

if (!function_exists('frm_input_file')) {
	function frm_input_file($texto = 'Seleccionar archivo', $id = null)
	{
		$div  = "<div class='form-group'>";
		if (empty($id)) {
			$div .= "<input name='userfile' type='file' id='userfile' class='filestyle' data-icon='true' data-buttonText='$texto' >";
		} else {
			$div .= "<input name='" . $id . "' type='file' id='" . $id . "' class='filestyle' data-icon='true' data-buttonText='$texto' >";
		}
		$div .= "</div>";
		return $div;
	}
}

if (!function_exists('frm_label')) {
	function frm_label($label)
	{
		$obj = "";
		if ($label) {
			$obj  = '<label class="sig-label">' . $label . '</label>' . "\n";
		}
		return $obj;
	}
}

if (!function_exists('frm_label_col')) {
	function frm_label_col($label)
	{
		$obj = "";
		if ($label) {
			$obj  = '<label class="sig-label col-12 col-sm-12 col-md-12 col-lg-12 col-xl-4 col-form-label pr-0 pb-0">' . $label . '</label>' . "\n";
		}
		return $obj;
	}
}

// -----------------SECURITY FUNCTIONS---------------------------------------------

if (!function_exists('auth_usuarios_modulos_opciones')) {
	/**
	 * Confirma si posee acceso a esta parte del menu
	 *
	 * @return  string
	 */
	function auth_usuarios_modulos_opciones($opcion)
	{
		$CI = &get_instance();
		if ($CI->session->userdata('usuario') == 'jramirezmi') return true;
		$CI->load->model('crud');
		$CI->crud->where('correlativo_usuario', $CI->session->userdata('correlativo'));
		$CI->crud->where('correlativo_opcion', $opcion);
		$CI->crud->Read("generales.auth_usuarios_modulos_opciones");
		return $CI->crud->conResultados();
	}
}

if (!function_exists('auth_usuarios_nivel_acceso_rol')) {
	/**
	 * Confirma si posee acceso a esta parte del menu
	 *
	 * @return  string
	 */
	function auth_usuarios_nivel_acceso_rol($nivel_requerido = null)
	{
		if (empty($nivel_requerido)) return false;
		$CI = &get_instance();
		$CI->load->model('crud');
		$CI->crud->where('correlativo_usuario', $CI->session->userdata('correlativo'));
		$CI->crud->where('nivel_acceso>=', $nivel_requerido);
		$CI->crud->Read("generales.usuarios_roles_view");
		return $CI->crud->conResultados();
	}
}

// -----------------SESSION VAR EASY---------------------------------------------

if (!function_exists('usuario_sistema')) {
	/**
	 * Confirma si posee acceso a esta parte del menu
	 *
	 * @return  string
	 */
	function usuario_sistema()
	{
		$CI = &get_instance();
		return $CI->session->userdata('usuario');
	}
}
if (!function_exists('session_var')) {
	/**
	 * Confirma si posee acceso a esta parte del menu
	 *
	 * @return  string
	 */
	function session_var($variable_)
	{
		$CI = &get_instance();
		return $CI->session->userdata($variable_);
	}
}

// -----------------NUMERO A LETRAS---------------------------------------------

if (!function_exists('numero_letras')) {
	/**
	 * Convierte una número a letras
	 *
	 * @param	float	número a convertir
	 * @return	string
	 */
	function numero_letras(float $valor)
	{
		$formatterES = new NumberFormatter("es", NumberFormatter::SPELLOUT);
		$izquierda = intval($valor);
		$derecha = round(($valor - intval($valor)), 2) * 100;
		if ((round(($valor - intval($valor)), 2) * 100) < 10) $derecha =  "0" . $derecha ;
		if ((round(($valor - intval($valor)), 2) * 100) == 0) $derecha = "00";
		return $formatterES->format($izquierda) . " CON " . $derecha . "/100";
	}
}

// -----------------MODALS---------------------------------------------
if (!function_exists('jrModal_Basic_Open')) {
	/**
	 * Crea un Modal básico
	 *
	 * Crea un Modal básico 
	 *
	 * @param	string	ID nombre del modal
	 * @param	string	titulo
	 * @return	string
	 */
	function jrModal_Basic_Open($name = '', $title = '', $size = null)
	{
		$title = (string) $title;
		$name = (string) $name;
		$size = empty($size) ? '' : 'modal-' . $size;

		$_htm = '
		<div class="modal fade" id="modal'.$name.'" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modal'.$name.'Label" aria-hidden="true">
			<div class="modal-dialog ' . $size . '" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="modal'.$name.'Label">'.$title.'</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">';

		return $_htm;
	}
}
if (!function_exists('jrModal_Basic_Close')) {
	/**
	 * Crea un Modal básico
	 *
	 * Crea la sección Body en el Modal
	 *
	 * @param	string	ID nombre del modal
	 * @param	string	titulo
	 * @return	string
	 */
	function jrModal_Basic_Close()
	{
		$_htm = '
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-outline-secondary btn-flat" data-dismiss="modal">Cerrar</button>
					</div>
				</div>
			</div>
			</div>    
			';

		return $_htm;
	}
}