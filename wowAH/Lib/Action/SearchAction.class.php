<?php
class SearchAction extends Action {
    public function Index($q = false, $page = 1) {
    	if($q){
            $totalNum = 0;
            $this->pageSize = 5;
            $page = $page - 1 > 0 ? intval($page) : 1;
            $Item = new ItemModel();
            $this->result = $Item->Search($q, ($page - 1) * $this->pageSize, $this->pageSize, $totalNum);
            $this->title = $q . ' - 搜索';
            $this->totalNum = $totalNum;
			$this->display('search');
    	}else{
    		$this->title = 'wowAH';
			$this->display('index');
    	}
    }
}