<?php
namespace app\mobile\controller;
use think\Controller;

class Index extends Controller{

	public function index()
	{
		//查询轮播
		$carouselList=db('carousel')->where('dis',1)->select();
		$this->assign('carouselList',$carouselList);
		
		//查询推荐
		$hotGoods=db('goods')->where('is_hot',1)->where('is_on_sale',1)->select();
		$this->assign('hotGoods',$hotGoods);

		//查询新品
		$newGoods=db('goods')->where('is_new',1)->where('is_on_sale',1)->select();
		$this->assign('newGoods',$newGoods);
		
		return $this->fetch();

	}



 

}