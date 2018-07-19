<?php
namespace Home\Model;
use Think\Model;
// +----------------------------------------------------------------------
// | 非速搜WEB项目
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://fairso.com All rights reserved.
// +----------------------------------------------------------------------
// | 展会系统-展位图模块
// +----------------------------------------------------------------------
// | Author: 狼啸 <11740390@qq.com>
// +----------------------------------------------------------------------
class ExhibitionMapModel{
    static protected $upload;
    protected $loguser;
    public $error;
	private $exhibition_venue;//展会场馆表
	private $citys;//城市表
	private $exhibition_venue_config;//城市表
	private $exhibition_position;//展位表
	private $real_enterprise_auth;//企业表
	public function __construct() {
		$this->exhibition_venue     = D('exhibition_venue');
		$this->citys     = D('citys');
		$this->exhibition_venue_config     = D('exhibition_venue_config');
		$this->exhibition_position  = D('exhibition_position');
		$this->real_enterprise_auth  = D('real_enterprise_auth');
		$this->loguser=D('log_user');
		$this->logrecord=D('log_record');
		//初始化redis缓存
		$this->redis = S(array());
		
      /*  $dir = dirname(__FILE__);
       $this->upload = new Upload;
       $this->upload->maxSize   =     1048576 ;// 设置附件上传大小    
       $this->upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');
       $this->upload->rootPath  = substr($dir,0,-16).'/Public';//'/home/www/html/app.langyue.org/phprksh/Public'
       $this->upload->savePath  = '/Uploads/';
       $this->upload->autoSub = true;
       $this->upload->subName = array('date','Ymd');
       $this->upload->saveName = array('uniqid',''); */
   }
   
   /* 
   *	名称：同步展位图(SVG)数据（syncPositionMap）
   *	参数：	configid 	展会分展馆配置表ID
   *			eid 		展位ID
   *	返回：	1		更新成功
   *			2		参数获取失败
   *			3		展位图数据包获取失败
   *			4		展位图数据包JSON解析失败
   *			5		展位图数据包更新失败：没有查找到更新项
   *			6		展位图数据包转化为JSON数据失败
   *			7		展位图数据包保存失败
   */
   public function syncPositionMap($configid,$eid){
		$state=2;
		if($configid&&$eid){
			//获取展位图数据包
			$res=$this->exhibition_venue_config->where('id='.$configid)->field('imgdata')->find();
			if($res){
				$res=$res['imgdata'];
				$mapdata=json_decode($res);
				if($mapdata){
					$mapdataNum=count($mapdata);
					for($i=0;$i<$mapdataNum;$i++){
						if($mapdata[$i]->type=="rect"&&$mapdata[$i]->data->zhanwei->zhanwei_id==$eid){
							//获取展位最新数据
							$positionNew=$this->exhibition_position->where("id=".$eid." and state=1")->find();
							if($positionNew){
								//处理展位最新数据
								$positionNew['companyName'] = $this->real_enterprise_auth->getFieldById($positionNew['bid'],'name');
								if($positionNew['configtype']==1){
									$positionNew['configTxt'] = '【标展】';
									if($positionNew['config1']){
										$positionNew['configTxt'] .= ' 楣板*'.$positionNew['config1'];
									}
									if($positionNew['config2']){
										$positionNew['configTxt'] .= ' 地毯*'.$positionNew['config2'];
									}
									if($positionNew['config3']){
										$positionNew['configTxt'] .= ' 咨询台（带锁）*'.$positionNew['config3'];
									}
									if($positionNew['config4']){
										$positionNew['configTxt'] .= ' 咨询台（不带锁）*'.$positionNew['config4'];
									}
									if($positionNew['config5']){
										$positionNew['configTxt'] .= ' 洽谈桌（方桌）*'.$positionNew['config5'];
									}
									if($positionNew['config6']){
										$positionNew['configTxt'] .= ' 洽谈桌（圆桌）*'.$positionNew['config6'];
									}
									if($positionNew['config7']){
										$positionNew['configTxt'] .= ' 洽谈椅*'.$positionNew['config7'];
									}
									if($positionNew['config8']){
										$positionNew['configTxt'] .= ' 层板*'.$positionNew['config8'];
									}
									if($positionNew['config9']){
										$positionNew['configTxt'] .= ' 垃圾篓*'.$positionNew['config9'];
									}
									if($positionNew['config10']){
										$positionNew['configTxt'] .= ' 资料架*'.$positionNew['config10'];
									}
									if($positionNew['config11']){
										$positionNew['configTxt'] .= ' 射灯*'.$positionNew['config11'];
									}
									if($positionNew['config12']){
										$positionNew['configTxt'] .= ' 射灯*'.$positionNew['config12'];
									}
								}else{
									$positionNew['configTxt'] = '【光地】';
								}
								//更新展位数据
								$mapdata[$i]->data->zhanwei->zhanwei_area=$positionNew['area'];
								$mapdata[$i]->data->zhanwei->zhanwei_buildimg=$positionNew['buildimg'];
								$mapdata[$i]->data->zhanwei->zhanwei_companyname=$positionNew['companyName'];
								$mapdata[$i]->data->zhanwei->zhanwei_configtxt=$positionNew['configTxt'];
								$mapdata[$i]->data->zhanwei->zhanwei_group=$positionNew['salestate'];
								$mapdata[$i]->data->zhanwei->zhanwei_kaikou=$positionNew['opennum'];
								$mapdata[$i]->data->zhanwei->zhanwei_number=$positionNew['number'];
								$mapdata[$i]->data->zhanwei->zhanwei_price=$positionNew['price'];
								$mapdata[$i]->data->zhanwei->zhanwei_salestate=$positionNew['salestate'];
								$mapdata[$i]->data->zhanwei->zhanwei_sign=$positionNew['sign'];
								$mapdata[$i]->data->zhanwei->zhanwei_size=$positionNew['width']."*".$positionNew['long'];
								if($positionNew['salestate']==1){
									//未预定
									$mapdata[$i]->attrs->fill='#34e952';
								}else if($positionNew['salestate']==2){
									//已预订
									$mapdata[$i]->attrs->fill='#f3f29e';
								}else if($positionNew['salestate']==3){
									//已购买
									$mapdata[$i]->attrs->fill='#fdb0c8';
								}else if($positionNew['salestate']==4){
									//已线下销售
									$mapdata[$i]->attrs->fill='#fdb0c8';
								}else if($positionNew['salestate']==5){
									//已过期
									$mapdata[$i]->attrs->fill='#fdb0c8';
								}else{
									$mapdata[$i]->attrs->fill='#fdb0c8';
								}
								$state=1;
							}
						}
					}
					
					//数据更新完成转化JSON数据
					if($state==1){
						$mapdataJson=json_encode($mapdata);
						if($mapdataJson){
							//执行保存
							$updataState=$this->exhibition_venue_config->where('id='.$configid)->setField('imgdata',$mapdataJson);
							if($updataState){
								return 1;
							}else{
								//展位图数据包保存失败
								return 7;
							}
						}else{
							//展位图数据包转化为JSON数据失败
							return 6;
						}
					}else{
						//展位图数据包更新失败：没有查找到更新项
						return 5;
					}
				}else{
					//展位图数据包JSON解析失败
					return 4;
				}
			}else{
				//展位图数据包获取失败
				return 3;
			}
		}else{
			//参数获取失败
			return 2;
		}
		
	}
   
   
}