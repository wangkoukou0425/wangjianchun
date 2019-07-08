<?php
namespace app\admin\controller;
use gmars\rbac\Rbac;
use Request;
use Db;

class User extends Common
{
    public function list()
  {
     return $this	->fetch();
  }


     public function show()
    { 
      $rbac= new Rbac();
      $arr=Db::query("select user.id,user.user_name,user.mobile,role.name from user join user_role on user.id=user_role.user_id join role on user_role.role_id=role.id");

      $json=['code'=>0,'status'=>'ok','data'=>$arr];
      echo $json=json_encode($arr);
      die;
    }

// 


     public function myShow()
    { 
    	$id=Request::get('id');
      $rbac= new Rbac();
      $arr=Db::query("select role_id from user_role where user_id='$id'");
      $json=['code'=>0,'status'=>'ok','data'=>$arr];
      return json($json);
    }

  public function updateAction(){
      $data=Request::post();
        $rbac= new Rbac();
      $validate = new \app\admin\validate\User;
        if (!$validate->check($data)) {
          $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
            return json($arr);
        }
      
        $user_name=$data['user_name'];
        $id=$data['id'];
        $mobile=$data['mobile'];       
        unset($data['__token__']);
        $arr=Db::query("select * from user where user_name='$user_name' or mobile='$mobile'");
      if (empty($getarr)) {
          $user_arr=['user_name'=>$user_name,'mobile'=>$data['mobile']];
          Db::name('user')->where('id', $data['id'])->update($user_arr);
          $u_r_arr=['role_id'=>$data['r_name']];
          Db::name('user_role')->where('user_id',$data['id'])->update($u_r_arr);
            $arr1 = ['code'=>'0','status'=>'ok','data'=>'修改成功'];
            $json =json_encode($arr1);
            echo $json;
            die;
        }else{
          foreach ($getarr as $key => $value) {
                if ($value['id']!=$data['id']) {
                    $arr = ['code'=>'1','status'=>'error','data'=>'name或phone已存在'];
                    $json =json_encode($arr);
                    echo $json;die;
                }
            }
            $user_arr=['user_name'=>$user_name,'password'=>$data['password'],'mobile'=>$data['mobile']];
          Db::name('user')->where('id', $data['id'])->update($user_arr);
          $u_r_arr=['role_id'=>$data['role_id']];
          Db::name('user_role')->where('user_id',$data['id'])->update($u_r_arr);
            $arr1 = ['code'=>'0','status'=>'ok','data'=>'修改成功'];
            $json =json_encode($arr1);
            echo $json;die;
        }
      }


  	public function add()
  	 {
  	     return $this->fetch();
  	 }

    public function addAction(){
      $data=Request::post();
  
      $validate = new \app\admin\validate\User;
      $token=Request::post('__token__');
      if(!$validate->check($data)){
        $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
        return json($arr);
      }
      //验证器
      $rbac= new Rbac();
      $r_name=$data['r_name'];  
      $user_name=$data['user_name'];
      $password=md5($data['password']);
      $mobile=$data['mobile'];
      $getname=Db::query("select * from user where user_name='$user_name'");
      if(empty($getname)){
      $arr=Db::query("insert into `user` (`user_name`,`password`,`mobile`) values ('$user_name','$password','$mobile')");
      $getname1=Db::query("select * from user where user_name='$user_name'");
      $id=$getname1[0]['id'];
      $arr=Db::query("insert into `user_role` (`user_id`,`role_id`) values ('$id','$r_name')");
       $json=['code'=>'0','status'=>'ok','data'=>'添加成功'];
      return json($json);
      }else{
        $json=['code'=>'1','status'=>'error','data'=>'名字不能重复'];
      return json($json);
      }
      
    }

        public function del()
    {
        $get=Request::param();
        $id=$get['id'];
        $data=Request::post();
        $rbac= new Rbac();
        $validate = new \app\admin\validate1\Role;
        if (!$validate->check($data)) {
            $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
            echo  $json=json_encode($arr);
            die;
        }
        $result=Db::table('user')->where('id',$id)->delete();
        if ($result) {
          echo "ok";
        }
    }

    //     public function del_More()
    // {
    //     $id=Request::post('id');
    //     $data=Request::post();
    //     $rbac= new Rbac();
    //     $validate = new \app\admin\validate1\Role;
    //     if (!$validate->check($data)) {
    //         $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
    //         echo  $json=json_encode($arr);
    //         die;}
        
    //     if(empty($id)){
    //         $arr=['code'=>'0','status'=>'ok','data'=>'不能为空'];
    //         echo $json=json_encode($arr);
    //         die;
    // }
    //     $arr=explode(',', $id);
    //     array_shift($arr);
    //     // $rbac= new Rbac();
    //     $rbac->delUser($arr);
    //     $rbac->delUser_role($arr);
    //     $arr=['code'=>'0','status'=>'ok','data'=>'删除成了'];
    //     echo $json=json_encode($arr);
       
    // }
  }

 