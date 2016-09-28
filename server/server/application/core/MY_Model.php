<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_model extends CI_Model {

	function __construct()
	{
	    parent::__construct();
	}
   
    function  validation($method,$data,$rules)
    {
		$this->load->library('form_validation');
		$valid = $this->valid($method,$data,$rules);
		$this->form_validation->set_data($valid['data']);
		$this->form_validation->set_rules($valid['rules']);
		$res['code'] = $this->form_validation->run()?1:0;
		$res['msg'] = $this->form_validation->error_array();
		$res['data'] = $this->form_validation->validation_data;
		return $res;
    }

     //删除某条记录
    public function remove($id)
    {
	    $data = array(
				'status'=>'-1',
				'utime'=>time()
			);
		$this->db->where($this->tbl_key,$id)
				 ->update($this->tbl, $data);
		if($this->db->affected_rows() > 0)
		{
			$res = array(
        'code' => 1,
        'msg' => '删除成功'
      );
		}
		else
		{
			$res = array(
				'code' => 0,
				'msg' => '删除失败'
			);
		}
		return $res;
    }

    //判断记录是否存在
    public function has($where)
    {
      $results = $this->db->from($this->tbl)
	                      ->select($this->tbl_key)
	                      ->where($where)
	                      ->get()->result_array();
      return count($results) > 0 ?true:false;
    }
    
    //取条数
    public function count()
    {
      $res = $this->db->from($this->tbl)
        ->group_start()
            ->where('status','1')
            ->or_where('status','0')
        ->group_end()
        ->count_all_results();
       return $res;
    }
    //获取有效的数据和校验规则
    protected function  valid($method,$data, $rules)
    {
      $valid = array(
         'data' => array(),
         'rules' => array()
        );
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