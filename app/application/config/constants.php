<?php
defined('BASEPATH') OR exit('No direct script access allowed');

defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

// JR CONSTANTS 
// DEL SISTEMA
defined('CODEMPRESA') or define('CODEMPRESA', 1);
defined('EMPRESA') or define('EMPRESA', 'Financiera DEMO');
defined('EMPRESA_CONTRATOS') or define('EMPRESA_CONTRATOS', 'Financiera DEMO');
defined('SISTEMA') or define('SISTEMA', 'BANKtek');
defined('SISTEMA_PREFIJO') or define('SISTEMA_PREFIJO', 'BANKtek');
defined('SISTEMA_SUFIJO') or define('SISTEMA_SUFIJO', '| tk');
defined('SISTEMA_DESCRIPCION') or define('SISTEMA_DESCRIPCION', 'ERP para Pequeñas y Medianas Empresas');
defined('BRAND_IMAGE') or define('BRAND_IMAGE', 'favicon_opt.gif');
defined('FAVICON') or define('FAVICON', 'favicon_opt.gif');
defined('FAVICON_HD') or define('FAVICON_HD', 'favicon.gif');
defined('SUB_TITULO_RPT') or define('SUB_TITULO_RPT', 'Calle Dr. Miguel Tomas Molina Av. Simeon Cañas');
defined('SUB_TITULO2_RPT') or define('SUB_TITULO2_RPT', 'Casa #2 Barrio El Centro Casa Matriz 2334-1684 Sucursal 2334-3546');

// INPUT: "SELECT"
defined('OPT_ACTIVO_INACTIVO') or define('OPT_ACTIVO_INACTIVO', serialize(array('false' => 'INACTIVO', 'true' => 'ACTIVO')));
defined('OPT_SEXO') or define('OPT_SEXO', serialize(array('M' => 'MASCULINO', 'F' => 'FEMENINO')));
defined('OPT_AGENCIAS') or define('OPT_AGENCIAS', serialize(array('0' => 'NO ESPECIFICADO', '1' => 'CASA MATRIZ', '2' => 'SUCURSAL 2')));
defined('OPT_ESTADO_CIVIL') or define('OPT_ESTADO_CIVIL', serialize(array('0' => 'NO ESPECIFICADO', '1' => 'SOLTERO', '2' => 'CASADO', '3' => 'VIUDO', '4' => 'UNION LIBRE')));
defined('OPT_TRUE_FALSE') or define('OPT_TRUE_FALSE', serialize(array('false' => 'NO', 'true' => 'SI')));
defined('OPT_DEPARTAMENTOS') or define('OPT_DEPARTAMENTOS', serialize(array('0' => 'NO ESPECIFICADO', '1' => 'AHUACHAPAN', '2' => 'SANTA ANA', '3' => 'SONSONATE', '4' => 'CHALATENANGO', '5' => 'LA LIBERTAD', '6' => 'SAN SALVADOR', '7' => 'CUSCATLAN', '8' => 'LA PAZ', '9' => 'CABAÑAS', '10' => 'SAN VICENTE', '11' => 'USULUTAN', '12' => 'SAN MIGUEL', '13' => 'MORAZAN', '14' => 'LA UNION')));
defined('OPT_SELECCIONE_VALOR') or define('OPT_SELECCIONE_VALOR', serialize(array('0' => 'NO ESPECIFICADO')));
defined('OPT_TIPO_CUPON') or define('OPT_TIPO_CUPON', serialize(array('1' => 'PORCENTAJE', '2' => 'VALOR FIJO')));
defined('OPT_LUGARES_DESPACHO') or define('OPT_LUGARES_DESPACHO', serialize(array('1' => 'SALA DE VENTAS', '2' => 'RESIDENCIA', '3' => 'TRABAJO', '4' => 'PERSONALIZADO')));

// MENSAJES GLOBALES DEL SISTEMA
defined('MSJ_CRUD_INSERT') or define('MSJ_CRUD_INSERT', 'Registro agregado satisfactoriamente!');
defined('MSJ_CRUD_UPDATE') or define('MSJ_CRUD_UPDATE', 'Registro actualizado satisfactoriamente!');
defined('MSJ_CRUD_DELETE') or define('MSJ_CRUD_DELETE', 'El registro ha sido eliminado!');
defined('MSJ_CRUD_FORM_VALIDATION_ERR') or define('MSJ_CRUD_FORM_VALIDATION_ERR', 'Hay campos que poseen errores');
defined('MSJ_CRUD_VIEW_LISTADO_NOT_EXISTS') or define('MSJ_CRUD_VIEW_LISTADO_NOT_EXISTS', 'Por los errores se cancela la consulta');

