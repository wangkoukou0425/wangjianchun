<?php
namespace app\admin\controller;
use think\Controller;
use think\facade\Session;
use think\facade\Request;
use think\Db;
use gmars\rbac\Rbac;
class Common extends Controller

{
    public function initialize()
    {
       $user=Session::get('user');
       if($user){
        $this->assign('user',$user);
       }else{
       
        $this->redirect('login/login');
       }

       $module=Request::module();
       $class=Request::controller();
       $action=Request::action();

       $arr_class=['Permission','Permissioncate','Role','User'];
       $arr_action=['show','del_more','addaction','updateaction','del'];
       if(in_array($class,$arr_class)){
        if(in_array($action,$arr_action)){
          $str="$module/$class/$action";
          $str=strtolower($str);
          $rbac=new Rbac;
          $bool=$rbac->can($str);
          // echo $str;
          // var_dump($bool);
          // die;
          if ($bool==false) {
            header("Content-Type:application/json");
            $arr=['code'=>'10001','status'=>'error','data'=>'没有权限'];
            echo $json=json_encode($arr); 
            die;
          }
        }
       }
    }
    
    public function commonToken()
    {
        $token = $this->request->token('__token__', 'sha1');
        $arr=['token'=>$token];
        echo $json=json_encode($arr); 
    }
	}
