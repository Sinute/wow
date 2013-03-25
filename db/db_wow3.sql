SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `w_auction_house_darkiron_2013`;
CREATE TABLE `w_auction_house_darkiron_2013` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '记录id',
  `item_id` int(10) unsigned NOT NULL COMMENT '物品id',
  `gold` int(10) unsigned NOT NULL COMMENT '金',
  `silver` tinyint(3) unsigned NOT NULL COMMENT '银',
  `copper` tinyint(3) unsigned NOT NULL COMMENT '铜',
  `gold_buyout` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '金一口价',
  `silver_buyout` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '银一口价',
  `copper_buyout` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '铜一口价',
  `quantity` int(10) unsigned NOT NULL COMMENT '数量',
  `date` date NOT NULL COMMENT '日期',
  `time` time NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `item_id-date` (`item_id`,`date`,`time`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
