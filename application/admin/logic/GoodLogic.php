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
		/*
		根据当前分类 获取该分类族谱ID数组
		返回结果['one'=>0,'two'=>0,'three'=0]
		表示各级分类的ID值
		*/
		public function typeGenealogy($type){
			$attr=['one'=>0,'two'=>0,'three'=>0];
			if(!$type->parent_id){//当前$type为顶级
				$attr['one']=$type->id;
			}else{
				$parentType=$this->getParentType($type);
				$attr['one']=$parentType->id;
				if($parentType->id==$type->parent_id){//当前为第二级
					$attr['two']=$type->id;
				}else{//当前为第三级
					$attr['two']=$type->parent_id;
					$attr['three']=$type->id;
				}
			}
			return $attr;
		}
		//根据种类ID 获取子分类 参数为0或不传时得到顶级分类
		public function getTypeChildById($id=0){
			return GoodsCategory::where('parent_id','=',$id)->field('id,name')->select();
		}
	}
?>