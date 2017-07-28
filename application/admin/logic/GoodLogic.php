<?php
	namespace app\admin\logic;
	use app\admin\model\GoodsCategory;
	class GoodLogic{
		//获取指定分类的顶层父级分类
		//typeid为当前分类的父级ID
		public function getParentType($type){
			$parentType=GoodsCategory::where('id','=',$type->parent_id)->find();
			if (isset($parentType)&&$parentType->parent_id!=0){
				$parentType=$this->getParentType($parentType);
			}
			return $parentType;
		}
	}
?>