<?php
namespace app\admin\controller;
use gmars\rbac\Rbac;
use Request;
use Db;

class Permission extends Common
{
    public function list()
  {

     return $this	->fetch();
  }
     public function show()
    { 
      $rbac= new Rbac();
      $arr=$rbac->getPermissionCategory([['status', '=', 1]]);
      echo json_encode($arr);
    }
}