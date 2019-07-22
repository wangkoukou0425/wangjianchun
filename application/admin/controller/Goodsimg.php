<?php
namespace app\admin\controller;
use Request;
use Db;

class Goodsimg extends Common
{
     
     public function goodsImgshow()
    { 
      $goods_id=Request::get('id');
      $this->assign('goods_id',$goods_id);
     return $this ->fetch();
    }

      public function goodsImg()
    { 
      $goods_id=Request::post('goods_id');

      $arr=Db::query("select goods_img.id,goods_img.big_img,goods_img.middle_img,goods_img.small_img,goods_img.goods_id from goods_img join goods on goods_img.goods_id=goods.goods_id where goods_img.goods_id=$goods_id");
      $json=['code'=>'0','status'=>'ok','data'=>$arr];
      return json($json);
    }


    public function addaction(){
      $data=Request::post();
       $goods_id=$data['goods_id'];
      $files = request()->file('goods_img');
      // 移动到框架应用根目录/uploads/ 目录下
      foreach ($files as $file) {
      $info = $file->validate(['size'=>1024*1024,'ext'=>'jpg,png,gif'])->move( './uploads/goodsimg');
      if($info){
      $name=$info->getFilename();
      $data=date("Ymd");
      $path="$data/$name";
      $image=\think\Image::open("./uploads/goodsimg/$path");
      // 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.png
      $big_img ="$data/big_$name";
      $image->thumb(150,150)->save('./uploads/goodsimg/'.$big_img);
      $middle_img ="$data/middle_$name";
      $image->thumb(100,100)->save('./uploads/goodsimg/'.$middle_img);
      $small_img ="$data/small_$name";
      $image->thumb(50,50)->save('./uploads/goodsimg/'.$small_img);
      // $path=str_replace("\\","/",$info->getSaveName());
      $arr=Db::query("insert into goods_img (`big_img`,`goods_id`,`middle_img`,`small_img`,`origin`) values ('$path','$goods_id','$big_img','$middle_img','$small_img')");
       $json=['code'=>'0','status'=>'ok','data'=>'添加成功'];
      return json($json);
       }else{
        $json=['code'=>'1','status'=>'error','data'=>'添加失败'];
       return json($json);
       }
    }
     
  }
  public function del(){
    $get=Request::param();
    $id=$get['id'];
    $data=Request::post();
    $arr=Db::table('goods_img')->where('id',$id)->delete();
    if($arr){
      echo"ok";
    }
  }

} 