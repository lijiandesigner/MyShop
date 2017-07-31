<?php
namespace app\admin\model;

use think\Model;

class GoodsModel extends Model
{
	//分页获取所有的商品模型
	static public function getAllPages(){
		$result=GoodsModel::order('id desc')->paginate(5);
		return $result;
	}
}