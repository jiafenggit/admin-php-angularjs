<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
class Admin extends REST_Controller {
  protected $_resourice =  array(
    'users' => 'admin/Admin_users_model',
    'roles' => 'admin/Admin_roles_model'
  );

  function __construct()
  {
    parent::__construct();
    $this->output->enable_profiler(TRUE);
    $this->load->database();
    
    $this->load->model('admin/Auth_model','auth');
    if($this->auth->run() === false)
    {
      $this->response('',401);
    }
    
    $this->load->model($this->_resourice[$this->router->method],'resourcies');
  }

  public function _remap($resource, $params = array())  
  { 
    $this->{'rest_'.$this->input->method()}();
  }

  public function rest_get()
  { 
    $key = $this->get('id');
    $field = $this->auth->Getter('field');
    if(isset($key))
    { 
      if($field !== '*')
      {
        $field = $this->resourcies->field_intersect($field,$this->resourcies->Getter('get_field'));
        $this->resourcies->Setter('get_field',$field);
      }
      $this->response($this->resourcies->get($key), 200);
    }
    if($field !== '*')
    {
      $field = $this->resourcies->field_intersect($field,$this->resourcies->Getter('query_field'));
      $this->resourcies->Setter('query_field',$field);
    }
    $this->response($this->resourcies->query($this->get()), 200);
  }

  public function rest_post()
  { 
    $result = $this->resourcies->create($this->post());
    $result['status'] || $this->response($result['errors'],400);
    $this->response('',201);
  }

  public function rest_put()
  {
    $field = $this->auth->Getter('field');
    if($field !== '*')
    {
      $field = $this->resourcies->field_intersect($field,$this->resourcies->Getter('update_field'));
      $this->resourcies->Setter('update_field',$field);
    }
    $result = $this->resourcies->update($this->put());
    $result['status'] || $this->response($result['errors'],400);
    $this->response('',204);
  }

  public function rest_delete()
  {
    $this->resourcies->remove($this->delete('id'));
    $this->response('',204);
  }
}

