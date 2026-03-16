<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{

	public function index()
	{
		$data = array('load_datatable' => null);
		$this->load->view('modulos/head',$data);
		$this->load->view('modulos/layout_login');
		session_destroy();
	}

	public function ingresar()
	{
		$this->load->model('generales');
		$usuario = $this->input->post('usuario');
		$clave = $this->input->post('clave');

		if (! empty($usuario) && ! empty($clave)) {
			$fila = $this->generales->validar_login($usuario, $clave);
			if (isset($fila)) {
				$rol = jr_find_row_database("generales.usuarios_roles_view", array("correlativo_usuario" => $fila->correlativo));				
				$whois=$this->getIPAddress();
				$data = array(
					'usuario'		=> $usuario,
					'login'			=> TRUE,
					'nombre'		=> $fila->nombre,
					'terminal'		=> $whois['ip']
				);
				$this->session->set_userdata($data);
				header("Location:" . base_url());
				return;
			} else {
				$this->session->set_flashdata('usuario', $usuario);
				$this->session->set_flashdata('err_login', 'Usuario o Contraseña inválidos');
				header("Location:" . go_to('login'));
			}
		} else {
			$this->session->set_flashdata('err_login', 'Usuario o Contraseña inválidos');
			header("Location:" . go_to('login'));
		}
	}


	public function lockscreen()
	{
		$data = array('usuario' => $this->session->userdata('usuario'), 'nombre' => $this->session->userdata('nombre'),'load_datatable' => null);

		$this->load->view('modulos\head',$data);
		$this->load->view('modulos\layout_lockscreen', $data);
		session_destroy();
	}

	public function salir()
	{
		session_destroy();
		header("Location:" . go_to('login'));
	}

    public function getIPAddress()
    {
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
		} else {
			$ip = $_SERVER['REMOTE_ADDR'] ?? '';
		}
	    $data = array('ip' => $ip,'hostname'=>'null');
		return $data;
    }	

	public function cambiarpassword()
	{
        if ($this->input->post()) {

			if (! $this->input->post('clave')) {
				$data = array('error' => true, 'mensaje' => 'Debe ingresar una clave');
				echo json_encode($data);
				return;
			}

            $data = array('clave' => $this->input->post('clave'));
            $this->sql->set($data);
            $this->sql->where('codigo_usuario', $this->session->userdata("usuario"));
            $this->sql->Update('generales.usuarios');

			$data = array('error' => false, 'mensaje' => 'Cambio realizado!');
            echo json_encode($data);
        } else {
			log_message('error', 'Usuario: {' . $this->session->userdata("usuario") . '} CONTROLLER{Login::cambiarpassword} Debe llegar hasta aquí con un POST METHOD');
            $data = array('error' => true, 'mensaje' => 'Debe llegar hasta aquí con un POST METHOD');
            echo json_encode($data);
        }		
	}
}