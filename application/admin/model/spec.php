<?php
namespace app\admin\model;

use think\Model;

class Spec extends Model
{

	/**
     * 后置操作方法
     * 自定义的一个函数 用于数据保存后做的相应处理操作, 使用时手动调用
     * @param int $id 规格id
     */
	public function afterSave($id){
		$item=new SpecItem;
		$specItems=input('post.specItems');
		$items=explode(PHP_EOL,$specItems);//替换空格
		foreach($items as $key=>$value){
			$value = str_replace('_', '', $value);
			$value = str_replace('@', '', $value);//替换特殊字符
			$value = trim($value);
			if(empty($value)){
			 	unset($items[$key]);
			}else{
			 //项不为空时
				$items[$key]=$value;
			}
		}
		

		//获取数据库中原有的规格项
		$db_items=db('Spec_item')->where('spec_id','=',$id)->field('id,name')->select();
		$db_items_array=array();//将数据库中查询出的数组对象数组 转换为ID为key的关系数组
		foreach ($db_items as $value) {
			$db_items_array[$value['id']]=$value['name'];
		}
		/*提交过来的数据 同数据库中比较 数据库中没有则新增*/
		$saveList=[];//创建存储需要持久化规格项的数组
		foreach ($items as $key=>$value) {
			if(!in_array($value,$db_items_array)){
				$saveList[]=array('spec_id'=>$id,'name'=>$value);
			}
		}
		$saveList&&$item->saveAll($saveList);//新增规格项
		/*提交过来的数据 同数据库中比较 数据库中没有则删除*/
		foreach ($db_items_array as $key => $value) {
			if (!in_array($value,$items)){
				//规格项被移除时
				//删除当前规格项 在产品规格价格表中的引用数据
				/*
				*待开发
				*/
				//删除当前规格项
				SpecItem::where('id','=',$key)->delete();
			}
		}		
	}

	static public function getAllPages(){
		$result=Spec::order('id desc')->paginate(5);
		return $result;
	}
	//映射规格和模型之间的多对一关系
	public function goodsModel(){
		return $this->belongsTo('GoodsModel','category_id');
	}
	//映射规格和规格项的一对多关系
	public function specItems(){
		return $this->hasMany('SpecItem');
	}
	
	
}