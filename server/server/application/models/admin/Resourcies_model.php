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
  protected $_query_field = 'id,controller,resource,tbl,template';
  protected $_get_field = 'id,controller,resource,tbl,template,utime,ctime';
  protected $_create_field = 'controller,resource,template';
  protected $_update_field = '';
  public function __construct()
  {  
    parent::__construct();    
  }

  // function create($resource)
  // { 
  //   $valid = $this->validation($resource,'create');
  //   if($valid['status'] === true)
  //   { 
  //     $this->load->model('admin/resource_templates_model','templates');
  //     $this->templates->get($)
  //       if(!$data = $this->rs->create_resource($resource['resource']))
  //       {
  //         return array(
  //           'status'=>false,
  //           'errors'=> array('创建失败')
  //         );
  //       }
  //       $resource['controller'] = $data['controller'];
  //       $resource['resource'] = $data['resource'];
  //       $resource['tbl'] = $data['tbl'];
  //     }
  //     else
  //     {
  //       return array(
  //         'status'=>false,
  //         'errors'=> array('控制器不存在')
  //       );
       
  //     }
  //     $resource['ctime'] = $resource['utime'] = time();
  //     $resource['status'] = 1;
  //     $this->db->insert($this->_tbl, $resource);
  //     return array('status' => true);
  //   }
  //   return $valid;
  // }

  // public function getResource($req)
  // { 
  //   $valid = $this->validation($resource,'create');
  //   if($valid['status'] === true)
  //   { 
  //     $resource = $valid['resource'];
  //     if(isset($this->_controller[$resource['controller']]))
  //     { 
  //       $result = $this->db
  //         ->select('tbl')
  //         ->where('controller',$resource['controller'])
  //         ->where('resource', $resource['resource'])
  //         ->get($this->_tbl)
  //         ->row();
  //       if(!$result)
  //       {
  //         return array(
  //           'status'=>false,
  //           'errors'=> array('资源不存在')
  //         );
  //       }
  //       $this->load->model($this->_controller[$resource['controller']],'rs');
  //       if(!$data = $this->rs->get_resource($result->tbl))
  //       {
  //         return array(
  //           'status'=>false,
  //           'errors'=> array('创建失败')
  //         );
  //       }
  //       return $data;      
  //     }
  //     else
  //     {
  //       return array(
  //         'status'=>false,
  //         'errors'=> array('控制器不存在')
  //       );
       
  //     }
  //   }
  //   return $valid;
  // }
}