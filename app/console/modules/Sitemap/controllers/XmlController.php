<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\console\modules\Sitemap\controllers;

use Yii;
use yii\console\Controller;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class XmlController extends Controller
{
    public function actionBegin()
    {
        Yii::$service->sitemap->beginSiteMap();
    }

    // 首页
    public function actionHome()
    {
        Yii::$service->sitemap->home();
    }

    // 分类页面的页面总数
    public function actionCategorypagecount()
    {
        echo Yii::$service->sitemap->categorypagecount();
    }

    // 生成分类页面
    public function actionCategory($pageNum)
    {
        Yii::$service->sitemap->category($pageNum);
    }

    // 产品页面的页面总数
    public function actionProductpagecount()
    {
        echo Yii::$service->sitemap->productpagecount();
    }

    // 生成产品页面
    public function actionProduct($pageNum)
    {
        Yii::$service->sitemap->product($pageNum);
    }

    // cms page页面的页面总数
    public function actionCmspagepagecount()
    {
        echo Yii::$service->sitemap->cmspagepagecount();
    }

    // 生成cms page页面的sitemap
    public function actionCmspage($pageNum)
    {
        Yii::$service->sitemap->cmspage($pageNum);
    }

    public function actionEnd()
    {
        Yii::$service->sitemap->endSiteMap();
    }
}
