<?php
namespace app\admin\controller;
use gmars\rbac\Rbac;
use Request;
use Db;
class Goodslist extends Common
{
	public function list()
	{
		$id=Request::get('id');
		$this->assign('goods_id',$id);
		return $this->fetch();
	}

	    public function add()
    {
		$id=Request::get('id');
        $newarr=[];
		$arr=Db::query("select a.id,a.name from goods_attr as ga join attribute as a on a.id = ga.attr_id where ga.goods_id='$id' group by ga.attr_id");
		foreach ($arr as $k => $v) {
			$newarr = Db::query("select ad.id,ad.name from goods_attr as ga join attr_details as ad on ad.id = ga.attr_details_id where ga.goods_id='$id' and ga.attr_id = {$v['id']}");
			$arr[$k]['son'] = $newarr;
		}

		$this->assign('arr',$arr);
		$this->assign('newarr',$newarr);
		$this->assign('goods_id',$id);
       return $this->fetch();
    }


	public function attrShow()
	{

		$id=Request::get('goods_id');
        $ware=Db::query("select * from goods_list where goods_id='$id'");
        var_dump($ware);die;
        for ($i=0; $i <count($ware) ; $i++) { 
            $new_arr=[];
            $attr_id=$ware[$i]['goods_attr_id'];
            $all_attr_id=explode("-", $attr_id);
            for ($j=0; $j < count($all_attr_id); $j++) { 
                $details_id=$all_attr_id[$j];
                $details=Db::query("select * from attr_details where id='$details_id'");

                $new_arr[]=$details[0]['name'];
            }
            $str=implode("-", $new_arr);
            $ware[$i]['attr_name']=$str;
           
        }
         
        $json=['code'=>'0','status'=>'ok','data'=>$ware];
        return json($json);

	}
}