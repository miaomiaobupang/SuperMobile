<?php
namespace Home\Model;
use Think\Model;
use Think\Upload;
/**
*栏目菜单管理
*
*/
class UploadFileModel
{
    static protected $upload;
    public $error;
   public function __construct() {
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
}