<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\GoodsCategory;
use think\Request;
use think\Db;
use app\admin\logic\GoodLogic;
use app\admin\model\GoodsModel;
use app\admin\model\GoodsAttr;
use app\admin\model\Spec;
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
		$type=new GoodsCategory(['id'=>null,'name'=>'','parent_id'=>'0','image'=>'a.jpg','goods_order'=>'50','is_show'=>'0']);//is_show为1显示勾选 为0不显示
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

	/*
	 *代码需要重构一下 把商品分类操作单独 独立出去 2017/8/3
	 */

	//显示所有模型
	public function showModels(){
		$pageResult=GoodsModel::getAllPages();
		$this->assign('goodsmodels',$pageResult);
		return $this->fetch();
	}
	//操作商品模型
	public function goodsModelManage($mId=0){
		if (request()->isGet()) {//首次请求页面
			$modelInfo=GoodsModel::get($mId);
			$this->assign('modelInfo',$modelInfo);
			return $this->fetch();
		}else if(request()->isPost()){//修改或添加商品模型 (使用POST异步提交)
			$resultArray=['message'=>'ok','status'=>1];
			//$mId为0时 添加 非0时 修改
			$GoodsModel=!$mId?new GoodsModel():GoodsModel::get($mId);
			$GoodsModel->name=input('name');
			if(!$GoodsModel->save()){
				$resultArray=['message'=>'no','status'=>0];
			}
			return json($resultArray);
		}
		
	}
	//删除商品模型
	public function goodsModelDel($mId=0){
		$model=GoodsModel::get($mId);
		if($model&&$model->delete()){
			return json(['message'=>'ok','status'=>1]);
		}
		return json(['message'=>'对象不存在','status'=>0]);
	}
	//显示所有商品属性信息
	public function ShowGoodsAttr(){
		$pageResult=GoodsAttr::getAllPages();
		$this->assign('pageResult',$pageResult);
		return $this->fetch('ProductModel/ShowGoodsAttr');
	}
	//操作商品属性（添加修改）
	public function goodsAttrManage($attrId=0){
		$modelList=GoodsModel::all();//获取所有模型 显示到下拉列表中
		$this->assign('attrId',$attrId);//将attrId注册到模版 修改时提供依据
		$this->assign('modelList',$modelList);//模型数据注册到模版中
		if(request()->isGet()){
			//如果是修改 就将需要修改的变量注册到模版
			$attrId&&$this->assign('attrInfo',GoodsAttr::get($attrId));
			return $this->fetch('ProductModel/GoodsAttrManage');
		}else if(request()->isAjax()){
			//$attrId=0为添加 不为0时为修改
			$modelAttr=new GoodsAttr;//添加时新建空白对象
			$attrId=input('post.attrId');//接收回传的attrId
			if($attrId){//修改时 根据attrId查询对象
				$modelAttr=GoodsAttr::get($attrId);	
			}
			$modelAttr->allowField(true)->data(input('post.'));//获取提交的数据填充到已创建的对象中
			if($modelAttr->save()){
				return json(['message'=>'ok','status'=>1]);
			}else{
				return json(['message'=>'bu ok','status'=>0]);
			}


		}

	}
	//删除属性
	public function delGoodsAttr($attrId=0){
		$goodsAttr=GoodsAttr::get($attrId);
		if($goodsAttr){
			if($goodsAttr->delete()){
				return json(['message'=>'ok','status'=>1]);
			}else{
				return json(['message'=>'bu ok','status'=>0]);
			}
		}else{
			return json(['message'=>'属性不存在','status'=>0]);
		}
	}
	//显示规格信息
	public function showGoodsSpec(){
		$specPages=Spec::getAllPages();
		$this->assign('specs',$specPages);
		return $this->fetch('ProductModel/ShowGoodsSpec');
	}
	//操作规格信息 添加 修改
	public function specManage($specId=0){
		$this->assign('specId',$specId);//把id注册到视图中 修改时回传
		if(request()->isGet()){//首次访问时
			$models=GoodsModel::all();
			if($specId){//如果为修改 将需修改对象注册到视图
				$specCurrent=Spec::get($specId);
				$this->assign('specCur',$specCurrent);			
			}
			$this->assign('models',$models);
			return $this->fetch('ProductModel/specManage');
		}else if(request()->isAjax()){
			//数据提交时
			$specInfo=new Spec();
			$specId=input('post.id');//id数据回传
			if ($specId){
				$specInfo=Spec::get($specId);//确认为修改时 获取需要修改的操作对象	
			}

			try{
				$specInfo->startTrans();
				$specInfo->allowField(true)->data(input('post.'))->save();
				//新增获取刚添加的规格的id 修改获取需要修改的规格id
				$specId=$specId?$specId:$specInfo->getLastInsID();
				$specInfo->afterSave($specId);
				//保存规格项 end
				//操作成功时
				$specInfo->commit();
				return json(['message'=>'ok','status'=>1]);
			}catch(\Exception $e){
				$specInfo->rollback();
				return json(['message'=>$e->getMessage()]);
			}
		}
	}


}