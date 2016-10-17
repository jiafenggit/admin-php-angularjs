<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Resource_templates_model extends MY_Model {  
  
  protected $_rules = array(
    'label' => array(
      'field'=>'label',
      'label'=>'标题',
      'rules'=>'trim|required',
      'errors' => array('required' => '{field}未设置')
    ),
    'template' => array(
      'field'=>'template',
      'label'=>'模板',
      'rules'=>'trim|required',
      'errors' => array('required' => '{field}未设置')
    )
  );
  protected $_tbl = 'resourcies_template';
  protected $_tbl_key = 'id';  
  protected $_query_field = 'id,lable,template,utime,ctime';
  protected $_get_field = 'id,lable,template,utime,ctime';
  protected $_create_field = '';
  protected $_update_field = 'lable';
}