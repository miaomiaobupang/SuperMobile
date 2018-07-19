
//================================省市县联动公共类==================================================
//初始化选择省市县数据
//获取省id
var  provinceSelect = $("#bLink").data("pid");
//获取市id
var  citySelect = $("#bLink").data("cid");
//获取区id
var  areaSelect = $("#bLink").data("aid");


$("#"+provinceSelect).change(function(){
	//更新缓存
	$(this).data('id',$(this).val());
	//执行选中
	//市和区执行初始化
	$("#"+citySelect).data('id',null);
	$("#"+areaSelect).data('id',null);
	$("#"+citySelect).html("<option>--请选择城市--</option>");
	$("#"+areaSelect).html("<option>--请选择区域--</option>");
	city(citySelect);	
});
$("#"+citySelect).change(function(){
	//更新缓存
	$(this).data('id',$(this).val());
	//执行选中
	//市和区执行初始化
	$("#"+areaSelect).data('id',null);
	$("#"+areaSelect).html("<option>--请选择区域--</option>");
	area(areaSelect);	
});
$("#"+areaSelect).change(function(){
	//更新缓存
	$(this).data('id',$(this).val());
});
//创建省信息下拉列表
province(provinceSelect);
//获取省信息的方法
function province(provinceName){
	var provinceId = $("#"+provinceName);
	$.ajax({
		url:WEBURL+'/Common/CityList',
		type:'post',
		data:'pid=6'+'&level=2',
		success:function(data){
			if(data.status == 1){
				for(var i in data.citylist){
					var provinceSelectId = provinceId.data("id");
					if(data.citylist[i].id == provinceSelectId){
						provinceId.append(
							"<option class='cityData' value='"+data.citylist[i].id+"' selected>"+data.citylist[i].name+"</option>"
						);
					}else{
						provinceId.append(
							"<option class='cityData' value='"+data.citylist[i].id+"'>"+data.citylist[i].name+"</option>"
						);
					}
				}
			}
		}
	});	
	//创建市信息下拉列表
	city(citySelect);
}
//获取市信息的方法
function city(cityName){
	var provinceId = $("#provinceSelect").data("id");
	var cityId = $("#"+cityName);
	$.ajax({
		url:WEBURL+'/Common/CityList',
		type:'post',
		data:'pid='+provinceId+'&level=3',
		success:function(data){
			if(data.status == 1){
				for(var i in data.citylist){
					var citySelectId = cityId.data("id");
					if(data.citylist[i].id == citySelectId){
						cityId.append(
							"<option class='cityData' value='"+data.citylist[i].id+"' selected>"+data.citylist[i].name+"</option>"
						);
					}else{
						cityId.append(
							"<option class='cityData' value='"+data.citylist[i].id+"'>"+data.citylist[i].name+"</option>"
						);
					}
				}
			}
		}
	});	
	//创建区域信息下拉列表
	area(areaSelect);
}
//获取区域信息的方法
function area(areaName){
	var cityId = $("#citySelect").data("id");
	var areaId = $("#"+areaName);
	$.ajax({
		url:WEBURL+'/Common/CityList',
		type:'post',
		data:'pid='+cityId+'&level=4',
		success:function(data){
			if(data.status == 1){
				for(var i in data.citylist){
					var areaSelectId = areaId.data("id");
					if(data.citylist[i].id == areaSelectId){
						areaId.append(
							"<option class='cityData' value='"+data.citylist[i].id+"' selected>"+data.citylist[i].name+"</option>"
						);
					}else{
						areaId.append(
							"<option class='cityData' value='"+data.citylist[i].id+"'>"+data.citylist[i].name+"</option>"
						);
					}
				}
			}
		}
	});	
}
//================================省市县联动公共类==================================================