<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Health extends CI_Controller {

    public function index()
    {
        echo "OK";
    }

    public function db()
    {
        $query = $this->db->query("SELECT version() as version");
        $row = $query->row();

        echo "<pre>";
        print_r($row);
        echo "</pre>";
    }
}