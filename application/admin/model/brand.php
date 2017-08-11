<?php
namespace app\admin\model;

use think\Model;

class Brand extends Model
{
	public function Category(){
		return $this->belongsTo('goodscategory','category_id');
	}
	static public function GetBrandPages(){
		return Brand::order('id desc')->paginate(5);
	} 
}