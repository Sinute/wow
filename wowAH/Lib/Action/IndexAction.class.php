<?php
class IndexAction extends Action {
    public function Index() {
    	$this->redirect('/Search/', null, 0, '');
    }
}