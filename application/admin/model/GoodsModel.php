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
	//一对多关联 主键位置设置 获取当前主键的商品模型所对应的属性列表
	//模型到属性的一对多映射
	public function goodsAttrs(){
		return $this->hasMany('GoodsAttr','type_id');
	}
	//模型到规格 一对多关联映射
	public function specs(){
		return $this->hasMany('Spec','category_id');
	}
}