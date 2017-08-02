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
	public function goodsAttr(){
		return $this->hasMany('GoodsAttr');
	}
}