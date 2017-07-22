<?php
namespace app\admin\model;

use think\Model;

class GoodsCategory extends Model
{
	static public function TypesPage(){
		$result=GoodsCategory::order('id desc')->paginate(5);
		return $result;
	}
	
}