<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model {
  public $a = 1;
	function __construct()
	{
	    parent::__construct();
	}
 
  public function create()
  {
    $res = array(
      'resource' => 'add',
      'method' => 'get'
      );
    return  $res;
  }

  public function remove($id)
  {
    $data = array(
      'status' => -1,
      'utime' => time()
    );
    $this->db->where($this->tbl_key,$id)
         ->update($this->tbl, $data);
    return $this->db->affected_rows() > 0;
  }

  protected function validation($method, $data, $rules)
  {
  	$this->load->library('form_validation');
  	$valid = $this->_valid($method,$data,$rules);
  	$this->form_validation->set_data($valid['data']);
  	$this->form_validation->set_rules($valid['rules']);
  	$res['code'] = $this->form_validation->run()?1:0;
  	$res['msg'] = $this->form_validation->error_array();
  	$res['data'] = $this->form_validation->validation_data;
  	return $res;
  }
  
  protected function _valid($method, $data, $rules)
  {
    $valid = array(
       'data' => array(),
       'rules' => array()
    );
    foreach ($data as $key => $value) {
      if($method === 'update')
      { 
         if($value === NULL)
         {
           continue;
         }
      }
      $valid['data'][$key] = $value;
      $valid['rules'][$key] = @$rules[$key];
    }
    return $valid;
  } 
}