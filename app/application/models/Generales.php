<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Generales extends MY_Model
{
    protected $table = 'generales.usuario';
    protected $view = 'generales.usuario_listado';
    protected $primaryKey = 'uuid';
    protected $defaultOrderBy = 'correlativo DESC';

    public function existsDui(string $dui, ?string $excludeUuid = null): bool
    {
        $this->db->from($this->table)
            ->where('dui', $dui)
            ->where('coalesce(eliminado, false) = false', null, false);

        if (!empty($excludeUuid)) {
            $this->db->where('uuid <>', $excludeUuid);
        }

        return $this->db->count_all_results() > 0;
    }

    public function existsNombre(string $nombre, ?string $excludeUuid = null): bool
    {
        $this->db->from($this->table)
            ->where('nombre', $nombre)
            ->where('coalesce(eliminado, false) = false', null, false);

        if (!empty($excludeUuid)) {
            $this->db->where('uuid <>', $excludeUuid);
        }

        return $this->db->count_all_results() > 0;
    }

    public function validar_login($codigo_usuario, $clave)
    {
        $this->where('clave',"crypt('" . $clave . "', clave)",FALSE);
        $this->where('codigo_usuario', $codigo_usuario);
        $this->where('activo', true);
        $eliminado = array('eliminado' => false );
        $this->where($eliminado);
        $this->Read('generales.usuario');

        if ($this->errores(__METHOD__))  return null; 		
        
        return $this->conResultados() ? $this->row() : null;


    }
    
    
        
}