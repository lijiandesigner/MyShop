<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\GoodsCategory;
/*
产品模型 控制器
分类 规格 属性 相关操作
*/
class ProductModel extends controller 
{
	/*产品分类 首次加载首页*/
	public function showTypes(){
		$types=GoodsCategory::TypesPage();
		$this->assign('types',$types);
		return $this->fetch();
	}
	
	public function addType(){
		return $this->fetch();
	}
	public function addType(){

	}
}