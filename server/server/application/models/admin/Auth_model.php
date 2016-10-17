<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Auth_model extends CI_Model {  
  
  protected $_user = NULL;
  protected $_field = NULL; 

  public function __construct()
  {  
    parent::__construct();
    $this->load->model('admin/Key_model','token');    
  } 
  
  public function run($token)
  {
    if($token && $this->token->key_exists($token))
    {
      $result =  $this->token->get_key($token);
      $this->set_user($result->uid);
      return $this->is_pass();
    }
    return false;
  }

  public function is_pass()
  {
    $ctr = $this->router->class;
    $res = $this->router->method;
    $method = $this->input->method();
    $role = $this->get_user('role');
    $rules = $role['resource'];
    if($rules === '*')
    {
      $this->Setter('filed','*');
      return true;
    }
    $rules = json_decode($rules);

    if(isset($rules->$ctr->$res) && !empty(trim($rules->$ctr->$res->fields)) )
    {
      if(in_array($method,$rules->$ctr->$res->method) && isset($rules->$ctr->$res->fields))
      {
        $this->Setter('filed',$rules->$ctr->$res->fields);
        return  true;
      }
    } 
    return false;
  }

  public function Setter($key,$value)
  {
    if(isset($this->{'_'.$key}))
    {
      $this->{'_'.$key} = $value;
      return true;
    }
    return false;
  }

  public function Getter($key)
  {
    if(isset($this->{'_'.$key}))
    {
      return $this->{'_'.$key};
    }
    return NULL;
  }

  public function get_user($key = NULL)
  {
    if ($key === NULL)
    {
      return $this->_user;
    }
    return isset($this->_user[$key]) ? $this->_user[$key] : NULL;
  }

  public function set_user($key)
  {
    $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
    $user = $this->_get_user($key);
    $user->role = $this->_get_role($user->role);
    $this->_user = $user; 
  }
  
  protected function _get_role($key)
  {
    // if(!$role = $this->cache->get('resourcies_admin_roles_'.$key))
    // {
      $this->load->model('admin/Admin_roles_model','roles');
      $role = $this->roles->get($key,'id,label,router,resource');
      // $this->cache->save('resourcies_admin_roles_'.$key,$role,86400);
      return $role;
    // }
    // return $role;
  }

  protected function _get_user($key)
  { 
    // if(!$user = $this->cache->get('resourcies_admin_users_'.$key))
    // {
      $this->load->model('admin/Admin_users_model','users');
      $user = $this->users->get($key,'uid,username,name,role'); 
      // $this->cache->save('resourcies_admin_users_'.$key,$user,86400);
      return $user;
    // }
    // return $user;
  }
}