<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Brand;
use app\admin\model\GoodsCategory;
use app\admin\logic\GoodLogic;
use think\Request;

class GoodsBrand extends controller
{

	public function ShowBrands(){
		$pageResult=Brand::getBrandPages();
		$this->assign('brands',$pageResult);
		return $this->fetch('ShowBrands');
	}
	//品牌详细信息修改和添加
	public function BrandManage($id=0,Request $req){
		if (request()->isGet()) {

			//预加载顶级分类并注册到分布视图
			$logic=new GoodLogic;
			$topTypes=$logic->getTypeChildById();
			$this->assign('firstTypes',$topTypes);
			//注册分类显示级别 1 2 3
			$this->assign('floor',2);
			//将品牌ID注册到视图模版
			$this->assign('id',$id);
			if($id)
			{
				$cur=Brand::get($id);
				$this->assign('curBrand',$cur);//若id不为0说明当前操作为修改 查询需修改的对象并注册到视图模版

				//修改时 分类选择还原业务 start
				$attr=$logic->typeGenealogy($cur->category);
				$this->assign('selectedTypes',$attr);
				if($attr['two']){//二级不为空时
					$secTypes=$logic->getTypeChildById($attr['one']);
					$this->assign('secTypes',$secTypes);
				}
				if ($attr['three']) {//三级不为空时
					$threeTypes=$logic->getTypeChildById($attr['two']);
					$this->assign('threeTypes',$threeTypes);
				}
				//修改时 分类选择业务还原 end
			}
			return $this->fetch('BrandManage');
		}else if(request()->isAjax())
		{	//添加或修改被保存
			try {
				$id=input('post.id');
				$brand=$id?Brand::get($id):new Brand();
				$brand->data(input('post.'));
				//所属类别ID
				$brand->category_id=input('post.firstType')==0?input('post.secondType'):input('post.firstType');
				//图片保存 路径赋值
				//如果图片上传不为空
				$file=$req->file('logo');
				$dir=ROOT_PATH.'public'.DS.'upload'.DS.'brand';
				if (!file_exists($dir)) {
					mkdir($dir);
				}
				if ($file) {
					//上传到服务器指定目录 并且使用uniqid规则
					$info=$file->rule('uniqid')->move($dir);
					$brand->logo=DS.'public'.DS.'upload'.DS.'brand'.DS.$info->getFilename();//获取生成的文件名
				}
				$brand->allowField(true)->save();
				return json(['message'=>'ok','status'=>1]);	
			} catch (Exception $e) {
				return json(['message'=>$e->getMessage()]);
			}
				
		}
	}
}