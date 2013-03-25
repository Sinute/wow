<?php
class PaginationWidget extends Widget{
	public function render($data){
		$pageSize = isset($data['pageSize']) ? $data['pageSize'] : 5;
		$totalNum = isset($data['totalNum']) ? $data['totalNum'] : 1;
		$totalPage = ceil($totalNum / $pageSize);
		$currentPage = isset($_GET['page']) && $_GET['page'] > 0 ? $_GET['page'] : 1;
		$prevUrl = '';
		$nextUrl = '';
		$startPage = 1;
		$endPage = 1;
		$urls = array();
		if($totalPage > 1) {
			$showPage = isset($data['showPage']) && $data['showPage'] > 1 ? $data['showPage'] : 5;
			$queryString = '?';
			foreach ($_GET as $key => $value) {
				if($key == 'page' || $key == '_URL_') continue;
				$queryString .= $key . "=" . $value . "&";
			}
			if($currentPage - floor($showPage / 2) > 0 && $currentPage + floor($showPage / 2) <= $totalPage){
				$startPage = $currentPage - floor($showPage / 2);
				$endPage = $currentPage + floor($showPage / 2);
			}else if($currentPage - floor($showPage / 2) <= 0){
				$startPage = 1;
				$endPage = min($totalPage, 2 * floor($showPage / 2) + 1);
			}else if($currentPage + floor($showPage / 2) > $totalPage){
				$startPage = max($totalPage - 2 * floor($showPage / 2), 1);
				$endPage = $totalPage;
			}
			$prevUrl = $currentPage - 1 > 0 ? $queryString . "page=" . ($currentPage - 1) : '';
			$nextUrl = $currentPage + 1 <= $totalPage ? $queryString . "page=" . ($currentPage + 1) : '';
			$firstUrl = "{$queryString}page=1";
			$lastUrl = "{$queryString}page={$totalPage}";
			for($i = $startPage; $i <= $endPage; $i++)
				$urls[$i] = "{$queryString}page={$i}";
		}
		$data = compact('currentPage', 'urls', 'prevUrl', 'nextUrl', 'endPage', 'firstUrl', 'lastUrl');
		return $this->renderFile('Pagination', $data);
	} 
}