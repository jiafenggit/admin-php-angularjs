<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
/*
   
*/
class Key_model extends CI_Model {  

    public function __construct()
    {  
      $this->tbl = 'token_key';  
      $this->tbl_key = 'id';  
      parent::__construct();    
    }

   public function generate_key()
   {
      do
      {
          // Generate a random salt
          $salt = base_convert(bin2hex($this->security->get_random_bytes(64)), 16, 36);

          // If an error occurred, then fall back to the previous method
          if ($salt === FALSE)
          {
              $salt = hash('sha256', time() . mt_rand());
          }

          $new_key = substr($salt, 0, 40);
      }
      while ($this->key_exists($new_key));

      return $new_key;
    }
    public function get_user($key)
      {
        $result = $this->db
          ->select('uid')
          ->where('key', $key)
          ->get($this->tbl)
          ->row();
        $user = $this->db
          ->select('uid,username,name,role')
          ->where('uid',$result->uid)
          ->where('status', 1)
          ->get('admin_info')
          ->row();
        if(isset($user))
        {
          return false;
        }
        $role = $this->db
          ->select('label,power,resource')
          ->where('id',$user->role)
          ->where('status', 1)
          ->get('admin_role')
          ->row();
        if(isset($user))
        {
          return false;
        }
        $user->role = $role->label;
        $user->power = json_decode($role->power);
        $user->resource = json_decode($role->resource);
        return $user;
      }

    public function key_exists($key)
    {
        return $this->db
            ->where('key', $key)
            ->count_all_results($this->tbl) > 0;
    }

    public function insert_key($req)
    {
        $data = array(
           'key' =>$req['key'],
           'uid' => $req['uid']
          );
        $data['ctime'] = $data['utime'] = function_exists('now') ? now() : time();

        return $this->db
            ->set($data)
            ->insert($this->tbl);
    }
}