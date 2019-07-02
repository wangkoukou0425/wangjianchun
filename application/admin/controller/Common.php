<?php
namespace app\admin\controller;
use think\Controller;
use think\facade\Session;
class Common extends Controller

{
    public function initialize()
    {
       $user=Session::get('user');
       if($user){
        $this->assign('user',$user);
       }else{
       
        $this->rederect('login/login');
       }
    }
    
    public function commonToken()
    {
        $token = $this->request->token('__token__', 'sha1');
        $arr=['token'=>$token];
        echo $json=json_encode($arr); 
        // $this->assign('token', $token);
        // return $this->fetch();
    }
	}
