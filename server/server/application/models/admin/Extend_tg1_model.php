<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Extend_tg1_model extends MY_Model {  

  protected $_rules = array(
    'name' => array(
      'field'=>'name',
      'label'=>'姓名',
      'rules'=>'trim|required',
      'errors' => array('required' => '{field}未设置')
    ),
    'phone' => array(
      'field'=>'phone',
      'label'=>'手机号',
      'rules'=>'trim|required',
      'errors' => array('required' => '{field}未设置')
    ),
    'email' => array(
      'field'=>'email',
      'label'=>'邮箱',
      'rules'=>'trim|required',
      'errors' => array('required' => '{field}未设置') 
    ),
    'qq' => array(
      'field'=>'qq',
      'label'=>'QQ',
      'rules'=>'trim|required',
      'errors' => array('required' => '{field}未设置')
    )
  );
  protected $_tbl = NULL;
  protected $_tbl_key = 'id';  
  protected $_query_field = 'id,name,phone,email,qq,';
  protected $_get_field = 'id,name,phone,email,qq,ip,utime,ctime';
  protected $_create_field = 'name,phone,email,qq';
  protected $_update_field = '';

  public function __construct($config = NULL)
  {  
    parent::__construct();
    if(isset($config))
    {
      $this->set_tbl =$config->tbl;
    }    
  }

  public function create_resource($resource)
  { 
    $data = array(
        'controller' => 'extend',
        'resource' => $resource,
        'tbl' => 'extend_'.$resource
    );
    if($this->db->table_exists($data['tbl']))
    {
      return false;
    }
    $this->load->dbforge();
    $fields = array(
      'id' => array(
          'type'=>'INT',
          'constraint'=>11,
          'unsigned'=>TRUE,
          'auto_increment' => TRUE
        ),
      'name' => array(
        'type'=>'VARCHAR',
        'constraint'=>20
      ),
      'phone' => array(
          'type'=>'VARCHAR',
          'constraint'=>12
      ),
      'email' => array(
          'type'=>'VARCHAR',
          'constraint'=>255
      ),
      'qq' => array(
          'type'=>'VARCHAR',
          'constraint'=>12
      ),
      'ip' => array(
          'type'=>'INT',
          'constraint'=>11
      ),
      'utime'=> array(
          'type'=>'INT',
         'constraint'=>11
      ),
      'ctime' => array(
          'type'=>'INT',
          'constraint'=>11
      )
    );
    $this->dbforge->add_field($fields);
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table($data['tbl'], TRUE);
    return $data;
  }

  public function get_resource($req)
  { 
      $fields = 'tbl,tbl_key,rules,'. $req['method'] . '_field';
      $result = $this->db
        ->from($this->_tbl)
        ->select($fields)
        ->where('controller', $req['controller'])
        ->where('resource', $req['resource'])
        ->get()->result_array();
      $resource = count($result) === 0 ? NULL : $result[0];
      return new $this($resource);
  }

}