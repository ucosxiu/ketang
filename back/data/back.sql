/*
SQLyog Ultimate v11.27 (32 bit)
MySQL - 5.7.23 : Database - back
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`back` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `back`;

/*Table structure for table `daka_ad` */

DROP TABLE IF EXISTS `daka_ad`;

CREATE TABLE `daka_ad` (
  `ad_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `position_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ad_img` varchar(255) NOT NULL DEFAULT '',
  `ad_link` varchar(255) NOT NULL DEFAULT '',
  `ad_title` varchar(255) NOT NULL DEFAULT '',
  `ad_stitle` varchar(255) NOT NULL DEFAULT '',
  `listorder` int(5) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ad_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `daka_ad` */

insert  into `daka_ad`(`ad_id`,`position_id`,`ad_img`,`ad_link`,`ad_title`,`ad_stitle`,`listorder`,`status`) values (5,3,'assets\\ads\\20181212\\7304d3adc700853819d69315a53b5f2e.jpg','','','',10000,1);

/*Table structure for table `daka_ad_position` */

DROP TABLE IF EXISTS `daka_ad_position`;

CREATE TABLE `daka_ad_position` (
  `position_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `position_name` varchar(60) NOT NULL DEFAULT '',
  `ad_width` smallint(5) unsigned NOT NULL DEFAULT '0',
  `ad_height` smallint(5) unsigned NOT NULL DEFAULT '0',
  `position_model` varchar(255) NOT NULL,
  `position_desc` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`position_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `daka_ad_position` */

insert  into `daka_ad_position`(`position_id`,`position_name`,`ad_width`,`ad_height`,`position_model`,`position_desc`) values (3,'首页广告',1920,380,'index_ad','');

/*Table structure for table `daka_admin` */

DROP TABLE IF EXISTS `daka_admin`;

CREATE TABLE `daka_admin` (
  `adminid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(60) NOT NULL DEFAULT '',
  `password` varchar(60) NOT NULL DEFAULT '',
  `email` varchar(60) NOT NULL DEFAULT '',
  `roleid` int(10) unsigned NOT NULL DEFAULT '0',
  `salt` varchar(8) NOT NULL DEFAULT '',
  `lastloginip` varchar(60) NOT NULL DEFAULT '',
  `lastlogintime` int(10) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`adminid`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

/*Data for the table `daka_admin` */

insert  into `daka_admin`(`adminid`,`username`,`password`,`email`,`roleid`,`salt`,`lastloginip`,`lastlogintime`,`status`) values (12,'admin','189ba9ab01e5d942c75fdcd47888624d','admin@163.com',1,'574557','127.0.0.1',1552223320,1);

/*Table structure for table `daka_admin_menu` */

DROP TABLE IF EXISTS `daka_admin_menu`;

CREATE TABLE `daka_admin_menu` (
  `menuid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '菜单id',
  `menuname` varchar(30) NOT NULL DEFAULT '' COMMENT '菜单名',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父菜单id',
  `m` varchar(30) NOT NULL DEFAULT '' COMMENT '模块',
  `c` varchar(30) NOT NULL DEFAULT '' COMMENT '控制',
  `a` varchar(30) NOT NULL DEFAULT '' COMMENT '操作',
  `parameter` varchar(255) NOT NULL DEFAULT '' COMMENT '参数',
  `status` tinyint(10) unsigned NOT NULL DEFAULT '0' COMMENT '显示',
  `icon` varchar(30) NOT NULL DEFAULT '' COMMENT '图标',
  `listorder` int(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '权限认证：1添加到权限',
  PRIMARY KEY (`menuid`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

/*Data for the table `daka_admin_menu` */

insert  into `daka_admin_menu`(`menuid`,`menuname`,`parentid`,`m`,`c`,`a`,`parameter`,`status`,`icon`,`listorder`,`type`) values (1,'面板',0,'back','index','index','',1,'dashboard',1,1),(18,'字体',0,'back','font','index','',1,'',10000,1),(3,'菜单',0,'back','menu','default','',1,'list',3,1),(4,'后台菜单',3,'back','menu','index','',1,'',102,1),(9,'管理员设置',0,'back','admin','default','',1,'group',4,1),(10,'管理员管理',9,'back','admin','index','',1,'',0,1),(11,'角色管理',9,'back','role','index','',1,'',0,1),(12,'设置',0,'back','setting','default','',1,'',2,1),(13,'网站信息',12,'back','setting','base','',1,'',10000,1),(14,'邮箱配置',12,'back','mailer','index','',1,'',10000,1),(15,'广告',0,'back','ads','default','',1,'',10000,1),(16,'广告位置',15,'back','adposition','index','',1,'',10000,1),(17,'广告列表',15,'back','ad','index','',1,'',10000,1),(19,'校园管理',0,'back','default','default','',1,'',10000,1),(20,'学院管理',19,'back','college','index','',1,'',10000,1),(21,'班级管理',19,'back','classes','index','',1,'',10000,1),(22,'老师管理',19,'back','teacher','index','',1,'',10000,1),(27,'学生管理',19,'back','student','index','',1,'',10000,1),(28,'请假管理',19,'back','leave','index','',1,'',10000,1);

/*Table structure for table `daka_admin_role` */

DROP TABLE IF EXISTS `daka_admin_role`;

CREATE TABLE `daka_admin_role` (
  `roleid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色id',
  `rolename` varchar(60) NOT NULL DEFAULT '' COMMENT '角色名',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '启用',
  PRIMARY KEY (`roleid`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `daka_admin_role` */

/*Table structure for table `daka_admin_role_priv` */

DROP TABLE IF EXISTS `daka_admin_role_priv`;

CREATE TABLE `daka_admin_role_priv` (
  `roleid` int(10) unsigned NOT NULL DEFAULT '0',
  `m` varchar(30) NOT NULL DEFAULT '',
  `c` varchar(30) NOT NULL DEFAULT '',
  `a` varchar(30) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `daka_admin_role_priv` */

/*Table structure for table `daka_assets` */

DROP TABLE IF EXISTS `daka_assets`;

CREATE TABLE `daka_assets` (
  `assetid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '路径',
  `width` int(3) unsigned NOT NULL DEFAULT '0' COMMENT '宽',
  `height` int(3) unsigned NOT NULL DEFAULT '0' COMMENT '高',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `recommend` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '推荐',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`assetid`)
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;

/*Data for the table `daka_assets` */

insert  into `daka_assets`(`assetid`,`uid`,`url`,`width`,`height`,`dateline`,`recommend`,`status`) values (1,2,'assets/20181214/87c8e67a382c600e889afb45e59aeff3.jpg',240,240,1544786787,0,0),(2,2,'assets/20181228/7542611fa311db1c03eef7b7b8abfe06.png',900,500,1545942772,0,0),(3,2,'assets/20181228/36cfede531c6c8214040e8367d92e5a6.png',289,285,1545942781,0,0),(4,2,'assets/20181228/0c1d16eddae6a3fdd6e4261225a965e5.png',220,96,1545942785,0,0),(5,2,'assets/20181228/9d418be09b1685a4025c07763a7aef12.png',220,96,1545942790,0,0),(6,2,'assets/20181228/fdbfeddf89bd698a4bcbcebb67945e3d.png',289,285,1545942803,0,0),(7,2,'assets/20181228/af94590e598216980f341b28551c6e43.png',72,197,1545942821,0,0),(8,2,'assets/20181228/2717e171d4b0187d3b362063896b01ca.png',154,158,1545942880,0,0),(9,2,'assets/20181228/68205fc2ff8275f1a78c09ae94e9d6c3.png',49,61,1545942892,0,0),(10,2,'assets/20181228/0ece455e1d06e65fb6a35e44d09de832.png',55,87,1545942894,0,0),(11,2,'assets/20181228/31e5da31f0fb6119f6f18149432f57f4.png',181,262,1545942897,0,0),(12,2,'assets/20181228/89a11b9c893c20a9bf330c711b172d66.png',157,133,1545942899,0,0),(13,2,'assets/20181228/e9bbfc20064e2793a1c2d54620e65b2c.png',116,96,1545942929,0,0),(14,2,'assets/20181228/69d1c23f244b1553cbc77123fa8af5e0.png',42,45,1545942940,0,0),(15,2,'assets/20181228/d5c845f612904dd9d73de6b71315fe8b.png',1800,766,1545994385,0,0),(16,2,'assets/20181228/14b17a8978a9f3b1abf0d77e54a95474.png',127,119,1545994389,0,0),(17,2,'assets/20181228/938c12aa9a6d55e392980b55d82cd160.png',952,335,1545994391,0,0),(18,2,'assets/20181228/b65d30a3503ed96165a450fcb5aa7ad3.png',222,57,1545997332,0,0),(19,2,'assets/20181228/5049a38431c93fc038948e74e0c97b68.png',410,93,1545997335,0,0),(20,2,'assets/20181228/64e3054edd22c13bf9f820ce277c0812.png',422,370,1545997337,0,0),(21,2,'assets/20181228/9607d86dbae45be7b195be535347ee5a.png',640,960,1545997782,0,0),(22,2,'assets/20181228/9dc836b21f456380cefdadc6d53bdd75.png',1772,3836,1545999023,0,0),(23,2,'assets/20181228/cc2b368060c27a490164c46b842ef50e.png',719,144,1545999038,0,0),(24,2,'assets/20181228/8a3a6cb54aad410f2d65ed6e29784719.png',1543,1998,1545999283,0,0),(25,2,'assets/20181228/bf2fb7bd5fd9f54d3a98a9142677038d.png',1681,3416,1545999440,0,0),(26,2,'assets/20181228/479e63a81301a23ced6398333231fabf.png',1734,3786,1546000919,0,0),(27,2,'assets/20181228/06d6e2479da487e8e7f991d6e75fb22d.png',1574,1628,1546000925,0,0),(28,2,'assets/20181228/e8968b0b26ba40d55cea2a82f386d6cb.png',692,178,1546000933,0,0),(29,2,'assets/20181228/b634add766198ab7f1b2b4125f85e343.png',1225,520,1546000935,0,0),(30,2,'assets/20181228/77f392b57b6bfd65656745febbcf64bc.png',1734,335,1546000993,0,0),(31,2,'assets/20181228/b5157b6825151b295a37e93b204c1137.png',1695,313,1546000997,0,0),(32,2,'assets/20181228/2d39f35ae9ac00c6ecc58c6cf3fb1472.png',1772,3835,1546001046,0,0),(33,2,'assets/20181228/98163a6b979bdd56d7d6e0502d29f7f1.png',1920,650,1546002194,0,0),(34,2,'assets/20181228/ac39b697e48b2e4d5ca504f6d053fcd6.png',672,438,1546002200,0,0),(35,2,'assets/20181228/f040324a8d410d97d71c01b6520b1fc5.png',694,436,1546002201,0,0),(36,2,'assets/20181228/ed44400633a5f860caffd03b7fb7839d.png',281,315,1546002205,0,0),(37,2,'assets/20181228/e526aebac94e3240b0106150937aef94.png',275,303,1546002208,0,0),(38,2,'assets/20181228/f3a64145204420b493042c1f2e51143e.png',536,517,1546002210,0,0),(39,2,'assets/20181228/fd7bbbd06bdc1e1b43664266cfe47d8f.png',796,321,1546002214,0,0),(40,2,'assets/20181228/589e92c67c51173c7f2df6bfb7ae695d.png',1999,530,1546002906,0,1),(41,2,'assets/20181228/92f199fc8bbcf14d02361f0544b3702f.png',1018,379,1546002910,0,1),(42,2,'assets/20181228/61e4f636f4a54dabb8d114116958bc52.png',1018,379,1546002913,0,0),(43,2,'assets/20181228/f23197a8483180689a727ab50101b609.png',147,147,1546002915,0,1),(44,2,'assets/20181228/53bd7082d063d866acb9053068ebe232.png',957,101,1546002922,0,1),(45,2,'assets/20181228/3380d9aa34c155b24ab3520373d88405.png',389,52,1546002926,0,1),(46,2,'assets/20181228/8b422840ff0ae183ef4109da7221052a.png',919,156,1546002936,0,1),(47,2,'assets/20181228/811b84f4370519d8b3da5eb0aec22c7d.png',588,40,1546003116,0,1);

/*Table structure for table `daka_config` */

DROP TABLE IF EXISTS `daka_config`;

CREATE TABLE `daka_config` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(30) NOT NULL DEFAULT '',
  `value` text,
  `remark` varchar(100) DEFAULT '解释,备注',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='配置参数表';

/*Data for the table `daka_config` */

insert  into `daka_config`(`id`,`code`,`value`,`remark`) values (1,'site_name','2z2x','网站名称'),(2,'site_phone','','网站客服服务电话'),(3,'site_state','1','网站状态'),(4,'site_logo','assets\\ads\\20181213\\7d84546df565bd902d4a69b48e02b6f3.jpg','网站logo图'),(5,'icp_number','','ICP备案号'),(6,'site_tel400','','公司电话号码'),(7,'site_email','ucosxiu','电子邮件'),(8,'flow_static_code','','底部版权信息');

/*Table structure for table `daka_fans` */

DROP TABLE IF EXISTS `daka_fans`;

CREATE TABLE `daka_fans` (
  `fans_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '粉丝ID',
  `openid` varchar(64) NOT NULL DEFAULT '' COMMENT 'openid',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `utype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0 学生 1 老师',
  `isbind` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1 已绑定',
  `token` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`fans_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Data for the table `daka_fans` */

insert  into `daka_fans`(`fans_id`,`openid`,`uid`,`utype`,`isbind`,`token`) values (1,'o11b-40paCEFfRUhCZtkgYEruQY0',0,0,0,'0b3b0be9cf5bebab8d4fbe0b29a60c94'),(8,'o11b-4ztkjwV8a_ma1qifk_kRabY',6,1,1,'6c33c82ea5019ffa993b3809059d4c13');

/*Table structure for table `daka_mail_templates` */

DROP TABLE IF EXISTS `daka_mail_templates`;

CREATE TABLE `daka_mail_templates` (
  `template_id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `template_code` varchar(30) NOT NULL DEFAULT '',
  `is_html` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `template_subject` varchar(200) NOT NULL DEFAULT '',
  `template_content` text NOT NULL,
  `last_modify` int(10) unsigned NOT NULL DEFAULT '0',
  `last_send` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`template_id`) USING BTREE,
  UNIQUE KEY `template_code` (`template_code`) USING BTREE,
  KEY `type` (`status`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

/*Data for the table `daka_mail_templates` */

insert  into `daka_mail_templates`(`template_id`,`template_code`,`is_html`,`template_subject`,`template_content`,`last_modify`,`last_send`,`status`) values (2,'forget_password',1,'找回密码','<p>&nbsp; &nbsp;{$user_name}您好！<br/><br/>您已经进行了密码重置的操作，</p><p>验证码：<span style=\"color: rgb(255, 0, 0);\">{$code}</span><br/><br/>{$send_date}</p>',1544603917,0,1);

/*Table structure for table `daka_option` */

DROP TABLE IF EXISTS `daka_option`;

CREATE TABLE `daka_option` (
  `optionid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `autoload` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `option_name` varchar(255) NOT NULL DEFAULT '',
  `option_value` text,
  PRIMARY KEY (`optionid`),
  UNIQUE KEY `option_name` (`option_name`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `daka_option` */

insert  into `daka_option`(`optionid`,`autoload`,`option_name`,`option_value`) values (1,0,'smtp_setting','a:7:{s:14:\"smtp_from_name\";s:7:\"ucosxiu\";s:12:\"smtp_from_to\";s:17:\"ucosxiu_z@163.com\";s:9:\"smtp_host\";s:12:\"smtp.163.com\";s:9:\"smtp_port\";s:2:\"25\";s:11:\"smtp_secure\";s:3:\"tls\";s:13:\"smtp_username\";s:17:\"ucosxiu_z@163.com\";s:13:\"smtp_password\";s:9:\"zxk123456\";}');

/*Table structure for table `daka_template_formid` */

DROP TABLE IF EXISTS `daka_template_formid`;

CREATE TABLE `daka_template_formid` (
  `fid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `formid` varchar(64) NOT NULL DEFAULT '',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`fid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `daka_template_formid` */

insert  into `daka_template_formid`(`fid`,`formid`,`add_time`,`status`) values (2,'sss',0,0);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
