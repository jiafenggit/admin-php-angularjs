<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
class Admin extends REST_Controller {
    
  function __construct()
  {
    parent::__construct();
    $this->output->enable_profiler(TRUE);
    $this->load->database();

    // $this->load->model('Auth_model ','auth'); 
    // $this->auth->run() || $this->response('',401);
    // $this->load->model('admin/Admin_user_model','users');
    // $this->load->model('admin/Admin_role_model','roles');
    
  }

  public function _remap($resource, $params = array())  
  { 
    $this->request->arg = $this->{$this->request->method}();
    $this->request->controller = $this->router->class;
    $this->request->resource = $resource;
    var_dump($this->request);
    return;
    if(isset($this->{$resource}))
    {
      $this->resourcies = $this->{$resource};
    }
    else
    {
     $this->response('Resource Not Found',404);
    }
   
    $this->{'resourcies_'.$request['method']}($this->request);
  }

  public function resourcies_query($req)
  { 
    $resourcies = $this->resourcies->query($req->arg);
    $this->response($resourcies,200);
  }

  public function resourcies_get($req)
  { 
    $resource = $this->resourcies->get($req->arg['id']);
    $this->response($resource,200);
  }
  public function resourcies_create($req)
  {
    $resource = $req->arg;
    $result = $this->resourcies->create($resource);
    $result['status'] || $this->response($result['errors'],400);
    $this->response('',201);
  }
  public function resourcies_update($req)
  {
    $resource = $req->arg;
    $result = $this->resourcies->update($resource);
    $result['status'] || $this->response($result['errors'],400);
    $this->response('',204);
  }
  public function resourcies_remove($req){
    $this->resourcies->remove($req->arg['id']);
    $this->response('',204);
  }
}

