<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
class Admin extends REST_Controller {
    
  function __construct()
  {
    parent::__construct();
    $this->output->enable_profiler(TRUE);
    $this->load->database();

    $this->load->model('admin/Key_model','token'); 
    $token = $this->input->get_request_header('authorization', TRUE);
    ($token && $this->token->key_exists($token)) || $this->response('',401); 

    $this->load->model('admin/Resource_controller_model','RC');
    $this->load->model('admin/Admin_user_model','users');
      // $this->load->model('admin/Admin_role_model','roles');
    
  }

  public function _remap($resource, $params = array())  
  {  
    $this->request->arg = $this->{$this->request->method}();
    $request = array(
       'controller' => $this->router->class,
       'resource' => $resource,
       'method' => $this->RC->get_method($this->request)
      );
    if(isset($this->{$resource}))
    {
      $this->resourcies = $this->{$resource};
    }
    else
    {
      if($this->RC->exists($request) === false)
      {
       $this->response('Resource Not Found', REST_Controller::HTTP_NOT_FOUND);
      }
      $this->resourcies = $this->RC->get_resource($request)
    }
    $this->{'resourcies_'.$request['method']}($this->request);
  }

  public function resourcies_query($req)
  { 
    var_dump($this->resourcies);
    return;
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

