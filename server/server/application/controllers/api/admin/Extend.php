<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
class Extend extends REST_Controller {
    
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
    $this->request->arg = $this->{$this->request->method}();
    $this->request->controller = $this->router->class;
    $this->request->resource = $resource;
    $role = $this->auth->get_user('role');
    $rules = $role['resource'];
    if(!$result = $this->auth->is_pass($this->request,$role['resource']))
    {
      $this->response('',401);
    } 
    $this->request->fields = $result['fields'];
    $this->load->model('admin/Admin_resourcies_model','RsCtr');
    $request = array(
    	'controller' => $this->request->controller,
    	'resource' => $this->request->resource
    	);
    $model = $this->RsCtr->new_resource($request);
    if(is_array($model) && isset($model['status']))
    {
       $this->response('',401);
    }
    $this->resourcies = $model;
    $this->{'resourcies_'.$result['method']}($this->request);
  }

  public function resourcies_query($req)
  { 
    if($req->fields !== '*' )
    {
      $resourcies = $this->resourcies->query($req->arg,$req->fields);
    }
    else
    {
      $resourcies = $this->resourcies->query($req->arg);
    }
    $this->response($resourcies,200);
  }

  public function resourcies_get($req)
  { 
    if($req->fields !== '*')
    {
      $resource = $this->resourcies->get($req->arg['id'],$req->fields);
    }
    else
    {
      $resource = $this->resourcies->get($req->arg['id']);
    }
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
    if($req->fields !== '*')
    {
      $result = $this->resourcies->update($resource,$req->fields);
    }
    else
    {
      $result = $this->resourcies->update($resource);
    }
    $result['status'] || $this->response($result['errors'],400);
    $this->response('',204);
  }
  public function resourcies_remove($req){
    $this->resourcies->remove($req->arg['id']);
    $this->response('',204);
  }
}
