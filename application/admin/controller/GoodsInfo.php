<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Goods;
use app\admin\logic\GoodLogic;

class GoodsInfo extends controller
{
	public function showGoods(){
		$goodss=Goods::getGoodsInfo();
		$this->assign('goodss',$goodss);
		return $this->fetch('GoodsInfo/ShowGoods');
	}
	public function goodsManage(){
		if(request()->isGet()){
			$logic=new GoodLogic;
			$topTypes=$logic->getTypeChildById();
			$this->assign('firstTypes',$topTypes);
			//注册分类显示级别 1 2 3
			$this->assign('floor',2);
			//$this->initEditor();//编辑器
			return $this->fetch('GoodsManage');
		}else if(request()->isAjax()){
			
		}
	}
	public function delGoods($id=0){
		return 1;
	}
	/**
	*编辑器初始化
	*http://fex.baidu.com/ueditor/
	*/
	private function initEditor()
    {
        $this->assign("URL_upload", U('admin/Ueditor/imageUp',array('savepath'=>'goods'))); // 图片上传目录
        $this->assign("URL_imageUp", U('admin/Ueditor/imageUp',array('savepath'=>'article'))); //  不知道啥图片
        $this->assign("URL_fileUp", U('admin/Ueditor/fileUp',array('savepath'=>'article'))); // 文件上传s
        $this->assign("URL_scrawlUp", U('admin/Ueditor/scrawlUp',array('savepath'=>'article')));  //  图片流
        $this->assign("URL_getRemoteImage", U('admin/Ueditor/getRemoteImage',array('savepath'=>'article'))); // 远程图片管理
        $this->assign("URL_imageManager", U('admin/Ueditor/imageManager',array('savepath'=>'article'))); // 图片管理        
        $this->assign("URL_getMovie", U('admin/Ueditor/getMovie',array('savepath'=>'article'))); // 视频上传
        $this->assign("URL_Home", "");
    }
}