<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Auth_model extends CI_Model {  

  public function __construct()
  {  
    parent::__construct();    
  }

  public function a($req,$rules)
  {
    if(is_object($rules))
    {
      foreach ($rules as $controller => $value) {
        if($controller === $req['controller'] && is_object($value))
        {
          foreach ($value as $resource => $v) {
            if($resource === $req['resource'])
            {
              $methods = $this->role_array($v->role);
              if(in_array($req['method'], $methods))
              {
                return $v->field;
              }
            }
          }
        }
        return false
      }
      return false
    }
    return false;
  }

  protected function role_array($number)
  {
    switch ($number) {
      case '0':
        return array();
        break;
      case '1':
        return array('query','get');
        break;
      case '2':
        return array('create','update');
        break;
      case '3':
        return array('remove');
        break;
      case '4':
        return array('query','get','create','update');
        break;
      case '5':
        return array('query','get','remove');
        break;
      case '6':
        return array('create','update','remove');
        break;
      case '7':
        return array('query','get','create','update','remove');
        break;
    }
  }
}