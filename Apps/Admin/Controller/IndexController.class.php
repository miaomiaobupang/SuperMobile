<?php
namespace Admin\Controller;
use Think\Controller;
// +----------------------------------------------------------------------
// | ����ҽ��WEB��Ŀ
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://hopenoah.com All rights reserved.
// +----------------------------------------------------------------------
// | ����������
// +----------------------------------------------------------------------
// | Author: Davin <yulong@hopenoah.com>
// +----------------------------------------------------------------------
class IndexController extends LimitController {
    public function index(){
		
		//��ѯ������Ϣ(���ɷ����������Լ���������)
			$room = M('Room')->where('state = 1 || state = 2')->select();
			$roomNum = M('Room')->select();
			//��������ѯ������
			$resultRoom = count($room);
			$resultRoomNum = count($roomNum);
			//ʵ����������Ϣ
			$this->assign('resultRoom',$resultRoom);
			$this->assign('resultRoomNum',$resultRoomNum);
			//������ס��(������������)
			$percent = round(($resultRoom/$resultRoomNum)*100,2);
			$this->assign('percent',$percent);
		
		//��ѯҵ����Ϣ(����ҵ������)
			$ownerNum = M("Owner")->select();
			//��������ѯҵ������
			$resultOwnerNum = count($ownerNum);
			//ʵ����ҵ����Ϣ
			$this->assign('resultOwnerNum',$resultOwnerNum);
			
		//��ѯ��ͬ�����Ϣ(����30�����۽��)
		//�ж��ǵ���ʱ��
			$nowTime= strtotime(date("Y-m-d",time()));
			$pact = M("Pact")->select();
			$count = count($pact);
			for($i=0;$i<$count;$i++){
				$decrease = ceil(($nowTime-$pact[$i]['date'])/86400);
				if($decrease < 31){
					//��������ѯ��ͬ��
					//���������۶�
					$resultPactNum += $pact[$i]['money'];
				}
			}
			//echo $resultPactNum;exit();
			//ʵ���������ܶ���Ϣ
			$this->assign('resultPactNum',$resultPactNum);
			
		//��ѯ��λ��Ϣ(���ɳ�λ����)
			$addStallNum = M("Addstall")->select();
			//��ѯ�ѳ��۳�λ
			$addStall = M("Addstall")->where("state = 2 || state =3")->select();
			//��������ѯ��λ����
			$resultAddStall = count($addStall);
			$resultAddStallNum = count($addStallNum);
			
			//echo $resultAddStall;die();
			//ʵ������λ��Ϣ
			$this->assign('resultAddStall',$resultAddStall);
			$this->assign('resultAddStallNum',$resultAddStallNum);
			
			//���㳵λ���۰ٷֱ�
			$addStallPercent = round(($resultAddStall/$resultAddStallNum)*100,2);
			$this->assign('addStallPercent',$addStallPercent);
			
		//��ѯ�̻���Ϣ
			//��ѯ����¥���ܵ��̻����
			$afforestSet = M("Afforestset")->select();
			$count = count($afforestSet);
			for($i=0;$i<$count;$i++){
				//��������ѯ��ͬ��
				//���������۶�
				$resultAfforestSetNum += $afforestSet[$i]['area'];
			}
			//��ѯ����¥���ܵ�ռ�����
			$house = M("Houses")->select();
			//var_dump($house);die();
			$count = count($house);
			//$resultHouse = array();
			for($i=0;$i<$count;$i++){
				//���㽨�����ռ����
				$resultHouseNum += $house[$i]['occupyArea'];
			}
			//�����̻��ٷֱ�
			$resultAfforestSetPercent = round(($resultAfforestSetNum/$resultHouseNum)*100,2);
			
			$this->assign('resultAfforestSetNum',$resultAfforestSetNum);
			$this->assign('resultAfforestSetPercent',$resultAfforestSetPercent);
		
		
		//��ѯ¥����Ϣ(¥�̽����������,��״ͼ)
			$house = M("Houses")->select();
			//var_dump($house);die();
			$count = count($house);
			//$resultHouse = array();
			for($i=0;$i<$count;$i++){
				//���㽨�����ռ����
				$resultHouse[] = round(($house[$i]['buildArea']/$house[$i]['occupyArea'])*100,2);
			}
			//print_r($resultHouse);die();
			//ʵ����¥�̽����������
			$this->assign('resultHouse',$resultHouse);
			
		
		//ʵ����ģ����Ϣ
			//������ർ���˵�����
			//�����ʼ��
			$cache = S(array());
			$this->assign("LeftNavInfo",session('LeftNav'));
			//��������ҳģ��
			$this->display("Index/test");
    }
}