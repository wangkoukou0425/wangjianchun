<?php
namespace app\admin\controller;
use think\Controller;
use gmars\rbac\Rbac;
use Db;
use Request;
class Category extends Common
{
    public function list()
    {
      return $this->fetch();
    }

  private function getTree($array, $pid =0, $level = 0){
   // private 私有的 只能自己调方法
    //声明静态数组,避免递归调用时,多次声明导致数组覆盖
    static $list = [];
    echo "<ul id='ui'>";
    foreach ($array as $key => $value){
        //第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
      
        if ($value['parent_id'] == $pid){
            //父节点为根节点的节点,级别为0，也就是第一级
            // 更新 名称值
            $value['cat_name'] =$value['cat_name'];
            $m_pid=$value['parent_id'];
            $c_id=$value['cat_id'];
            // 输出 名称
            echo "<li value='$c_id' id='li1'>".$value['cat_name']."&nbsp;&nbsp;&nbsp;<a style='text-decoration:none' title='删除' onclick='del(".$value['cat_id'].")'><i class='Hui-iconfont'>&#xe706;</i></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onClick='modaldemo()' style='text-decoration:none' title='编辑' id='update'><i class='Hui-iconfont'>&#xe6df;</i></a></li></li>";
            //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
            $this->getTree($array, $value['cat_id'], $level+1);
        }
    }
    echo "</ul>";
}

    public function show()
      { 
        $rbac= new Rbac();
        $arr=Db::query("select * from category");
        $this->getTree($arr);
      }

    public function addAction(){
         $data=Request::post(); 
        $cat_name=$data['cat_name'];
        $parent_id=$data['parent_id'];
         $arr=Db::query("select * from category where cat_name='$cat_name'");
         if(empty($arr)){
        $arr=Db::query("insert into category (`cat_name`,`parent_id`) values ('$cat_name','$parent_id')");
         $json=['code'=>'0','status'=>'ok','data'=>'添加成功'];
        return json($json);
         }else{
           $json=['code'=>'1','status'=>'error','data'=>'名字不能重复'];
         return json($json);
         }
      }

     public  function del()
      {
      $data=Request::post();
      $validate = new \app\admin\validate1\Category;
        if (!$validate->check($data)) {
            $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
            echo  $json=json_encode($arr);
            die;
        }
        $get=Request::param();
        $id=$get['cat_id'];
         $arr=Db::query("delete from category where cat_id=$id");
         $this->del_action($id);
         $json=['code'=>'1','status'=>'ok','data'=>'删除成功'];
           echo json_encode($json);
      }
      function del_action($id)
      {
        $arr=Db::query("select * from category where parent_id=$id");
        if (empty($arr)) {
            
        }else{
          foreach ($arr as $key => $value) {
            $cat_id=$value['cat_id'];
            Db::query("delete from category where cat_id=$cat_id");
            $this->del_action($value['cat_id']);
        }
      }
    }
  }

