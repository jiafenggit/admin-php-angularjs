<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
class Admin extends REST_Controller {
  protected $_resourice =  array(
    'users' => 'admin/Admin_user_model',
    'roles' => 'admin/Admin_role_model'
  );

  function __construct()
  {
    parent::__construct();
    // $this->output->enable_profiler(TRUE);
    $this->load->database();
    
    $this->load->model('admin/Auth_model','auth');
    if($this->auth->run() === false)
    {
      $this->response('',401);
    }
  }

  public function _remap($resource, $params = array())  
  { 
    $this->request->controller = $this->router->class;
    $this->request->resource = $resource;
    $this->request->arg = $this->{$this->request->method}();

    if(!$result = $this->auth->is_pass($this->request))
    { 
      $this->response('',401);
    }
    $this->request->fields = $result['fields'];
    $this->load->model($this->_resourice[$resource],'resourcies');
    $this->{'rest_'.$result['method']}($this->request);
  }
  
  public function get_info()
  {

  }

  public function rest_query($req)
  { 
    $this->response($this->resourcies->query($req->arg,$req->fields), 200);
  }

  public function rest_get($req)
  { 
    $this->response($this->resourcies->get($req->arg['id'],$req->fields), 200);
  }
  public function rest_create($req)
  {
    $result = $this->resourcies->create($req->arg);
    $result['status'] || $this->response($result['errors'],400);
    $this->response('',201);
  }
  public function rest_update($req)
  {
    $result = $this->resourcies->update($req->arg);
    $result['status'] || $this->response($result['errors'],400);
    $this->response('',204);
  }
  public function rest_remove($req){
    $this->resourcies->remove($req->arg['id']);
    $this->response('',204);
  }
}

