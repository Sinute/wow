<?php
class AdminApiAction extends Action {
    /**
     * 检查权限
     * Author: Sinute
     * @param  integer $private 权限值
     */
    private function __CheckPrivilege($private = 0) {
        if(!P($private)) {
            $this->ajaxReturn('', 'Authentication failure', false);
            exit();
        }
    }

    /**
     * 增加搜索道具
     * Author: Sinute
     * @param  integer $itemId 道具id
     */
    public function AddSearchItem($itemId){
        $this->__CheckPrivilege();
        $result['data'] = '';
        $result['message'] = 'itemId error';
        $result['status'] = false;
        if(is_numeric($itemId) && $itemId > 0){        
            $itemModel = new ItemModel();
            if($itemModel->InsertSearchItem($itemId)) {
                $result['message'] = 'success';
                $result['status'] = true;
            }
        }
        $this->ajaxReturn($result['data'], $result['message'], $result['status']);
    }
}