<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model {
  
  protected $_rules = array();
  protected $_tbl = NULL; 
  protected $_tbl_key = NULL;
  protected $_query_field = NULL;
  protected $_get_field = NULL;
  protected $_create_field = NULL;
  protected $_update_field = NULL;

	function __construct()
	{
	  parent::__construct();
	}
 
  public function query($query,$fields = NULL)
  {
    $fields = isset($fields) ? $this->field_intersect($fields,$this->_query_field) : $this->_query_field;
    $fields = isset($query['fields']) ? $this->field_intersect($query['fields'],$fields) : $fields;
    $sort = isset($query['sort']) ? $this->sort_string($query['sort']) : $this->_tbl_key . ' DESC';
    $sql = $this->db
      ->from($this->_tbl)
      ->group_start()
        ->where('status',1)
        ->or_where('status',0)
      ->group_end();
    if(isset($query['filter']))
    {
      $filter = $this->where($query['filter'],$fields);
      if($filter === false)
      {
        return array(
          'status' => false,
          'errors' => array('filter' => 'Your filter parameters has errots!')
        );
      }
      $sql = $sql->where($filter);
    } 
    $last_sql = clone($sql);
    $count = $last_sql->count_all_results();
    $this->output->set_header('X-Total-Count: '.$count);
    if(isset($query['limit'])){
      $offset = isset($query['offset']) ? $query['offset'] : 0;
      $sql = $sql->limit($query['limit'],$offset);
    }
    return $sql->select($fields)
      ->order_by($sort)
      ->get()
      ->result_array();
  }
 
  function get($key,$fields = NULL)
  {
    $fields = isset($fields) ? $this->field_intersect($fields,$this->_get_field) : $this->_get_field;
    $result = $this->db->from($this->_tbl)
      ->select($fields)
      ->where($this->_tbl_key, $key)
      ->get()
      ->result_array();
    $resource = count($result) === 0 ? NULL : $result[0];
    return $resource;
  }

  function create($resource)
  { 
    $valid = $this->validation($resource,'create');
    if($valid['status'] === true)
    {
      $resource = $valid['resource'];
      $resource['ctime'] = $resource['utime'] = time();
      $resource['status'] = 1;
      $this->db->insert($this->_tbl, $resource);
      return array('status' => true);
    }
    return $valid;
  }

  function update($resource,$fields = NULL)
  {
    $key = $resource['id'];
    unset($resource['id']);
    if(isset($fields))
    {
      $field = array_flip( explode(',',$fields) );
      $resource = array_intersect_key($resource,$field);
    }
    $valid = $this->validation($resource,'update');
    if($valid['status'] === true)
    {
      $resource = $valid['resource'];
      $resource['utime'] = time();
      $this->db
        ->where($this->_tbl_key,$key)
        ->update($this->_tbl, $resource);
      if( $this->db->affected_rows() < 1)
      {
        $valid = array(
          'status'=> false,
          'errors'=> array('更新失败') 
        );
      }
    }
    return $valid;
  }

  public function remove($key)
  {
    $data = array(
      'status' => -1,
      'utime' => time()
    );
    $this->db->where($this->tbl_key,$id)
         ->update($this->tbl, $data);
    return $this->db->affected_rows() > 0;
  }
  
  public  function get_info()
  {
    return array(
      'query' => $this->_query_field,
      'get' => $this->_get_field, 
      'create' => $this->_create_field, 
      'update' => $this->_update_field,
      'remove' => ''
    );
  }

  public function set_tbl($tbl)
  {
    $this->_tbl = $tbl;
  }

  public function set_tbl_key($key)
  {
    $this->_tbl_key = $key;
  }

  public function set_rules($input)
  {
    $rules  = array();
    $object = json_decode($input);
    foreach ($object as $k => $v) {
      $rules[$k] = array(
          'field' => $k,
          'label' => $v->label,
          'rules' => $v->rules,
          'errors' => (array)$v->errors
        );
    }
    $this->_rules = $rules;
  }

  public function set_query_field($field)
  {
    $this->_query_field = $field;
  }

  public function set_get_field($field)
  {
    $this->_get_field = $field;
  }

  public function set_create_field($field)
  {
    $this->_create_field = $field;
  }

  public function set_update_field($field)
  {
    $this->_update_field = $field;
  }

  protected function filter_string($input, $default)
  { 
    $fields = substr( preg_replace('/(.*?)(<=|>=|<|=|>)(.*?),/',',$1',$input.','), 1);
    $fields_arr = array_intersect( explode(',',$fields), explode(',',$default));
    $filter = '';
    $filter_arr = explode(',', substr( preg_replace('/(.*?)(<=|>=|<|=|>)(.*?),/',',$1 $2 \'$3\'',$input.','), 1));
    foreach ($fields_arr as $k => $v) {
      $filter .= ' AND ' . $filter_arr[$k];
    }
    return substr($filter,5);
  }

  protected function sort_string($input)
  {
    $sort_arr = explode(',',$input);
    $sort = '';
    foreach ($sort_arr as $k => $v) {
      if(strpos($k,'-') === 0)
      {
        $sort .= substr($k,1) . ' DESC';
        continue;
      }
      $sort .= $k . ' ASC';
    }
    return $sort;
  }

  protected function field_intersect($input,$default)
  {
    $arr = array_intersect( explode(',',$input) , explode(',',$default) );
    return join(',',$arr);
  }

  protected function validation($resource,$method)
  {
  	$this->load->library('form_validation');
    $field = array_flip( explode(',', $this->{'_'.$method.'_field'} ) );
    $data = array_intersect_key($resource,$field);
    if($method ==='update')
    {
      $rules = array_intersect_key($this->_rules,$data);
    }
    else
    {
      $rules = array_intersect_key($this->_rules,$field);
    }
  	$this->form_validation->set_data($data);
  	$this->form_validation->set_rules($rules);
  	if($this->form_validation->run() === false)
    {
      return array(
        'status' => false,
        'errors' => $this->form_validation->error_array()
      );
    }
  	return array(
      'status' => true,
      'resource' => $this->form_validation->validation_data
    );
  }
  
}