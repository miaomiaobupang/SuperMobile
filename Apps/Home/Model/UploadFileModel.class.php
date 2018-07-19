<?php
namespace Home\Model;
use Think\Model;
use Think\Upload;
/**
*栏目菜单管理
*
*/

class UploadFileModel{
	static protected $upload;
	public $error;
	public function __construct() {
		//路径可以修改为自动获取
		define( 'ROOT_PATH', realpath(dirname(__FILE__)).'/' );
		$dir = dirname(__FILE__);
		$this->upload = new Upload;
		$this->upload->maxSize   =     1048576 ;// 设置附件上传大小    
		$this->upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');
		$this->upload->rootPath  = substr($dir,0,-16).'/Public';//'/home/www/html/app.langyue.org/phprksh/Public'
		$this->upload->savePath  = '/Uploads/';
		$this->upload->autoSub = true;
		$this->upload->subName = array('date','Ymd');
		$this->upload->saveName = array('uniqid','');
	}
   
	public function upload_head() {
       	$info   =   $this->upload->uploadOne($_FILES['uploadfile']);    
		if(!$info) {
			// 上传错误提示错误信息   
			$this->error = $this->upload->rootPath.$this->upload->getError();
			return false;
		} else {
			 // 上传成功 获取上传文件信息
			return '/Public'.$info['savepath'].$info['savename'];
		}
	}
	
   /**
	*图片压缩上传
	*
	*/
	public function upload($file = "thumb"){ 
       	$info   =   $this->upload->uploadOne($_FILES[$file]);    
		if(!$info) {
		// 上传错误提示错误信息           
		$this->error = $this->upload->getError();
			return false;
		} else {
			$dir = dirname(__FILE__);
			$image = new \Think\Image(); 
			$image->open(substr($dir,0,-16).'/Public'.$info['savepath'].$info['savename']);
			$image->thumb(400,400)->save(substr($dir,0,-16).'/Public'.$info['savepath'].$info['savename']);
			 // 上传成功 获取上传文件信息
			return '/Public'.$info['savepath'].$info['savename'];
		}
	}
   
   /**
	*图片上传
	*
	*/
	public function uploadyRaw($file = "thumb"){ 
       	$info   =   $this->upload->uploadOne($_FILES[$file]);    
		file_put_contents('./img0.log',$info);
		if(!$info) {
		// 上传错误提示错误信息           
		$this->error = $this->upload->getError();
			return false;
		} else {
			$dir = dirname(__FILE__);
			$image = new \Think\Image(); 
			 // 上传成功 获取上传文件信息
			return '/Public'.$info['savepath'].$info['savename'];
			
		}
	}

	public function getImageInfo($img ){
		$imageInfo = getimagesize($img);
		if( $imageInfo!== false) {
			$imageType = strtolower(substr(image_type_to_extension($imageInfo[2]),1));
			$info = array(
					"width"		=>$imageInfo[0],
					"height"	=>$imageInfo[1],
					"type"		=>$imageType,
					"mime"		=>$imageInfo['mime'],
			);
			return $info;
		}else {
			return false;
		}
	}
	
	public function resize( $ori ){
		if( preg_match('/^http:\/\/[a-zA-Z0-9]+/', $ori ) ){
			return $ori;
		}
		$info = $this->getImageInfo( ROOT_PATH . $ori );    
		file_put_contents('./img1.log',$info);
		if( $info ){
			//上传图片后切割的最大宽度和高度
			$width = 500;
			$height = 500;
			$scrimg = ROOT_PATH . $ori;
			if( $info['type']=='jpg' || $info['type']=='jpeg' ){
				$im = imagecreatefromjpeg( $scrimg );
			}
			if( $info['type']=='gif' ){
				$im = imagecreatefromgif( $scrimg );
			}
			if( $info['type']=='png' ){
				$im = imagecreatefrompng( $scrimg );
			}
			if( $info['width']<=$width && $info['height']<=$height ){
				return;
			} else {
				if( $info['width'] > $info['height'] ){
					$height = intval( $info['height']/($info['width']/$width) );
				} else {
					$width = intval( $info['width']/($info['height']/$height) );
				}
			}
			$newimg = imagecreatetruecolor( $width, $height );
			imagecopyresampled( $newimg, $im, 0, 0, 0, 0, $width, $height, $info['width'], $info['height'] );
			imagejpeg( $newimg, ROOT_PATH . $ori );
			imagedestroy( $im );
		}
		return;
	}
}