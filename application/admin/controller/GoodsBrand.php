<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Brand;
use app\admin\model\GoodsCategory;

class GoodsBrand extends controller
{

	public function ShowBrands(){
		$pageResult=Brand::getBrandPages();
		$this->assign('brands',$pageResult);
		return $this->fetch('ShowBrands');
	}
	//品牌详细信息修改和添加
	public function BrandManage($id=0){
		if (request()->isGet()) {
			$topTypes=GoodsCategory::where('parent_id',
			'=',0)->field('id,name')->select();
		$this->assign('firstTypes',$topTypes);
		
			$this->assign('id',$id);//将品牌ID注册到视图模版
			$id&&$this->assign('curBrand',Brand::get($id));//若id不为0说明当前操作为修改 查询需修改的对象并注册到视图模版
			return $this->fetch('BrandManage');
		}else if(request()->isAjax())
		{//添加或修改被保存
			
		}
	}
}