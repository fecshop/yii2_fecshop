<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appfront\widgets;
use Yii;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Page
{
    public $pageNum;
	public $numPerPage;
	public $countTotal;
	public $page;
	
	public function getLastData(){
		$spaceShowNum = 4;
		$productNumPerPage 	= $this->numPerPage;
		$countTotal 		= $this->countTotal;
		$pageNum 			= $this->pageNum;
		
		$maxPageNum = ceil($countTotal/$productNumPerPage);
		if($pageNum >$maxPageNum){
			$pageNum = $maxPageNum;
		}
		$firstSpaceShow = false;
		$lastSpaceShow = false;
		$frontPage = [];
		$behindPage= [];
		$endSpaceNum = $maxPageNum - $spaceShowNum +1	;
		$hiddenPageMaxCount = 2*$spaceShowNum+1;
		$hiddenFrontStr = '';
		$hiddenBehindStr = '';
		if($maxPageNum <= $hiddenPageMaxCount){
			$c = $pageNum;
			while($c >= 1){
				$c = $c - 1;
				if($c){
					$frontPage = array_merge([$c],$frontPage);
				}
				
			}
			$c = $pageNum;
			while($c < $maxPageNum){
				$c = $c + 1;
				$behindPage[] = $c;
			}
		}else if(($pageNum > $spaceShowNum)&&($pageNum < $endSpaceNum)){
			$firstSpaceShow = true;
			$lastSpaceShow = true;
			$hiddenFrontStr = '...';
			$hiddenBehindStr = '...';
			$frontPage[] = $pageNum - 1;
			$behindPage[]= $pageNum + 1;
			$behindPage[]= $pageNum + 2;
		}else if($pageNum == 1){
			$firstSpaceShow = false;
			$lastSpaceShow = true;
			$hiddenBehindStr = '...';
			$behindPage[]= $pageNum + 1;
			$behindPage[]= $pageNum + 2;
			$behindPage[]= $pageNum + 3;
			$behindPage[]= $pageNum + 4;
		}else if($pageNum == 2){
			$firstSpaceShow = false;
			$lastSpaceShow = true;
			$hiddenBehindStr = '...';
			$frontPage[] = $pageNum - 1;
			$behindPage[]= $pageNum + 1;
			$behindPage[]= $pageNum + 2;
			$behindPage[]= $pageNum + 3;
		}else if($pageNum == 3){
			$firstSpaceShow = false;
			$lastSpaceShow = true;
			$hiddenBehindStr = '...';
			$frontPage[] = $pageNum - 2;
			$frontPage[] = $pageNum - 1;
			$behindPage[]= $pageNum + 1;
			$behindPage[]= $pageNum + 2;
		}else if($pageNum == 4){
			$firstSpaceShow = false;
			$lastSpaceShow = true;
			$hiddenBehindStr = '...';
			$frontPage[] = $pageNum - 3;
			$frontPage[] = $pageNum - 2;
			$frontPage[] = $pageNum - 1;
			$behindPage[]= $pageNum + 1;
			$behindPage[]= $pageNum + 2;
		}else if($pageNum == $endSpaceNum){
			$firstSpaceShow = true;
			$lastSpaceShow = false;
			$hiddenFrontStr = '...';
			$frontPage[]= $pageNum - 1;
			$behindPage[]= $pageNum + 1;
			$behindPage[]= $pageNum + 2;
			$behindPage[]= $pageNum + 3;
			
		}else if($pageNum == ($endSpaceNum + 1)){
			$firstSpaceShow = true;
			$lastSpaceShow = false;
			$hiddenFrontStr = '...';
			$frontPage[]= $pageNum - 2;
			$frontPage[]= $pageNum - 1;
			$behindPage[]= $pageNum + 1;
			$behindPage[]= $pageNum + 2;
		}else if($pageNum == ($endSpaceNum + 2)){
			$firstSpaceShow = true;
			$lastSpaceShow = false;
			$hiddenFrontStr = '...';
			$frontPage[]= $pageNum - 3;
			$frontPage[]= $pageNum - 2;
			$frontPage[]= $pageNum - 1;
			$behindPage[]= $pageNum + 1;
		}else if($pageNum == ($endSpaceNum + 3)){
			$firstSpaceShow = true;
			$lastSpaceShow = false;
			$hiddenFrontStr = '...';
			$frontPage[]= $pageNum - 4;
			$frontPage[]= $pageNum - 3;
			$frontPage[]= $pageNum - 2;
			$frontPage[]= $pageNum - 1;
		
		}
		//Yii::$service->url->category->getFilterChooseAttrUrl($this->page,$val);
		if($firstSpaceShow){
			$url = Yii::$service->url->category->getFilterChooseAttrUrl($this->page,1);
			$firstSpaceShow = [
				'p'   => 1,
				'url' => $url,
			];
		}
		if($lastSpaceShow){
			$url = Yii::$service->url->category->getFilterChooseAttrUrl($this->page,$maxPageNum);
			$lastSpaceShow = [
				'p'   => $maxPageNum,
				'url' => $url,
			];
		}
		$frontPageU = [];
		//var_dump($frontPage);
		if(is_array($frontPage) && !empty($frontPage)){
			foreach($frontPage as $p){
				$frontPageU[] = [
					'p'   => $p,
					'url' => Yii::$service->url->category->getFilterChooseAttrUrl($this->page,$p),
				];
			}
		}
		$behindPageU = [];
		if(is_array($behindPage) && !empty($behindPage)){
			foreach($behindPage as $p){
				$behindPageU[] = [
					'p'   => $p,
					'url' => Yii::$service->url->category->getFilterChooseAttrUrl($this->page,$p),
				];
			}
		}
		$prevPage = '';
		$nextPage = '';
		if($pageNum > 1){
			$prevPage = $pageNum - 1;
			$prevPage = [
				'p'	=> $prevPage,
				'url' => Yii::$service->url->category->getFilterChooseAttrUrl($this->page,$prevPage),
			];
		}
		if($pageNum != $maxPageNum){
			$nextPage = $pageNum - 1;
			$nextPage = [
				'p'	=> $nextPage,
				'url' => Yii::$service->url->category->getFilterChooseAttrUrl($this->page,$nextPage),
			];
		}
		$currentPage = [
			'p'		=> $pageNum,
		]; 
		//var_dump($frontPageU);
		return [
			'firstSpaceShow'=> $firstSpaceShow,
			'lastSpaceShow' => $lastSpaceShow,
			'frontPage' 	=> $frontPageU,
			'behindPage' 	=> $behindPageU,
			'currentPage' 	=> $currentPage,
			//'maxPageNum' 	=> $maxPageNum,
			'prevPage' 		=> $prevPage,
			'nextPage' 		=> $nextPage,
			'hiddenFrontStr'=> $hiddenFrontStr,
			'hiddenBehindStr'=>$hiddenBehindStr,
		];
	}
}
