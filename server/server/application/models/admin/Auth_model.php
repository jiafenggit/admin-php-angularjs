<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Auth_model extends CI_Model {  
  
  protected $_pass = FALSE;
  protected $_uid = NULL; 

  public function __construct()
  {  
    parent::__construct();
    $this->load->model('admin/Key_model','token');    
  } 
  
  public function index($token)
  {
    $key = $this->input->get_request_header('authorization', TRUE);
    if($key && $this->token->key_exists($token))
    {
      $this->_pass = true;
      $this->_uid = $this->db
        ->select('uid')
        ->where('key', $key)
        ->get($this->tbl)
        ->row()
        ->uid;
    }
    return $this->_pass;
  }

  public function get_rosource($role)
  {
    $this->db
      ->select('power')
      ->where('key', $key)
      ->get($this->tbl)
      ->row();
  }

  public function get_userinfo($key)
  {
    $result = 
    $user = $this->db
      ->select('uid,username,name,role')
      ->where('uid',$result->uid)
      ->where('status', 1)
      ->get('admin_info')
      ->row();
    if(isset($user))
    {
      return false;
    }
    $role = $this->db
      ->select('label,power,resource')
      ->where('id',$user->role)
      ->where('status', 1)
      ->get('admin_role')
      ->row();
    if(isset($user))
    {
      return false;
    }
    $user->role = $role->label;
    $user->power = json_decode($role->power);
    $user->resource = json_decode($role->resource);
    return $user;
  }


  public function a($req,$rules)
  {
    if(is_object($rules))
    {
      foreach ($rules as $controller => $value) {
        if($controller === $req['controller'] && is_object($value))
        {
          foreach ($value as $resource => $v) {
            if($resource === $req['resource'])
            {
              $methods = $this->role_array($v->role);
              if(in_array($req['method'], $methods))
              {
                return $v->field;
              }
            }
          }
        }
        return false
      }
      return false
    }
    return false;
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