<?php
namespace app\admin\controller;
use gmars\rbac\Rbac;
use Request;
use Db;
class Permissioncate extends Common
{
    public function list()
    {
    	return $this->fetch();
    }


    public function show()
    {	
    	$rbac= new Rbac();
    	$arr=$rbac->getPermissionCategory([['status', '=', 1]]);
    	echo json_encode($arr);
    }


    public function addAction()
    {
    	$name=Request::post('name');
        $description=Request::post('description');
        $token=Request::post('__token__');
        $data = [
            'name'  => $name,
            'description' => $description,
            '__token__'=>$token
        ];
        $validate = new \app\admin\validate\Permissioncate;
    	if (!$validate->check($data)) {
            $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
            echo $json=json_encode($arr);
            die;
        }
    	$rbac= new Rbac();
    	$aa=$rbac->getPermissionCategory([['name', '=', $data['name']]]);
    		if (empty($aa)) {
	    		$arr=$rbac->savePermissionCategory([
				    'name' => $data['name'],		    
				    'description' => $data['description'],
				    'status' => 1
				]);
				$arr=['code'=>'0','status'=>'ok','data'=>'添加成功'];
				echo $json=json_encode($arr);
    		}else{
				$arr=['code'=>'1','status'=>'error','data'=>'分类已经存在'];
				echo $json=json_encode($arr);
    		}
        }
    public function del()
    {
        $get=Request::param();
        $id=$get['id'];
        $data=Request::post();
        $rbac= new Rbac();
        $validate = new \app\admin\validate1\Permissioncate;
        if (!$validate->check($data)) {
            $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
            echo  $json=json_encode($arr);
            die;
        }
        $result=Db::table('permission_category')->where('id',$id)->delete();
        if ($result) {
          echo "ok";
        }
    }

       public function del_More()
    {
        $id=Request::post('id');
         $data=Request::post();
        $rbac= new Rbac();
        $validate = new \app\admin\validate1\Permissioncate;
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
        $rbac->delPermissionCategory($arr);
        $arr=['code'=>'0','status'=>'ok','data'=>'删除成了'];
        echo $json=json_encode($arr);
       
    }
    public function updateAction()
    {
        $data=Request::post();
        $rbac= new Rbac();
        $validate = new \app\admin\validate\Permissioncate;
        $aa=$rbac->getPermissionCategory([['name', '=', $data['name']]]);
        if (!$validate->check($data)) {
            $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
            echo  $json=json_encode($arr);
            die;
        }
            if (empty($aa)) {
               Db::table('permission_category')->where('id',$data['id'])->update(['name'=>$data['name'],'description'=>$data['description']]);
                $arr=['code'=>'0','status'=>'ok','data'=>'修改成功'];
                echo $json=json_encode($arr);
                die;
            }else{
                 if($aa[0]['id']!=$data['id']){
                $arr=['code'=>'1','status'=>'error','data'=>'分类已经存在'];
                echo $json=json_encode($arr);
                die;

            }else{
                Db::table('permission_category')->where('id',$data['id'])->update(['name'=>$data['name'],'description'=>$data['description']]);
                $arr=['code'=>'0','status'=>'ok','data'=>'修改成功'];
                echo $json=json_encode($arr);
                die;
            }    
        }
    }
}
