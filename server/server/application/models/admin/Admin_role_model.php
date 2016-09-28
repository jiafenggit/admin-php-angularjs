<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Admin_role_model extends MY_Model {  

  //校验数组
    private $rules = array(
            'id' => array(
                    'field'=>'id',
                    'label'=>'权级',
                    'rules'=>'trim|required|numeric',
                    'errors' => array('required' => '{field}不能为空', 'numeric' => '请输入正确的{field}')
            ),
            'label' => array(
                    'field'=>'label',
                    'label'=>'名称',
                    'rules'=>'trim|required',
                    'errors' => array('required' => '{field}未设置')
            ),
            'power' => array(
                    'field'=>'power',
                    'label'=>'权限',
                    'rules'=>'trim|required',
                    'errors' => array('required' => '{field}未设置')
            )
          );

    public function __construct()
    {  
      $this->tbl = 'admin_role';
      $this->tbl_key = 'id';  
      parent::__construct();    
    }

    function gets($query)
    {  
       $limit = isset($query['limit']) ? $query['limit'] : 0;
       $offset = isset($query['offset']) ? $query['offset'] : 0;
       $fields ='id,label,power,utime,ctime';
       if(isset($query['fields']))
       {
          $fields_arr = array_intersect( explode(',',$query['fields']) , explode(',',$fields) );
          $fields =  join(',',$fields_arr);
       }
       $sql = $this->db->from($this->tbl)
                  ->group_start()
                    ->where('status',1)
                    ->or_where('status',0)
                  ->group_end();
      $this->db->order_by($this->tbl_key,'ASC');
      $last_sql = clone($sql);
      $count = $sql->count_all_results(); 
      $res = $last_sql->select($fields)
                  ->limit($limit,$offset)
                  ->get()->result_array();
      return array(
        'data' => $res,
        'count' => $count
        );
    }
    function get($id,$query = array())
    {
      $fields ='id,label,power,utime,ctime';
      if(isset($query['fields']))
       {
          $fields_arr = array_intersect( explode(',',$query['fields']) , explode(',',$fields) );
          $fields =  join(',',$fields_arr);
       }
      $res = $this->db->from($this->tbl)
           ->select($fields)
           ->where($this->tbl_key,$id)
           ->get()->result_array();
      return $res[0];
    }


    function create($req)
    { 
        $this->load->library('form_validation');
        $data = @array(
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

    function update($req)
    { 
      $data = @array(
        'id' => $req['id'],
        'label'=> $req['label'],
        'power' => $req['power'],
        'status' => $req['status']
      );
      $valid = $this->validation('update',$data,$this->rules);
      $res = array(
        'code' => $valid['code'],
        'msg' => $valid['msg']
      );
      if($res['code'] == 1){
            $data = $valid['data'];
            $data['utime'] = time();
            $key = $data['id'];unset($data['id']);
            $this->db->where($this->tbl_key,$key)
                     ->update($this->tbl, $data);
            if($this->db->affected_rows() > 0)
            {
               $res['msg'] = '更新成功';
            }
            else
            {
              $res = array(
                'code' => 0,
                'msg' => '更新失败'
              );
            }
      }
      return $res;
    }

  }