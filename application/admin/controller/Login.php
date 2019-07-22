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
   // 
  


}
  
