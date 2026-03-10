<?php
defined('BASEPATH') or exit('No direct script access allowed');


class My_Model extends CI_Model
{

    protected $table = '';
    protected $schema = '';
    protected $pk = 'correlativo';
    protected $query;

    // ---------------------------------------------------------------------------------------
    // FUNCIONES PUBLICAS
    // ---------------------------------------------------------------------------------------

    // MANEJO DE DOCUMENTOS ADJUNTOS
	public function create_documento($schema)
	{
		$archivo = $_FILES["userfile"]["tmp_name"];
		$type =  $_FILES['userfile']['type'];
		$nombre = $_FILES['userfile']['name'];
		if ($_FILES['userfile']['size'] > (8 * 1000 * 1000)) {
			return false;
		}
        if (empty($type)) {
            return false;
        }
		$data = bin2hex(file_get_contents($archivo)); // This may be a problem on too large files

		$this->db->set('uuid_registro_asociado', $this->input->Post('uuid'));
		$this->db->set('tipo', $this->input->Post('tipo'));
		$this->db->set('extension', $type);
		$this->db->set('documento', $data);

		$this->db->insert('digitalizacion_'.$schema.'.documentos');
        if ($this->errores(__METHOD__)) {
            return null;
        }
		return true;
		
	}
    public function read_documento($schema,$uuid)
	{
        $this->where("uuid", $uuid);
		$this->read('digitalizacion_'.$schema.'.documentos');

        if ($this->errores(__METHOD__)) {
            throw new Exception($this->msj_error(), 1);
        } else {
            if ($this->conResultados()) {
                return $this->row();
            } else {
                throw new Exception("Este documento no esta disponible", 1);
            }
        }
		
	}



    public function usuario()
    {
        return $this->session->userdata('usuario');
    }
    public function errores($Metodo)
    {
        if ($this->db->error()['message']) {
            $err = strtok($this->db->error()['message'], "\n").' '.strtok("\n");
            $this->Log('Usuario: {'.$this->usuario().'} -> ModelFile ' . $Metodo . ' -> ' . $err );
            return true; 
        }
        return false;
    }
    public function msj_error()
    {
        $err = strtok($this->db->error()['message'], "\n").' '.strtok("\n");
        return $err;
    }
    public function Log($mensaje)
    {
        log_message('error', 'Usuario: {'.$this->usuario().'} ' . $mensaje);
    }    
    // ---------------------------------------------------------------------------------------
    // FUNCIONES BASE DE DATOS
    // ---------------------------------------------------------------------------------------
	public function nextval($secuencia)
    {
        $query = $this->db->query("select nextval('".$secuencia."') as correlativo");
        $row = $query->row();
        return $row->correlativo;
    }    
    public function query($query,$parametros=null)
    {       
        if (empty($query)) {
            throw new Exception("Debe especificar un string SQL");
        }
        $this->query = $this->db->query($query, $parametros);      
        return $this->query;
    }
    public function simple_query($query)
    {       
        if (empty($query)) {
            throw new Exception("Debe especificar un string SQL");
        }
        $this->db->simple_query($query);        
    }
    public function limit(int $limit = 1)
    {
        $this->db->limit($limit);
    }
    public function order_by($campo,$tipo = "ASC")
    {
        $this->db->order_by($campo, $tipo);
    }
    public function group_by($campos)
    {
        $this->db->group_by($campos);
    }    
    public function select($campos,$scape = null)
    {
        $this->db->select($campos,$scape);
    }
    public function where($campo, $valor=null, $EscapeString = TRUE)
    {
        if (is_array($campo)) {
            $this->db->where($campo);
            return;
        } elseif ($valor=='' || empty($campo)) {
			throw new Exception('Error de parámetros en WHERE',1);
        }
        $this->db->where($campo, $valor, $EscapeString);
    }
    public function where_not_in($campo, $valor)
    {
        if (empty($valor) || empty($campo)) {
			throw new Exception('Error de parámetros en WHERE_IN',1);
        }
        $this->db->where_not_in($campo, $valor);
    }
    public function where_in($campo, $valor, $escape_ = null)
    {
        if (empty($valor) || empty($campo)) {
			throw new Exception('Error de parámetros en WHERE_IN',1);
        }
        $this->db->where_in($campo, $valor, $escape_);
    }
    public function num_rows()
    {
        return $this->query->num_rows();
    }
    public function like($campo,$valor)
    {
        $this->db->like($campo,$valor);
    }
    public function Read($tabla)
    {
        $this->query = $this->db->get($tabla);
        return $this->query;
    }
    public function Update($tabla)
    {
        return $this->db->update($tabla);
    }
    public function insert($tabla)
    {
        return $this->db->insert($tabla);
    }
    public function Delete($tabla)
    {
        return $this->db->delete($tabla);
    }
    public function conResultados()
    {
        if ($this->query->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    public function row()
    {
        return $this->query->row();
    }
    public function rows()
    {
        return $this->query->result();
    }
    public function set($field,$value=NULL,$protected=TRUE)
    {
        if (is_array($field)) {
            $this->db->set($field);
            return;
        } elseif (empty($field) || empty($value)) {
			throw new Exception('Error de parámetros en SET field <' . $field . '>');
        }
        $this->db->set($field,$value,$protected);
    }
    public function set2($field,$value=NULL,$protected=TRUE)
    {
        if (empty($field) ) {
			throw new Exception('Error de parámetros en SET nombre campo null');
        }
        $this->db->set($field,$value,$protected);
    }
    private function db_return($tipo)
    {
        if ($tipo=='row') {
            return $this->row();
        } else {
            return $this->rows();
        }
        return null;
    }

    // ---------------------------------------------------------------
    // CRUD 
    // ---------------------------------------------------------------
    public function crud_read($tabla = NULL, $uuid = NULL, $order = TRUE)
    {
        $tipo = 'result';
        if ($uuid != NULL) {
            $this->where('uuid', $uuid);
            $tipo = 'row';
        }

        if ($order === TRUE) {
            $this->order_by($this->pk, 'DESC');
        }
        $query =  $this->Read($tabla);

        if ($this->errores(__METHOD__)) {
            $data = array('error' => TRUE, 'mensaje' => $this->db->error()['message']);
            return $data;
        }
        if ($this->conResultados($query) === FALSE) {
            $data = array('error' => FALSE, 'rows' => NULL);
            return $data;
        }
        
        $result = $this->db_return($tipo);
        $data = array('error' => FALSE, 'rows' => $result,'mensaje'=>'ok');
        return $data;
    }
    public function crud_create($tabla,$data)
    {
        if (empty($tabla)) {
            $data = array('error' => TRUE, 'mensaje' => 'Debe especificar un TABLENAME');
            return $data;
        }

        if (!is_array($data)) {
            $data = array('error' => TRUE, 'mensaje' => 'Los datos deben ser un ARRAY');
            return $data;
        }
        
        // $this->set('creado_por', $this->usuario());
        // $this->set('fecha_creacion', 'current_timestamp', FALSE);
        $this->set($data);
        $this->insert($tabla);

        if ($this->errores(__METHOD__)) {
            $data = array('error' => TRUE, 'mensaje' => $this->db->error()['message']);
            return $data;
        }
        
        $data = array('error' => FALSE,'mensaje'=>MSJ_CRUD_INSERT);
        return $data;     
    }
    public function crud_update($tabla, $data, $uuid)
    {
        if (empty($uuid)) {
            $data = array('error' => TRUE, 'mensaje' => 'Debe especificar un UUID');
            return $data;
        }

        if (empty($tabla)) {
            $data = array('error' => TRUE, 'mensaje' => 'Debe especificar un TABLENAME');
            return $data;
        }

        if (!is_array($data)) {
            $data = array('error' => TRUE, 'mensaje' => 'Los datos deben ser un ARRAY');
            return $data;
        }

        $this->set('actualizado_por', $this->usuario());
        $this->set('fecha_actualizado', 'current_timestamp', FALSE);

        $this->set($data);
        $this->where('uuid', $uuid);
        $this->Update($tabla);

        if ($this->errores(__METHOD__)) {
            $data = array('error' => TRUE, 'mensaje' => $this->db->error()['message']);
            return $data;
        }
        
        $data = array('error' => FALSE,'mensaje'=>MSJ_CRUD_UPDATE);
        return $data;
    }
    public function crud_delete($tabla,$uuid)
    {
        if (empty($uuid)) {
            $data = array('error' => TRUE, 'mensaje' => 'Debe especificar un UUID');
            return $data;
        }

        if (empty($tabla)) {
            $data = array('error' => TRUE, 'mensaje' => 'Debe especificar un TABLENAME');
            return $data;
        }        
        
        $this->where('uuid', $uuid);
        if ($this->delete($tabla)==FALSE) {
            // return array('error' => TRUE, 'mensaje' => 'No se elimino ningún registro');
        }

        if ($this->errores(__METHOD__)) {
            $data = array('error' => TRUE, 'mensaje' => $this->db->error()['message']);
            return $data;
        }
        $this->Log(__METHOD__.' TABLE: {'.$tabla.'} UUID: {'.$uuid.'}');
        $data = array('error' => FALSE,'mensaje'=>MSJ_CRUD_DELETE);
        return $data;
    }

    // ---------------------------------------------------------------------------------------
    // FUNCIONES PRIVADAS
    // ---------------------------------------------------------------------------------------

    
}
