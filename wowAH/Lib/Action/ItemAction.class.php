<?php
class ItemAction extends Action {
    public function index($itemId = false) {
    	if(is_numeric($itemId) && $itemId > 0){
    		$itemModel = new ItemModel();
    		$this->item = $itemModel->Get($itemId);
    		if(!$this->item) {
    			$this->redirect('/Search/', null, 0, '');
    			return;
    		}
            $this->title = $this->item['item_name'] . ' - 详情';
			$this->display('index');
    	}
    }
}