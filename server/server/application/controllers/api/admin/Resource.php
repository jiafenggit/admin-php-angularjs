<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
class Resource extends REST_Controller {
  protected $_resourice =  array(
    'resourcies' => 'admin/resourcies_model'
  );

  function __construct()
  {
    parent::__construct();
    $this->output->enable_profiler(TRUE);
    $this->load->database();
    
    // $this->load->model('admin/Auth_model','auth');
    // if($this->auth->run() === false)
    // {
    //   $this->response('',401);
    // }
    $this->load->model('admin/resourcies_model','resourcies');
  }


 
  public function resourcies_get()
  { 
    $result = $this->resourcies->get(array('id'=>'1','controller'=>'a'));
    var_dump($result);
    // $id = $this->get('id');
    // if(!isset($id))
    // {
    //    $this->response($this->resourcies->query(,$req->fields), 200);
    // }
    // $this->response($this->resourcies->get($req->arg['id'],$req->fields), 200);
  }

  public function resourcies_post($req)
  {
    $result = $this->resourcies->create($this->post());
    $result['status'] || $this->response($result['errors'],400);
    $this->response('',201);
  }

}

