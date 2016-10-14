<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Admin_users_model extends MY_Model {  

  protected $_rules = array(
    'username' => array(
      'field'=>'username',
      'label'=>'用户名',
      'rules'=>'trim|required',
      'errors' => array('required' => '{field}未设置')
    ),
    'name' => array(
      'field'=>'name',
      'label'=>'昵称',
      'rules'=>'trim|required',
      'errors' => array('required' => '{field}未设置')
    ),
    'password' => array(
      'field'=>'password',
      'label'=>'密码',
      'rules'=>'trim|required|min_length[5]|md5',
      'errors' => array('required' => '{field}未设置', 'min_length' => '{field}不能低于{param}位数') 
    ),
    'role' => array(
      'field'=>'role',
      'label'=>'权限组',
      'rules'=>'trim|required|numeric',
      'errors' => array('required' => '{field}未设置', 'numeric' => '请输入正确的{field}')
    )
  );
  protected $_tbl = 'admin_users';
  protected $_tbl_key = 'uid';  
  protected $_query_field = 'uid,username,name,role';
  protected $_get_field = 'uid,username,name,role,ip,utime,ctime';
  protected $_create_field = 'username,name,password,role';
  protected $_update_field = 'username,name,password,role';


  function create($resource)
  { 
    $valid = $this->validation($resource,'create');
    if($valid['status'] === true)
    {
      $resource = $valid['resource'];
      $hasuser = $this->db
        ->where('username',$resource['username'])
        ->count_all_results($this->_tbl) > 0;
      if($hasuser)
      {
        $valid = array(
          'status' => false,
          'error' => array('用户名已存在')
        );
      }
      $resource['ctime'] = $resource['utime'] = time();
      $data['ip'] =ip2long($this->input->ip_address());
      $resource['status'] = 1;
      $this->db->insert($this->_tbl, $resource);
      return array('status' => true);
    }
    return $valid;
  }

  public function auth($res)
  { 
    $this->_create_field = 'username,password';
    $valid = $this->validation($res,'create');
    if($valid['status'] === true)
    {  
      $resource = $valid['resource'];
      $results = $this->db->from($this->_tbl)
        ->select('uid,username,password,name,role')
        ->where('username',$resource['username'])
        ->where('status',1)
        ->get()
        ->result_array();
      if(count($results) < 1)
      {  
        $valid = array(
          'status' => false,
          'errors' => array('用户不存在')
        );
      }
      else if($results[0]['password'] !== $resource['password'])
      {
        $valid = array(
          'status' => false,
          'errors' => array('密码不正确')
        );
      }
      else
      { 
        $user = $results[0];
        unset($user['password']);
        $valid = array(
          'status' => true,
          'info' => $user
        );
      }
    }
    return $valid;
  }
}