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
    public $pageSection; // = 'p_comment';

    public function getLastData()
    {
        $this->page = $this->page ? $this->page : 'p';
        $spaceShowNum = 4;
        $productNumPerPage = $this->numPerPage;
        $countTotal = $this->countTotal;
        $pageNum = $this->pageNum;

        $maxPageNum = ceil($countTotal / $productNumPerPage);
        if ($pageNum > $maxPageNum) {
            $pageNum = $maxPageNum;
        }
        $firstSpaceShow = false;
        $lastSpaceShow = false;
        $frontPage = [];
        $behindPage = [];
        $endSpaceNum = $maxPageNum - $spaceShowNum + 1;
        $hiddenPageMaxCount = 2 * $spaceShowNum + 1;
        $hiddenFrontStr = '';
        $hiddenBehindStr = '';
        if ($maxPageNum <= $hiddenPageMaxCount) {
            $c = $pageNum;
            while ($c > 1) {
                $c = $c - 1;
                if ($c) {
                    $frontPage = array_merge([$c], $frontPage);
                }
            }
            $c = $pageNum;
            while ($c < $maxPageNum) {
                $c = $c + 1;
                $behindPage[] = $c;
            }
            //var_dump($behindPage);
        } elseif (($pageNum > $spaceShowNum) && ($pageNum < $endSpaceNum)) {
            $firstSpaceShow = true;
            $lastSpaceShow = true;
            $hiddenFrontStr = '<span>...</span>';
            $hiddenBehindStr = '<span>...</span>';
            $frontPage[] = $pageNum - 1;
            $behindPage[] = $pageNum + 1;
            $behindPage[] = $pageNum + 2;
        } elseif ($pageNum == 1) {
            $firstSpaceShow = false;
            $lastSpaceShow = true;
            $hiddenBehindStr = '<span>...</span>';
            $behindPage[] = $pageNum + 1;
            $behindPage[] = $pageNum + 2;
            $behindPage[] = $pageNum + 3;
            $behindPage[] = $pageNum + 4;
        } elseif ($pageNum == 2) {
            $firstSpaceShow = false;
            $lastSpaceShow = true;
            $hiddenBehindStr = '<span>...</span>';
            $frontPage[] = $pageNum - 1;
            $behindPage[] = $pageNum + 1;
            $behindPage[] = $pageNum + 2;
            $behindPage[] = $pageNum + 3;
        } elseif ($pageNum == 3) {
            $firstSpaceShow = false;
            $lastSpaceShow = true;
            $hiddenBehindStr = '<span>...</span>';
            $frontPage[] = $pageNum - 2;
            $frontPage[] = $pageNum - 1;
            $behindPage[] = $pageNum + 1;
            $behindPage[] = $pageNum + 2;
        } elseif ($pageNum == 4) {
            $firstSpaceShow = false;
            $lastSpaceShow = true;
            $hiddenBehindStr = '<span>...</span>';
            $frontPage[] = $pageNum - 3;
            $frontPage[] = $pageNum - 2;
            $frontPage[] = $pageNum - 1;
            $behindPage[] = $pageNum + 1;
            $behindPage[] = $pageNum + 2;
        } elseif ($pageNum == $endSpaceNum) {
            $firstSpaceShow = true;
            $lastSpaceShow = false;
            $hiddenFrontStr = '<span>...</span>';
            $frontPage[] = $pageNum - 1;
            $behindPage[] = $pageNum + 1;
            $behindPage[] = $pageNum + 2;
            $behindPage[] = $pageNum + 3;
        } elseif ($pageNum == ($endSpaceNum + 1)) {
            $firstSpaceShow = true;
            $lastSpaceShow = false;
            $hiddenFrontStr = '<span>...</span>';
            $frontPage[] = $pageNum - 2;
            $frontPage[] = $pageNum - 1;
            $behindPage[] = $pageNum + 1;
            $behindPage[] = $pageNum + 2;
        } elseif ($pageNum == ($endSpaceNum + 2)) {
            $firstSpaceShow = true;
            $lastSpaceShow = false;
            $hiddenFrontStr = '<span>...</span>';
            $frontPage[] = $pageNum - 3;
            $frontPage[] = $pageNum - 2;
            $frontPage[] = $pageNum - 1;
            $behindPage[] = $pageNum + 1;
        } elseif ($pageNum == ($endSpaceNum + 3)) {
            $firstSpaceShow = true;
            $lastSpaceShow = false;
            $hiddenFrontStr = '<span>...</span>';
            $frontPage[] = $pageNum - 4;
            $frontPage[] = $pageNum - 3;
            $frontPage[] = $pageNum - 2;
            $frontPage[] = $pageNum - 1;
        }
        //Yii::$service->url->category->getFilterChooseAttrUrl($this->page,$val);
        if ($firstSpaceShow) {
            $url = $this->getPageUrl($pageNum, 1);
            //Yii::$service->url->category->getFilterChooseAttrUrl($this->page,1);
            $firstSpaceShow = [
                $this->page   => 1,
                'url' => $url,
            ];
        }
        if ($lastSpaceShow) {
            $url = $this->getPageUrl($pageNum, $maxPageNum);
            //Yii::$service->url->category->getFilterChooseAttrUrl($this->page,$maxPageNum);
            $lastSpaceShow = [
                $this->page   => $maxPageNum,
                'url' => $url,
            ];
        }
        $frontPageU = [];
        //var_dump($frontPage);
        if (is_array($frontPage) && !empty($frontPage)) {
            foreach ($frontPage as $p) {
                $frontPageU[] = [
                    $this->page   => $p,
                    'url' => $this->getPageUrl($pageNum, $p),
                    //Yii::$service->url->category->getFilterChooseAttrUrl($this->page,$p),
                ];
            }
        }
        $behindPageU = [];
        //var_dump($behindPage);
        if (is_array($behindPage) && !empty($behindPage)) {
            foreach ($behindPage as $p) {
                $behindPageU[] = [
                    $this->page   => $p,
                    'url' => $this->getPageUrl($pageNum, $p),
                    //Yii::$service->url->category->getFilterChooseAttrUrl($this->page,$p),
                ];
            }
        }
        $prevPage = '';
        $nextPage = '';
        if ($pageNum > 1) {
            $prevPage = $pageNum - 1;
            $prevPage = [
                $this->page        => $prevPage,
                'url'    => $this->getPageUrl($pageNum, $prevPage),
                //Yii::$service->url->category->getFilterChooseAttrUrl($this->page,$prevPage),
            ];
        }
        if ($pageNum != $maxPageNum) {
            $nextPage = $pageNum + 1;
            $nextPage = [
                $this->page        => $nextPage,
                'url'    => $this->getPageUrl($pageNum, $nextPage),
                //Yii::$service->url->category->getFilterChooseAttrUrl($this->page,$nextPage),
            ];
        }
        $currentPage = [
            $this->page        => $pageNum,
        ];
        //var_dump($currentPage);exit;
        return [
            'firstSpaceShow'=> $firstSpaceShow,
            'lastSpaceShow' => $lastSpaceShow,
            'frontPage'    => $frontPageU,
            'behindPage'    => $behindPageU,
            'currentPage'    => $currentPage,
            //'maxPageNum' 	=> $maxPageNum,
            'prevPage'        => $prevPage,
            'nextPage'        => $nextPage,
            'hiddenFrontStr'=> $hiddenFrontStr,
            'hiddenBehindStr'=>$hiddenBehindStr,
            'pageParam' => $this->page,
        ];
    }
    
    
    public function getMiniBar()
    {
        $this->page = $this->page ? $this->page : 'p';
        $spaceShowNum = 4;
        $productNumPerPage = $this->numPerPage;
        $countTotal = $this->countTotal;
        $pageNum = $this->pageNum;

        $maxPageNum = ceil($countTotal / $productNumPerPage);
        if ($pageNum > $maxPageNum) {
            $pageNum = $maxPageNum;
        }
        
        $prevPage = '';
        $nextPage = '';
        if ($pageNum > 1) {
            $prevPage = $pageNum - 1;
            $prevPage = [
                $this->page        => $prevPage,
                'url'    => $this->getPageUrl($pageNum, $prevPage),
            ];
        }
        if ($pageNum != $maxPageNum) {
            $nextPage = $pageNum + 1;
            $nextPage = [
                $this->page        => $nextPage,
                'url'    => $this->getPageUrl($pageNum, $nextPage),
            ];
        }
        $currentPage = [
            $this->page        => $pageNum,
        ];
        return [
            'prevPage'        => $prevPage,
            'nextPage'        => $nextPage,
            'pageNum'        => $this->pageNum,
            'numPerPage'    => $this->numPerPage,
            'pageCount'      => $this->getPageCount(),
        ];
        
    }
    
    public function getPageCount()
    {
        $pageCount = 0;
        if ($this->numPerPage > 0) {
            $pageCount = ceil ($this->countTotal / $this->numPerPage );
        }
        
        return $pageCount;
    }
    
    
    public function getPageUrl($currentPage, $showPage)
    {
        $currentUrl = Yii::$service->url->getCurrentUrl();
        $pVal = Yii::$app->request->get($this->page);
        if ($pVal) {
            $currentPageStr = $this->page.'='.$pVal;
            $showPageStr = $this->page.'='.$showPage;
            $url = str_replace($currentPageStr, $showPageStr, $currentUrl);
        } else {
            if (strstr($currentUrl, '?')) {
                $url = $currentUrl.'&'.$this->page.'='.$showPage;
            } else {
                $url = $currentUrl.'?'.$this->page.'='.$showPage;
            }
        }
        if ($this->pageSection) {
            $url = $url . '#' . $this->pageSection;
        }
        return [
            'url' => $url,
        ];
    }
}
