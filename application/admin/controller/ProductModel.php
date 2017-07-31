<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\GoodsCategory;
use think\Request;
use think\Db;
use app\admin\logic\GoodLogic;
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
	
	public function typeManage($typeid=null){
		//默认添加操作
		$type=new GoodsCategory(['id'=>null,'name'=>'','parent_id'=>'0','image'=>'a.jpg','goods_order'=>'50','is_show'=>'0']);//is_show为0显示勾选 为1不显示
		//typeid有值时 为修改操作
		$secTypes=[];//当前选择菜单为三级菜单时 存储二级菜单项
		$typeLevels=['first'=>0,'second'=>0];
		if (isset($typeid)) {
			$goodLogic=new GoodLogic;
			$type=GoodsCategory::get($typeid);
			if($type->parent_id!=0){
				$parentType=$goodLogic->getParentType($type);
				$typeLevels['first']=$parentType->id;
				if($parentType->id!=$type->parent_id){
					$typeLevels['second']=$type->parent_id;
					$secTypes=Db::name('GoodsCategory')->where('parent_id','=',$parentType->id)->select();//当前选择内容为三级菜单时 加载二级菜单内容并在视图模版中选择所属菜单
				}
			}
		}
		$topTypes=Db::name('GoodsCategory')->where('parent_id',
			'=',0)->field('id,name')->select();
		$this->assign("typeinfo",$type);//产品基本信息
		$this->assign("topTypes",$topTypes);//预加载顶级菜单
		$this->assign("typeLevels",$typeLevels);//修改时 原顶级父菜单和二级父菜单信息
		$this->assign("secTypes",$secTypes);
		return $this->fetch();
	}
	//添加或修改分类
	public function addType(Request $req){
		$file=$req->file('image');
		$dir=ROOT_PATH.'public'.DS.'upload'.DS.'category';
		if (!file_exists($dir)) {
			mkdir($dir);
		}
		
		
		$type=new GoodsCategory();
		if($req->param('typeid')){//确认为修改
			$type=GoodsCategory::get($req->param('typeid'));	
			if ($type->parent_id==$type->id){//修改时当前选中的上级分类为自己时
			return json(['message'=>'上级分类不可以是自己','status'=>0]);
			}
		}
		//如果图片上传不为空
		if ($file) {
			//上传到服务器指定目录 并且使用uniqid规则
			$info=$file->rule('uniqid')->move($dir);
			$type->image=DS.'upload'.DS.'category'.DS.$info->getFilename();//获取生成的文件名
		}
		
		$type->name=$req->param('name');
		$type->parent_id=$req->param('parent_id_1');
		$req->param('parent_id_2')&&$type->parent_id=$req->param('parent_id_2');
		$type->is_show=$req->param('is_show');
		$type->goods_order=$req->param('goods_order');	
		$result;
		if($type->save()){//操作成功时
			$result=['message'=>'ok','status'=>1];
		}else{
			$result=['message'=>'no','status'=>0];
		}
		return json($result);
	}
	//通过分类ID获取该分类下的二级分类
	public function getChildTypes($typeid){
		$typesChild=Db::name('GoodsCategory')->where('parent_id','=',$typeid)->field('id,name')->select();
		return json_encode($typesChild);
	}
}