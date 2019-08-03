<?php
namespace app\admin\controller;
use think\Controller;
use think\facade\Session;
use think\facade\Request;
use think\Db;
use gmars\rbac\Rbac;
use Redis;
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

       public function index()
    {
        $redis = new Redis();
        $redis->connect('127.0.0.1',6379);
        // incr() 对指定的key的值加1
        // decr()对指定的key的值减1
        // incrBy() 将第二个参数的值加到key的值上
        // decrBy() 将第二个参数的值加到key的值上
        // incrByFloat() 自增一个浮点类型的值
        echo $redis->incr('shenzhen')."<br/>";//1
        echo $redis->incr('shenzhen')."<br/>";//2
        echo $redis->incrBy('shenzhen',6)."<br/>";//8
        echo $redis->decr('shenzhen')."<br/>";//7
        echo $redis->decr('shenzhen')."<br/>";//6
        echo $redis->decrBY('shenzhen',3)."<br>"; //3
        echo $redis->incrByFloat('shenzhen',0.88);//3.88

	}
}
