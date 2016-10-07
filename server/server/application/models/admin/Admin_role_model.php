<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Admin_role_model extends MY_Model {  

  protected $_rules = array(
    'label' => array(
      'field'=>'label',
      'label'=>'标签',
      'rules'=>'trim|required',
      'errors' => array('required' => '{field}未设置')
    ),
    'power' => array(
      'field'=>'role',
      'label'=>'权限',
      'rules'=>'trim|required',
      'errors' => array('required' => '{field}未设置')
    ),
   'status' => array(
      'field'=>'status',
      'label'=>'状态',
      'rules'=>'trim|required|numeric',
      'errors' => array('required' => '{field}未设置', 'numeric' => '{field}格式不正确')
    )
  );
  protected $_tbl = 'admin_role';
  protected $_tbl_key = 'id';  
  protected $_query_field = 'id,label,power,utime,ctime';
  protected $_get_field = 'id,label,power,utime,ctime';
  protected $_create_field = 'label,power';
  protected $_update_field = 'label,power,status';

  public function __construct()
  {  
    parent::__construct();    
  }
}