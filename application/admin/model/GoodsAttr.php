<?php
namespace app\admin\model;

use think\Model;

class GoodsAttr extends Model
{
	//当前属性 所属模型
	public function goodsModel(){
		//type_id 为指定的外键列 默认查找 goods_model_id
		return $this->belongsTo('GoodsModel','type_id');
	}

	static public function getAllPages(){
		$result=GoodsAttr::with('GoodsModel')->order('id desc')->paginate(5);
		return $result;
	}
}