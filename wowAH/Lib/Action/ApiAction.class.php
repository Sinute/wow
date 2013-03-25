<?php
// api接口
class ApiAction extends Action {
    /**
     * 返回拍卖行记录
     * @param int $itemId
     * @return json
     */
    public function wowAH($itemId){
        $result['data'] = array();
        $result['message'] = 'itemId error';
        $result['status'] = false;
        if(is_numeric($itemId) && $itemId > 0){        
            $itemModel = new ItemModel();
            $result['data'] = $itemModel->GetAHRecord($itemId);
            $result['message'] = 'success';
            $result['status'] = true;
        }
        $this->ajaxReturn($result['data'], $result['message'], $result['status']);
    }

    /**
     * 调用数据接口返回物品详细
     * @param int $itemId
     * @return json
     */
    public function wowItemTooltip($itemId){
        $result =  S(
            $itemId, 
            '', 
            array(
                'host'   => '127.0.0.1',
                'port'   => '11211',
                'type'   => 'Memcache',
                'prefix' => 'w_ItemTooltip_', 
                'expire' => 30 * 24 * 3600
                )
            );
        if(!$result) {
            $result = json_decode(exec('python /home/pi/workspace/wow/fetch/wowAPI.py -itip ' . $itemId), true);
            if($result['status'])
                S($itemId, $result);
        }
        $this->ajaxReturn($result['data'], $result['message'], $result['status']);
    }
}