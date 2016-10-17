<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    
	public function signin()
	{   
		$this->load->database();
		$this->load->model('admin/Admin_users_model','users');
		$req = $this->input->post();
		$res = $this->users->auth($req);
		if($res['status'] === true)
		{
           $this->load->model('admin/Key_model','token');
           $key = $this->token->generate_key();
           $data = array('key' => $key,'uid' => $res['info']['uid']);
           $this->token->insert_key($data);
           unset($res['info']);
           $res['token'] = $key; 
		}
		echo json_encode($res);
	}

	public function info()
	{
		$this->load->database();
		$this->load->model('admin/Auth_model','auth');
		$token = $this->input->get_request_header('authorization', TRUE);
		if($this->auth->run($token) === false)
	    {
	      show_error('Unauthorized',401);
	      return;
	    }
		echo json_encode($this->auth->get_user());
	}
}
