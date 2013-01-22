<?php

$assetList = array(
	'swf/background1.swf?v=2011102601',
	'swf/background2.swf?v=2011102601',
	'swf/movieclip1.swf?v=2011111501',
	'swf/building1.swf?v=2011102601',
	'swf/building2.swf?v=2011111501',
	'swf/phytotron1.swf?v=2011111501',
	'swf/animal1.swf?v=2011111501',
	'swf/decorate1.swf?v=2011123001',
	'swf/icon1.swf?v=2012010301',
	'swf/headIcon1.swf?v=2011111501',
	'swf/materialIcon1.swf?v=2012010301',
	'swf/achievement1.swf?v=2011121401',
	'swf/mission1.swf?v=2011111501',
	'swf/animalList.swf?v=2011102601',
	'swf/buildingStatus.swf?v=2012010301',
	'swf/buildLevelUp.swf?v=2011111501',
	'swf/buyItem.swf?v=2011102601',
	'swf/DIYres.swf?v=2012010301',
	'swf/employ.swf?v=2011102601',
	'swf/errorUI.swf?v=2011121501',
	'swf/ipandaUI.swf?v=2011121401',
	'swf/kuojian.swf?v=2011112301',
	'swf/levelUp.swf?v=2011102601',
	'swf/loading.swf?v=2011102601',
	'swf/log.swf?v=2011123001',
	'swf/pandaQuestion.swf?v=2011102601',
	'swf/peiyuwu.swf?v=2011102601',
	'swf/shopBag.swf?v=2011111501',
	'swf/sound.swf?v=2012010301',
	'swf/success.swf?v=2011102601',
	'swf/task.swf?v=2011122202',
	'swf/expire.swf?v=2011102401',
	'swf/useitem1.swf?v=2011111501',
	'swf/items1.swf?v=2011121201',
	'swf/hitCombo.swf?v=2011121501',
	'swf/personMsgUi.swf?v=2011121201'
);

$mainSwf = 'swf/ipandaMain.swf?v=2012010302';

// interface list
$interface = array(
    'swfHostURL'       				=> STATIC_HOST . '/swf/',
    'jpgHostURL'        			=> STATIC_HOST . '/jpg/',
    'interfaceHostURL'  			=> HOST . '/',

	'inituserinfo'      			=> 'api/inituserinfo',
	'getfriends'       	 			=> 'api/getfriends',
	'initipanda'					=> 'api/initipanda',
	'getdepot'						=> 'api/getdepot',
	'checkoutlove'					=> 'api/checkoutlove',
	'readfeed'				    	=> 'api/readfeed',
	'getadminfriends'				=> 'api/getadminfriends',
	'phytotronadminlist'			=> 'api/phytotronadminlist',
	'phytotronbuyrobot'				=> 'api/phytotronbuyrobot',
	'phytotronfriendadmin'			=> 'api/phytotronfriendadmin',
	'getthejob'						=> 'api/getthejob',
	'myemployeeinfo'				=> 'api/myemployeeinfo',
	'friendphytotronjob'			=> 'api/friendphytotronjob',
	'resignphytotronadmin'			=> 'api/resignphytotronadmin',
	'unlockadminlevel'				=> 'api/unlockadminlevel',
	'acceptphytotronadmin'			=> 'api/acceptphytotronadmin',
	'getphytotronadminaward'		=> 'api/getphytotronadminaward',
	'ignorephytotronadminlog'		=> 'api/ignorephytotronadminlog',

	'putdecorate'					=> 'forest/putdecorate',
	'putbuilding'					=> 'forest/putbuilding',
	'putphytotron'					=> 'forest/putphytotron',
	'putcomplete'					=> 'forest/putcomplete',
	'putpackage'					=> 'forest/putpackage',
	'putallpackage'					=> 'forest/putallpackage',
	'updatebuildingandphytotron'	=> 'forest/updatebuildingandphytotron',
	'salething'						=> 'forest/salething',
	'developland'					=> 'forest/developland',
	'expirelist'					=> 'forest/expirelist',
	'expirehandler'					=> 'forest/expirehandler',
	'setinteraction'				=> 'forest/setinteraction',

	'buildinglevelupinfo'			=> 'service/buildinglevelupinfo',
	'buildingunlocklevel'			=> 'service/buildingunlocklevel',
	'buildinglevelup'				=> 'service/buildinglevelup',
	'fixbuilding'					=> 'service/fixbuilding',
	'completefixbuilding'			=> 'service/completefixbuilding',
	'turnbuilding'					=> 'service/changedirection',
	'takefriendbuildinglove'		=> 'service/takefriendbuildinglove',
	'cardshoplist'				    => 'service/cardshoplist',
	'buycard'				    	=> 'service/buycard',
	'mycardlist'				    => 'service/mycardlist',
	'usecard'						=> 'service/usecard',
	'myachievement'					=> 'service/myachievement',
	'changeMyTitle'					=> 'service/changetitle',
	'buildingunlock'				=> 'service/buildingunlock',
	'paddedmaterial'				=> 'service/paddedmaterial',

	'unlocklist'					=> 'animal/unlocklist',
	'unlock'						=> 'animal/unlock',
	'rantanimal'					=> 'animal/rantanimal',
	'reciveanimal'					=> 'animal/reciveanimal',
	'addintimacy'					=> 'animal/addintimacy',
	'pandaAnswer'					=> 'animal/answerquestion',

	'getTask'						=> 'task/gettask',
	'checkTask'						=> 'task/checktask',
	'completecondition'				=> 'task/completecondition',
	'fullScreen'					=> 'task/fullscreen',
	'doneclientaction'				=> 'task/doneclientaction',
	
	'GiftGetActInitStatic'			=> 'gift/list?v=2011121201',
	'GiftGetActInit'				=> 'gift/user',
	'FriendRequest'					=> 'gift/friendrequest',
	'IgnoreGift'					=> 'gift/ignoregift',
	'ReceiveGift'					=> 'gift/receivegift',
	'ReleaseMyWish'					=> 'gift/mywish',
	'SendGift'					    => 'gift/send',
	'GiftGetKnowNew'			    => 'gift/hadread',

	'SignAwardInitStatic'			=> 'signaward/initdailyaward',
	'SignAwardIsFan'				=> 'signaward/befans',
	'SignAward'						=> 'signaward/gaindailyaward',

	'hitComboReward'				=> 'award/hit',

	'initChristmas'                 =>'event/initchristmas',
	'ChristmasExchange'             =>'event/christmasexchange',

	'initHospital'                  =>'hospital/initdata',
	'getDrug'						=> 'hospital/getdrug',
	'buildHospital'                 =>'hospital/build',
	'productDrug'                  	=>'hospital/productdrug',
	'completeProduct'               =>'hospital/completeproduct',
	'initDisDecorate'               =>'hospital/initdisdecorate',
	'initTombstone'				    =>'hospital/inittombstone',
	'healAnimal'                    =>'hospital/heal', 
	'prayAnimal'                    =>'hospital/pray',
	'checkdeath'					=>'hospital/checkdeath',
	'getUserMaterial'               =>'hospital/getusermaterial',
);

//开关设置
$setting = array(
	'charge'			=>  1,
	'checkOverlay'		=>  1,
	'feed'				=>  0,
	'gift'				=>  1,
	'payGift'			=>  1
);

$assetResult = array(
    'initSwf'      		=> $assetList,
	'mainSwf'      		=> $mainSwf,
	'mainClass' 		=> 'IpandaMain',
	'staticHost' 		=> STATIC_HOST . '/',
	'swfConfig'			=> 'swf/',
	'initUi'			=> '',
    'interface' 		=> $interface,
	'setting'			=> $setting
);



