<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Crud extends MY_Model {

    protected $pk = 'correlativo';

    public function change_pk_field($field)
    {
        $this->pk=$field;
    }

}