<?php
class AdminAction extends Action {
    /**
     * 检查权限
     * Author: Sinute
     * @param  integer $private 权限值
     */
    private function __CheckPrivilege($private = 0) {
        if(!P($private)) {
            header('Location: ' . C('HOST_URL') . '/Admin/Login?callback=' . urlencode((is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
            exit();
        }
    }

    /**
     * 登录页
     * Author: Sinute
     */
    public function Login($account = false, $password = false) {
        if(P($private)) $this->redirect('/Admin/', null, 0, '');
        $callback = isset($_GET['callback']) ? urlencode(urldecode($_GET['callback'])) : '';
        if('233' == $account && 'Max' == $password) {
            session('privilege', 0xFFFF);
            header('Location: ' . urldecode($callback ? $callback : U('Admin/Index')));
        }else{
            $this->callback = $callback;
            $this->title = '登录';
            $this->display('login');
        }
    }

    /**
     * 默认入口
     * Author: Sinute
     */
    public function Index() {
        $this->AddItem();
    }
    
    /**
     * 添加搜索道具
     * Author: Sinute
     * @param  string  $q    道具名
     * @param  integer $page 页数
     */
    public function AddItem($q = false, $page = 1) {
        $this->__CheckPrivilege();
    	if($q){
            $totalNum = 0;
            $this->pageSize = 5;
            $page = $page - 1 > 0 ? intval($page) : 1;
            $Item = new ItemModel();
            $this->result = $Item->SearchAll($q, ($page - 1) * $this->pageSize, $this->pageSize, $totalNum);
            $this->title = $q . ' - 搜索';
            $this->totalNum = $totalNum;
            $this->display('addItem');
        }else{
            $this->title = '添加道具';
            $this->display('addItem');
        }
    }
}