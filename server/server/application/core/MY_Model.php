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
 
  public function query($query)
  {
    $limit = isset($query['limit']) ? $query['limit'] : 0;
    $offset = isset($query['offset']) ? $query['offset'] : 0;
    $fields = isset($query['fields']) ? $this->field_intersect($query['fields'],$this->$_query_field) : $this->$_query_field;
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
    $sql = $sql->select($fields)
      ->limit($limit,$offset)
      ->order_by($sort);
    return array(
        'count' => $count,
        'resourcies' => $sql->get()->result_array()
    );
  }
 
  function get($key)
  {
    $result = $this->db->from($this->_tbl)
      ->select($this->_get_field)
      ->where($this->_tbl_key, $key)
      ->get()
      ->result_array();
    $resource = count($result) === 0 ? NULL : result[0];
    return $resource;
  }

  function create($resource)
  { 
    $data = array(
     'label' => $req['label'],
    'power' => $req['power'],
    );
        $valid = $this->validation('create',$data,$this->rules);
        $res = array(
          'code' => $valid['code'],
          'msg' => $valid['msg']
        );

        if($res['code'] == 1){
              $data = $valid['data'];
              $data['ctime'] = $data['utime'] = time();
              $data['status'] = 1;
              $this->db->insert($this->tbl, $data);
              $res = array(
                'code' => '1',
                'msg' => '创建成功'
              );
        }
      return $res;
    }

  public function remove($id)
  {
    $data = array(
      'status' => -1,
      'utime' => time()
    );
    $this->db->where($this->tbl_key,$id)
         ->update($this->tbl, $data);
    return $this->db->affected_rows() > 0;
  }
  
  public function set_rules($rules)
  {
    $this->_rules = $rules;
  }

  public function set_tbl($tbl)
  {
    $this->_tbl = $tbl;
  }

  public function set_tbl_key($key)
  {
    $this->_tbl_key = $key;
  }

  public function set_query_field($field)
  {
    $$this->_query_field = $field;
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

  protected function validation($method, $data, $rules)
  {
  	$this->load->library('form_validation');
  	$valid = $this->_valid($method,$data,$rules);
  	$this->form_validation->set_data($valid['data']);
  	$this->form_validation->set_rules($valid['rules']);
  	$res['code'] = $this->form_validation->run()?1:0;
  	$res['msg'] = $this->form_validation->error_array();
  	$res['data'] = $this->form_validation->validation_data;
  	return $res;
  }
  
  protected function _valid($method, $data, $rules)
  {
    $valid = array('data' => array(),'rules' => array());
    foreach ($data as $key => $value) {
      if($method === 'update')
      { 
         if($value === NULL)
         {
           continue;
         }
      }
      $valid['data'][$key] = $value;
      $valid['rules'][$key] = @$rules[$key];
    }
    return $valid;
  } 
}