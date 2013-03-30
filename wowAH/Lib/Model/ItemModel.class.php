<?php
    class ItemModel extends Model{
    	/**
	     * Search 搜索道具
	     * Author: Sinute
	     * @param str $q
	     * @param int $start
	     * @param int $size
	     * @param int &$totalNum
	     * @return array
	     */
        public function Search($q = false, $start, $size = 5, &$totalNum) {
        	if(!$q) return array();
            $result = $this->query("SELECT SQL_CALC_FOUND_ROWS `w_item`.* 
            	FROM `w_item_search`
            	LEFT JOIN `w_item` USING(`id`)
            	WHERE `w_item_search`.`enable` = 1
                AND `w_item`.`item_name` LIKE '%%%s%%' 
            	LIMIT %u, %u", array(str_replace('%', '', $q), $start, $size));
            $totalNum = $this->query("SELECT FOUND_ROWS()");
            $totalNum = $totalNum[0]["FOUND_ROWS()"];
            return $result;
        }

        /**
         * 搜索全部道具
         * Author: Sinute
         * @param  boolean $q        搜索字符串
         * @param  integer $start    开始位置
         * @param  integer $size     每页数量
         * @param  point   $totalNum 总数
         */
        public function SearchAll($q = false, $start, $size = 5, &$totalNum) {
            if(!$q) return array();
            $result = $this->query("SELECT SQL_CALC_FOUND_ROWS `w_item`.*, `w_item_search`.`enable` 
                FROM `w_item`
                LEFT JOIN `w_item_search` USING(`id`)
                WHERE `w_item`.`item_name` LIKE '%%%s%%' 
                LIMIT %u, %u", array(str_replace('%', '', $q), $start, $size));
            $totalNum = $this->query("SELECT FOUND_ROWS()");
            $totalNum = $totalNum[0]["FOUND_ROWS()"];
            return $result;
        }

        public function Get($itemId) {
        	if(!is_numeric($itemId) || $itemId <= 0) return false;
    		return $this->where('id=%d', array($itemId))->find();
        }

        /**
         * 获取拍卖所记录
         * Author: Sinute
         * @param int $itemId 物品id
         */
        public function GetAHRecord($itemId) {
	    	$dayRecord = S(
	    		$itemId, 
	    		'', 
	    		array(
	    			'host'   => '127.0.0.1',
	    			'port'   => '11211',
	    			'type'   => 'Memcache',
	    			'prefix' => 'w_AH_day_', 
	    			'expire' => mktime(date("H") + 1, 30, 0, date("m"), date("d"), date("Y")) - time()
	    			)
	    		);
	    	if(!$dayRecord) {
	    		$dayRecord = $this->db(1, "DB_CONFIG_3")->query("SELECT `date`, `time`, `gold_buyout`, `silver_buyout`, `copper_buyout` FROM `%s`
					WHERE `item_id` = %u
                    AND `date` = '%s'", 
					array($this->__GetTable(), $itemId, date('Y-m-d'))
					);
	    		S($itemId, $dayRecord);
	    	}

	    	$monthRecord = S(
	    		$itemId, 
	    		'', 
	    		array(
	    			'host'   => '127.0.0.1',
	    			'port'   => '11211',
	    			'type'   => 'Memcache',
	    			'prefix' => 'w_AH_month_', 
	    			'expire' => mktime(0, 30, 0, date("m"), date("d") + 1, date("Y")) - time()
	    			)
	    		);
	    	if(!$monthRecord) {
	        	$monthRecord = $this->db(1, "DB_CONFIG_3")->query("SELECT * FROM(
					SELECT `date`, `time`, `gold_buyout`, `silver_buyout`, `copper_buyout` FROM `%s`
					WHERE `item_id` = %u
					AND `date` BETWEEN '%s' AND '%s'
					ORDER BY gold_buyout, silver_buyout, copper_buyout ASC 
					) AS r
					GROUP BY `date`", 
					array($this->__GetTable(), $itemId, date("Y") . '-' . date("m") . '-01', date("Y") . '-' . date("m") . '-31')
					);
	            S($itemId, $monthRecord);
	        }

            $itemSearch = M("item_search");
            $itemSearch->switchModel("Adv")->where('id=%d', array($itemId))->setLazyInc("rank", 1, 600);

	        $result['monthRecord'] = $monthRecord;
        	$result['dayRecord'] = $dayRecord;
            return $result;
        }

        /**
         * 获取表名
         * Author: Sinute
         * @return string 表名
         */
        private function __GetTable() {
        	return 'w_auction_house_darkiron_' . date('Y');
        }

        /**
         * 添加新的搜索物品
         * Author: Sinute
         * @param  int $itemId 物品id
         */
        public function InsertSearchItem($itemId) {
        	if(!is_numeric($itemId) || $itemId <= 0) return false;
        	if(!$this->where('id=%d', array($itemId))->find()) return false;
        	return $this->execute("INSERT INTO `w_item_search`(`id`, `rank`, `enable`, `time`) 
        			VALUES (%d, 0, 1, NOW()) 
                    ON DUPLICATE KEY UPDATE `enable` = 1, `time` = NOW()", array($itemId));
        } 
    }