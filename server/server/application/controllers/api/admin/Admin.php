<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
class Admin extends REST_Controller {
    
    function __construct()
    {
      parent::__construct();
      $this->output->enable_profiler(TRUE);
      // $this->load->database();
      // $this->load->model('admin/Key_model','token');
      // $token = $this->input->get_request_header('authorization', TRUE);
      // $bool = $token && $this->token->key_exists($token);
      // if(!$bool)
      // {
      //   $this->response('', REST_Controller::HTTP_UNAUTHORIZED);
      // }
      // $this->load->model('admin/Admin_user_model','users');
      // $this->load->model('admin/Admin_role_model','roles');
      $this->load->model('MY_Model');
      $c = $this->MY_Model->create();
      $this->{$c['resource'].'_'.$c['method']} = function(){
        echo '233';
      }; 
  }
  public function _remap($method, $params = array())  
  {  
    var_dump($this);   
  }

  public function info_get()
  { 
    var_dump($this);
    // $token = $this->input->get_request_header('authorization', TRUE);
    // $uid = $this->token->get_key($token)->uid;
    // $info = $this->users->get($uid,array('fields' =>'uid,username,name,role'));
    // $role = $this->roles->get($info['role'],array('fields' =>'label,power'));
    // $info['label'] = $role['label'];
    // $info['power'] = $role['power'];
    // $this->response($info, REST_Controller::HTTP_OK);
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
    $this->response($res, REST_Controller::HTTP_OK);
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

