<?php

/*
 * 事件派发工具类
 */
class Hapyfish2_Ipanda_Bll_Event
{
	//完成培育屋建设
	//$event = array('uid' => $uid, 'ipanda_user_phytotron_animal_id' => $data['ipanda_user_phytotron_animal_id']);
	public static function buildPhytotron($event)
	{
		//建设培育屋
		$type = 1;
		$id = $event['ipanda_user_phytotron_animal_id'];
		$animalData = Hapyfish2_Ipanda_Bll_PhytotronAnimal::getOnePhytotronAnimal($event['uid'], $id);
		$data = array('cid' => $animalData['phytotron_cid'], 'animal_cid' => $animalData['animal_cid']);
		Hapyfish2_Ipanda_Bll_Task::listen($event['uid'], $type, $data);
		
		//建设培育屋数
		$type = 11;
		Hapyfish2_Ipanda_Bll_Task::listen($event['uid'], $type);
	}
	
	//完成建筑建设
	//type = 2 建设建筑
	//$event = array('uid' => $uid, 'cid' => $data['cid']);
	public static function buildBuilding($event)
	{
		//建设建筑
		$type = 2;
		$data = array('cid' => $event['cid']);
		Hapyfish2_Ipanda_Bll_Task::listen($event['uid'], $type, $data);
		
		//建设建筑数
		$type = 12;
		Hapyfish2_Ipanda_Bll_Task::listen($event['uid'], $type);
		
		//建设建筑数
		$type = 32;
		$data = array('cid' => $event['cid']);
		Hapyfish2_Ipanda_Bll_Task::listen($event['uid'], $type, $data);
	}
	
	//放置装饰物
	//$event = array('uid' => $uid, 'cid' => $basicInfo['cid']);
	public static function putDecorate($event)
	{
		$type = 3;
		$data = array('cid' => $event['cid']);
		Hapyfish2_Ipanda_Bll_Task::listen($event['uid'], $type, $data);
	}
	
	//收爱心
	//$event = array('uid' => $uid, 'love' => $building['love']);
	public static function gainLove($event)
	{
		//爱心收取值
		$type = 4;
		$data = array('cid' => 1, 'love' => $event['love']);
		Hapyfish2_Ipanda_Bll_Task::listen($event['uid'], $type, $data);
		
		//爱心收取次数
		$type = 5;
		$data = array('cid' => 1);
		Hapyfish2_Ipanda_Bll_Task::listen($event['uid'], $type, $data);
	}
	
	//花费爱心
	//$event = array('uid' => $uid, 'love' => $loveChange);
	public static function consumeLove($event)
	{
		$type = 6;
		$data = array('cid' => 1, 'love' => $event['love']);
		Hapyfish2_Ipanda_Bll_Task::listen($event['uid'], $type, $data);
	}
	
	//全屏
	//$event = array('uid' => $uid);
	public static function fullScreen($event)
	{
		$type = 7;
		Hapyfish2_Ipanda_Bll_Task::listen($event['uid'], $type);
	}
	
	//访问好友
	public static function visitFriend($event)
	{
		$type = 8;
		Hapyfish2_Ipanda_Cache_Visit::dailyVisit($event['uid'], $event['fid']);
		Hapyfish2_Ipanda_Bll_Task::listen($event['uid'], $type);
	}
	
	//解锁动物
	//$event = array('uid' => $uid, 'animal_cid' => $animalInfo['animal_cid']);
	public static function unlockAnimal($event)
	{
		//解锁动物数
		$type = 9;
		Hapyfish2_Ipanda_Bll_Task::listen($event['uid'], $type);
		
		//解锁某动物
		$type = 10;
		$data = array('cid' => $event['animal_cid']);
		Hapyfish2_Ipanda_Bll_Task::listen($event['uid'], $type, $data);
	}
	
	//升级建筑
	//$event = array('uid' => $uid, 'cid' => $buildingInfo['cid']);
	public static function upgradeBuilding($event)
	{
		$type = 13;
		$data = array('cid' => $event['cid']);
		Hapyfish2_Ipanda_Bll_Task::listen($event['uid'], $type, $data);
	}
	
	//维修
	//$event = array('uid' => $uid, 'fuid' => $fuid);
	public static function fix($event)
	{
		$type = 14;
		Hapyfish2_Ipanda_Bll_Task::listen($event['uid'], $type);
	}
	
	//点击亲密度
	//$event = array('uid' => $uid, 'cid' => $cid);
	public static function addIntimacy($event)
	{
		$type = 15;
		Hapyfish2_Ipanda_Bll_Task::listen($event['uid'], $type);
	}
	
	//对培育屋使用加速道具
	//$event = array('uid' => $uid, 'cid' => $cardInfo['cid']);
	public static function useSpeedCardForPhytotron($event)
	{
		$type = 16;
		$data = array('cid' => $event['cid']);
		Hapyfish2_Ipanda_Bll_Task::listen($event['uid'], $type, $data);
	}
	
	//对建筑使用加速道具，修复
	//$event = array('uid' => $uid, 'cid' => $cardInfo['cid']);
	public static function useSpeedCardForFixBuilding($event)
	{
		$type = 17;
		$data = array('cid' => $event['cid']);
		Hapyfish2_Ipanda_Bll_Task::listen($event['uid'], $type, $data);
	}
	
	//连续登录
	public static function loginCounter($event)
	{
		//连续登录
		$type = 18;
		$data = array('days' => $event['days']);
		Hapyfish2_Ipanda_Bll_Task::listen($event['uid'], $type, $data);
	}
	
	//培育动物
	//$event = array('uid' => $uid, 'animal_cid' => $animalBasic['cid'], 'num' => $num);
	public static function receiveAnimal($event)
	{
		//收动物数(培育动物数)
		$type = 19;
		$data = array('cid' => $event['animal_cid'], 'num' => $event['num']);
		Hapyfish2_Ipanda_Bll_Task::listen($event['uid'], $type, $data);
		
		//收动物次数
		$type = 20;
		$data = array('cid' => $event['animal_cid']);
		Hapyfish2_Ipanda_Bll_Task::listen($event['uid'], $type, $data);
		
		//培育动物总类
		$type = 31;
		$data = array('cid' => $event['animal_cid']);
		Hapyfish2_Ipanda_Bll_Task::listen($event['uid'], $type, $data);
	}
	
	//收好友爱心
	//$event = array('uid' => $uid, 'fid' => $fuid, 'love' => $num);
	public static function takeFriendLove($event)
	{
		//收好友爱心数
		$type = 21;
		$data = array('cid' => 1, 'love' => $event['love']);
		Hapyfish2_Ipanda_Bll_Task::listen($event['uid'], $type, $data);
		
		//收好友爱心次
		$type = 22;
		$data = array('cid' => 1);
		Hapyfish2_Ipanda_Bll_Task::listen($event['uid'], $type, $data);
	}
	
	//租动物
	//$event = array('uid' => $uid, 'animal_cid' => $animalBasic['cid'], 'num' => $num);
	public static function rantAnimal($event)
	{
		//租动物数
		$type = 23;
		$data = array('cid' => $event['animal_cid'], 'num' => $event['num']);
		Hapyfish2_Ipanda_Bll_Task::listen($event['uid'], $type, $data);
		
		//租动物次数
		$type = 24;
		$data = array('cid' => $event['animal_cid']);
		Hapyfish2_Ipanda_Bll_Task::listen($event['uid'], $type, $data);
		
		//租动物总类
		$type = 29;
		$data = array('cid' => $event['animal_cid']);
		Hapyfish2_Ipanda_Bll_Task::listen($event['uid'], $type, $data);
	}
	
	//调整培育屋时间
	//$event = array('uid' => $uid, 'animal_cid' => $data['animal_cid']);
	public static function changePhytotronTime($event)
	{
		$type = 25;
		$data = array('cid' => $event['animal_cid']);
		Hapyfish2_Ipanda_Bll_Task::listen($event['uid'], $type, $data);
	}
	
	//调整建筑收爱心时间
	//$event = array('uid' => $uid, 'cid' => $data['cid']);
	public static function changeBuildingTime($event)
	{
		$type = 26;
		$data = array('cid' => $event['cid']);
		Hapyfish2_Ipanda_Bll_Task::listen($event['uid'], $type, $data);
	}
	
	//扩地
	//$event = array('uid' => $uid, 'land_num' => $theLandNum + 1);
	public static function expandLand($event)
	{
		$type = 27;
		Hapyfish2_Ipanda_Bll_Task::listen($event['uid'], $type);
	}
	
	//获得材料
	//$event = array('uid' => $uid, 'cid' => $cid, 'num' => $count);
	public static function gainMaterial($event)
	{
		$type = 28;
		$data = array('cid' => $event['cid'], 'num' => $event['num']);
		Hapyfish2_Ipanda_Bll_Task::listen($event['uid'], $type, $data);
	}
	
	//使用道具卡
	//$event = array('uid' => $uid, 'cid' => $cid);
	public static function useCard($event)
	{
		$type = 30;
		$data = array('cid' => $event['cid']);
		Hapyfish2_Ipanda_Bll_Task::listen($event['uid'], $type, $data);
	}
	
	//客户端用户动作事件
	//$event = array('uid' => $uid, 'type' => $type);
	//type:34,35,36,37,38,39,40,41,45
	public static function clientAction($event)
	{
		Hapyfish2_Ipanda_Bll_Task::listen($event['uid'], $event['type']);
	}
	
	//用户升级
	public static function levelUp($event)
	{
		$uid = $event['uid'];
		$level = $event['level'];
		
		$type = 33;
		$data = array('level' => $level);
		Hapyfish2_Ipanda_Bll_Task::listen($uid, $type, $data);
		
		$idsBuffer = array();
		$openTask = Hapyfish2_Ipanda_HFC_TaskOpen::getInfo($uid);
		//检查是否有以前遗留的等级未到任务
		if (!empty($openTask['buffer_list'])) {
			foreach ($openTask['buffer_list'] as $id => $lv) {
				if ($lv == $level) {
					$idsBuffer[] = (int)$id;
				}
			}
		}
		
		//检查是否有新任务触发
		$idsNew = Hapyfish2_Ipanda_Cache_Basic_Info::getTaskIdsByLevel($level);
		
		if (empty($idsBuffer) && empty($idsNew)) {
			return;
		}

		Hapyfish2_Ipanda_Bll_Task_Base::addTask($uid, $openTask, $idsBuffer, $idsNew);
	}
	
	//移动装饰物
	//$event = array('uid' => $uid, 'cid' => $cid);
	public static function moveDecorate($event)
	{
		$type = 42;
		$data = array('cid' => $event['cid'], 'item_type' => 21);
		Hapyfish2_Ipanda_Bll_Task::listen($event['uid'], $type, $data);
	}
	
	//移动建筑
	//$event = array('uid' => $uid, 'cid' => $cid);
	public static function moveBuilding($event)
	{
		$type = 42;
		$data = array('cid' => $event['cid'], 'item_type' => 11);
		Hapyfish2_Ipanda_Bll_Task::listen($event['uid'], $type, $data);
	}
	
	//移动培育屋
	//$event = array('uid' => $uid, 'cid' => $cid);
	public static function movePhytotron($event)
	{
		$type = 42;
		$data = array('cid' => $event['cid'], 'item_type' => 31);
		Hapyfish2_Ipanda_Bll_Task::listen($event['uid'], $type, $data);
	}
	
	//回答某动物的几个问题
	//$event = array('uid' => $uid, 'id' => $id);
	public static function answerQuestion($event)
	{
		$type = 43;
		$data = array('id' => $event['id']);
		Hapyfish2_Ipanda_Bll_Task::listen($event['uid'], $type, $data);
	}
	
	//领取每日登陆
	//$event = array('uid' => $uid);
	public static function gainDailyAward($event)
	{
		$type = 44;
		Hapyfish2_Ipanda_Bll_Task::listen($event['uid'], $type);
	}
	
	//给好友赠送礼物
	//$event = array('uid' => $uid, 'num' => $num);
	public static function sendGift($event)
	{
		$type = 46;
		$data = array('num' => $event['num']);
		Hapyfish2_Ipanda_Bll_Task::listen($event['uid'], $type, $data);
	}
	
	//发布礼物愿望
	//$event = array('uid' => $uid);
	public static function sendWish($event)
	{
		$type = 47;
		Hapyfish2_Ipanda_Bll_Task::listen($event['uid'], $type);
	}
	
	//收几个礼物
	//$event = array('uid' => $uid, 'num' => $num);
	public static function receiveGift($event)
	{
		$type = 48;
		$data = array('num' => $event['num']);
		Hapyfish2_Ipanda_Bll_Task::listen($event['uid'], $type, $data);
	}
	
	public static function buyItem($event)
	{
		//info_log('[buyItem]:' . json_encode($event), 'event');
	}
	
	//放置一个新建筑
	public static function putNewBuilding($event)
	{
		//info_log('[putNewBuilding]:' . json_encode($event), 'event');
	}
	
	//放置一个新培育屋
	public static function putNewPhytotron($event)
	{
		//info_log('[putNewPhytotron]:' . json_encode($event), 'event');
	}

}