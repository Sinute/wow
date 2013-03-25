<?php
    class WowAHModel{
        public function Get($itemId){
        	S(array(
	            'type' => 'memcache', 
	            'host' => 'localhost', 
	            'port' => '11211', 
	            'prefix' => 'w_AH_', 
	            'expire' => 5 * 60
	        ));
	    	$result = S($itemId);
	    	if(!$result) {
	        	$result = json_decode(exec('python /home/pi/workspace/wow/fetch/wowAPI.py -ah ' . $itemId), true);
	            if($result['status'])
	            	S($itemId, $result);
	        }
            return $result;
        }
    }