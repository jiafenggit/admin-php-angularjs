<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    
	public function signin()
	{   
		$this->load->database();
		$this->load->model('admin/Admin_user_model','user');
		$req = $this->input->post();
		$res = $this->user->auth($req);
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
}
