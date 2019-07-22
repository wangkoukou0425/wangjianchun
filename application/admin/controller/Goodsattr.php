<?php
namespace app\admin\controller;
use gmars\rbac\Rbac;
use Request;
use Db;
class Goodsattr extends Common
{
	public function list()
	{
		$id=Request::get('id');
		$this->assign('goods_id',$id);
		return $this->fetch();
	}

     public function selectshow()
    {
		$data=Request::post();
        $goods_id=$data['goods_id'];
        $arr1=Db::query("select attr_cate from goods where goods_id=$goods_id");
        	
		$arr=Db::query("select * from attr_category");
		$json=['code'=>0,'status'=>'ok','data'=>$arr];
        if (!empty($arr1)) {
                $json['default']=$arr1;
            }
		return json($json);
	}

      public function show()
    {
		$id=Request::post('attr_cate');
		$goods_id=Request::post('goods_id');
		$goods_arr=Db::query("select attr_details_id from goods_attr where goods_id='$goods_id'");
    
		$arr=Db::query("select a.id ,a.name ,ad.id as ad_id,ad.name as ad_name from attribute as a join attr_details as ad on a.id=ad.attr_id where a.attrcate_id='$id'");
		$new=[];
		foreach ($arr as $key => $value) {
			$new[$value['name']][]=$value;
		}
		$json=['code'=>0,'status'=>'ok','data'=>$new];
		if (!empty($goods_arr)) {
            
			$json['default']=$goods_arr;
		}
		return json($json);
    }

        public function add(){
        $data=Request::post();
        $goods_id=$data['goods_id'];
        $attr_cate=$data['attr_cate'];
        $id=$data['id'];
        // var_dump($id);die;

        $id=explode(",", $data['id']);

        Db::startTrans();
        try {
            $arr1=Db::query("update goods set `attr_cate`='$attr_cate' where goods_id='$goods_id'");
                Db::query("delete from goods_attr where goods_id in ($goods_id)");
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
        }
        if ($id!=NULL) {
                foreach ($id as $key => $value) {
                    // var_dump($value);
                    $bb=Db::query("select attr_id from attr_details where id='$value'");
       

                    $attr_id=$bb[0]['attr_id'];
                    $arr=Db::query("insert into goods_attr (`goods_id`,`attr_details_id`,`attr_id`) values ('$goods_id','$value','$attr_id')");            
                }
                
                $json=['code'=>'0','status'=>'ok','data'=>'添加成功'];
                echo json_encode($json);
                die;    
            }
        }

  }