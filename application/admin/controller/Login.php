<?php
namespace app\admin\controller;
use think\Controller;
use Request;
use Db;
use think\captcha\Captcha;
use think\facade\Session;
use gmars\rbac\Rbac;
class Login extends Controller
{
    public function login()
    {
    	return $this->fetch();
    }
    public function loginAction(){
    	$verify=Request::post('verify');
    	$user=Request::post('user');
    	$password=Request::post('password');
    	$captcha = new Captcha();
    	if( !$captcha->check($verify))
		{
			$arr=['code'=>'0','status'=>'error','message'=>'验证码错误'];
			echo json_encode($arr);
			die;
		}else{
			$where=['user_name'=>$user,'password'=>md5($password)];
    		$res=Db::table('user')->where($where)->find();
			if(empty($res)){
				$arr=['code'=>'1','status'=>'error','message'=>'账号,密码错误'];
				echo json_encode($arr);
				die;
			}else{
                $rbac= new Rbac();
                $rbac->cachePermission($res['id']);
				$arr=['code'=>'2','status'=>'ok','message'=>'登陆成功'];
				session::set("user",$user);
				$json=json_encode($arr);
				echo $json;
			}
			
		}
    }
    //生成验证码图片
    public function verify(){
    	$captcha = new Captcha();
        return $captcha->entry();
    }

     public function loginOut(){
        Session::clear();
        $this->redirect('login/login'); 
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
  
