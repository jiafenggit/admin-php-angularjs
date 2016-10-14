<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Admin_roles_model extends MY_Model {  

  protected $_rules = array(
    'label' => array(
      'field'=>'label',
      'label'=>'标签',
      'rules'=>'trim|required',
      'errors' => array('required' => '{field}未设置')
    ),
    'router' => array(
      'field'=>'router',
      'label'=>'路由',
      'rules'=>'trim|required',
      'errors' => array('required' => '{field}未设置')
    ),
    'resource' => array(
      'field'=>'resource',
      'label'=>'资源',
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
  protected $_tbl = 'admin_roles';
  protected $_tbl_key = 'id';  
  protected $_query_field = 'id,label,utime,ctime';
  protected $_get_field = 'id,label,router,resource,utime,ctime';
  protected $_create_field = 'label,router,resource';
  protected $_update_field = 'label,router,resource';

}