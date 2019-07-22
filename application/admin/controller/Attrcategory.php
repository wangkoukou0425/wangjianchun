<?php
namespace app\admin\controller;
use gmars\rbac\Rbac;
use Request;
use Db;
class Attrcategory extends Common
{
    public function list()
    {
    	return $this->fetch();
    }


    public function show()
    {	
     $arr=Db::query("select * from attr_category");
      $json=['code'=>0,'status'=>'ok','data'=>$arr];
      return json($json);
    }

     public function addAction(){
      $data=Request::post();
      $userId= Db::name('attr_category')->insertGetId($data);
      $json=['code'=>'0','status'=>'ok','data'=>'添加成功'];
      return json($json);
    }

       public function del()
    {
        $get=Request::param();
        $id=$get['id'];
        $data=Request::post();
        $arr=Db::query("select * from attribute where id=$id");
        if(empty($arr)){
           $arr1=Db::table('attr_category')->where('id',$id)->delete();
            $arr2=Db::table('attribute')->where('attrcate_id',$id)->delete();
            $json=['code'=>'0','status'=>'ok','data'=>'删除成功'];
            return json($json);
        }else{
            $json=['code'=>'1','status'=>'error','data'=>' 该分类有属性无法删除,请先删除下属性'];
            return json($json);
        }
       
      
    }

           public function del_More()
    {
        $id=Request::post('id');
        $data=Request::post();
        // $rbac= new Rbac();
        // $validate = new \app\admin\validate1\Permission;
        // if (!$validate->check($data)) {
        //     $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
        //     echo  $json=json_encode($arr);
        //     die;}
        
        if(empty($id)){
            $arr=['code'=>'0','status'=>'ok','data'=>'不能为空'];
            echo $json=json_encode($arr);
            die;
    }
        $arr=explode(',', $id);
        array_shift($arr);
        for ($i=0; $i < count($arr); $i++) { 
          $id=$arr[$i];
           Db::table('attr_category')->where('id',$id)->delete();
        }

        $arr=['code'=>'0','status'=>'ok','data'=>'删除成了'];
        echo $json=json_encode($arr);
       
    }

	public function updateAction(){
	  $data=Request::post();
	  // $validate = new \app\admin\validate\Role;
	  //   if (!$validate->check($data)) {
	  //     $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
	  //       return json($arr);
	  //   }
	  //   unset($data['__token__']);
	    //$rbac= new Rbac();
	    // $getname=$rbac->getPermission([['name', '=', $data['name']]]);
	    $name=$data['name'];
	    $id=$data['id'];
	    $up_data=$data;
	    $arr=Db::query("select * from attr_category where name='$name'");
	    if (empty($arr)||!empty($arr)&&$arr[0]['id']==$data['id']) {
	    $arr=Db::table('attr_category')->update($up_data);
		$arr=['code'=>'0','status'=>'ok','data'=>'修改成功'];

		}else{
		$arr=['code'=>'1','status'=>'error','data'=>'重名了'];
		}
		return json($arr);

		}
  
}
