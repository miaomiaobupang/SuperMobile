/*展位图数据更新 */

	function syncMapData(cid,eid){
		var syncEstate=3;
		var imgYdataId=0;
		var imgNdatas=0;
		var imgYdata;
		var state=2;
		//获取展位图数据包
		$.ajax({
			//url:WEBURL+'/Exhibitionmap/getPositionImgDatas',
			url:'http://'+location.host+'/admin.php/Exhibitionmap/getPositionImgDatas',
			type:'post',
			data:'configid='+cid,
			async:false,
			success:function(data){
				if(data.status == 1){
					imgYdata = JSON.parse(data.data.imgdata); //由JSON字符串转换为JSON对象
					var imgYdataSum = imgYdata.length;
					for(var i=0;i<imgYdataSum;i++){
						if(imgYdata[i].type=="rect" && imgYdata[i].data.zhanwei.zhanwei_id==eid){
							//获取最新展位数据
							//alert(imgYdata[i].data.zhanwei.zhanwei_id);
							syncEstate=2;
							//展位数据位置
							imgYdataId = i;
						}
					}
				}
			}
		});	
		if(syncEstate == 2 && imgYdataId != 0){
			//获取展会最新数据
			$.ajax({
				//url:WEBURL+'/Common/CityList',
				url:'http://'+location.host+'/admin.php/Exhibitionmap/getIdPositionData',
				type:'post',
				data:'id='+eid,
				async:false,
				success:function(data){
					if(data.status == 1){
						syncEstate=1;
						imgNdatas = data.data;
					}
				}
			});	
		}
		if(syncEstate==1 && imgNdatas != 0){
			//更新数据
			imgYdata[imgYdataId].data.zhanwei.zhanwei_area = imgNdatas.area;
			imgYdata[imgYdataId].data.zhanwei.zhanwei_buildimg = imgNdatas.buildimg;
			imgYdata[imgYdataId].data.zhanwei.zhanwei_companyname = imgNdatas.companyName;
			imgYdata[imgYdataId].data.zhanwei.zhanwei_configtxt = imgNdatas.configTxt;
			imgYdata[imgYdataId].data.zhanwei.zhanwei_group = imgNdatas.salestate;
			imgYdata[imgYdataId].data.zhanwei.zhanwei_kaikou = imgNdatas.opennum;
			imgYdata[imgYdataId].data.zhanwei.zhanwei_number = imgNdatas.number;
			imgYdata[imgYdataId].data.zhanwei.zhanwei_price = imgNdatas.price;
			imgYdata[imgYdataId].data.zhanwei.zhanwei_salestate = imgNdatas.salestate;
			imgYdata[imgYdataId].data.zhanwei.zhanwei_sign = imgNdatas.sign;
			imgYdata[imgYdataId].data.zhanwei.zhanwei_size = imgNdatas.width+"*"+imgNdatas.long;
			if(imgNdatas.salestate==1){
				//未预定
				imgYdata[imgYdataId].attrs.fill = '#34e952';
			}else if(imgNdatas.salestate==2){
				//已预订
				imgYdata[imgYdataId].attrs.fill = '#f3f29e';
			}else if(imgNdatas.salestate==3){
				//已购买
				imgYdata[imgYdataId].attrs.fill = '#fdb0c8';
			}else if(imgNdatas.salestate==4){
				//已线下销售
				imgYdata[imgYdataId].attrs.fill = '#fdb0c8';
			}else if(imgNdatas.salestate==5){
				//已过期
				imgYdata[imgYdataId].attrs.fill = '#fdb0c8';
			}else{
				imgYdata[imgYdataId].attrs.fill = '#fdb0c8';
			}
			var imgdatas=JSON.stringify(imgYdata);
			if(imgdatas){
				//数据更新完成，请求保存数据
				$.ajax({
					//url:WEBURL+'/Exhibitionmap/getPositionImgDatas',
					url:'http://'+location.host+'/admin.php/Exhibitionmap/savePositionImgDatas',
					type:'post',
					data:'configid='+cid+'&imgdata='+imgdatas,
					async:false,
					success:function(data){
						if(data.status == 1){
							state=1;
						}
					}
				});
			}
		}
		return state;
	}

