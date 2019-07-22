<?php
namespace app\admin\controller;
use think\Controller;
use gmars\rbac\Rbac;
use Db;
use Request;
class Brand extends Common
{
    public function list()
    {
      return $this->fetch();
    }
       public function show()
    { 
      $rbac= new Rbac();
      $arr=Db::query("select * from brand");
      $json=['code'=>0,'status'=>'ok','data'=>$arr];
      return json($json); 
    }

    public function addAction(){
      $data=Request::post();
      var_dump($data);
      // $validate = new \app\admin\validate1\Brand;
      // if (!$validate->check($data)) {
      //     $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
      //     echo  $json=json_encode($arr);
      //     die;
      // }
      $brand_name=$data['brand_name'];
      $brand_desc=$data['brand_desc'];
      $site_url=$data['site_url'];
      $sort_order=$data['sort_order'];
      $file = request()->file('brand_logo');
      // 移动到框架应用根目录/uploads/ 目录下
      $info = $file->validate(['size'=>1024*1024,'ext'=>'jpg,png,gif'])->move( 'uploads');
      if($info){

        $path=str_replace("\\","/",$info->getSaveName());
        //当天时间加字段名 拼接成字符串  方便以后查找

        $getname=Db::query("select * from brand where brand_name='$brand_name'");
       if(empty($getname)){
      $arr=Db::query("insert into brand (`brand_name`,`brand_logo`,`brand_desc`,`site_url`,`sort_order`) values ('$brand_name','$path','$brand_desc','$site_url','$sort_order')");
       $json=['code'=>'0','status'=>'ok','data'=>'添加成功'];
      return json($json);
       }else{
         $json=['code'=>'1','status'=>'error','data'=>'名字不能重复'];
       return json($json);
       }
    }
  }
      
      public function del()
    {
        $get=Request::param();
        $id=$get['brand_id'];
        $data=Request::post();
        // $rbac= new Rbac();
        // $validate = new \app\admin\validate1\Brand;
        // if (!$validate->check($data)) {
        //     $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
        //     echo  $json=json_encode($arr);
        //     die;
        // }
        $arr=Db::query("select brand_logo from brand where brand_id=$id");
        // var_dump($arr);
        // die;
        $img=$arr[0]['brand_logo'];
         unlink("./uploads/".$img);
        $result=Db::table('brand')->where('brand_id',$id)->delete();
        if ($result) {
          echo "ok";
        }
    }

       public function del_More()
    {
        $id=Request::post('id');
        $data=Request::post();
        // $rbac= new Rbac();
        $validate = new \app\admin\validate1\Permission;
        if (!$validate->check($data)) {
            $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
            echo  $json=json_encode($arr);
            die;}
        
        if(empty($id)){
            $arr=['code'=>'0','status'=>'ok','data'=>'不能为空'];
            echo $json=json_encode($arr);
            die;
    }
        $arr=explode(',', $id);
        array_shift($arr);
        for ($i=0; $i < count($arr); $i++) { 
          $id=$arr[$i];
           Db::table('brand')->where('brand_id',$id)->delete();
        }

        $arr=['code'=>'0','status'=>'ok','data'=>'删除成了'];
        echo $json=json_encode($arr);
       
    }

     public function updateAction(){
      $data=Request::post();
      // $validate = new \app\admin\validate\Permission;
      // if(!$validate->check($data)){
      //     $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
      //     return json($arr);
      // }
      // unset($data['__token__']);
      // // $rbac= new Rbac();
      $brand_id=$data['brand_id'];  
      $brand_name=$data['brand_name'];
      $brand_desc=$data['brand_desc'];
      $site_url=$data['site_url'];
      $sort_order=$data['sort_order'];
      $arr=Db::query("select * from brand where brand_name='$brand_name'");
      if (empty($arr)) {
          $arr=Db::table('brand')->where('brand_id',$brand_id)->update(['brand_name'=>$brand_name,'brand_desc'=> $brand_desc,'site_url'=>$site_url,'sort_order'=>$sort_order]);
          $arr=['code'=>0,'status'=>'ok','data'=>'修改成功'];
         echo json_encode($arr); 
         die;
      }else{
          $arr=['code'=>'1','status'=>'error','data'=>'重名了']; 
           echo json_encode($arr);
      }
  }

      public function up_logo(){
      $data=Request::post();
      $id=$data['brand_id'];
      $newfile = request()->file('brand_logo');
      $arr=Db::query("select * from brand where brand_id='$id'");
      $img=$arr[0]['brand_logo'];
      unlink("./uploads/".$img);  
      // 移动到框架应用根目录/uploads/ 目录下
      $info = $newfile->validate(['size'=>1024*1024,'ext'=>'jpg,png,gif'])->move('uploads');
      if($info){
        $path=str_replace("\\","/",$info->getSaveName());
       $arr=Db::query("update brand set brand_logo='$path' where brand_id='$id'");
       $json=['code'=>'0','status'=>'ok','data'=>'添加成功'];
      return json($json);
       }else{
         $json=['code'=>'1','status'=>'error','data'=>'修改失败'];
       return json($json);
       }
    }

    
}

