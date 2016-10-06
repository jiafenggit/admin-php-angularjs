<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
class Admin extends REST_Controller {
    
  function __construct()
  {
    parent::__construct();
    $this->output->enable_profiler(TRUE);
    $this->load->database();
      // $this->load->model('admin/Key_model','token');
      // $token = $this->input->get_request_header('authorization', TRUE);
      // $bool = $token && $this->token->key_exists($token);
      // if(!$bool)
      // {
      //   $this->response('', REST_Controller::HTTP_UNAUTHORIZED);
      // }
    $this->load->model('admin/Admin_user_model','users');
      // $this->load->model('admin/Admin_role_model','roles');
    $this->load->model('admin/Resource_controller_model','RC');
  }

  public function _remap($resource, $params = array())  
  {  
     $this->request->arg = $this->{$this->request->method}();
     $query = array(
       'controller' => $this->router->class,
       'resource' => $resource
      );
     if($this->RC->exists($query) === false)
     {
        $this->response('Resource Not Found', REST_Controller::HTTP_NOT_FOUND);
     }
     $method = $this->RC->get_method($this->request);
     $this->resourcies = isset($this->{$resource}) ? $this->{$resource} : $this->RC->get_resource($query,$method);
     $this->{'resourcies_'.$method}($this->request);
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

  public function role_get()
  { 
    $query = array(
      'limit' => 100,
      'fields' => 'id,label'
      );
    $result = $this->roles->gets($query);
    $res = $result['data'];
    $this->response($res, REST_Controller::HTTP_OK);
  }

  public function users_get()
  { 
    $id = $this->get('id');
    if(isset($id))
    {
      $res = $this->users->get($id);
    }
    else
    {
      $query = $this->get();
      $result = $this->users->gets($query);
      $res = $result['data'];
      $this->output->set_header('X-Total-Count: '.$result['count']);
    }
    $this->response($res, 200);
  }
  public function users_post()
  {
     $req = $this->post();
     $res = $this->users->create($req);
     if($res['code']  == 1)
     {
        $this->response('', REST_Controller::HTTP_CREATED);
     }
     else
     {
       $this->response($res['msg'], REST_Controller::HTTP_BAD_REQUEST);
     }
  }
  public function users_put()
  {
     $req = $this->put();
     $res = $this->users->update($req);
     if($res['code']  == 1){
        $this->response('', REST_Controller::HTTP_NO_CONTENT);
     }
     else
     {
       $this->response($res['msg'], REST_Controller::HTTP_BAD_REQUEST);
     }
  }
  public function users_delete()
  {
    $id = $this->get('id');
    $res = $this->users->remove($id);
    $this->response($res, REST_Controller::HTTP_OK);
  }

  public function roles_get()
  {
    $id = $this->get('id');
    if(isset($id))
    {
      $res = $this->roles->get($id);
    }
    else
    {
        $query = $this->get();
        $result = $this->roles->gets($query);
        $res = $result['data'];
        $this->output->set_header('X-Total-Count: '.$result['count']);
    }
    $this->response($res, REST_Controller::HTTP_OK);
  }

  public function roles_put()
  {
     $req = $this->put();
     $res = $this->roles->update($req);
     if($res['code']  == 1){
        $this->response('', REST_Controller::HTTP_NO_CONTENT);
     }
     else
     {
       $this->response($res['msg'], REST_Controller::HTTP_BAD_REQUEST);
     }
  }
  public function roles_post()
  {
     $req = $this->post();
     $res = $this->roles->create($req);
     if($res['code']  == 1)
     {
        $this->response('', REST_Controller::HTTP_CREATED);
     }
     else
     {

       $this->response($res['msg'], REST_Controller::HTTP_BAD_REQUEST);
     }
  }
  public function roles_delete()
  {
    $id = $this->get('id');
    $res = $this->roles->remove($id);
    $this->response('', REST_Controller::HTTP_NO_CONTENT);
  }
}

