<?php
namespace app\admin\controller;
use gmars\rbac\Rbac;
use Request;
use Db;
class Attrdetails extends Common
{
      public function list()
      {
        $attr_id=Request::get('id');

        $this->assign('attr_id',$attr_id);
        return $this->fetch();
      }

      public function show()
      {
        $attr_id=Request::post('attr_id');
        $arr=Db::query("select a_d.id,a_d.name,a.name as aname,a.attrcate_id from attr_details as a_d join attribute as a on a_d.attr_id=a.id where a_d.attr_id='$attr_id'");
        $json=['code'=>'0','status'=>'ok','data'=>$arr];
        return json($json);
      }

      public function addaction()
    {
      $name=Request::post('name');
      $attr_id=Request::post('attr_id');
      $arr=Db::query("select * from attr_details where name='$name'");
      
      if(!empty($arr)){
        $json=['code'=>'1','status'=>'error','data'=>'名称不能重复1'];
        return json($json);
      }else{
        
        Db::query("insert into attr_details (`name`,`attr_id`)values ('$name','$attr_id')");
            $json=['code'=>'0','status'=>'ok','data'=>'添加成功'];
            return json($json);
      }
    }
      public function updateaction()
        {   
          $data=Request::post();
          $attr_id=$data['attr_id'];
          $name=$data['name'];
          $arr=Db::query("select * from attrdetails where name='$name'");

          if (empty($arr)) {
              $where=['name'=>$data['name'],'attr_id'=>$data['attr_id']];
            $arr=Db::table('attrdetails')->where('id',$data['id'])->update($where);
            $json=['code'=>'0','status'=>'ok','data'=>'修改成功'];
            return json($json);
          }else{
            $json=['code'=>'1','status'=>'error','data'=>'产品类型不能重复'];
            return json($json);
          }          
        }
      public function deletemore()
        {   
            $data=Request::post();
            $id=$data['id'];
          $arr1=Db::query("select * from attrdetails where id='$id'");
          Db::query("delete from attrdetails where id='$id'");
            $this->del($id);
            $json=['code'=>'0','status'=>'ok','data'=>'删除成功'];
            echo json_encode($json);   
        }


          public function del()
    {
        $get=Request::param();
        $id=$get['id'];
        $data=Request::post();
        $result=Db::table('attr_details')->where('id',$id)->delete();
        if ($result) {
          echo "ok";
        }
    }
  }