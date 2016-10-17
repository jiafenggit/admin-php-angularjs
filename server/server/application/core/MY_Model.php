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

	public function __construct($config = NULL)
  {  
    parent::__construct();
    if(isset($config))
    {
      $this->Setter('tbl',$config['tbl']);
    }    
  }
 
  public function query($query = array())
  {

    $fields = isset($query['fields']) ? $this->field_intersect($query['fields'],$this->_query_field) : $this->_query_field;
    $sort = isset($query['sort']) ? $this->sort_string($query['sort']) : $this->_tbl_key . ' DESC';
    $sql = $this->db
      ->from($this->_tbl)
      ->group_start()
        ->where('status',1)
        ->or_where('status',0)
      ->group_end();
    if(isset($query['filter']))
    {
      $filter = $this->filter_string($query['filter'],$fields);
      if($filter === false)
      {
        return array('status' => false,'errors' => array('filter' => 'Your filter parameters has errots!'));
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
 
  function get($key)
  {
    $this->db->from($this->_tbl);
    $this->db->select($this->_get_field);
    $this->db->where($this->_tbl_key,$key);
    return $this->db->get()->row();
  }

  function create($data)
  { 
    $field = $this->_create_field;
    $valid = $this->validation($data, $field,$strict = true);
    if($valid['status'] === true)
    {
      $resource = $valid['data'];
      $resource['ctime'] = $resource['utime'] = time();
      $resource['status'] = 1;
      $this->db->insert($this->_tbl,$resource);
      return array('status' => true);
    }
    return $valid;
  }

  function update($data)
  { 
    $field = $this->_update_field;
    $valid = $this->validation($data, $field,$strict = false);
    if($valid['status'] === true)
    {
      $resource = $valid['data'];
      $resource['utime'] = time();
      $this->db
        ->where($this->_tbl_key,$data['id'])
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
  public function field_intersect($input,$default)
  {
    $arr = array_intersect( explode(',',$input) , explode(',',$default) );
    return join(',',$arr);
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
  
  protected function validation($data, $field,$strict = true)
  {
  	$this->load->library('form_validation');

    $field = array_flip(explode(',', $field));
    $data = array_intersect_key($data,$field);
    $rules = $strict ? array_intersect_key($this->_rules,$field) : array_intersect_key($this->_rules,$data);

  	$this->form_validation->set_data($data);
  	$this->form_validation->set_rules($rules);
  	if($this->form_validation->run() === false)
    {
      return array('status' => false, 'errors' => $this->form_validation->error_array());
    }
  	return array('status' => true,'data' => $this->form_validation->validation_data);
  }
  
}