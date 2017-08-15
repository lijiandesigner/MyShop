<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Goods;

class GoodsInfo extends controller
{
	public function showGoods(){
		$goodss=Goods::getGoodsInfo();
		$this->assign('goodss',$goodss);
		return $this->fetch('GoodsInfo/ShowGoods');
	}
	public function goodsManage(){
		if(request()->isGet()){
			return $this->fetch();
		}else if(request()->isAjax()){
			
		}
	}
	public function delGoods(){

	}
}