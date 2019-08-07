<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\apphtml5\modules\Catalog\helpers;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Review  extends \yii\base\BaseObject
{
    // 初始化当前appfront的设置，覆盖service的初始设置。
    public static function initReviewConfig()
    {
        // $reviewParam = Yii::$app->getModule('catalog')->params['review'];
        //$appName = Yii::$service->helper->getAppName();
        //$reviewFilterByLang = Yii::$app->store->get($appName.'_catalog','review_filterByLang');
        //$reviewFilterByLang = ($reviewFilterByLang == Yii::$app->store->enable) ? true : false;
        //
        //if (isset($reviewParam['filterByLang'])) {
        //    Yii::$service->product->review->filterByLang = $reviewParam['filterByLang'];
        //}
    }

    /**
     * @param $product | Object
     * @return array
     *               通过service的配置，是否通过语言进行过滤产品的总个数
     *               如果不通过语言，则直接将产品的属性	review_count 		reviw_rate_star_average 返回
     *               如果通过语言，那么通过属性		review_count_lang 		reviw_rate_star_average_lang ,在通过当前的语言获取相应的属性值。
     */
    public static function getReviewAndStarCount($product)
    {
        // 这个是是否通过语言进行过滤评论，可以通过上面的函数 self::initReviewConfig进行初始化，
        // 也就是通过当前模块的配置，来覆盖service的配置
        $filterByLang = Yii::$service->product->review->filterByLang;
        
        if ($filterByLang) {
            $langCode = Yii::$service->store->currentLangCode;
            if ($langCode) {
                $a = Yii::$service->fecshoplang->getLangAttrName('review_count_lang', $langCode);
                $b = Yii::$service->fecshoplang->getLangAttrName('reviw_rate_star_average_lang', $langCode);
                $c = Yii::$service->fecshoplang->getLangAttrName('reviw_rate_star_info_lang', $langCode);
                $review_count_lang = 0;
                if (isset($product['review_count_lang'][$a])) {
                    $review_count_lang = $product['review_count_lang'][$a];
                    $review_count_lang = $review_count_lang ? $review_count_lang : 0;
                }
                $reviw_rate_star_average_lang = 0;
                if (isset($product['reviw_rate_star_average_lang'][$b])) {
                    $reviw_rate_star_average_lang = $product['reviw_rate_star_average_lang'][$b];
                    $reviw_rate_star_average_lang = $reviw_rate_star_average_lang ? $reviw_rate_star_average_lang : 0;
                }
                
                $reviw_rate_star_info_lang = [];
                if (isset($product['reviw_rate_star_info_lang'][$c])) {
                    $reviw_rate_star_info_lang = $product['reviw_rate_star_info_lang'][$c];
                    $reviw_rate_star_info_lang = $reviw_rate_star_info_lang ? $reviw_rate_star_info_lang : [];
                }
                $reviw_rate_star_info = self::getRateStarInfo($review_count_lang, $reviw_rate_star_info_lang);
                return [$review_count_lang, $reviw_rate_star_average_lang, $reviw_rate_star_info];
            }
        } else {
            $review_count = $product['review_count'] ? $product['review_count'] : 0;
            $reviw_rate_star_average = $product['reviw_rate_star_average'] ? $product['reviw_rate_star_average'] : 0;
            $reviw_rate_star_info = $product['reviw_rate_star_info'] ? $product['reviw_rate_star_info'] : [];
            $reviw_rate_star_info = self::getRateStarInfo($review_count, $reviw_rate_star_info);
            return [$review_count, $reviw_rate_star_average, $reviw_rate_star_info];
        }
    }
    
    public static function getRateStarInfo($review_count, $reviw_rate_star_info){
        if (empty($reviw_rate_star_info)) {
            $reviw_rate_star_info['star_0'] = 0;
            $reviw_rate_star_info['star_1'] = 0;
            $reviw_rate_star_info['star_2'] = 0;
            $reviw_rate_star_info['star_3'] = 0;
            $reviw_rate_star_info['star_4'] = 0;
            $reviw_rate_star_info['star_5'] = 0;
        }
        foreach ($reviw_rate_star_info as $k => $v) {
            if ($review_count) {
                $reviw_rate_star_info[$k] = ceil($v / $review_count * 1000) / 10;
            }
        }
        
        return $reviw_rate_star_info;
    }
}
