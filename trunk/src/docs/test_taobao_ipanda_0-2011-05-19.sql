-- phpMyAdmin SQL Dump
-- version 3.2.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2011 年 05 月 19 日 13:13
-- 服务器版本: 5.1.37
-- PHP 版本: 5.2.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `test_taobao_ipanda_0`
--

-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_addgold_log_0`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_addgold_log_0` (
  `uid` int(10) unsigned NOT NULL,
  `gold` int(10) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL,
  KEY `idx_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_addgold_log_0`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_addgold_log_1`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_addgold_log_1` (
  `uid` int(10) unsigned NOT NULL,
  `gold` int(10) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL,
  KEY `idx_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_addgold_log_1`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_addgold_log_2`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_addgold_log_2` (
  `uid` int(10) unsigned NOT NULL,
  `gold` int(10) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL,
  KEY `idx_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_addgold_log_2`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_addgold_log_3`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_addgold_log_3` (
  `uid` int(10) unsigned NOT NULL,
  `gold` int(10) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL,
  KEY `idx_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_addgold_log_3`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_addgold_log_4`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_addgold_log_4` (
  `uid` int(10) unsigned NOT NULL,
  `gold` int(10) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL,
  KEY `idx_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_addgold_log_4`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_addgold_log_5`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_addgold_log_5` (
  `uid` int(10) unsigned NOT NULL,
  `gold` int(10) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL,
  KEY `idx_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_addgold_log_5`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_addgold_log_6`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_addgold_log_6` (
  `uid` int(10) unsigned NOT NULL,
  `gold` int(10) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL,
  KEY `idx_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_addgold_log_6`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_addgold_log_7`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_addgold_log_7` (
  `uid` int(10) unsigned NOT NULL,
  `gold` int(10) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL,
  KEY `idx_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_addgold_log_7`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_addgold_log_8`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_addgold_log_8` (
  `uid` int(10) unsigned NOT NULL,
  `gold` int(10) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL,
  KEY `idx_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_addgold_log_8`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_addgold_log_9`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_addgold_log_9` (
  `uid` int(10) unsigned NOT NULL,
  `gold` int(10) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL,
  KEY `idx_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_addgold_log_9`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_background_0`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_background_0` (
  `uid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `bgid` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL,
  `buy_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_background_0`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_background_1`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_background_1` (
  `uid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `bgid` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL,
  `buy_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_background_1`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_background_2`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_background_2` (
  `uid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `bgid` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL,
  `buy_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_background_2`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_background_3`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_background_3` (
  `uid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `bgid` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL,
  `buy_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_background_3`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_background_4`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_background_4` (
  `uid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `bgid` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL,
  `buy_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_background_4`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_background_5`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_background_5` (
  `uid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `bgid` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL,
  `buy_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_background_5`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_background_6`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_background_6` (
  `uid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `bgid` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL,
  `buy_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_background_6`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_background_7`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_background_7` (
  `uid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `bgid` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL,
  `buy_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_background_7`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_background_8`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_background_8` (
  `uid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `bgid` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL,
  `buy_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_background_8`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_background_9`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_background_9` (
  `uid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `bgid` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL,
  `buy_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_background_9`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_background_10`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_background_10` (
  `uid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `bgid` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL,
  `buy_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_background_10`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_background_11`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_background_11` (
  `uid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `bgid` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL,
  `buy_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_background_11`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_background_12`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_background_12` (
  `uid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `bgid` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL,
  `buy_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_background_12`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_background_13`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_background_13` (
  `uid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `bgid` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL,
  `buy_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_background_13`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_background_14`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_background_14` (
  `uid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `bgid` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL,
  `buy_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_background_14`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_background_15`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_background_15` (
  `uid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `bgid` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL,
  `buy_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_background_15`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_background_16`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_background_16` (
  `uid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `bgid` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL,
  `buy_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_background_16`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_background_17`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_background_17` (
  `uid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `bgid` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL,
  `buy_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_background_17`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_background_18`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_background_18` (
  `uid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `bgid` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL,
  `buy_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_background_18`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_background_19`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_background_19` (
  `uid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `bgid` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL,
  `buy_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_background_19`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_background_20`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_background_20` (
  `uid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `bgid` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL,
  `buy_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_background_20`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_background_21`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_background_21` (
  `uid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `bgid` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL,
  `buy_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_background_21`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_background_22`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_background_22` (
  `uid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `bgid` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL,
  `buy_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_background_22`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_background_23`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_background_23` (
  `uid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `bgid` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL,
  `buy_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_background_23`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_background_24`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_background_24` (
  `uid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `bgid` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL,
  `buy_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_background_24`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_background_25`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_background_25` (
  `uid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `bgid` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL,
  `buy_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_background_25`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_background_26`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_background_26` (
  `uid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `bgid` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL,
  `buy_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_background_26`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_background_27`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_background_27` (
  `uid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `bgid` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL,
  `buy_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_background_27`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_background_28`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_background_28` (
  `uid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `bgid` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL,
  `buy_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_background_28`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_background_29`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_background_29` (
  `uid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `bgid` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL,
  `buy_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_background_29`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_background_30`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_background_30` (
  `uid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `bgid` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL,
  `buy_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_background_30`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_background_31`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_background_31` (
  `uid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `bgid` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL,
  `buy_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_background_31`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_background_32`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_background_32` (
  `uid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `bgid` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL,
  `buy_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_background_32`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_background_33`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_background_33` (
  `uid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `bgid` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL,
  `buy_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_background_33`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_background_34`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_background_34` (
  `uid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `bgid` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL,
  `buy_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_background_34`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_background_35`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_background_35` (
  `uid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `bgid` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL,
  `buy_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_background_35`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_background_36`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_background_36` (
  `uid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `bgid` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL,
  `buy_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_background_36`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_background_37`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_background_37` (
  `uid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `bgid` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL,
  `buy_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_background_37`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_background_38`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_background_38` (
  `uid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `bgid` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL,
  `buy_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_background_38`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_background_39`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_background_39` (
  `uid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `bgid` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL,
  `buy_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_background_39`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_card_0`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_card_0` (
  `uid` int(10) unsigned NOT NULL,
  `cid` int(10) unsigned NOT NULL,
  `count` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_card_0`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_card_1`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_card_1` (
  `uid` int(10) unsigned NOT NULL,
  `cid` int(10) unsigned NOT NULL,
  `count` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_card_1`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_card_2`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_card_2` (
  `uid` int(10) unsigned NOT NULL,
  `cid` int(10) unsigned NOT NULL,
  `count` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_card_2`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_card_3`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_card_3` (
  `uid` int(10) unsigned NOT NULL,
  `cid` int(10) unsigned NOT NULL,
  `count` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_card_3`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_card_4`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_card_4` (
  `uid` int(10) unsigned NOT NULL,
  `cid` int(10) unsigned NOT NULL,
  `count` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_card_4`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_card_5`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_card_5` (
  `uid` int(10) unsigned NOT NULL,
  `cid` int(10) unsigned NOT NULL,
  `count` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_card_5`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_card_6`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_card_6` (
  `uid` int(10) unsigned NOT NULL,
  `cid` int(10) unsigned NOT NULL,
  `count` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_card_6`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_card_7`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_card_7` (
  `uid` int(10) unsigned NOT NULL,
  `cid` int(10) unsigned NOT NULL,
  `count` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_card_7`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_card_8`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_card_8` (
  `uid` int(10) unsigned NOT NULL,
  `cid` int(10) unsigned NOT NULL,
  `count` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_card_8`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_card_9`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_card_9` (
  `uid` int(10) unsigned NOT NULL,
  `cid` int(10) unsigned NOT NULL,
  `count` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_card_9`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_card_status_0`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_card_status_0` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `card_0` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'insurance(保安卡)',
  `card_1` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'defense(防御卡)',
  `card_2` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '财神卡',
  `card_3` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '穷神卡',
  `card_4` int(10) unsigned NOT NULL DEFAULT '0',
  `card_5` int(10) unsigned NOT NULL DEFAULT '0',
  `card_6` int(10) unsigned NOT NULL DEFAULT '0',
  `card_7` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_card_status_0`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_card_status_1`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_card_status_1` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `card_0` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'insurance(保安卡)',
  `card_1` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'defense(防御卡)',
  `card_2` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '财神卡',
  `card_3` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '穷神卡',
  `card_4` int(10) unsigned NOT NULL DEFAULT '0',
  `card_5` int(10) unsigned NOT NULL DEFAULT '0',
  `card_6` int(10) unsigned NOT NULL DEFAULT '0',
  `card_7` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_card_status_1`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_card_status_2`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_card_status_2` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `card_0` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'insurance(保安卡)',
  `card_1` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'defense(防御卡)',
  `card_2` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '财神卡',
  `card_3` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '穷神卡',
  `card_4` int(10) unsigned NOT NULL DEFAULT '0',
  `card_5` int(10) unsigned NOT NULL DEFAULT '0',
  `card_6` int(10) unsigned NOT NULL DEFAULT '0',
  `card_7` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_card_status_2`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_card_status_3`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_card_status_3` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `card_0` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'insurance(保安卡)',
  `card_1` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'defense(防御卡)',
  `card_2` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '财神卡',
  `card_3` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '穷神卡',
  `card_4` int(10) unsigned NOT NULL DEFAULT '0',
  `card_5` int(10) unsigned NOT NULL DEFAULT '0',
  `card_6` int(10) unsigned NOT NULL DEFAULT '0',
  `card_7` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_card_status_3`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_card_status_4`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_card_status_4` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `card_0` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'insurance(保安卡)',
  `card_1` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'defense(防御卡)',
  `card_2` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '财神卡',
  `card_3` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '穷神卡',
  `card_4` int(10) unsigned NOT NULL DEFAULT '0',
  `card_5` int(10) unsigned NOT NULL DEFAULT '0',
  `card_6` int(10) unsigned NOT NULL DEFAULT '0',
  `card_7` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_card_status_4`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_card_status_5`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_card_status_5` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `card_0` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'insurance(保安卡)',
  `card_1` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'defense(防御卡)',
  `card_2` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '财神卡',
  `card_3` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '穷神卡',
  `card_4` int(10) unsigned NOT NULL DEFAULT '0',
  `card_5` int(10) unsigned NOT NULL DEFAULT '0',
  `card_6` int(10) unsigned NOT NULL DEFAULT '0',
  `card_7` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_card_status_5`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_card_status_6`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_card_status_6` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `card_0` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'insurance(保安卡)',
  `card_1` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'defense(防御卡)',
  `card_2` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '财神卡',
  `card_3` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '穷神卡',
  `card_4` int(10) unsigned NOT NULL DEFAULT '0',
  `card_5` int(10) unsigned NOT NULL DEFAULT '0',
  `card_6` int(10) unsigned NOT NULL DEFAULT '0',
  `card_7` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_card_status_6`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_card_status_7`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_card_status_7` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `card_0` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'insurance(保安卡)',
  `card_1` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'defense(防御卡)',
  `card_2` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '财神卡',
  `card_3` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '穷神卡',
  `card_4` int(10) unsigned NOT NULL DEFAULT '0',
  `card_5` int(10) unsigned NOT NULL DEFAULT '0',
  `card_6` int(10) unsigned NOT NULL DEFAULT '0',
  `card_7` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_card_status_7`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_card_status_8`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_card_status_8` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `card_0` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'insurance(保安卡)',
  `card_1` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'defense(防御卡)',
  `card_2` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '财神卡',
  `card_3` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '穷神卡',
  `card_4` int(10) unsigned NOT NULL DEFAULT '0',
  `card_5` int(10) unsigned NOT NULL DEFAULT '0',
  `card_6` int(10) unsigned NOT NULL DEFAULT '0',
  `card_7` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_card_status_8`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_card_status_9`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_card_status_9` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `card_0` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'insurance(保安卡)',
  `card_1` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'defense(防御卡)',
  `card_2` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '财神卡',
  `card_3` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '穷神卡',
  `card_4` int(10) unsigned NOT NULL DEFAULT '0',
  `card_5` int(10) unsigned NOT NULL DEFAULT '0',
  `card_6` int(10) unsigned NOT NULL DEFAULT '0',
  `card_7` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_card_status_9`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_compensationlog_1`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_compensationlog_1` (
  `id` smallint(5) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_compensationlog_1`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_compensationlog_2`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_compensationlog_2` (
  `id` smallint(5) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_compensationlog_2`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_compensationlog_3`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_compensationlog_3` (
  `id` smallint(5) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_compensationlog_3`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_compensationlog_4`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_compensationlog_4` (
  `id` smallint(5) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_compensationlog_4`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_compensationlog_5`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_compensationlog_5` (
  `id` smallint(5) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_compensationlog_5`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_compensationlog_6`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_compensationlog_6` (
  `id` smallint(5) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_compensationlog_6`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_compensationlog_7`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_compensationlog_7` (
  `id` smallint(5) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_compensationlog_7`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_compensationlog_8`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_compensationlog_8` (
  `id` smallint(5) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_compensationlog_8`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_compensationlog_9`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_compensationlog_9` (
  `id` smallint(5) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_compensationlog_9`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_getcoin_0`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_getcoin_0` (
  `uid` int(10) unsigned NOT NULL,
  `num` int(10) unsigned NOT NULL DEFAULT '0',
  `date` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uid`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_getcoin_0`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_getcoin_1`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_getcoin_1` (
  `uid` int(10) unsigned NOT NULL,
  `num` int(10) unsigned NOT NULL DEFAULT '0',
  `date` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uid`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_getcoin_1`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_getcoin_2`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_getcoin_2` (
  `uid` int(10) unsigned NOT NULL,
  `num` int(10) unsigned NOT NULL DEFAULT '0',
  `date` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uid`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_getcoin_2`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_getcoin_3`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_getcoin_3` (
  `uid` int(10) unsigned NOT NULL,
  `num` int(10) unsigned NOT NULL DEFAULT '0',
  `date` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uid`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_getcoin_3`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_getcoin_4`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_getcoin_4` (
  `uid` int(10) unsigned NOT NULL,
  `num` int(10) unsigned NOT NULL DEFAULT '0',
  `date` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uid`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_getcoin_4`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_getcoin_5`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_getcoin_5` (
  `uid` int(10) unsigned NOT NULL,
  `num` int(10) unsigned NOT NULL DEFAULT '0',
  `date` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uid`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_getcoin_5`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_getcoin_6`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_getcoin_6` (
  `uid` int(10) unsigned NOT NULL,
  `num` int(10) unsigned NOT NULL DEFAULT '0',
  `date` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uid`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_getcoin_6`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_getcoin_7`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_getcoin_7` (
  `uid` int(10) unsigned NOT NULL,
  `num` int(10) unsigned NOT NULL DEFAULT '0',
  `date` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uid`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_getcoin_7`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_getcoin_8`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_getcoin_8` (
  `uid` int(10) unsigned NOT NULL,
  `num` int(10) unsigned NOT NULL DEFAULT '0',
  `date` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uid`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_getcoin_8`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_getcoin_9`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_getcoin_9` (
  `uid` int(10) unsigned NOT NULL,
  `num` int(10) unsigned NOT NULL DEFAULT '0',
  `date` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uid`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_getcoin_9`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_goldlog_201102_0`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_goldlog_201102_0` (
  `uid` int(10) unsigned NOT NULL,
  `cost` int(10) unsigned NOT NULL,
  `summary` varchar(30) NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  `user_level` tinyint(3) unsigned NOT NULL,
  `cid` int(10) unsigned NOT NULL,
  `num` smallint(5) unsigned NOT NULL,
  KEY `uid` (`uid`,`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_goldlog_201102_0`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_info_0`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_info_0` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `coin` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '金币数',
  `gold` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '宝石数',
  `exp` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '经验值',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '等级',
  `ipanda_level` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '初始岛等级',
  `ipanda_level_2` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '第二岛屿等级',
  `ipanda_level_3` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '第三岛屿等级',
  `ipanda_level_4` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '第四岛屿等级',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0',
  `today_login_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `active_login_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `max_active_login_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `all_login_count` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '累计登录天数',
  `star_login_count` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '星座活动专用累计登录天数',
  `starfish` int(10) unsigned NOT NULL DEFAULT '0',
  `inviter` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_info_0`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_info_1`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_info_1` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `love` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '爱心值',
  `gold` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '宝石数',
  `exp` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '经验值',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '等级',
  `energy` smallint(6) NOT NULL DEFAULT '0' COMMENT '体力值',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `today_login_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `active_login_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `max_active_login_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `all_login_count` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '累计登录天数',
  `inviter` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_info_1`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_info_2`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_info_2` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `coin` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '金币数',
  `gold` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '宝石数',
  `exp` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '经验值',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '等级',
  `ipanda_level` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '初始岛等级',
  `ipanda_level_2` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '第二岛屿等级',
  `ipanda_level_3` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '第三岛屿等级',
  `ipanda_level_4` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '第四岛屿等级',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0',
  `today_login_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `active_login_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `max_active_login_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `all_login_count` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '累计登录天数',
  `star_login_count` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '星座活动专用累计登录天数',
  `starfish` int(10) unsigned NOT NULL DEFAULT '0',
  `inviter` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_info_2`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_info_3`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_info_3` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `coin` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '金币数',
  `gold` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '宝石数',
  `exp` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '经验值',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '等级',
  `ipanda_level` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '初始岛等级',
  `ipanda_level_2` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '第二岛屿等级',
  `ipanda_level_3` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '第三岛屿等级',
  `ipanda_level_4` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '第四岛屿等级',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0',
  `today_login_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `active_login_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `max_active_login_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `all_login_count` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '累计登录天数',
  `star_login_count` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '星座活动专用累计登录天数',
  `starfish` int(10) unsigned NOT NULL DEFAULT '0',
  `inviter` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_info_3`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_info_4`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_info_4` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `coin` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '金币数',
  `gold` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '宝石数',
  `exp` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '经验值',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '等级',
  `ipanda_level` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '初始岛等级',
  `ipanda_level_2` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '第二岛屿等级',
  `ipanda_level_3` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '第三岛屿等级',
  `ipanda_level_4` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '第四岛屿等级',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0',
  `today_login_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `active_login_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `max_active_login_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `all_login_count` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '累计登录天数',
  `star_login_count` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '星座活动专用累计登录天数',
  `starfish` int(10) unsigned NOT NULL DEFAULT '0',
  `inviter` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_info_4`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_info_5`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_info_5` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `coin` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '金币数',
  `gold` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '宝石数',
  `exp` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '经验值',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '等级',
  `ipanda_level` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '初始岛等级',
  `ipanda_level_2` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '第二岛屿等级',
  `ipanda_level_3` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '第三岛屿等级',
  `ipanda_level_4` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '第四岛屿等级',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0',
  `today_login_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `active_login_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `max_active_login_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `all_login_count` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '累计登录天数',
  `star_login_count` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '星座活动专用累计登录天数',
  `starfish` int(10) unsigned NOT NULL DEFAULT '0',
  `inviter` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_info_5`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_info_6`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_info_6` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `coin` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '金币数',
  `gold` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '宝石数',
  `exp` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '经验值',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '等级',
  `ipanda_level` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '初始岛等级',
  `ipanda_level_2` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '第二岛屿等级',
  `ipanda_level_3` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '第三岛屿等级',
  `ipanda_level_4` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '第四岛屿等级',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0',
  `today_login_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `active_login_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `max_active_login_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `all_login_count` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '累计登录天数',
  `star_login_count` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '星座活动专用累计登录天数',
  `starfish` int(10) unsigned NOT NULL DEFAULT '0',
  `inviter` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_info_6`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_info_7`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_info_7` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `coin` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '金币数',
  `gold` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '宝石数',
  `exp` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '经验值',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '等级',
  `ipanda_level` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '初始岛等级',
  `ipanda_level_2` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '第二岛屿等级',
  `ipanda_level_3` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '第三岛屿等级',
  `ipanda_level_4` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '第四岛屿等级',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0',
  `today_login_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `active_login_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `max_active_login_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `all_login_count` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '累计登录天数',
  `star_login_count` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '星座活动专用累计登录天数',
  `starfish` int(10) unsigned NOT NULL DEFAULT '0',
  `inviter` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_info_7`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_info_8`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_info_8` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `coin` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '金币数',
  `gold` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '宝石数',
  `exp` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '经验值',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '等级',
  `ipanda_level` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '初始岛等级',
  `ipanda_level_2` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '第二岛屿等级',
  `ipanda_level_3` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '第三岛屿等级',
  `ipanda_level_4` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '第四岛屿等级',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0',
  `today_login_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `active_login_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `max_active_login_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `all_login_count` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '累计登录天数',
  `star_login_count` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '星座活动专用累计登录天数',
  `starfish` int(10) unsigned NOT NULL DEFAULT '0',
  `inviter` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_info_8`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_info_9`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_info_9` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `coin` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '金币数',
  `gold` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '宝石数',
  `exp` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '经验值',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '等级',
  `ipanda_level` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '初始岛等级',
  `ipanda_level_2` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '第二岛屿等级',
  `ipanda_level_3` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '第三岛屿等级',
  `ipanda_level_4` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '第四岛屿等级',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0',
  `today_login_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `active_login_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `max_active_login_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `all_login_count` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '累计登录天数',
  `star_login_count` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '星座活动专用累计登录天数',
  `starfish` int(10) unsigned NOT NULL DEFAULT '0',
  `inviter` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_info_9`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_invitelog_0`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_invitelog_0` (
  `uid` int(10) unsigned NOT NULL,
  `fid` int(10) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uid`,`fid`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_invitelog_0`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_invitelog_1`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_invitelog_1` (
  `uid` int(10) unsigned NOT NULL,
  `fid` int(10) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL,
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_invitelog_1`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_invitelog_2`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_invitelog_2` (
  `uid` int(10) unsigned NOT NULL,
  `fid` int(10) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL,
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_invitelog_2`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_invitelog_3`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_invitelog_3` (
  `uid` int(10) unsigned NOT NULL,
  `fid` int(10) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL,
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_invitelog_3`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_invitelog_4`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_invitelog_4` (
  `uid` int(10) unsigned NOT NULL,
  `fid` int(10) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL,
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_invitelog_4`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_invitelog_5`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_invitelog_5` (
  `uid` int(10) unsigned NOT NULL,
  `fid` int(10) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL,
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_invitelog_5`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_invitelog_6`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_invitelog_6` (
  `uid` int(10) unsigned NOT NULL,
  `fid` int(10) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL,
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_invitelog_6`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_invitelog_7`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_invitelog_7` (
  `uid` int(10) unsigned NOT NULL,
  `fid` int(10) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL,
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_invitelog_7`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_invitelog_8`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_invitelog_8` (
  `uid` int(10) unsigned NOT NULL,
  `fid` int(10) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL,
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_invitelog_8`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_invitelog_9`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_invitelog_9` (
  `uid` int(10) unsigned NOT NULL,
  `fid` int(10) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL,
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_invitelog_9`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_leveluplog_0`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_leveluplog_0` (
  `uid` int(10) unsigned NOT NULL,
  `from_level` tinyint(3) unsigned NOT NULL,
  `to_level` tinyint(3) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_leveluplog_0`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_leveluplog_1`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_leveluplog_1` (
  `uid` int(10) unsigned NOT NULL,
  `from_level` tinyint(3) unsigned NOT NULL,
  `to_level` tinyint(3) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_leveluplog_1`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_leveluplog_2`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_leveluplog_2` (
  `uid` int(10) unsigned NOT NULL,
  `from_level` tinyint(3) unsigned NOT NULL,
  `to_level` tinyint(3) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_leveluplog_2`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_leveluplog_3`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_leveluplog_3` (
  `uid` int(10) unsigned NOT NULL,
  `from_level` tinyint(3) unsigned NOT NULL,
  `to_level` tinyint(3) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_leveluplog_3`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_leveluplog_4`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_leveluplog_4` (
  `uid` int(10) unsigned NOT NULL,
  `from_level` tinyint(3) unsigned NOT NULL,
  `to_level` tinyint(3) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_leveluplog_4`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_leveluplog_5`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_leveluplog_5` (
  `uid` int(10) unsigned NOT NULL,
  `from_level` tinyint(3) unsigned NOT NULL,
  `to_level` tinyint(3) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_leveluplog_5`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_leveluplog_6`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_leveluplog_6` (
  `uid` int(10) unsigned NOT NULL,
  `from_level` tinyint(3) unsigned NOT NULL,
  `to_level` tinyint(3) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_leveluplog_6`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_leveluplog_7`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_leveluplog_7` (
  `uid` int(10) unsigned NOT NULL,
  `from_level` tinyint(3) unsigned NOT NULL,
  `to_level` tinyint(3) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_leveluplog_7`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_leveluplog_8`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_leveluplog_8` (
  `uid` int(10) unsigned NOT NULL,
  `from_level` tinyint(3) unsigned NOT NULL,
  `to_level` tinyint(3) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_leveluplog_8`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_leveluplog_9`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_leveluplog_9` (
  `uid` int(10) unsigned NOT NULL,
  `from_level` tinyint(3) unsigned NOT NULL,
  `to_level` tinyint(3) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_leveluplog_9`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_lovelog_201102_1`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_lovelog_201102_1` (
  `uid` int(10) unsigned NOT NULL,
  `cost` int(10) unsigned NOT NULL,
  `summary` varchar(30) NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  KEY `uid` (`uid`,`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_lovelog_201102_1`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_paylog_0`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_paylog_0` (
  `uid` int(10) unsigned NOT NULL,
  `orderid` varchar(32) NOT NULL,
  `pid` varchar(32) NOT NULL,
  `amount` int(10) unsigned NOT NULL,
  `gold` int(10) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  `user_level` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `pay_before_gold` int(11) NOT NULL DEFAULT '-1',
  KEY `idx_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_paylog_0`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_paylog_1`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_paylog_1` (
  `uid` int(10) unsigned NOT NULL,
  `orderid` varchar(32) NOT NULL,
  `pid` varchar(32) NOT NULL,
  `amount` int(10) unsigned NOT NULL,
  `gold` int(10) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  `user_level` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `pay_before_gold` int(11) NOT NULL DEFAULT '-1',
  KEY `idx_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_paylog_1`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_paylog_2`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_paylog_2` (
  `uid` int(10) unsigned NOT NULL,
  `orderid` varchar(32) NOT NULL,
  `pid` varchar(32) NOT NULL,
  `amount` int(10) unsigned NOT NULL,
  `gold` int(10) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  `user_level` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `pay_before_gold` int(11) NOT NULL DEFAULT '-1',
  KEY `idx_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_paylog_2`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_paylog_3`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_paylog_3` (
  `uid` int(10) unsigned NOT NULL,
  `orderid` varchar(32) NOT NULL,
  `pid` varchar(32) NOT NULL,
  `amount` int(10) unsigned NOT NULL,
  `gold` int(10) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  `user_level` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `pay_before_gold` int(11) NOT NULL DEFAULT '-1',
  KEY `idx_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_paylog_3`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_paylog_4`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_paylog_4` (
  `uid` int(10) unsigned NOT NULL,
  `orderid` varchar(32) NOT NULL,
  `pid` varchar(32) NOT NULL,
  `amount` int(10) unsigned NOT NULL,
  `gold` int(10) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  `user_level` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `pay_before_gold` int(11) NOT NULL DEFAULT '-1',
  KEY `idx_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_paylog_4`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_paylog_5`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_paylog_5` (
  `uid` int(10) unsigned NOT NULL,
  `orderid` varchar(32) NOT NULL,
  `pid` varchar(32) NOT NULL,
  `amount` int(10) unsigned NOT NULL,
  `gold` int(10) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  `user_level` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `pay_before_gold` int(11) NOT NULL DEFAULT '-1',
  KEY `idx_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_paylog_5`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_paylog_6`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_paylog_6` (
  `uid` int(10) unsigned NOT NULL,
  `orderid` varchar(32) NOT NULL,
  `pid` varchar(32) NOT NULL,
  `amount` int(10) unsigned NOT NULL,
  `gold` int(10) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  `user_level` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `pay_before_gold` int(11) NOT NULL DEFAULT '-1',
  KEY `idx_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_paylog_6`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_paylog_7`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_paylog_7` (
  `uid` int(10) unsigned NOT NULL,
  `orderid` varchar(32) NOT NULL,
  `pid` varchar(32) NOT NULL,
  `amount` int(10) unsigned NOT NULL,
  `gold` int(10) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  `user_level` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `pay_before_gold` int(11) NOT NULL DEFAULT '-1',
  KEY `idx_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_paylog_7`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_paylog_8`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_paylog_8` (
  `uid` int(10) unsigned NOT NULL,
  `orderid` varchar(32) NOT NULL,
  `pid` varchar(32) NOT NULL,
  `amount` int(10) unsigned NOT NULL,
  `gold` int(10) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  `user_level` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `pay_before_gold` int(11) NOT NULL DEFAULT '-1',
  KEY `idx_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_paylog_8`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_paylog_9`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_paylog_9` (
  `uid` int(10) unsigned NOT NULL,
  `orderid` varchar(32) NOT NULL,
  `pid` varchar(32) NOT NULL,
  `amount` int(10) unsigned NOT NULL,
  `gold` int(10) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  `user_level` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `pay_before_gold` int(11) NOT NULL DEFAULT '-1',
  KEY `idx_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_paylog_9`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_payorder`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_payorder` (
  `orderid` varchar(32) NOT NULL,
  `amount` int(10) unsigned NOT NULL,
  `gold` int(10) NOT NULL,
  `create_time` int(10) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `pid` varchar(32) NOT NULL DEFAULT '',
  `complete_time` int(10) NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL,
  `user_level` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`orderid`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_payorder`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_payorder_flow_201102_0`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_payorder_flow_201102_0` (
  `orderid` varchar(32) NOT NULL,
  `amount` int(10) unsigned NOT NULL,
  `gold` int(10) NOT NULL,
  `create_time` int(10) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `result` int(11) NOT NULL DEFAULT '0',
  `pid` varchar(32) NOT NULL DEFAULT '',
  `complete_time` int(10) NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL,
  `user_level` tinyint(3) unsigned NOT NULL DEFAULT '0',
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_payorder_flow_201102_0`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_plant_0`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_plant_0` (
  `uid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL,
  `cid` int(10) unsigned NOT NULL,
  `level` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `item_id` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL,
  `x` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `y` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `z` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `mirro` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `can_find` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `buy_time` int(10) unsigned NOT NULL DEFAULT '0',
  `start_pay_time` int(10) unsigned NOT NULL DEFAULT '0',
  `wait_visitor_num` int(10) unsigned NOT NULL DEFAULT '0',
  `delay_time` int(10) unsigned NOT NULL DEFAULT '0',
  `event` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `start_deposit` int(10) unsigned NOT NULL DEFAULT '0',
  `deposit` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_plant_0`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_seq_0`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_seq_0` (
  `uid` int(10) unsigned NOT NULL,
  `name` char(1) NOT NULL,
  `id` int(10) unsigned NOT NULL DEFAULT '100',
  PRIMARY KEY (`uid`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_seq_0`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_seq_1`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_seq_1` (
  `uid` int(10) unsigned NOT NULL,
  `name` char(1) NOT NULL,
  `id` int(10) unsigned NOT NULL DEFAULT '100',
  PRIMARY KEY (`uid`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_seq_1`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_seq_2`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_seq_2` (
  `uid` int(10) unsigned NOT NULL,
  `name` char(1) NOT NULL,
  `id` int(10) unsigned NOT NULL DEFAULT '100',
  PRIMARY KEY (`uid`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_seq_2`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_seq_3`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_seq_3` (
  `uid` int(10) unsigned NOT NULL,
  `name` char(1) NOT NULL,
  `id` int(10) unsigned NOT NULL DEFAULT '100',
  PRIMARY KEY (`uid`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_seq_3`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_seq_4`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_seq_4` (
  `uid` int(10) unsigned NOT NULL,
  `name` char(1) NOT NULL,
  `id` int(10) unsigned NOT NULL DEFAULT '100',
  PRIMARY KEY (`uid`,`name`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_seq_4`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_seq_5`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_seq_5` (
  `uid` int(10) unsigned NOT NULL,
  `name` char(1) NOT NULL,
  `id` int(10) unsigned NOT NULL DEFAULT '100',
  PRIMARY KEY (`uid`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_seq_5`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_seq_6`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_seq_6` (
  `uid` int(10) unsigned NOT NULL,
  `name` char(1) NOT NULL,
  `id` int(10) unsigned NOT NULL DEFAULT '100',
  PRIMARY KEY (`uid`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_seq_6`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_seq_7`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_seq_7` (
  `uid` int(10) unsigned NOT NULL,
  `name` char(1) NOT NULL,
  `id` int(10) unsigned NOT NULL DEFAULT '100',
  PRIMARY KEY (`uid`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_seq_7`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_seq_8`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_seq_8` (
  `uid` int(10) unsigned NOT NULL,
  `name` char(1) NOT NULL,
  `id` int(10) unsigned NOT NULL DEFAULT '100',
  PRIMARY KEY (`uid`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_seq_8`
--


-- --------------------------------------------------------

--
-- 表的结构 `ipanda_user_seq_9`
--

CREATE TABLE IF NOT EXISTS `ipanda_user_seq_9` (
  `uid` int(10) unsigned NOT NULL,
  `name` char(1) NOT NULL,
  `id` int(10) unsigned NOT NULL DEFAULT '100',
  PRIMARY KEY (`uid`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ipanda_user_seq_9`
--


-- --------------------------------------------------------

--
-- 表的结构 `platform_user_friend_0`
--

CREATE TABLE IF NOT EXISTS `platform_user_friend_0` (
  `uid` int(10) unsigned NOT NULL,
  `fids` varchar(12000) NOT NULL DEFAULT '',
  `count` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `platform_user_friend_0`
--


-- --------------------------------------------------------

--
-- 表的结构 `platform_user_friend_1`
--

CREATE TABLE IF NOT EXISTS `platform_user_friend_1` (
  `uid` int(10) unsigned NOT NULL,
  `fids` varchar(12000) NOT NULL DEFAULT '',
  `count` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `platform_user_friend_1`
--


-- --------------------------------------------------------

--
-- 表的结构 `platform_user_friend_2`
--

CREATE TABLE IF NOT EXISTS `platform_user_friend_2` (
  `uid` int(10) unsigned NOT NULL,
  `fids` varchar(12000) NOT NULL DEFAULT '',
  `count` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `platform_user_friend_2`
--


-- --------------------------------------------------------

--
-- 表的结构 `platform_user_friend_3`
--

CREATE TABLE IF NOT EXISTS `platform_user_friend_3` (
  `uid` int(10) unsigned NOT NULL,
  `fids` varchar(12000) NOT NULL DEFAULT '',
  `count` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `platform_user_friend_3`
--


-- --------------------------------------------------------

--
-- 表的结构 `platform_user_friend_4`
--

CREATE TABLE IF NOT EXISTS `platform_user_friend_4` (
  `uid` int(10) unsigned NOT NULL,
  `fids` varchar(12000) NOT NULL DEFAULT '',
  `count` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `platform_user_friend_4`
--


-- --------------------------------------------------------

--
-- 表的结构 `platform_user_friend_5`
--

CREATE TABLE IF NOT EXISTS `platform_user_friend_5` (
  `uid` int(10) unsigned NOT NULL,
  `fids` varchar(12000) NOT NULL DEFAULT '',
  `count` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `platform_user_friend_5`
--


-- --------------------------------------------------------

--
-- 表的结构 `platform_user_friend_6`
--

CREATE TABLE IF NOT EXISTS `platform_user_friend_6` (
  `uid` int(10) unsigned NOT NULL,
  `fids` varchar(12000) NOT NULL DEFAULT '',
  `count` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `platform_user_friend_6`
--


-- --------------------------------------------------------

--
-- 表的结构 `platform_user_friend_7`
--

CREATE TABLE IF NOT EXISTS `platform_user_friend_7` (
  `uid` int(10) unsigned NOT NULL,
  `fids` varchar(12000) NOT NULL DEFAULT '',
  `count` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `platform_user_friend_7`
--


-- --------------------------------------------------------

--
-- 表的结构 `platform_user_friend_8`
--

CREATE TABLE IF NOT EXISTS `platform_user_friend_8` (
  `uid` int(10) unsigned NOT NULL,
  `fids` varchar(12000) NOT NULL DEFAULT '',
  `count` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `platform_user_friend_8`
--


-- --------------------------------------------------------

--
-- 表的结构 `platform_user_friend_9`
--

CREATE TABLE IF NOT EXISTS `platform_user_friend_9` (
  `uid` int(10) unsigned NOT NULL,
  `fids` varchar(12000) NOT NULL DEFAULT '',
  `count` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `platform_user_friend_9`
--


-- --------------------------------------------------------

--
-- 表的结构 `platform_user_info_0`
--

CREATE TABLE IF NOT EXISTS `platform_user_info_0` (
  `uid` int(10) unsigned NOT NULL,
  `puid` int(10) NOT NULL,
  `name` varchar(32) NOT NULL DEFAULT '',
  `figureurl` varchar(255) NOT NULL DEFAULT '',
  `gender` tinyint(4) NOT NULL DEFAULT '-1',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `vuid` varchar(16) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `status_update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `platform_user_info_0`
--


-- --------------------------------------------------------

--
-- 表的结构 `platform_user_info_1`
--

CREATE TABLE IF NOT EXISTS `platform_user_info_1` (
  `uid` int(10) unsigned NOT NULL,
  `puid` int(10) NOT NULL,
  `name` varchar(32) NOT NULL DEFAULT '',
  `figureurl` varchar(255) NOT NULL DEFAULT '',
  `gender` tinyint(4) NOT NULL DEFAULT '-1',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `vuid` varchar(16) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `status_update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `platform_user_info_1`
--


-- --------------------------------------------------------

--
-- 表的结构 `platform_user_info_2`
--

CREATE TABLE IF NOT EXISTS `platform_user_info_2` (
  `uid` int(10) unsigned NOT NULL,
  `puid` int(10) NOT NULL,
  `name` varchar(32) NOT NULL DEFAULT '',
  `figureurl` varchar(255) NOT NULL DEFAULT '',
  `gender` tinyint(4) NOT NULL DEFAULT '-1',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `vuid` varchar(16) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `status_update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `platform_user_info_2`
--


-- --------------------------------------------------------

--
-- 表的结构 `platform_user_info_3`
--

CREATE TABLE IF NOT EXISTS `platform_user_info_3` (
  `uid` int(10) unsigned NOT NULL,
  `puid` int(10) NOT NULL,
  `name` varchar(32) NOT NULL DEFAULT '',
  `figureurl` varchar(255) NOT NULL DEFAULT '',
  `gender` tinyint(4) NOT NULL DEFAULT '-1',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `vuid` varchar(16) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `status_update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `platform_user_info_3`
--


-- --------------------------------------------------------

--
-- 表的结构 `platform_user_info_4`
--

CREATE TABLE IF NOT EXISTS `platform_user_info_4` (
  `uid` int(10) unsigned NOT NULL,
  `puid` int(10) NOT NULL,
  `name` varchar(32) NOT NULL DEFAULT '',
  `figureurl` varchar(255) NOT NULL DEFAULT '',
  `gender` tinyint(4) NOT NULL DEFAULT '-1',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `vuid` varchar(16) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `status_update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `platform_user_info_4`
--


-- --------------------------------------------------------

--
-- 表的结构 `platform_user_info_5`
--

CREATE TABLE IF NOT EXISTS `platform_user_info_5` (
  `uid` int(10) unsigned NOT NULL,
  `puid` int(10) NOT NULL,
  `name` varchar(32) NOT NULL DEFAULT '',
  `figureurl` varchar(255) NOT NULL DEFAULT '',
  `gender` tinyint(4) NOT NULL DEFAULT '-1',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `vuid` varchar(16) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `status_update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `platform_user_info_5`
--


-- --------------------------------------------------------

--
-- 表的结构 `platform_user_info_6`
--

CREATE TABLE IF NOT EXISTS `platform_user_info_6` (
  `uid` int(10) unsigned NOT NULL,
  `puid` int(10) NOT NULL,
  `name` varchar(32) NOT NULL DEFAULT '',
  `figureurl` varchar(255) NOT NULL DEFAULT '',
  `gender` tinyint(4) NOT NULL DEFAULT '-1',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `vuid` varchar(16) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `status_update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `platform_user_info_6`
--


-- --------------------------------------------------------

--
-- 表的结构 `platform_user_info_7`
--

CREATE TABLE IF NOT EXISTS `platform_user_info_7` (
  `uid` int(10) unsigned NOT NULL,
  `puid` int(10) NOT NULL,
  `name` varchar(32) NOT NULL DEFAULT '',
  `figureurl` varchar(255) NOT NULL DEFAULT '',
  `gender` tinyint(4) NOT NULL DEFAULT '-1',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `vuid` varchar(16) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `status_update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `platform_user_info_7`
--


-- --------------------------------------------------------

--
-- 表的结构 `platform_user_info_8`
--

CREATE TABLE IF NOT EXISTS `platform_user_info_8` (
  `uid` int(10) unsigned NOT NULL,
  `puid` int(10) NOT NULL,
  `name` varchar(32) NOT NULL DEFAULT '',
  `figureurl` varchar(255) NOT NULL DEFAULT '',
  `gender` tinyint(4) NOT NULL DEFAULT '-1',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `vuid` varchar(16) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `status_update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `platform_user_info_8`
--


-- --------------------------------------------------------

--
-- 表的结构 `platform_user_info_9`
--

CREATE TABLE IF NOT EXISTS `platform_user_info_9` (
  `uid` int(10) unsigned NOT NULL,
  `puid` int(10) NOT NULL,
  `name` varchar(32) NOT NULL DEFAULT '',
  `figureurl` varchar(255) NOT NULL DEFAULT '',
  `gender` tinyint(4) NOT NULL DEFAULT '-1',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `vuid` varchar(16) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `status_update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `platform_user_info_9`
--

