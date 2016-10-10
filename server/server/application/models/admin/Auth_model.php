<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Auth_model extends CI_Model {  
  
  protected $_pass = FALSE;
  protected $_uid = NULL; 

  public function __construct()
  {  
    parent::__construct();
    $this->load->model('admin/Key_model','token');    
  } 
  
  public function run($token)
  {
    $token = $this->input->get_request_header('authorization', TRUE);
    if($token && $this->token->key_exists($token))
    {
      return $this->token->get_key($token);
    }
    return $this->_pass;
  }

  public function get_role($key)
  {
    $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
    if(!$role = $this->cache->get('resourcies_admin_roles_'.$key))
    {
      $this->load->model('admin/Admin_role_model','roles');
      if(!$role = $this->roles->get($key))
      { 
        $this->cache->save('resourcies_admin_roles_'.$key,$role,86400)
        return $role;
      }
      return false;
    }
    return $role;
  }

  public function get_user($key)
  {
    $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
    if(!$user = $this->cache->get('resourcies_admin_users_'.$key))
    {
      $this->load->model('admin/Admin_user_model','users');
      if(!$user = $this->users->get($key))
      { 
        $this->cache->save('resourcies_admin_users_'.$key,$user,86400)
        return $user;
      }
      return false;
    }
    return $user;
  }


  public function is_pass($req,$rules)
  {
    if($rules === '*')
    {
      return array(
        'method' => $this->get_method($req);
        'filed' => '*';
      );
    }
    else
    {
      foreach ($rules as $controller => $value) {
        if($controller === $req->controller && is_object($value))
        {
          foreach ($value as $resource => $v) {
            if($resource === $req->resource)
            {
              $methods = $this->role_array($v->role);
              $method = $this->get_method($req);
              if(in_array($method, $methods))
              {
                return array(
                  'method' => $method,
                  'filed' => isset($V->filed) ? $v->filed : NULL;
                  );
              }
            }
          }
        }
        return false
      }
      return false
  }

  protected function get_method($req)
  {
    switch ($req->method) {
      case 'get':
        $method = isset($req->arg['id']) ? 'get' : 'query';
        break;
      case 'post':
        $method = 'create';
        break;
      case 'put':
        $method = 'update';
        break;
      case 'delete':
        $method = 'remove';
        break;   
    }
    return $method;
  }
  protected function role_array($number)
  {
    switch ($number) {
      case '0':
        return array();
        break;
      case '1':
        return array('query','get');
        break;
      case '2':
        return array('create','update');
        break;
      case '3':
        return array('remove');
        break;
      case '4':
        return array('query','get','create','update');
        break;
      case '5':
        return array('query','get','remove');
        break;
      case '6':
        return array('create','update','remove');
        break;
      case '7':
        return array('query','get','create','update','remove');
        break;
    }
  }
}