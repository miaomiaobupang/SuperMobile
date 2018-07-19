<?php
namespace Home\Model;
use Think\Model;
use Home\Model\ExhibitionMapModel as ExhibitionMap;
// +----------------------------------------------------------------------
// | 非速搜WEB项目
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://fairso.com All rights reserved.
// +----------------------------------------------------------------------
// | 展会系统-展位图模块
// +----------------------------------------------------------------------
// | Author: 狼啸 <11740390@qq.com>
// +----------------------------------------------------------------------
class PlanModel{
    static protected $upload;
    protected $loguser;
    public $error;
	private $exhibition_venue;//展会场馆表
	private $citys;//城市表
	private $exhibition_venue_config;//城市表
	private $exhibition_ledger_total;//反馈管理
	private $exhibition_ledger_list;//反馈管理
	private $exhibition;//反馈管理
	private $order;//反馈管理
	private $plan_price;//反馈管理
	private $plan_task;//反馈管理
	private $exhibition_position;//展位表
	private $real_enterprise_auth;//企业表
	private $ExhibitionMap;//企业表
	public function __construct() {
		$this->exhibition_venue     = D('exhibition_venue');
		$this->exhibition_ledger_total = D('exhibition_ledger_total');
		$this->exhibition_ledger_list = D('exhibition_ledger_list');
		$this->exhibition = D('exhibition');
		$this->exhibition_position = D('exhibition_position');
		$this->order = D('order');
		$this->plan_price = D('plan_price');
		$this->plan_task = D('plan_task');
		$this->exhibition_ledger_total     = D('exhibition_ledger_total');
		$this->citys     = D('citys');
		$this->exhibition_venue_config     = D('exhibition_venue_config');
		$this->real_enterprise_auth  = D('real_enterprise_auth');
		$this->loguser=D('log_user');
		$this->logrecord=D('log_record');
		$this->ExhibitionMap = new ExhibitionMap();
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
   *	名称：订单网站已确认创建金额计划
   *	参数：	id 		订单ID
   *	返回：	1		执行创建金额计划成功
   *			2		查询不到符合条件的订单信息
   *			3		展位图数据包获取失败
   *			4		展位图数据包JSON解析失败
   *			5		展位图数据包更新失败：没有查找到更新项
   *			6		展位图数据包转化为JSON数据失败
   *			7		展位图数据包保存失败
   */
	public function orderFinsh($id){
		//查询企业金额账户ID
		$orderInfo=$this->order->where('id='.$id.' and statecode=3 and state=1')->find();
		if($orderInfo){
			//查询是否已创建计划金额
			$plans=$this->plan_price->where('oid='.$id)->find();
			if(!$plans){
				//删除订单超时相关计划任务
				$planTaskWhere=null;
				$planTaskWhere['pid']  = $orderInfo['id'];
				$planTaskWhere['ido']  = $orderInfo['uid'];
				$planTaskWhere['type']  = array('in','1,2');
				$this->plan_task->where($planTaskWhere)->delete();
				//拨款至平台资金池
				$Ztotalstate=$this->exhibition_ledger_total->where('id=1')->setInc('total',$orderInfo['price']);
				if($Ztotalstate){
					//创建打款记录
					$dataTotal=null;
					function signNum($signs){
						$signNum=time().rand_num(5,1);
						$read=$signs->where('sign='.$signNum)->find();
						if($read){
							signNum($signs);
						}else{
							return $signNum;
						}
					}
					$dataTotal['sign']=signNum($this->exhibition_ledger_list);
					$dataTotal['eid']=0;
					$dataTotal['tid']=1;
					$dataTotal['price']=$orderInfo['price'];
					$dataTotal['type']=1;
					$dataTotal['pricetype']=1;
					$dataTotal['oid']=$orderInfo['id'];
					$dataTotal['stime']=time();
					$dataTotal['explain']="订单".$orderInfo['title']."（展位ID:".$orderInfo['gid']."）的资金池打款";
					$dataTotal['statecode']=1;
					$this->exhibition_ledger_list->add($dataTotal);
				}
				//查询企业金额账户ID
				$toid=$this->exhibition_ledger_total->getFieldByEid($orderInfo['eid'],'id');
				if($toid){
					//查询相关信息
					if($orderInfo['gtype']==1){
						//展位
						$positionInfo=$this->exhibition_position->where('id='.$orderInfo['gid'].' and salestate=3 and state=1')->field("id,eid,commission")->find();
						if($positionInfo['id'] && $positionInfo['commission'] && $positionInfo['eid']){
							//查询开展时间
							$stime=$this->exhibition->getFieldById($positionInfo['eid'],'stime');
							//计算佣金
							$commissionRatio=$positionInfo['commission']/100;
							$commission=$orderInfo['price']*$commissionRatio;
							//计算出去佣金后的订单总额
							$orderPrice=$orderInfo['price']-$commission;
							if($orderPrice && $commission && $stime){
								/* 
								*商家费用结算：
								*	第一次结算：佣金：交易金额*佣金比例=佣金金额￥
								*				预定金：（交易金额-佣金金额）*20%=第一次展位结算费用
								*
								*	说明：第一次计算费用日期为订单成交第十天，若订单成交日期距离开展日期不足10日，则在开展前一日结算所有费用，即100%结算展位费用
								*
								*	第二次结算：第二次结算费用为开展前一日结算所有费用，
								*
								*/
								//第一次结款时间
								$ntime=time()+86400*10;
								if($ntime<$stime-86400){
									//第一次结算佣金和预定金
									//第二次开展前一天结算尾款
									//计算订金
									$reservePrice=$orderPrice*0.2;
									//计算尾款
									$orderPriceN=$orderPrice-$reservePrice;
									//执行佣金打款
									$dataC=null;
									//打入系统账户
									$dataC['toid']=2;
									//来自平台资金池
									$dataC['fromid']=1;
									$dataC['price']=$commission;
									$dataC['oid']=$orderInfo['id'];
									$dataC['plantime']=$ntime;
									$dataC['ctime']=time();
									$dataC['title']="订单".$orderInfo['title']."（展位ID:".$orderInfo['gid']."）的佣金打款";
									$dataC['statecode']=2;
									$this->plan_price->add($dataC);
									//执行订金打款
									$dataR=null;
									//打入商户账户
									$dataR['toid']=$toid;
									//来自平台资金池
									$dataR['fromid']=1;
									$dataR['price']=$reservePrice;
									$dataR['oid']=$orderInfo['id'];
									$dataR['plantime']=$ntime;
									$dataR['ctime']=time();
									$dataR['title']="订单".$orderInfo['title']."（展位ID:".$orderInfo['gid']."）的订金打款";
									$dataR['statecode']=2;
									$this->plan_price->add($dataR);
									//执行尾款打款
									$dataO=null;
									//打入商户账户
									$dataO['toid']=$toid;
									//来自平台资金池
									$dataO['fromid']=1;
									$dataO['price']=$orderPriceN;
									$dataO['oid']=$orderInfo['id'];
									$dataO['plantime']=$ntime;
									$dataO['ctime']=time();
									$dataO['title']="订单".$orderInfo['title']."（展位ID:".$orderInfo['gid']."）的尾款打款";
									$dataO['statecode']=2;
									$this->plan_price->add($dataO);
								}else{
									//小于或等于九天（10天-开展后一天）
									//开展前一天一次性结算全部货款
									//执行佣金打款
									$dataC=null;
									//打入系统账户
									$dataC['toid']=2;
									//来自平台资金池
									$dataC['fromid']=1;
									$dataC['price']=$commission;
									$dataC['oid']=$orderInfo['id'];
									$dataC['plantime']=$stime+86400;
									$dataC['ctime']=time();
									$dataC['title']="订单".$orderInfo['title']."（展位ID:".$orderInfo['gid']."）的佣金打款";
									$dataC['statecode']=2;
									$this->plan_price->add($dataC);
									//执行尾款打款
									$dataO=null;
									//打入商户账户
									$dataO['toid']=$toid;
									//来自平台资金池
									$dataO['fromid']=1;
									$dataO['price']=$orderPrice;
									$dataO['oid']=$orderInfo['id'];
									$dataO['plantime']=$stime+86400;
									$dataO['ctime']=time();
									$dataO['title']="订单".$orderInfo['title']."（展位ID:".$orderInfo['gid']."）的尾款打款";
									$dataO['statecode']=2;
									$this->plan_price->add($dataO);
								}
								//执行创建金额计划成功
								return 1;
							}else{
								//订单总额异常
								return 7;
							}
						}else{
							//查询不到展位的相关信息
							return 6;
						}
					}else{
						//订单不是购买展位类型
						return 5;
					}
				}else{
					//查询不到企业金额计划ID
					return 4;
				}
			}else{
				//已创建金额计划
				return 3;
			}
		}else{
			//查询不到符合条件的订单信息
			return 2;
		}
	}
   
  /* 
   *	名称：订单已预定创建预定计划任务
   *	参数：	id 		订单ID
   *	返回：	1		执行计划成功
   *			2		查询不到符合条件的订单信息
   */
	public function orderReserve($id){
		//查询订单信息
		$orderInfo=$this->order->where('id='.$id.' and statecode=1 and state=1')->find();
		if($orderInfo){
			//创建订单离超时还有10小时提醒任务
			$dataO=null;
			$dataO['pid']=$orderInfo['id'];
			$dataO['ido']=$orderInfo['uid'];
			$dataO['type']=1;
			//72小时-10小时=62小时
			$dataO['plantime']=$orderInfo['ctime']+60*60*62;
			$dataO['ctime']=time();
			$dataO['title']=$orderInfo['title']."的10小时超时提醒任务";
			$dataO['statecode']=2;
			$this->plan_task->add($dataO);
			//创建订单超时取消计划任务
			$dataT=null;
			$dataT['pid']=$orderInfo['id'];
			$dataT['ido']=$orderInfo['uid'];
			$dataT['type']=2;
			$dataT['plantime']=$orderInfo['ctime']+60*60*72;
			$dataT['ctime']=time();
			$dataT['title']=$orderInfo['title']."的超时取消任务";
			$dataT['statecode']=2;
			$this->plan_task->add($dataT);
			//执行成功
			return 1;
		}else{
			//查询不到符合条件的订单信息
			return 2;
		}
	}
	/* 
   *	名称：订单已取消删除预定计划任务
   *	参数：	id 		订单ID
   *	返回：	1		执行计划成功
   *			2		查询不到符合条件的订单信息
   */
	public function orderCancel($id){
		//查询订单信息
		$orderInfo=$this->order->where('id='.$id.' and statecode=6 and state=1')->find();
		if($orderInfo){
			//删除订单超时相关计划任务
			$planTaskWhere=null;
			$planTaskWhere['pid']  = $orderInfo['id'];
			$planTaskWhere['ido']  = $orderInfo['uid'];
			$planTaskWhere['type']  = array('in','1,2');
			$this->plan_task->where($planTaskWhere)->delete();
			//执行成功
			return 1;
		}else{
			//查询不到符合条件的订单信息
			return 2;
		}
	}
	
	public function planTaskUpdata($type,$id){
		$planTask=$this->plan_task->where('pid='.$id.' and type='.$type)->find();
		$ntime=time()+1;
		if($type==1){
		
		}else if($type==2){
		
		}else if($type==3){
			//展会过期
			$exhibitionInfo=$this->exhibition->where('id='.$id)->field('id,name,stime')->find();
			if($exhibitionInfo['stime']){
				if($exhibitionInfo['stime']>$ntime){
					if(!$planTask){
						$data=null;
						$data['pid']=$id;
						$data['type']=$type;
						$data['plantime']=$exhibitionInfo['stime'];
						$data['ctime']=time();
						$data['title']=$exhibitionInfo['name']."-展会执行过期任务";
						$data['statecode']="2";
						$this->plan_task->add($data);
					}else{
						$data=null;
						$data['plantime']=$exhibitionInfo['stime'];
						$data['ctime']=time();
						$data['title']=$exhibitionInfo['name']."-展会执行过期任务";
						$data['statecode']="2";
						$this->plan_task->save($data);
					}
				}else{
					//展会已经过期执行
					$positionWhere=null;
					$positionWhere['eid']=$id;
					$positionWhere['salestate']=array('in','1,2');
					$positionDatas=$this->exhibition_position->where($positionWhere)->field('id,cid')->select();
					if($positionDatas){
						$this->exhibition_position->where($positionWhere)->setField('salestate','5');
						$positionDatasNum=count($positionDatas);
						for($i=0;$i<$positionDatasNum;$i++){
							$this->ExhibitionMap->syncPositionMap($positionDatas[$i]['cid'],$positionDatas[$i]['id']);
							$this->plan_task->where('pid='.$positionDatas[$i]['id'].' and type=4')->delete();
						}
					}
					$this->exhibition->where('id='.$id)->setField('salestate','2');
					//清除任务
					if($planTask){
						$this->plan_task->where('pid='.$id.' and type='.$type)->delete();
					}
				}
			}else{
				//清除任务
				if($planTask){
					$this->plan_task->where('pid='.$id.' and type='.$type)->delete();
				}
			}
		}else if($type==4){
			//展位过期
			$positionInfo=$this->exhibition_position->where('id='.$id)->find();
			if($positionInfo){
				if($positionInfo['retime']>$ntime){
					//展位还没有过期
					if(!$planTask){
						$data=null;
						$data['pid']=$id;
						$data['type']=$type;
						$data['plantime']=$positionInfo['retime'];
						$data['ctime']=time();
						$data['title']=$positionInfo['sign']."-展位执行过期任务";
						$data['statecode']="2";
						$this->plan_task->add($data);
					}else{
						$data=null;
						$data['plantime']=$positionInfo['retime'];
						$data['ctime']=time();
						$data['title']=$positionInfo['sign']."-展位执行过期任务";
						$data['statecode']="2";
						$this->plan_task->save($data);
					}
				}else{
					//展位已过期
					$this->exhibition_position->where('id='.$id)->setField('salestate','5');
					$this->ExhibitionMap->syncPositionMap($positionInfo['cid'],$id);
					//清除任务
					if($planTask){
						$this->plan_task->where('pid='.$id.' and type='.$type)->delete();
					}
				}
			}else{
				//清除任务
				if($planTask){
					$this->plan_task->where('pid='.$id.' and type='.$type)->delete();
				}
			}
		}
	}
}