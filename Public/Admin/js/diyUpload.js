/* 
*	jQuery文件上传插件,封装UI,上传处理操作采用Baidu WebUploader;
*	@Author 黑爪爪;
*/
(function( $ ) {
    $.fn.extend({
		/*
		*	上传方法 opt为参数配置;
		*	serverCallBack回调函数 每个文件上传至服务端后,服务端返回参数,无论成功失败都会调用 参数为服务器返回信息;
		*/
        diyUpload:function( opt, serverCallBack ) {
 			if ( typeof opt != "object" ) {
				alert('参数错误!');
				return;	
			}
			var $fileInput = $(this);
			var $fileInputId = $fileInput.attr('id');
			//组装参数;
			if( opt.url ) {
				opt.server = opt.url; 
				delete opt.url;
			}
			if( opt.success ) {
				var successCallBack = opt.success;
				delete opt.success;
			}
			
			if( opt.error ) {
				var errorCallBack = opt.error;
				delete opt.error;
			}
			
			//迭代出默认配置
			$.each( getOption( '#'+$fileInputId ),function( key, value ){
					opt[ key ] = opt[ key ] || value; 
			});
			
			if ( opt.buttonText ) {
				opt['pick']['label'] = opt.buttonText;
				delete opt.buttonText;	
			}
			
			var webUploader = getUploader( opt );
			
			if ( !WebUploader.Uploader.support() ) {
				alert( ' 上传组件不支持您的浏览器！');
				return false;
       		}
			
			//绑定文件加入队列事件;
			webUploader.on('fileQueued', function( file ) {
				createBox( $fileInput, file ,webUploader);
			});
			
			//进度条事件
			webUploader.on('uploadProgress',function( file, percentage){
				var $fileBox = $('#fileBox_'+file.id);
				var $diyBar = $fileBox.find('.diyBar');	
				$diyBar.show();
				percentage = percentage*100;
				showDiyProgress( percentage.toFixed(2), $diyBar);
				
			});
			
			//全部上传结束后触发;
			webUploader.on('uploadFinished', function(){
				$fileInput.next('.parentFileBox').children('.diyButton').remove();
			});
			//绑定发送至服务端返回后触发事件;
			webUploader.on('uploadAccept', function( object ,data ){
				if ( serverCallBack ) serverCallBack( data );
			});
			
			//上传成功后触发事件;
			webUploader.on('uploadSuccess',function( file, response ){
				var $fileBox = $('#fileBox_'+file.id);
				var $diyBar = $fileBox.find('.diyBar');	
				$fileBox.removeClass('diyUploadHover');
				$diyBar.fadeOut( 1000 ,function(){
					$fileBox.children('.diySuccess').show();
				});
				if ( successCallBack ) {
					successCallBack( response );
				}	
			});
			
			//上传失败后触发事件;
			webUploader.on('uploadError',function( file, reason ){
				var $fileBox = $('#fileBox_'+file.id);
				var $diyBar = $fileBox.find('.diyBar');	
				showDiyProgress( 0, $diyBar , '上传失败!' );
				var err = '上传失败! 文件:'+file.name+' 错误码:'+reason;
				if ( errorCallBack ) {
					errorCallBack( err );
				}
			});
			
			//选择文件错误触发事件;
			webUploader.on('error', function( code ) {
				var text = '';
				switch( code ) {
					case  'F_DUPLICATE' : text = '该文件已经被选择了!' ;
					break;
					case  'Q_EXCEED_NUM_LIMIT' : text = '上传文件数量超过限制!' ;
					break;
					case  'F_EXCEED_SIZE' : text = '文件大小超过限制!';
					break;
					case  'Q_EXCEED_SIZE_LIMIT' : text = '所有文件总大小超过限制!';
					break;
					case 'Q_TYPE_DENIED' : text = '文件类型不正确或者是空文件!';
					break;
					default : text = '未知错误!';
 					break;	
				}
            	alert( text );
        	});
        }
    });
	
	//Web Uploader默认配置;
	function getOption(objId) {
		/*
		*	配置文件同webUploader一致,这里只给出默认配置.
		*	具体参照:http://fex.baidu.com/webuploader/doc/index.html
		*/
		return {
			//按钮容器;
			pick:{
				id:objId,
				label:"点击选择图片"
			},
			//类型限制;
			accept:{
				title:"Images",
				extensions:"gif,jpg,jpeg,bmp,png",
				mimeTypes:"image/*"
			},
			//配置生成缩略图的选项
			thumb:{
				width:170,
				height:150,
				// 图片质量，只有type为`image/jpeg`的时候才有效。
				quality:70,
				// 是否允许放大，如果想要生成小图的时候不失真，此选项应该设置为false.
				allowMagnify:false,
				// 是否允许裁剪。
				crop:true,
				// 为空的话则保留原有图片格式。
				// 否则强制转换成指定的类型。
				type:"image/jpeg"
			},
			//文件上传方式
			method:"POST",
			//服务器地址;
			server:"",
			//是否已二进制的流的方式发送文件，这样整个上传内容php://input都为文件内容
			sendAsBinary:false,
			// 开起分片上传。 thinkphp的上传类测试分片无效,图片丢失;
			chunked:true,
			// 分片大小
			chunkSize:512000 * 102400 * 1024,
			//最大上传的文件数量, 总文件大小,单个文件大小(单位字节);
			fileNumLimit:1000,
			fileSizeLimit:50000000 * 1024*1024,
			fileSingleSizeLimit:50000000 * 1024*1024
		};
	}
	
	//实例化Web Uploader
	function getUploader( opt ) {

		return new WebUploader.Uploader( opt );;
	}
	
	//操作进度条;
	function showDiyProgress( progress, $diyBar, text ) {
		
		if ( progress >= 100 ) {
			progress = progress + '%';
			text = text || '上传完成';
		} else {
			progress = progress + '%';
			text = text || progress;
		}
		
		var $diyProgress = $diyBar.find('.diyProgress');
		var $diyProgressText = $diyBar.find('.diyProgressText');
		$diyProgress.width( progress );
		$diyProgressText.text( text );
	
	}
	
	//取消事件;	
	function removeLi ( $li ,file_id ,webUploader) {
		webUploader.removeFile( file_id );
		if ( $li.siblings('li').length <= 0 ) {
			$li.parents('.parentFileBox').remove();
		} else {
			$li.remove();
		}
		
	}
	
	//创建文件操作div;	
	function createBox( $fileInput, file, webUploader ) {
		var file_id = file.id;
		var $parentFileBox = $fileInput.next('.parentFileBox');
		
		/**上传按钮样式**/
		/**diybuttontop距上距离,diybuttonh高度**/
		var diybuttontop=$fileInput.data('diybuttontop');
		var diybuttonh=$fileInput.data('diybuttonh');
		var diybuttonsize=$fileInput.data('diybuttonsize');
		var btnbackgrounds=$fileInput.data('btnbackground');
		var btncolors=$fileInput.data('btncolor');
		var btnradius2=$fileInput.data('btnradius');
		var burstingLayerheight=$('.burstingLayer').height();
		var letterMaskeds=$('.letterMasked').height();
		var titheight=$('.tile-body').height();
		$('.parentFileBox .fileBoxUl').css({'height':'395px','overflow-y':'visible'});
		
		$('.letterMasked').css({'height':titheight+300+'px'});
		if(diybuttontop==0 || diybuttontop=='' || diybuttontop==undefined){
			diybuttontop=5;
		}
		if(diybuttonh==0 || diybuttonh=='' || diybuttonh==undefined){
			diybuttonh=1000;
		}
		if(diybuttonsize==0 || diybuttonsize=='' || diybuttonsize==undefined){
			diybuttonsize=14;
		}
		if(btnbackgrounds==0 || btnbackgrounds=='' || btnbackgrounds==undefined){
			btnbackgrounds="#00b7ee";
		}
		if(btncolors==0 || btncolors=='' || btncolors==undefined){
			btncolors="#fff";
		}
		if(btnradius2==0 || btnradius2=='' || btnradius2==undefined){
			btnradius2=0;
		}
		
		//添加父系容器;
		if ( $parentFileBox.length <= 0 ) {
			var div = '<div class="parentFileBox"> \
						<ul class="fileBoxUl"></ul>\
					</div>';
			$fileInput.after( div );
			$parentFileBox = $fileInput.next('.parentFileBox');
		
		}
		
		//创建按钮
		if ( $parentFileBox.find('.diyButton').length <= 0 ) {
			
			var div = '<div class="diyButton"> \
						<a class="diyStart" href="javascript:void(0)">开始上传</a> \
						<a class="diyCancelAll" href="javascript:void(0)">全部取消</a> \
					</div>';
			$parentFileBox.append( div );
			var $startButton = $parentFileBox.find('.diyStart');
			var $cancelButton = $parentFileBox.find('.diyCancelAll');
			
			//开始上传,暂停上传,重新上传事件;
			var uploadStart = function (){
				webUploader.upload();
				$startButton.text('暂停上传').one('click',function(){
					webUploader.stop();
					$(this).text('继续上传').one('click',function(){
						uploadStart();
					});
				});
			}
				
			//绑定开始上传按钮;
			$startButton.one('click',uploadStart);
			
			//绑定取消全部按钮;
			$cancelButton.bind('click',function(){
				var fileArr = webUploader.getFiles( 'queued' );
				$.each( fileArr ,function( i, v ){
					removeLi( $('#fileBox_'+v.id), v.id, webUploader );
				});
			});
		
		}
		//添加子容器;
		var li = '<li id="fileBox_'+file_id+'" class="diyUploadHover"> \
					<div class="viewThumb"></div> \
					<div class="diyCancel"></div> \
					<div class="diySuccess"></div> \
					<div class="diyFileName">'+file.name+'</div>\
					<div class="diyBar"> \
							<div class="diyProgress"></div> \
							<div class="diyProgressText">0%</div> \
					</div> \
				</li>';
				
		$parentFileBox.children('.fileBoxUl').append( li );
		$parentFileBox.children('.diyButton').css({'margin-top':diybuttontop+'px','height':diybuttonh+'px'});
		$parentFileBox.children('.diyButton').children('a').css({'padding':diybuttonh/4+'px 10px','font-size':diybuttonsize+'px','background':btnbackgrounds,'color':btncolors,'border-radius':btnradius2+'px'});
		$parentFileBox.children('.diyButton').children('a').hover(function(){
			$(this).css({'color':'red'});
		},function(){
			$(this).css({'color':btncolors});
		});
		//父容器宽度;
		var $width = $('.fileBoxUl>li').length * 180;
		var $maxWidth = $fileInput.parent().width();
		$width = $maxWidth > $width ? $width : $maxWidth;
		//$parentFileBox.width( $width );
		//$parentFileBox.width("100%");
		$parentFileBox.css({'width':'100%','margin-top':'5px'});
		
		var $fileBox = $parentFileBox.find('#fileBox_'+file_id);

		//绑定取消事件;
		var $diyCancel = $fileBox.children('.diyCancel').one('click',function(){
			removeLi( $(this).parent('li'), file_id, webUploader );
		});
		
		if ( file.type.split("/")[0] != 'image' ) {
			var liClassName = getFileTypeClassName( file.name.split(".").pop() );
			$fileBox.addClass(liClassName);
			return;	
		}
		//生成预览缩略图;
		webUploader.makeThumb( file, function( error, dataSrc ) {
			if ( !error ) {	
				$fileBox.find('.viewThumb').append('<img src="'+dataSrc+'" >');
			}
		});	
	}
	
	//获取文件类型;
	function getFileTypeClassName ( type ) {
		var fileType = {};
		var suffix = '_diy_bg';
		fileType['pdf'] = 'pdf';
		fileType['zip'] = 'zip';
		fileType['rar'] = 'rar';
		fileType['csv'] = 'csv';
		fileType['doc'] = 'doc';
		fileType['xls'] = 'xls';
		fileType['xlsx'] = 'xls';
		fileType['txt'] = 'txt';
		fileType['rtf'] = 'rtf';
		fileType['docx'] = 'docx';
		fileType['tar'] = 'tar';
		fileType['jar'] = 'jar';
		fileType['bz2'] = 'bz2';
		fileType['wpd'] = 'wpd';
		fileType['wps'] = 'wps';
		fileType['wpt'] = 'wpt';
		fileType['ppt'] = 'ppt';
		fileType['pptx'] = 'pptx';
		fileType = fileType[type] || 'txt';
		return 	fileType+suffix;
	}
	
})( jQuery );

//PDF激活上传
function fileload(as){
	
	var fileroad=$("#"+as).data("fileroad");//文件路径类型
	var btnname=$("#"+as).data("btnname");//上传按钮的名称
	var filename=$("#"+as).data("filename");//上传文件路径name
	var rawname=$("#"+as).data("rawname");//上传文件原名称name
	var URLL=WEBURLS+'/Common/uploadify?fileroad='+fileroad;
	
	$("#"+as).diyUpload({
		url:URLL,
		success:function( data ) {
			$('input[name="filepdfname"]').val(data.result.path);
			$('#uploadeDivftp').html('上传成功');
			var titheights=$('.tile-body').height();
			if(!titheights){
				var titheights=$('body').height();
			}
			$('.letterMasked').css({'height':titheights+200+'px'});
		},
		error:function( err ) {
			console.info( err );			
		},
		buttonText : btnname,
		chunked:true,
		// 分片大小
		chunkSize:5120000 * 102400 * 1024,
		//最大上传的文件数量, 总文件大小,单个文件大小(单位字节);
		fileNumLimit:500,
		fileSizeLimit:50000000 * 102400*1024,
		fileSingleSizeLimit:5000000 * 102400*1024,
		accept: {}
	});
	//样式
	$('#'.as).ready(function(){
		var width=$("#"+as).parent().width();//宽度
		
		var btnbackground=$("#"+as).data('btnbackground');//选择按钮背景颜色
		var btnradius=$("#"+as).data('btnradius');//选择按钮圆角
		var btncolor=$("#"+as).data('btncolor');//选择按钮字体颜色
		var btnpadding=$("#"+as).data('btnpadding');//选择按钮padding
		var btnfontsize=$("#"+as).data('btnfontsize');//选择按钮字体大小
		var btnwidth=$("#"+as).data('btnwidth');//选择按钮width
		
		/**选择按钮样式**/
		/**btnbackground背景颜色,btnradius圆角,btncolor字体颜色,btnpadding上下内补白,4padding左右内补白,btnfontsize字体大小,btnwidth宽度**/
		if(btnbackground==0 || btnbackground=='' || btnbackground==undefined){
			btnbackground="#00b7ee";
		}
		if(btnradius==0 || btnradius=='' || btnradius==undefined){
			btnradius=0;
		}
		if(btncolor==0 || btncolor=='' || btncolor==undefined){
			btncolor="#fff";
		}
		if(btnpadding==0 || btnpadding=='' || btnpadding==undefined){
			btnpadding="10";
		}
		if(btnfontsize==0 || btnfontsize=='' || btnfontsize==undefined){
			btnfontsize="15";
		}
		if(btnwidth==0 || btnwidth=='' || btnwidth==undefined){
			btnwidth=width;
		}
		
		$("#"+as).children('.webuploader-pick').css({'background':btnbackground,'border-radius':btnradius+'px','color':btncolor,'padding':btnpadding+'px 0px '+btnpadding+'px 0px','font-size':btnfontsize+'px','width':btnwidth+'px','margin':'auto'});
	});
}
//企业名录激活上传
function fileloads(as){
	
	var fileroad=$("#"+as).data("fileroad");//文件路径类型
	var btnname=$("#"+as).data("btnname");//上传按钮的名称
	var filename=$("#"+as).data("filename");//上传文件路径name
	var rawname=$("#"+as).data("rawname");//上传文件原名称name
	var URLL=WEBURLS+'/Common/uploadify?fileroad='+fileroad;
	
	$("#"+as).diyUpload({
		url:URLL,
		success:function( data ) {
			console.info( data );
			$(".uploadContent").append("<input name='"+filename+"[]' type='hidden' value='"+data.result['path']+"'/>");
			$(".uploadContent").append("<input name='"+rawname+"[]' type='hidden' value='"+data.result['rawname']+"'/>");
			//Excel
			$(".uploadContentExcel").append("<input name='"+filename+"[]' class='"+filename+"' type='hidden' value='"+data.result['path']+"'/>");
			$(".uploadContentExcel").append("<input name='"+rawname+"[]' class='"+rawname+"' type='hidden' value='"+data.result['rawname']+"'/>");
			
			
			
			
			$('.tableFile').append("<tr><td>"+data.result['rawname']+"</td></tr>");
			var titheights=$('.tile-body').height();
			if(!titheights){
				var titheights=$('body').height();
			}
			$('.letterMasked').css({'height':titheights+200+'px'});
		},
		error:function( err ) {
			console.info( err );			
		},
		buttonText : btnname,
		chunked:true,
		// 分片大小
		chunkSize:51200 * 1024 * 1024,
		//最大上传的文件数量, 总文件大小,单个文件大小(单位字节);
		fileNumLimit:500,
		fileSizeLimit:50000000 * 1024*1024,
		fileSingleSizeLimit:5000000 * 1024*1024,
		accept: {}
	});
	//样式
	$('#'.as).ready(function(){
		var width=$("#"+as).parent().width();//宽度
		
		var btnbackground=$("#"+as).data('btnbackground');//选择按钮背景颜色
		var btnradius=$("#"+as).data('btnradius');//选择按钮圆角
		var btncolor=$("#"+as).data('btncolor');//选择按钮字体颜色
		var btnpadding=$("#"+as).data('btnpadding');//选择按钮padding
		var btnfontsize=$("#"+as).data('btnfontsize');//选择按钮字体大小
		var btnwidth=$("#"+as).data('btnwidth');//选择按钮width
		
		/**选择按钮样式**/
		/**btnbackground背景颜色,btnradius圆角,btncolor字体颜色,btnpadding上下内补白,4padding左右内补白,btnfontsize字体大小,btnwidth宽度**/
		if(btnbackground==0 || btnbackground=='' || btnbackground==undefined){
			btnbackground="#00b7ee";
		}
		if(btnradius==0 || btnradius=='' || btnradius==undefined){
			btnradius=0;
		}
		if(btncolor==0 || btncolor=='' || btncolor==undefined){
			btncolor="#fff";
		}
		if(btnpadding==0 || btnpadding=='' || btnpadding==undefined){
			btnpadding="10";
		}
		if(btnfontsize==0 || btnfontsize=='' || btnfontsize==undefined){
			btnfontsize="15";
		}
		if(btnwidth==0 || btnwidth=='' || btnwidth==undefined){
			btnwidth=width;
		}
		
		$("#"+as).children('.webuploader-pick').css({'background':btnbackground,'border-radius':btnradius+'px','color':btncolor,'padding':btnpadding+'px 0px '+btnpadding+'px 0px','font-size':btnfontsize+'px','width':btnwidth+'px','margin':'auto'});
	});
}