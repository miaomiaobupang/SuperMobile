<?php
namespace Admin\Controller;
use Think\Controller;
// +----------------------------------------------------------------------
// | 超级医生WEB项目
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://hopenoah.com All rights reserved.
// +----------------------------------------------------------------------
// | 词条控制器
// +----------------------------------------------------------------------
// | Author: Davin <yulong@hopenoah.com>
// +----------------------------------------------------------------------
class IndexController extends LimitController {
    public function index(){
		
		//查询房间信息(生成房间销售率以及房间总数)
			$room = M('Room')->where('state = 1 || state = 2')->select();
			$roomNum = M('Room')->select();
			//按条件查询房间数
			$resultRoom = count($room);
			$resultRoomNum = count($roomNum);
			//实例化房间信息
			$this->assign('resultRoom',$resultRoom);
			$this->assign('resultRoomNum',$resultRoomNum);
			//房间入住率(包括已租已售)
			$percent = round(($resultRoom/$resultRoomNum)*100,2);
			$this->assign('percent',$percent);
		
		//查询业主信息(生成业主总数)
			$ownerNum = M("Owner")->select();
			//按条件查询业主总数
			$resultOwnerNum = count($ownerNum);
			//实例化业主信息
			$this->assign('resultOwnerNum',$resultOwnerNum);
			
		//查询合同金额信息(生成30天销售金额)
		//判断是当月时间
			$nowTime= strtotime(date("Y-m-d",time()));
			$pact = M("Pact")->select();
			$count = count($pact);
			for($i=0;$i<$count;$i++){
				$decrease = ceil(($nowTime-$pact[$i]['date'])/86400);
				if($decrease < 31){
					//按条件查询合同数
					//计算总销售额
					$resultPactNum += $pact[$i]['money'];
				}
			}
			//echo $resultPactNum;exit();
			//实例化销售总额信息
			$this->assign('resultPactNum',$resultPactNum);
			
		//查询车位信息(生成车位总数)
			$addStallNum = M("Addstall")->select();
			//查询已出售车位
			$addStall = M("Addstall")->where("state = 2 || state =3")->select();
			//按条件查询车位总数
			$resultAddStall = count($addStall);
			$resultAddStallNum = count($addStallNum);
			
			//echo $resultAddStall;die();
			//实例化车位信息
			$this->assign('resultAddStall',$resultAddStall);
			$this->assign('resultAddStallNum',$resultAddStallNum);
			
			//计算车位销售百分比
			$addStallPercent = round(($resultAddStall/$resultAddStallNum)*100,2);
			$this->assign('addStallPercent',$addStallPercent);
			
		//查询绿化信息
			//查询所有楼盘总的绿化面积
			$afforestSet = M("Afforestset")->select();
			$count = count($afforestSet);
			for($i=0;$i<$count;$i++){
				//按条件查询合同数
				//计算总销售额
				$resultAfforestSetNum += $afforestSet[$i]['area'];
			}
			//查询所有楼盘总的占地面积
			$house = M("Houses")->select();
			//var_dump($house);die();
			$count = count($house);
			//$resultHouse = array();
			for($i=0;$i<$count;$i++){
				//计算建筑面积占有率
				$resultHouseNum += $house[$i]['occupyArea'];
			}
			//计算绿化百分比
			$resultAfforestSetPercent = round(($resultAfforestSetNum/$resultHouseNum)*100,2);
			
			$this->assign('resultAfforestSetNum',$resultAfforestSetNum);
			$this->assign('resultAfforestSetPercent',$resultAfforestSetPercent);
		
		
		//查询楼盘信息(楼盘建筑面积比率,饼状图)
			$house = M("Houses")->select();
			//var_dump($house);die();
			$count = count($house);
			//$resultHouse = array();
			for($i=0;$i<$count;$i++){
				//计算建筑面积占有率
				$resultHouse[] = round(($house[$i]['buildArea']/$house[$i]['occupyArea'])*100,2);
			}
			//print_r($resultHouse);die();
			//实例化楼盘建筑面积比例
			$this->assign('resultHouse',$resultHouse);
			
		
		//实例化模板信息
			//加载左侧导航菜单缓存
			//缓存初始化
			$cache = S(array());
			$this->assign("LeftNavInfo",session('LeftNav'));
			//加载内容页模板
			$this->display("Index/test");
    }
}