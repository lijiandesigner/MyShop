<?php
namespace app\mobile\controller;
use think\Controller;

class Category extends Controller{

	public function index()
	{

		dump($this->category_list());

		//return $this->fetch();
	}

	
	public function category_list()
	{
		$top_category=db('goods_category')->where('parent_id',0)->select();

		foreach ($top_category as $key => $value) {
			$tmp_category=db('goods_category')->select();

			foreach ($tmp_category as $key2 => $value2) {
				if($value2['parent_id']==$value['id'])
				{
					$categorys[$value['name']][]=[
						'id'=>$value2['id'],
						'name'=>$value2['name'],
						'image'=>$value2['image']
						];
				}
			}	
		}
		return $categorys;
	}


/*	public function category_list()
	{
		$top_category=db('goods_category')->where('parent_id',0)->select();

		while($top_category){

		$categorys['id']=$top_category['id'];	

	}
 	*/

}


/*array(
	array("id"=>18,"name"=>'电脑','child'=>array(

		)
	)*/