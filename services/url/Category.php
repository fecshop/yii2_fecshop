<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\url;

use fecshop\services\Service;
use Yii;
use yii\base\InvalidValueException;

/**
 * Url Category Service
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Category extends Service
{
    /**
     * @param $strVal | String
     * 把属性值转换成url格式的字符串，用于生成url.
     */
    protected function actionAttrValConvertUrlStr($strVal)
    {
        if ($strVal) {
            return urlencode($strVal);
            // 如果在其他url重写的需要，可以在这里添加一下字符串转换，添加后
            // 函数actionUrlStrConvertAttrVal()也要做相应的转变
            //if (!preg_match('/^[A-Za-z0-9-_ &]+$/', $strVal)) {
            //    throw new InvalidValueException('"'.$strVal .'":contain special str , you can only contain special string [A-Za-z0-9-_ &]');
            //}
            //$convert = $this->strUrlRelation();
            //foreach ($convert as $originStr => $nStr) {
            //    $strVal = str_replace($originStr, $nStr, $strVal);
            //}
            //return $strVal;
        }
    }

    /**
     * @param $urlStr | String
     * 把url格式的字符串转换成属性值，用于解析url，得到相应的属性值
     */
    protected function actionUrlStrConvertAttrVal($urlStr)
    {
        return urldecode($urlStr);
        // 如果在其他url重写的需要，可以在这里添加一下字符串转换，添加后
        // 函数 actionAttrValConvertUrlStr()也要做相应的转变
        //$convert = $this->strUrlRelation();
        //foreach ($convert as $originStr => $nStr) {
        //    $urlStr = str_replace($nStr, $originStr, $urlStr);
        //}
        //return $urlStr;
    }

    /**
     * 对Url中的特殊字符进行转换。（用name生成url的时候，会使用这些字符进行转换）
     */
    protected function strUrlRelation()
    {
        return [
            ' ' => '!',
            '&' => '@',
        ];
    }

    /**
     * 在分类侧栏点击过滤属性，得到选择这个属性的url.
     * @param $attrUrlStr|string 属性的url处理后的字符串
     * @param $val|string 属性对应的值。未url处理的值
     * @param $p|string  在url中用来表示分页的参数，一般用p来标示。
     * @param $pageBackToOne|bool 是否让p的页数回归第一页
     */
    protected function actionGetFilterChooseAttrUrl($attrUrlStr, $val, $p = 'p', $pageBackToOne = true)
    {
        $val = $this->attrValConvertUrlStr($val);
        $str = $attrUrlStr.'='.$val;
        $currentRequestVal = Yii::$app->request->get($attrUrlStr);
        $originPUrl = '';
        if ($pageBackToOne && $p) {
            $pVal = Yii::$app->request->get($p);
            if ($pVal) {
                $originPUrl = $p.'='.$pVal;
                $afterPUrl = $p.'=1';
            }
        }

        if ($currentRequestVal) {
            $originAttrUrlStr = $attrUrlStr . '=' . $this->attrValConvertUrlStr($currentRequestVal);
            $currentUrl = Yii::$service->url->getCurrentUrl();
            if ($originAttrUrlStr == $str) {
                $url = $currentUrl;
                if (strstr($currentUrl, '?'.$originAttrUrlStr.'&')) {
                    $url = str_replace('?'.$originAttrUrlStr.'&', '?', $currentUrl);
                } elseif (strstr($currentUrl, '?'.$originAttrUrlStr)) {
                    $url = str_replace('?'.$originAttrUrlStr, '', $currentUrl);
                } elseif (strstr($currentUrl, '&'.$originAttrUrlStr)) {
                    $url = str_replace('&'.$originAttrUrlStr, '', $currentUrl);
                }
                if ($originPUrl) {
                    $url = str_replace($originPUrl, $afterPUrl, $url);
                }

                return [
                    'url'    => $url,
                    'selected'    => true,
                ];
            } else {
                if ($originPUrl) {
                    $currentUrl = str_replace($originPUrl, $afterPUrl, $currentUrl);
                }

                return [
                    'url'    => str_replace($originAttrUrlStr, $str, $currentUrl),
                    'selected'    => false,
                ];
            }

            return str_replace($originAttrUrlStr, $str, $currentUrl);
        } else {
            $currentUrl = Yii::$service->url->getCurrentUrl();
            if (strstr($currentUrl, '?')) {
                if ($originPUrl) {
                    $currentUrl = str_replace($originPUrl, $afterPUrl, $currentUrl);
                }

                return [
                    'url'    => $currentUrl.'&'.$str,
                    'selected'    => false,
                ];
            } else {
                if ($originPUrl) {
                    $currentUrl = str_replace($originPUrl, $afterPUrl, $currentUrl);
                }

                return [
                    'url'    => $currentUrl.'?'.$str,
                    'selected'    => false,
                ];
            }
        }
    }

    /**
     * 得到排序的url.
     * @param $arr|array sort的字段和值  dir的字段和值
     * @param $p|string  在url中用来表示分页的参数，一般用p来标示。
     * @param $pageBackToOne|bool 是否让p的页数回归第一页
     */
    protected function actionGetFilterSortAttrUrl($arr, $p = '', $pageBackToOne = true)
    {
        $sort = $arr['sort']['key'];
        $sortVal = $arr['sort']['val'];
        $dir = $arr['dir']['key'];
        $dirVal = $arr['dir']['val'];

        $originPUrl = '';
        if ($pageBackToOne && $p) {
            $pVal = Yii::$app->request->get($p);
            if ($pVal) {
                $originPUrl = $p.'='.$pVal;
                $afterPUrl = $p.'=1';
            }
        }

        $sortVal = $this->attrValConvertUrlStr($sortVal);
        $sortStr = $sort.'='.$sortVal;
        $currentSortVal = Yii::$app->request->get($sort);

        $dirVal = $this->attrValConvertUrlStr($dirVal);
        $dirStr = $dir.'='.$dirVal;
        $currentDirVal = Yii::$app->request->get($dir);

        $str = $sortStr.'&'.$dirStr;
        if ($currentSortVal && $currentDirVal) {
            $originAttrUrlStr = $sort.'='.$currentSortVal.'&'.$dir.'='.$currentDirVal;
            $currentUrl = Yii::$service->url->getCurrentUrl();

            if ($originAttrUrlStr == $str) {
                //return str_replace($originAttrUrlStr,$str,$currentUrl);
                $url = $currentUrl;
                if (strstr($currentUrl, '?'.$originAttrUrlStr.'&')) {
                    $url = str_replace('?'.$originAttrUrlStr.'&', '?', $currentUrl);
                } elseif (strstr($currentUrl, '?'.$originAttrUrlStr)) {
                    $url = str_replace('?'.$originAttrUrlStr, '', $currentUrl);
                } elseif (strstr($currentUrl, '&'.$originAttrUrlStr)) {
                    $url = str_replace('&'.$originAttrUrlStr, '', $currentUrl);
                }
                if ($originPUrl) {
                    $url = str_replace($originPUrl, $afterPUrl, $url);
                }

                return [
                    'url'    => $url,
                    'selected'    => true,
                ];
            } else {
                if ($originPUrl) {
                    $currentUrl = str_replace($originPUrl, $afterPUrl, $currentUrl);
                }

                return [
                    'url'    => str_replace($originAttrUrlStr, $str, $currentUrl),
                    'selected'    => false,
                ];
            }

            return str_replace($originAttrUrlStr, $str, $currentUrl);
        } else {
            $currentUrl = Yii::$service->url->getCurrentUrl();
            if (strstr($currentUrl, '?')) {
                if ($originPUrl) {
                    $currentUrl = str_replace($originPUrl, $afterPUrl, $currentUrl);
                }

                return [
                    'url'    => $currentUrl.'&'.$str,
                    'selected'    => false,
                ];
            } else {
                if ($originPUrl) {
                    $currentUrl = str_replace($originPUrl, $afterPUrl, $currentUrl);
                }

                return [
                    'url'    => $currentUrl.'?'.$str,
                    'selected'    => false,
                ];
            }
        }
    }
    
    
    /**
     * 没有排序参数的url
     */
    public  function getFilterNoSortUrl()
    {
        $currentUrl = Yii::$service->url->getCurrentUrl();
        $ar=parse_url($currentUrl);
        if (!isset($ar['query'])) {
            return $url;
        }
        parse_str($ar['query'],$arr);
        unset($arr['sort']);
        unset($arr['dir']);
        
        return $ar['scheme'].'://'.$ar['host'].$ar['path'].($arr ? '?'.http_build_query($arr) : '');
    }

    // 得到不选择这个属性的url
    /*
    protected function actionGetFilterUnChooseAttrUrl($attrUrlStr,$val){
        $val = $this->attrValConvertUrlStr($val);
        $str = $attrUrlStr.'='.$val;
        $currentUrl = Yii::$service->url->getCurrentUrl();
        $currentUrl = str_replace($str,'',$currentUrl);
        return $currentUrl ;
    }
    */
}
