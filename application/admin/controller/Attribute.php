<?php
namespace app\admin\controller;
use gmars\rbac\Rbac;
use Request;
use Db;
class Attribute extends Common
{
    public function list()
    {
      // $attrcate_id=Request::get('attrcate_id');
      // // var_dump($attrcate_id);
      // // die;
      // $this->assign('attrcate_id',$attrcate_id);
    	return $this->fetch();
    }


    public function show()
    {	
     $arr=Db::query("select ab.id,ab.name,a_c.name as a_c_name,ab.attrcate_id from attribute as ab join attr_category as a_c on ab.attrcate_id=a_c.id ");
      $json=['code'=>0,'status'=>'ok','data'=>$arr];
      return json($json);
    }

      public function showselect()
    {
		$data=Request::post();
		// $id=$data['attrcate_id'];	
		$arr=Db::query("select * from attr_category");
		$json=['code'=>0,'status'=>'ok','data'=>$arr];
		return json($json);
    }

    public function addAction(){
         $data=Request::post(); 
        $name=$data['name'];
        $a_c_id=$data['a_c_id'];
         $arr=Db::query("select * from attribute where name='$name'");
         if(empty($arr)){
        $arr=Db::query("insert into attribute (`name`,`attrcate_id`) values ('$name','$a_c_id')");
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
        $result=Db::table('attribute')->where('id',$id)->delete();
        if ($result) {
          echo "ok";
        }
    }

       public function jishuqi()
    {
        $redis = new Redis();
        $redis->connect('127.0.0.1',6379);
 
        // incr() 对指定的key的值加1
        // decr()对指定的key的值减1
        // incrBy() 将第二个参数的值加到key的值上
        // decrBy() 将第二个参数的值加到key的值上
        // incrByFloat() 自增一个浮点类型的值
        
        echo $redis->incr('shenzhen')."<br/>";//1
        echo $redis->incr('shenzhen')."<br/>";//2
        echo $redis->incrBy('shenzhen',6)."<br/>";//8
        echo $redis->decr('shenzhen')."<br/>";//7
        echo $redis->decr('shenzhen')."<br/>";//6
        echo $redis->decrBY('shenzhen',3)."<br>"; //3
        echo $redis->incrByFloat('shenzhen',0.88);//3.88

  }
}
