<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Resource_controller_model extends MY_Model {  

    public function __construct($config = NULL)
    {  
      if(isset($config))
      {
        foreach ($config as $k => $v) {
          $this->{'set_'.$k}($v);
        }
      }
      else
      {
        $this->_tbl = 'resource_controller';
        $this->_tbl_key = 'id';  
      }
      parent::__construct();    
    }
 
    public function exists($query)
    {  
      return $this->db
        ->where('controller', $query['controller'])
        ->where('resource', $query['resource'])
        ->count_all_results($this->_tbl) > 0;
    }
    public function get_method($req)
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

    public function get_resource($query,$method)
    { 
      $fields = 'tbl,tbl_key,rules,'. $method . '_field';
      $result = $this->db
        ->from($this->_tbl)
        ->select($fields)
        ->where('controller', $query['controller'])
        ->where('resource', $query['resource'])
        ->get()->result_array();
      $resource = count($result) === 0 ? NULL : $result[0];
      return new $this($resource);
    }
}