<?php
namespace app\admin\model;

use think\Model;
/*
*产品分类 model
*/
class GoodsCategory extends Model
{
	static public function TypesPage(){
		$result=GoodsCategory::order('id desc')->paginate(5);
		return $result;
	}
	
	public function Brands(){
		return $this->hasMany('Brand');
	}
	
}