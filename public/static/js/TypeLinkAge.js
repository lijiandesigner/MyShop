$(function(){
	
	var bindChange=function($parent,$child){
		$parent.bind('change',function(){
			var fid=$parent.prop('value');
			$child.find('option:gt(0)').remove();
			if(parseInt(fid)){
				$.ajax({
					url:'..\\Category\\GetChildTypes',
					data:{'id':fid},
					type:'POST'
				}).done(function(data){
					for(var i = 0; i<data.length; i++) {
						var option=document.createElement('option');
								option.value=data[i].id;
						var text=document.createTextNode(data[i].name);
								option.appendChild(text);
						$child.append(option);
					}
				});
			}
		});
	}
	/*顶级菜单被改变时 填充二级*/
	bindChange($('#firstType'),$('#secondType'));
	/*二级菜单被改变时 填充三级(若有需要)*/
	if($('#threeType')){
		bindChange($('#secondType'),$('#threeType'));
	}

});