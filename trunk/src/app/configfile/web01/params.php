<?php

//乐乐信息
define('GM_UID_LELE', 66);
define('GM_NAME_LELE', '灵灵');
define('GM_FACE_LELE', 'lele.jpg');
define('GM_AVATAR_LELE', 1);

//初始化信息
define('INIT_USER_LOVE', 10000);
define('INIT_USER_GOLD', 50);
define('INIT_USER_ENERGY', 20);

define('EG_RECOVERY_TIME', 120);
define('EG_RECOVERY_ENERGY', 1);

//语言
define('COUNTRY', 'cn');

//速率倍数
define('SPEED_BASE', 1);

//判断 叠加 true 能叠加 false 不能叠加
define('CAN_OVERLAY', false);

//结算时间(动物消费时间)
define('UNIT_TIME', 60);

//等级限制
//维修建筑的等级
define('FIX_BUILD_LEVEL', 1);
//偷好友爱心的等级限制
define('TAKE_BUILD_LOVE_LEVEL', 1);
//租动物的等级限制
define('RENT_ANIMAL_LEVEL', 1);

//获得操作经验
//收取自己的爱心值/每次
define('EXP_CHECKOUT_LOVE', 1);
//收取动物/每次
define('EXP_RECIVE_ANIMAL', 4);
//获得别人的爱心值/每次
define('EXP_TAKE_FRIEND_LOVE', 1);
//成功建设建筑/每次
define('EXP_PER_BUILDING', 1);
//作为管理员获得动物奖励/每个动物
define('EXP_ADMIN_PER_ANIMAL', 1);
//扩地/每次
define('EXP_EXPAND_LAND', 1);
//成功建设一个培育屋/每次
define('EXP_PER_PHYTOTRON', 1);
//维修/每次
define('EXP_FIX_BUILDING', 1);

//////
//体力消耗
define('EG_CHECKOUT_LOVE', 1);
//收取动物/每次
define('EG_RECIVE_ANIMAL', 1);
//获得别人的爱心值/每次
define('EG_TAKE_FRIEND_LOVE', 1);
//帮助好友维修
define('EG_FIX_FRIEND_BUILDING', 5);
//自己维修自己的建筑
define('EG_FIX_MY_BUILDING', 1);
//租用动物
define('EG_RENT_ANIMAL', 1);
//建设培育屋
define('EG_PER_PHYTOTRON', 3);
//建设建筑
define('EG_PER_BUILDING', 3);
//点击亲密度
define('EG_ANIMAL_EXP', 1);

//////
//熊猫100问
//出题概率
define('QUESTION_RATE', 60);
