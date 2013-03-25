SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `w_item`;
CREATE TABLE `w_item` (
  `id` int(10) unsigned NOT NULL COMMENT '物品id',
  `item_name` varchar(255) NOT NULL COMMENT '物品名称',
  `image` varchar(255) NOT NULL COMMENT '物品图片',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `w_item_search`;
CREATE TABLE `w_item_search` (
  `id` int(10) unsigned NOT NULL COMMENT '物品id',
  `rank` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '热度',
  `enable` bit(1) NOT NULL DEFAULT b'0' COMMENT '是否启用',
  `time` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `w_server`;
CREATE TABLE `w_server` (
  `id` tinyint(3) unsigned NOT NULL COMMENT '服务器id',
  `server` tinyint(3) unsigned NOT NULL COMMENT '服务器',
  `server_name` varchar(50) NOT NULL COMMENT '服务器名',
  `server_name_en` varchar(50) NOT NULL COMMENT '服务器英文名',
  `account_name` varchar(50) NOT NULL COMMENT '用户名',
  `password` varchar(50) NOT NULL COMMENT '密码',
  `db_name` varchar(50) NOT NULL COMMENT '数据库名',
  `db_host` varchar(50) NOT NULL COMMENT '数据库服务器地址',
  `db_port` smallint(5) unsigned NOT NULL DEFAULT '3306' COMMENT '数据库端口',
  `db_user` varchar(50) NOT NULL COMMENT '数据库用户名',
  `db_pwd` varchar(50) NOT NULL COMMENT '数据库密码',
  `db_charset` varchar(10) NOT NULL COMMENT '数据库编码',
  `enable` bit(1) NOT NULL DEFAULT b'1' COMMENT '是否启用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='保存区服信息';
