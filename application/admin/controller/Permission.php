<?php
namespace app\admin\controller;
use Request;
use Db;
use gmars\rbac\Rbac;

class Permission extends Common
{
    public function list()
  {
      $arr=Db::query("select * from permission");
      $this->assign('arr',$arr);
     return $this	->fetch();
  }


     public function show()
    { 
      $rbac= new Rbac();
      $arr=Db::query("select p.id,p.name,p.description,p.path,p_c.name as p_c_name,p.category_id from permission as p join permission_category as p_c on p.category_id=p_c.id");
      $json=['code'=>0,'status'=>'ok','data'=>$arr];
      return json($json);
    }


    public function updateAction(){
      $data=Request::post();
      $validate = new \app\admin\validate\Permission;
      if(!$validate->check($data)){
          $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
          return json($arr);
      }
      unset($data['__token__']);
      $rbac= new Rbac();
      //$getname=$rbac->getPermission([['name','=',$data['name']]]);
      $name=$data['name'];
      $path=$data['path'];
      $arr=Db::query("select * from permission where name='$name' or path='$path'");
      if (empty($arr)) {
          $arr=Db::table('permission')->update($data);
          $arr=['code'=>0,'status'=>'ok','data'=>'修改成功'];
         echo json_encode($arr);
      }else{
        foreach ($arr as $key => $value) {
           if ($value['id']!=$data['id']) {
              $arr=['code'=>1,'status'=>'error','data'=>'name或者paht已经存在'];
              echo json_encode($arr);
              die;
        }     
      }
      $arr=Db::table('permission')->update($data);
      $arr=['code'=>0,'status'=>'ok','data'=>'修改成功'];
      echo json_encode($arr);
      
    }
  }

      // 1.取值（name or path)
      // 2.if(值为空){直接修改}
      // 3.if（不是空）{
      //   if(自己的ID){
      //     可以修改
      //   }else{
      //     name或者paht已经存在
      //   }
      // }
    public function addAction(){
      $data=Request::post();
      $validate = new \app\admin\validate\Permission;
        $token=Request::post('__token__');
      if(!$validate->check($data)){
        $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
        return json($arr);
      }
      $rbac= new Rbac();
      $getname=$rbac->getPermission([['name','=',$data['name']]]);
      $getpath=$rbac->getPermission([['name','=',$data['path']]]);
      if(empty($getname)&&empty($getpath)){
        $arr=$rbac->createPermission([
      'name' => $data['name'],
      'description' => $data['description'],
      'status' => 1,
      'type' => 1,
      'category_id' => $data['category_id'],
      'path' => $data['path'],
        ]);
       $json=['code'=>'0','status'=>'ok','data'=>$arr];
      return json($json);
      }else{
        $json=['code'=>'1','status'=>'error','data'=>'名字或者路径不能重复'];
      return json($json);
      }
      
    }

        public function del()
    {
        $get=Request::param();
        $id=$get['id'];
        $data=Request::post();
        $rbac= new Rbac();
        $validate = new \app\admin\validate1\Permission;
        if (!$validate->check($data)) {
            $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
            echo  $json=json_encode($arr);
            die;
        }
        $result=Db::table('permission')->where('id',$id)->delete();
        if ($result) {
          echo "ok";
        }
    }

        public function del_More()
    {
        $id=Request::post('id');
        $data=Request::post();
        $rbac= new Rbac();
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
        $rbac= new Rbac();
        $rbac->delPermission($arr);
        $arr=['code'=>'0','status'=>'ok','data'=>'删除成了'];
        echo $json=json_encode($arr);
       
    }
} 