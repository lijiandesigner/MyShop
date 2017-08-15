<?php
namespace app\admin\model;

use think\Model;

class Goods extends Model
{
	static public function getGoodsInfo(){
		return Goods::order('id desc')->paginate(5);
	}
}