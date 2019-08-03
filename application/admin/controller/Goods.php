<?php
namespace app\admin\controller;
use Request;
use Db;
use gmars\rbac\Rbac;
use Redis;

class Goods extends Common
{
      public function list()
    {
       return $this	->fetch();
    }

     public function show()
    { 
        $redis = new Redis();
        $redis->connect('127.0.0.1',6379);
        $aa=$redis->ZREVRANGE('z', 0, 3);//排序点击量前4个
        $data=Request::post();
        $s_name=$data['s_name'];
     if (!empty($s_name)) {
        $redis->Hsetnx('h',"$s_name",0);//哈希存值
        $num=$redis->HincrBy('h',"$s_name",1);//哈希value值递增
        $num1=$redis->Hget('h',"$s_name");//取value值
        $redis->ZINCRBY("z","$num1","$s_name");
     }
        if (empty($s_name)) {
            $arr=Db::query("select goods_id,goods_name,brand_name,goods_sn,goods_number,is_show,goods_sold,goods_price,add_time,cat_name from goods as g join brand as b on g.brand_id=b.brand_id join category as cat on cat.cat_id=g.cat_id limit 0,300");
            
        }elseif ($num1<10) {
            $arr=Db::query("select goods_id,goods_name,brand_name,goods_sn,goods_number,is_show,goods_sold,goods_price,add_time,cat_name from goods as g join brand as b on g.brand_id=b.brand_id join category as cat on cat.cat_id=g.cat_id where goods_name like '%".$s_name."%'");
        }elseif ($num1=10) {
            $arr=Db::query("select goods_id,goods_name,brand_name,goods_sn,goods_number,is_show,goods_sold,goods_price,add_time,cat_name from goods as g join brand as b on g.brand_id=b.brand_id join category as cat on cat.cat_id=g.cat_id where goods_name like '%".$s_name."%'");
            $arr=json_encode($arr);
            $redis->Hset("h",$s_name,$arr);
        }elseif ($num1>10) {
            $arr=$redis->Hget("h",$s_name);
            $arr=json_decode($arr);
        }  
      $json=['code'=>'0','status'=>'ok','data'=>$arr ,'aa'=>$aa];
      echo json_encode($json);
      
    }

     public function showCat()
    { 
      $arr=Db::query("select * from category");
      $this->getTree($arr);
    }

    private function getTree($array, $pid =0, $level = 0){

      static $list = [];
      foreach ($array as $key => $value){

          if ($value['parent_id'] == $pid){
              $flg = str_repeat('|--',$level);
              $value['cat_name'] =$flg.$value['cat_name'];
              $m_pid=$value['parent_id'];
              $c_id=$value['cat_id'];
              $name=$value['cat_name'];
              echo "<option value='$c_id'>$name</option>";
              $this->getTree($array, $value['cat_id'], $level+1);
          }
      }
    }

    public function add()
    {
       return $this->fetch();
    }

      public function addAction(){
      $data=Request::post();
      // unset($data['__token__']);
      $userId= Db::name('goods')->insertGetId($data);
      $json=['code'=>'0','status'=>'ok','data'=>'添加成功'];
      return json($json);
    }

    public function del()
    {
      $get=Request::param();
      $id=$get['goods_id'];
      $data=Request::post();
      $arr=Db::table('goods')->where('goods_id',$id)->delete();
       if ($arr) {
          echo "ok";
        }
    }

    public function updateshow(){
       $data=Request::post();
       $id=$data['id'];
      $arr=Db::query("select * from goods where goods_id='$id'");
       $arr3=Db::query("select * from category");
       $arr2=Db::query("select * from brand");
       $json=['code'=>'0','status'=>'ok','data'=>$arr,'data2'=>$arr2,'data3'=>$arr3];
        return json($json);
    }


    public function update_Action(){
      $data=Request::post();
      $goods_id=$data['goods_id'];
      $goods_name=$data['goods_name'];
      $goods_price=$data['goods_price'];
      $goods_number=$data['goods_number'];
      $goods_sn=$data['goods_sn'];
      $is_show=$data['is_show'];
      $brand_id=$data['brand_id'];
      $cat_id=$data['cat_id'];
      $arr=Db::query("select * from goods where goods_id=$goods_id");
      if (empty($arr)) {
        $arr=Db::query("update goods set `goods_name`='$goods_name',`goods_price`='$goods_price',`goods_number`='$goods_number',`goods_sn`='$goods_sn',`is_show`='$is_show',`brand_id`='$brand_id',`brand_id`='$brand_id',`cat_id`='$cat_id'");
        $arr=['code'=>'0','status'=>'ok','data'=>'修改成功'];
        echo $json=json_encode($arr);
        die;
      }else{
        if ($arr[0]['goods_id']==$goods_id) {
        $arr=Db::query("update goods set `goods_name`='$goods_name',`goods_price`='$goods_price',`goods_number`='$goods_number',`goods_sn`='$goods_sn',`is_show`='$is_show',`brand_id`='$brand_id',`brand_id`='$brand_id',`cat_id`='$cat_id' where goods_id='$goods_id'");
        $arr=['code'=>'0','status'=>'ok','data'=>'修改成功'];
        echo $json=json_encode($arr);
        die;
      }else{
         $arr=['code'=>'0','status'=>'error','data'=>'修改失败'];
        echo $json=json_encode($arr);
        die;
      }
    }
  }
}
