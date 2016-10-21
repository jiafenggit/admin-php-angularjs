<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Resourcies_model extends MY_Model {  
  
  protected $_rules = array(
    'controller' => array(
      'field'=>'controller',
      'label'=>'控制器',
      'rules'=>'trim|required',
      'errors' => array('required' => '{field}未设置')
    ),
    'resource' => array(
      'field'=>'resource',
      'label'=>'资源',
      'rules'=>'trim|required',
      'errors' => array('required' => '{field}未设置')
    )
  );
  protected $_tbl = 'resourcies';
  protected $_tbl_key = 'id';  
  protected $_query_field = 'id,controller,resource,tbl,label,template,xfield,method';
  protected $_get_field = 'id,controller,resource,tbl,template,utime,ctime';
  protected $_create_field = 'controller,resource,template';
  protected $_update_field = '';
  public function __construct()
  {  
    parent::__construct();    
  }
  
  function create($data)
  { 
    $field = $this->_create_field;
    $valid = $this->validation($data, $field,$strict = true);
    if($valid['status'] === true)
    {
      $resource = $valid['data'];
      $this->load->model($resource['template'],'template');
      if(!$tbl = $this->template->TableCreate($resource['controller'],$resource['resource']))
      {
        return array('status' => false,'error' => array('表已存在'));
      }
      $resource['tbl']  = $tbl;
      $resource['ctime'] = $resource['utime'] = time();
      $resource['status'] = 1;
      $this->db->insert($this->_tbl,$resource);
      return array('status' => true);
    }
    return $valid;
  }

  public function GetResource($controller,$resource)
  {
    $config =  $this->_GetResourceConfig(array('controller' => $controller,'resource' => $resource));
    if($config)
    {
      $this->load->model($config['template'],template);
      return $this->template->GetResource($config['tbl']);
    }
    return false;
  }

  public function _GetResourceConfig($where)
  { 
    $this->db->from($this->_tbl);
    $this->db->select('id,controller,resource,tbl,template');
    if(is_array($where))
    {
      foreach ($where as $k => $v) {
        $this->db->where($k,$v);
      }
    }
    $this->db->where($where);
    $this->db->where('status','1');
    if($res = $this->db->get()->row())
    {
      return $res;
    };
    return false;
  }
}