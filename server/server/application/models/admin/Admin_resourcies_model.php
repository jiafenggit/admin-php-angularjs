<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Admin_resourcies_model extends MY_Model {  
  
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
  protected $_tbl = 'resource_controller';
  protected $_tbl_key = 'id';  
  protected $_query_field = 'id,controller,resource,tbl,tbl_key';
  protected $_get_field = 'id,controller,resource';
  protected $_create_field = 'controller,resource';
  protected $_update_field = '';
  protected $_controller = array(
    'extend' => 'admin/Extend_tg1_model',
  );
  public function __construct()
  {  
    parent::__construct();    
  }

  function create($resource)
  { 
    $valid = $this->validation($resource,'create');
    if($valid['status'] === true)
    { 
      $resource = $valid['resource'];
      if(isset($this->_controller[$resource['controller']]))
      {
        $this->load->model('admin/Extend_tg1_model','rs');
        if(!$data = $this->rs->create_resource($resource['resource']))
        {
          return array(
            'status'=>false,
            'errors'=> array('创建失败')
          );
        }
        $valid['controller'] = $data['controller'];
        $valid['resource'] = $data['resource'];
        $valid['tbl'] = $data['tbl'];
      }
      else
      {
        return array(
          'status'=>false,
          'errors'=> array('控制器不存在')
        );
       
      }
      $resource['ctime'] = $resource['utime'] = time();
      $resource['status'] = 1;
      $this->db->insert($this->_tbl, $resource);
      return array('status' => true);
    }
    return $valid;
  }
}