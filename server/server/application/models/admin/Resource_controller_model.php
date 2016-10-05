<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Resource_controller_model extends MY_Model {  

    public function __construct()
    {  
      $this->tbl = 'resource_controller';
      $this->tbl_key = 'id';  
      parent::__construct();    
    }
 
    public function exists($query)
    {  
      return $this->db
        ->where('controller', $query['controller'])
        ->where('resource', $query['resource'])
        ->count_all_results($this->tbl) > 0;
    }

    public function get_resource($query)
    { 
      $fields ='tbl,tbl_key,rule,query_field,get_field,post_field,put_field';
      $this->db
        ->from($this->tbl)
        ->select($fields)
        ->where('controller', $query['controller'])
        ->where('resource', $query['resource'])
        ->get()->result_array();
    }
}