<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Admin_user_model extends MY_Model {  

  protected $_rules = array();
  protected $_tbl = 'resource_controller';
  protected $_tbl_key = 'id';  
  protected $_query_field = 'id,controller,resource,tbl,tbl_key';
  protected $_get_field = 'id,controller,resource,tbl,tbl_key,query_field,get_field,create_field,update_field,status,utime,ctime';
  protected $_create_field = 'controller,resource,tbl,tbl_key,query_field,get_field,create_field,update_field';
  protected $_update_field = '';
  public function __construct()
  {  
    parent::__construct();    
  }
  
}