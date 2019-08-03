<?php
namespace app\admin\controller;
use think\Controller;
use Db;

class Ceshi extends Common

{
   public function Ceshi()
    {  
       return $this	->fetch();
    }


   function get($num=10000000){  // $num为生成汉字的数量
       $b = '';
       for ($i=0; $i<$num; $i++) {
           // 使用chr()函数拼接双字节汉字，前一个chr()为高位字节，后一个为低位字节
           $a = chr(mt_rand(0xB0,0xD0)).chr(mt_rand(0xA1, 0xF0));
           $b = chr(mt_rand(0xB0,0xD0)).chr(mt_rand(0xA1, 0xF0));
           $c = chr(mt_rand(0xB0,0xD0)).chr(mt_rand(0xA1, 0xF0));
           // 转码
           $a1 = iconv('GB2312', 'UTF-8', $a);
           $b1 = iconv('GB2312', 'UTF-8', $b);
           $c1 = iconv('GB2312', 'UTF-8', $c);
			echo $d=$a1.$b1.$c1;
			echo "<br>";
			$add = ['goods_name' => $d, 'brand_id' => '12','cat_id' => '1'];
			$acc=Db::table('goods')->insert($add);

       }
   	}


}


