<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\GoodsCategory;

class Category extends controller
{
	/*根据上级id获取下级分类内容*/
	public function GetChildTypes($id=0){
		$childTypes=GoodsCategory::where('parent_id','=',$id)->field('id,name')->select();
		return json($childTypes);
	}
}