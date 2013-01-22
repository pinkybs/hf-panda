-- phpMyAdmin SQL Dump
-- version 3.2.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2011 年 05 月 19 日 13:12
-- 服务器版本: 5.1.37
-- PHP 版本: 5.2.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `test_taobao_ipanda_basic`
--

-- --------------------------------------------------------

--
-- 表的结构 `ipanda_background`
--

CREATE TABLE IF NOT EXISTS `ipanda_background` (
  `bgid` int(11) NOT NULL COMMENT '海岛背景id',
  `name` varchar(200) DEFAULT NULL COMMENT '名称',
  `price` int(11) DEFAULT NULL COMMENT '购买价格',
  `price_type` tinyint(4) DEFAULT NULL COMMENT '购买币种,1:coin,2:gold',
  `cheap_price` int(11) DEFAULT '0' COMMENT '折扣价格',
  `cheap_start_time` int(11) DEFAULT '0' COMMENT '开始折扣时间',
  `cheap_end_time` int(11) DEFAULT '0' COMMENT '结束折扣时间',
  `sale_price` int(11) DEFAULT NULL COMMENT '售出价格',
  `introduce` varchar(200) DEFAULT NULL COMMENT '介绍',
  `class_name` varchar(200) DEFAULT NULL COMMENT '图像素材',
  `need_level` int(11) DEFAULT NULL COMMENT '需要等级',
  `add_praise` int(11) DEFAULT '0' COMMENT '好评度增加数',
  `item_type` tinyint(4) DEFAULT NULL COMMENT '11:岛,12:天,13:海,14:船坞',
  `new` tinyint(4) DEFAULT '0' COMMENT '是否新商品,0:非新,1:新  ',
  `can_buy` tinyint(4) DEFAULT '1' COMMENT '是否可以在商店购买,1:可以,0:不可以',
  PRIMARY KEY (`bgid`),
  KEY `need_level` (`need_level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_background`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_building`
--

CREATE TABLE IF NOT EXISTS `ipanda_building` (
  `cid` int(11) NOT NULL COMMENT '海岛装饰物id',
  `name` varchar(200) CHARACTER SET utf8 DEFAULT NULL COMMENT '名称',
  `class_name` varchar(200) CHARACTER SET utf8 DEFAULT NULL COMMENT '图像素材',
  `map` varchar(200) CHARACTER SET utf8 DEFAULT NULL COMMENT '缩图',
  `content` varchar(200) CHARACTER SET utf8 DEFAULT NULL COMMENT '介绍',
  `love_price` int(11) DEFAULT NULL COMMENT '购买爱心价格',
  `gold_price` int(11) DEFAULT NULL COMMENT '金币价格',
  `price_type` tinyint(4) DEFAULT NULL COMMENT '购买币种,1:love,2:gold,3:love+gold',
  `cheap_price` int(11) DEFAULT '0' COMMENT '折扣价格',
  `cheap_start_time` int(11) DEFAULT '0' COMMENT '开始折扣时间',
  `cheap_end_time` int(11) DEFAULT '0' COMMENT '结束折扣时间',
  `sale_price` int(11) DEFAULT NULL COMMENT '售出价格',
  `need_level` int(11) DEFAULT NULL COMMENT '使用需要等级',
  `need_material` varchar(200) CHARACTER SET utf8 DEFAULT NULL COMMENT '所需材料,json格式,例:[{"cid":101,"num":2},{"cid":102,"num":2}]',
  `nodes` varchar(200) CHARACTER SET utf8 DEFAULT NULL COMMENT '装饰类占地信息',
  `item_type` tinyint(4) DEFAULT NULL COMMENT '设施类型',
  `item_id` int(11) DEFAULT NULL COMMENT '设施类型 id，同一设施不同级别的 item_id 相同',
  `new` tinyint(4) DEFAULT '0' COMMENT '是否新商品,0:非新,1:新',
  `can_buy` tinyint(4) DEFAULT '1' COMMENT '是否可以在商店购买,1:可以,0:不可以',
  `love_fee` int(4) DEFAULT NULL COMMENT '爱心值产出',
  `pay_time` int(11) DEFAULT NULL COMMENT '结算时间',
  `safe_time` int(11) DEFAULT NULL COMMENT '保护时间',
  `safe_love_num` varchar(200) CHARACTER SET utf8 DEFAULT NULL COMMENT '保护爱心数',
  `level` tinyint(4) DEFAULT '1' COMMENT '设施等级',
  `next_level_cid` int(11) DEFAULT NULL COMMENT '升级后对应的 bid',
  `act_name` varchar(200) CHARACTER SET utf8 DEFAULT NULL COMMENT '相关活动名',
  `durable` int(11) NOT NULL DEFAULT '0' COMMENT '初始的耐久度',
  `durable_time` int(11) NOT NULL DEFAULT '0' COMMENT '耐久度回复时间',
  `attribute` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '等级1时为增长的属性值，大于1 是增长的属性值，-1是属性锁定',
  `attribute_change` varchar(200) CHARACTER SET utf8 DEFAULT NULL COMMENT '八个属性变化设置 ,json格式[[{"cid":101,"node":2,"num":2,"action":1},{"cid":102,"node":3,"num":1,"action":0}],[{"cid":101,"node":2,"num":3,"action":1},{"cid":102,"node":3,"num":8,"action":1}]] action 0 是 减属性，1是加属性',
  `get_material_group` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '[{"cid":101,"num":1,"condtion":[{"cid":201,"node":5},{"cid":301,"node":3}]},{"cid":102,"num":1,"condtion":[{"cid":201,"node":5},{"cid":401,"node":2}]}]',
  `update_time` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`cid`),
  KEY `need_level` (`need_level`),
  KEY `level` (`level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `ipanda_building`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_card`
--

CREATE TABLE IF NOT EXISTS `ipanda_card` (
  `cid` int(11) NOT NULL COMMENT '道具id',
  `name` varchar(200) DEFAULT NULL COMMENT '道具名称',
  `class_name` varchar(200) DEFAULT NULL COMMENT '道具类名',
  `introduce` varchar(200) DEFAULT NULL COMMENT '介绍',
  `price` int(11) DEFAULT NULL COMMENT '购买价格',
  `price_type` tinyint(4) DEFAULT NULL COMMENT '购买币种,1:coin,2:gold',
  `cheap_price` int(11) DEFAULT '0' COMMENT '折扣价格',
  `cheap_start_time` int(11) DEFAULT '0' COMMENT '开始折扣时间',
  `cheap_end_time` int(11) DEFAULT '0' COMMENT '结束折扣时间',
  `sale_price` int(11) DEFAULT NULL COMMENT '售出价格',
  `add_exp` int(11) DEFAULT NULL COMMENT '增加经验值',
  `need_level` int(11) DEFAULT NULL COMMENT '需要等级',
  `item_type` tinyint(4) DEFAULT '41' COMMENT '41:功能道具',
  `plant_level` tinyint(4) DEFAULT '0',
  `new` tinyint(4) DEFAULT '0' COMMENT '是否新商品,0:非新,1:新  ',
  `can_buy` tinyint(4) DEFAULT '1' COMMENT '是否可以在商店购买,1:可以,0:不可以',
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_card`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_decorate`
--

CREATE TABLE IF NOT EXISTS `ipanda_decorate` (
  `cid` int(11) NOT NULL COMMENT '海岛装饰物id',
  `name` varchar(200) DEFAULT NULL COMMENT '名称',
  `class_name` varchar(200) DEFAULT NULL COMMENT '图像素材',
  `map` varchar(200) DEFAULT NULL COMMENT '缩图',
  `content` varchar(200) DEFAULT NULL COMMENT '介绍',
  `add_praise` int(4) DEFAULT '0' COMMENT '好评度增加数',
  `price` int(11) DEFAULT NULL COMMENT '购买价格',
  `price_type` tinyint(4) DEFAULT NULL COMMENT '购买币种,1:coin,2:gold',
  `cheap_price` int(11) DEFAULT '0' COMMENT '折扣价格',
  `cheap_start_time` int(11) DEFAULT '0' COMMENT '开始折扣时间',
  `cheap_end_time` int(11) DEFAULT '0' COMMENT '结束折扣时间',
  `sale_price` int(11) DEFAULT NULL COMMENT '售出价格',
  `need_level` int(11) DEFAULT NULL COMMENT '使用需要等级',
  `nodes` varchar(200) DEFAULT NULL COMMENT '装饰类占地信息',
  `item_type` tinyint(4) DEFAULT NULL COMMENT '21:绿化,22:地面,31:建筑,32:饮食',
  `new` tinyint(4) DEFAULT '0' COMMENT '是否新商品,0:非新,1:新',
  `can_buy` tinyint(4) DEFAULT '1' COMMENT '是否可以在商店购买,1:可以,0:不可以',
  PRIMARY KEY (`cid`),
  KEY `need_level` (`need_level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_decorate`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_dock`
--

CREATE TABLE IF NOT EXISTS `ipanda_dock` (
  `pid` tinyint(4) unsigned NOT NULL COMMENT '拓展船位id',
  `level` smallint(11) unsigned NOT NULL DEFAULT '0' COMMENT '需要等级',
  `power` smallint(11) unsigned NOT NULL DEFAULT '0' COMMENT '需要好友数',
  `price` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '价格',
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_dock`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_feed_template`
--

CREATE TABLE IF NOT EXISTS `ipanda_feed_template` (
  `id` int(11) NOT NULL,
  `title` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_feed_template`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_level_user`
--

CREATE TABLE IF NOT EXISTS `ipanda_level_user` (
  `level` tinyint(3) unsigned NOT NULL AUTO_INCREMENT COMMENT '玩家级别',
  `exp` int(11) unsigned NOT NULL COMMENT '升级需要经验',
  PRIMARY KEY (`level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `ipanda_level_user`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_notice`
--

CREATE TABLE IF NOT EXISTS `ipanda_notice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT '',
  `position` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `priority` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `link` varchar(255) NOT NULL DEFAULT '',
  `opened` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `ipanda_notice`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_phytotron_animal`
--

CREATE TABLE IF NOT EXISTS `ipanda_phytotron_animal` (
  `cid` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `class_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_type` int(11) NOT NULL,
  `level_up` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '动物等级变化的条件及需要的管理员数,json  {"2":{"service_num":50,"admin_num":2},"3":{"service_num":150,"admin_num":3},"4":{"service_num":350,"admin_num":4},"5":{"service_num":650,"admin_num":5}} ',
  `need_user_level` int(4) NOT NULL,
  `unlock_condition` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '动物解锁条件 [{"cid":101,"level":10},{"cid":102,"level":10},{"cid":103,"level":10}]',
  `product_time` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '动物生产时间选项 [{"animal_level":0,"num":2,"time":600},{"animal_level":5,"num":4,"time":900},{"animal_level":20,"num":7,"time":1900}]',
  `buy_type` int(1) NOT NULL COMMENT '购买类型0 爱心 1 金币',
  `price` int(11) NOT NULL COMMENT '价格',
  `phytotron_cid` int(11) NOT NULL COMMENT '培育屋cid',
  `phytotron_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `phytotron_class_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `phytotron_item_id` int(11) NOT NULL,
  `phytotron_item_type` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='动物表及对应的动物的培育屋';

--
-- 转存表中的数据 `ipanda_phytotron_animal`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_phytotron_unlock_list`
--

CREATE TABLE IF NOT EXISTS `ipanda_phytotron_unlock_list` (
  `id` int(11) NOT NULL,
  `love` int(11) DEFAULT NULL,
  `gold` int(11) DEFAULT NULL,
  `need_material` int(11) DEFAULT NULL COMMENT '所需材料,json格式,例:[{"cid":101,"num":2},{"cid":102,"num":2}]',
  `level` int(11) NOT NULL DEFAULT '0',
  `friend_num` int(11) NOT NULL DEFAULT '0',
  `is_open` int(2) DEFAULT '0' COMMENT '状态 0 开放 1 不开放',
  `build_time` int(11) NOT NULL DEFAULT '0' COMMENT '建设的时间',
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='培育室解锁的列表';

--
-- 转存表中的数据 `ipanda_phytotron_unlock_list`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_title`
--

CREATE TABLE IF NOT EXISTS `ipanda_title` (
  `id` smallint(5) unsigned NOT NULL,
  `title` varchar(30) NOT NULL DEFAULT '' COMMENT '称号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_title`
--


-- --------------------------------------------------------

--
-- 表的结构 `ispanda_card`
--

CREATE TABLE IF NOT EXISTS `ispanda_card` (
  `cid` int(11) NOT NULL COMMENT '道具id',
  `name` varchar(200) DEFAULT NULL COMMENT '道具名称',
  `class_name` varchar(200) DEFAULT NULL COMMENT '道具类名',
  `introduce` varchar(200) DEFAULT NULL COMMENT '介绍',
  `price` int(11) DEFAULT NULL COMMENT '购买价格',
  `price_type` tinyint(4) DEFAULT NULL COMMENT '购买币种,1:coin,2:gold',
  `cheap_price` int(11) DEFAULT '0' COMMENT '折扣价格',
  `cheap_start_time` int(11) DEFAULT '0' COMMENT '开始折扣时间',
  `cheap_end_time` int(11) DEFAULT '0' COMMENT '结束折扣时间',
  `sale_price` int(11) DEFAULT NULL COMMENT '售出价格',
  `add_exp` int(11) DEFAULT NULL COMMENT '增加经验值',
  `need_level` int(11) DEFAULT NULL COMMENT '需要等级',
  `item_type` tinyint(4) DEFAULT '41' COMMENT '41:功能道具',
  `plant_level` tinyint(4) DEFAULT '0',
  `new` tinyint(4) DEFAULT '0' COMMENT '是否新商品,0:非新,1:新  ',
  `can_buy` tinyint(4) DEFAULT '1' COMMENT '是否可以在商店购买,1:可以,0:不可以',
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ispanda_card`
--


-- --------------------------------------------------------

--
-- 表的结构 `seq_uid`
--

CREATE TABLE IF NOT EXISTS `seq_uid` (
  `name` char(1) NOT NULL,
  `id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `seq_uid`
--


-- --------------------------------------------------------

--
-- 表的结构 `uid_map_0`
--

CREATE TABLE IF NOT EXISTS `uid_map_0` (
  `uid` int(11) unsigned NOT NULL,
  `puid` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `puid` (`puid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `uid_map_0`
--


-- --------------------------------------------------------

--
-- 表的结构 `uid_map_1`
--

CREATE TABLE IF NOT EXISTS `uid_map_1` (
  `uid` int(11) unsigned NOT NULL,
  `puid` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `puid` (`puid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `uid_map_1`
--


-- --------------------------------------------------------

--
-- 表的结构 `uid_map_2`
--

CREATE TABLE IF NOT EXISTS `uid_map_2` (
  `uid` int(11) unsigned NOT NULL,
  `puid` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `puid` (`puid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `uid_map_2`
--


-- --------------------------------------------------------

--
-- 表的结构 `uid_map_3`
--

CREATE TABLE IF NOT EXISTS `uid_map_3` (
  `uid` int(11) unsigned NOT NULL,
  `puid` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `puid` (`puid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `uid_map_3`
--


-- --------------------------------------------------------

--
-- 表的结构 `uid_map_4`
--

CREATE TABLE IF NOT EXISTS `uid_map_4` (
  `uid` int(11) unsigned NOT NULL,
  `puid` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `puid` (`puid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `uid_map_4`
--


-- --------------------------------------------------------

--
-- 表的结构 `uid_map_5`
--

CREATE TABLE IF NOT EXISTS `uid_map_5` (
  `uid` int(11) unsigned NOT NULL,
  `puid` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `puid` (`puid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `uid_map_5`
--


-- --------------------------------------------------------

--
-- 表的结构 `uid_map_6`
--

CREATE TABLE IF NOT EXISTS `uid_map_6` (
  `uid` int(11) unsigned NOT NULL,
  `puid` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `puid` (`puid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `uid_map_6`
--


-- --------------------------------------------------------

--
-- 表的结构 `uid_map_7`
--

CREATE TABLE IF NOT EXISTS `uid_map_7` (
  `uid` int(11) unsigned NOT NULL,
  `puid` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `puid` (`puid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `uid_map_7`
--


-- --------------------------------------------------------

--
-- 表的结构 `uid_map_8`
--

CREATE TABLE IF NOT EXISTS `uid_map_8` (
  `uid` int(11) unsigned NOT NULL,
  `puid` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `puid` (`puid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `uid_map_8`
--


-- --------------------------------------------------------

--
-- 表的结构 `uid_map_9`
--

CREATE TABLE IF NOT EXISTS `uid_map_9` (
  `uid` int(11) unsigned NOT NULL,
  `puid` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `puid` (`puid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `uid_map_9`
--

