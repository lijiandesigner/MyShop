<?php
namespace app\admin\model;

use think\Model;

class SpecItem extends Model
{
	//映射规格项和规格的多对一关系
	public function spec(){
		return $this->belongsTo('Spec','spec_id');
	}
}