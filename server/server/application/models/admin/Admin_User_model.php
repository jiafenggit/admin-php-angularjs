<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Admin_user_model extends MY_Model {  

  //校验数组
    private $rules = array(
            'uid' => array(
                    'field'=>'uid',
                    'label'=>'用户编号',
                    'rules'=>'trim|required|numeric',
                    'errors' => array('required' => '{field}不能为空', 'numeric' => '请输入正确的{field}')
            ),
            'username' => array(
                    'field'=>'username',
                    'label'=>'用户名',
                    'rules'=>'trim|required',
                    'errors' => array('required' => '{field}未设置')
            ),
            'name' => array(
                    'field'=>'name',
                    'label'=>'昵称',
                    'rules'=>'trim|required',
                    'errors' => array('required' => '{field}不能为空')
            ),
            'password' => array(
                    'field'=>'password',
                    'label'=>'密码',
                    'rules'=>'trim|required|min_length[5]|md5',
                    'errors' => array('required' => '{field}不能为空', 'min_length' => '{field}不能低于{param}位数') 
                    ),
          'role' => array(
                    'field'=>'role',
                    'label'=>'权限组',
                    'rules'=>'trim|required|numeric',
                    'errors' => array('required' => '{field}未设置', 'numeric' => '请输入正确的{field}')
            )
          );

    public function __construct()
    {  
      $this->tbl = 'admin_info';
      $this->tbl_key = 'uid';  
      parent::__construct();    
    }

    function gets($query)
    {
       $limit = isset($query['limit']) ? $query['limit'] : 0;
       $offset = isset($query['offset']) ? $query['offset'] : 0;
       $fields ='uid,username,name,role';
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
      $fields ='uid,username,name,role,ip,utime,ctime';
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
             'username' => $req['username'],
             'name' => $req['name'],
             'password' => $req['password'],
             'role' => $req['role']
            );
        $valid = $this->validation('create',$data,$this->rules);
        $res = array(
          'code' => $valid['code'],
          'msg' => $valid['msg']
        );

        if($res['code'] == 1){
            $data = $valid['data'];
            if($this->has(array('username' => $data['username'])) ){
              $res = array(
              'code' => 0,
              'msg' =>  array('username' => '用户名已存在')
              );
            }
            else
            {
              $data['ctime'] = $data['utime'] = time();
              $data['status'] = 1;
              $data['ip'] =ip2long($this->input->ip_address());
              $this->db->insert($this->tbl, $data);
              $res = array(
                'code' => '1',
                'msg' => '创建成功'
              );
            }
        }
      return $res;
    }

    function update($req)
    { 
      $data = @array(
        'uid' => $req['uid'],
        'name'=> $req['name'],
        'password' => $req['password'],
        'role' => $req['role']
      );
      $valid = $this->validation('update',$data,$this->rules);
      $res = array(
        'code' => $valid['code'],
        'msg' => $valid['msg']
      );
      if($res['code'] == 1){
            $data = $valid['data'];
            $data['utime'] = time();
            $key = $data['uid'];unset($data['uid']);
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

    public function auth($user)
    {   
        $data = array(
             'username' => $user['username'],
             'password' => $user['password']
            );
        $valid = $this->validation('create',$data,$this->rules);
        $res = array(
          'code' => $valid['code'],
          'msg' => $valid['msg']
        );
        if($res['code'] == 1)
        {   
            $data = $valid['data']; 
            $results = $this->db->from($this->tbl)
                        ->select('uid,username,password,name,role')
                        ->where('username',$data['username'])
                        ->where('status',1)
                        ->get()->result_array();
            if(count($results) > 0)
            {  
                $result = $results[0];
                if(count($results)>1)
                { 
                  $res = array(
                    'code' => 0,
                    'msg' => array('*'=>'数据异常')
                  );
                }
                else if($result['password'] != $data['password'])
                {   
                  $res = array(
                    'code' => 0,
                    'msg' => array('password'=>'密码不正确')
                  ); 
                }
                else
                {
                  $res = array(
                    'code' => 1,
                    'msg' => '认证通过',
                    'data'=> array(
                        'uid'=>$result['uid'],
                        'username'=>$result['username'],
                        'name'=>$result['name'],
                        'role'=>$result['role'],
                      )
                  ); 
                }
              
            }
            else
            {  
              $res = array(
                    'code' => 0,
                    'msg' =>  array('username'=>'用户名不存在')
               ); 
            }
        }
        return $res;
    }
  }