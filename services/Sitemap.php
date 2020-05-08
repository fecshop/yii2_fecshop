<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services;

use Yii;

/**
 * Sitemap services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Sitemap extends Service
{
    public $numPerPage = 100;

    public $sitemapConfig;

    protected $currentDate; // = date('Y-m-d');

    protected function initSiteMap()
    {
        $this->currentDate = date('Y-m-d');
    }

    /**
     * 在store的配置中，没一个store都有一个sitemap文件路径的配置项，譬如：
     * 'sitemapDir' => '@appfront/web/sitemap.xml',
     * 下面就是sitemap开始阶段，把格式头写入到对应的sitemap文件中
     * 对于sitemp.xml文件的访问，为了需要，您可以在nginx中做重新的指向。
     * sitemap的更多资料，您可以参看：http://www.fecshop.com/doc/fecshop-guide/instructions/cn-1.0/guide-fecshop_sitemap.html
     */
    public function beginSiteMap()
    {
        $this->initSiteMap();
        if (is_array($this->sitemapConfig) && !empty($this->sitemapConfig)) {
            foreach ($this->sitemapConfig as $appIn => $store) {
                if (is_array($store) && !empty($store)) {
                    foreach ($store as $domain => $info) {
                        $sitemapDir = (isset($info['sitemapDir']) && $info['sitemapDir']) ? $info['sitemapDir'] : '';
                        if ($sitemapDir) {
                            $sitemapAbsoluteDir = Yii::getAlias($sitemapDir);
                            $xmlFile = fopen($sitemapAbsoluteDir, 'w') or die('Unable to open file!');
                            if (file_exists($sitemapAbsoluteDir)) {
                                $str = '<?xml version="1.0" encoding="UTF-8"?>
<?xml-stylesheet type="text/xsl" href="gss.xsl"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.google.com/schemas/sitemap/0.84 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
';
                                fwrite($xmlFile, $str);
                            }
                            fclose($xmlFile);
                        }
                    }
                }
            }
        }
    }

    /**
     * sitemap 文件写入内容后的结束执行的函数。
     */
    public function endSiteMap()
    {
        $this->initSiteMap();
        if (is_array($this->sitemapConfig) && !empty($this->sitemapConfig)) {
            foreach ($this->sitemapConfig as $appIn => $store) {
                if (is_array($store) && !empty($store)) {
                    foreach ($store as $domain => $info) {
                        $sitemapDir = (isset($info['sitemapDir']) && $info['sitemapDir']) ? $info['sitemapDir'] : '';
                        if ($sitemapDir) {
                            $sitemapAbsoluteDir = Yii::getAlias($sitemapDir);
                            $xmlFile = fopen($sitemapAbsoluteDir, 'a') or die('Unable to open file!');
                            if (file_exists($sitemapAbsoluteDir)) {
                                $str = '</urlset>';
                                fwrite($xmlFile, $str);
                            }
                            fclose($xmlFile);
                        }
                    }
                }
            }
        }
    }

    /**
     * 在sitemap文件中写入home部分的链接
     */
    public function home()
    {
        $this->initSiteMap();
        if (is_array($this->sitemapConfig) && !empty($this->sitemapConfig)) {
            foreach ($this->sitemapConfig as $appIn => $store) {
                if (is_array($store) && !empty($store)) {
                    foreach ($store as $domain => $info) {
                        $https = $info['https'];
                        $showScriptName = $info['showScriptName'];
                        $sitemapDir = (isset($info['sitemapDir']) && $info['sitemapDir']) ? $info['sitemapDir'] : '';
                        if ($sitemapDir) {
                            $sitemapAbsoluteDir = Yii::getAlias($sitemapDir);
                            $xmlFile = fopen($sitemapAbsoluteDir, 'a') or die('Unable to open file!');
                            if (file_exists($sitemapAbsoluteDir)) {
                                $home_url = Yii::$service->url->getUrlByDomain('', [], $https, $domain, $showScriptName, true);
                                $str = '<url><loc>'.$home_url.'</loc><lastmod>'.$this->currentDate.'</lastmod></url>';
                                fwrite($xmlFile, $str);
                            }
                            fclose($xmlFile);
                        }
                    }
                }
            }
        }
    }

    /**
     * 得到分类的总个数
     */
    public function categorypagecount()
    {
        $this->initSiteMap();
        $coll = Yii::$service->category->coll();
        $count = $coll['count'];
        
        echo ceil($count / $this->numPerPage);
    }

    /**
     * 在sitemap文件中写入分类部分的链接
     */
    public function category($pageNum)
    {
        $this->initSiteMap();
        if (is_array($this->sitemapConfig) && !empty($this->sitemapConfig)) {
            foreach ($this->sitemapConfig as $appIn => $store) {
                if (is_array($store) && !empty($store)) {
                    foreach ($store as $domain => $info) {
                        $https = $info['https'];
                        $showScriptName = $info['showScriptName'];
                        $sitemapDir = (isset($info['sitemapDir']) && $info['sitemapDir']) ? $info['sitemapDir'] : '';
                        if ($sitemapDir) {
                            $sitemapAbsoluteDir = Yii::getAlias($sitemapDir);
                            $xmlFile = fopen($sitemapAbsoluteDir, 'a') or die('Unable to open file!');
                            if (file_exists($sitemapAbsoluteDir)) {
                                $filter = [
                                    'numPerPage'    => $this->numPerPage,
                                    'pageNum'        => $pageNum,
                                    'asArray'        => true,
                                ];
                                $coll = Yii::$service->category->coll($filter);
                                $data = $coll['coll'];
                                if (is_array($data) && !empty($data)) {
                                    foreach ($data as $one) {
                                        $category_url_key = $one['url_key'];
                                        $category_url = Yii::$service->url->getUrlByDomain($category_url_key, [], $https, $domain, $showScriptName, true);
                                        $str = '<url><loc>'.$category_url.'</loc><lastmod>'.$this->currentDate.'</lastmod></url>';
                                        fwrite($xmlFile, $str);
                                    }
                                }
                            }
                            fclose($xmlFile);
                        }
                    }
                }
            }
        }
    }

    /**
     * 得到产品的总个数
     */
    public function productpagecount()
    {
        $this->initSiteMap();
        $coll = Yii::$service->product->coll();
        $count = $coll['count'];
        
        echo ceil($count / $this->numPerPage);
    }

    /**
     * 在sitemap文件中写入产品部分的链接
     */
    public function product()
    {
        $this->initSiteMap();
        if (is_array($this->sitemapConfig) && !empty($this->sitemapConfig)) {
            foreach ($this->sitemapConfig as $appIn => $store) {
                if (is_array($store) && !empty($store)) {
                    foreach ($store as $domain => $info) {
                        $https = $info['https'];
                        $showScriptName = $info['showScriptName'];
                        $sitemapDir = (isset($info['sitemapDir']) && $info['sitemapDir']) ? $info['sitemapDir'] : '';
                        if ($sitemapDir) {
                            $sitemapAbsoluteDir = Yii::getAlias($sitemapDir);
                            $xmlFile = fopen($sitemapAbsoluteDir, 'a') or die('Unable to open file!');
                            if (file_exists($sitemapAbsoluteDir)) {
                                $filter = [
                                    'numPerPage'    => $this->numPerPage,
                                    'pageNum'        => $pageNum,
                                    'asArray'        => true,
                                ];
                                $coll = Yii::$service->product->coll($filter);
                                $data = $coll['coll'];
                                if (is_array($data) && !empty($data)) {
                                    foreach ($data as $one) {
                                        $product_url_key = $one['url_key'];
                                        $product_url = Yii::$service->url->getUrlByDomain($product_url_key, [], $https, $domain, $showScriptName, true);
                                        $str = '<url><loc>'.$product_url.'</loc><lastmod>'.$this->currentDate.'</lastmod></url>';
                                        fwrite($xmlFile, $str);
                                    }
                                }
                            }
                            fclose($xmlFile);
                        }
                    }
                }
            }
        }
    }

    /**
     * page页的总个数
     */
    public function cmspagepagecount()
    {
        $this->initSiteMap();
        $coll = Yii::$service->cms->article->coll();
        $count = $coll['count'];
        echo ceil($count / $this->numPerPage);
    }

    /**
     * 在sitemap文件中写入page部分的链接
     */
    public function cmspage()
    {
        $this->initSiteMap();
        if (is_array($this->sitemapConfig) && !empty($this->sitemapConfig)) {
            foreach ($this->sitemapConfig as $appIn => $store) {
                if (is_array($store) && !empty($store)) {
                    foreach ($store as $domain => $info) {
                        $https = $info['https'];
                        $showScriptName = $info['showScriptName'];
                        $sitemapDir = (isset($info['sitemapDir']) && $info['sitemapDir']) ? $info['sitemapDir'] : '';
                        if ($sitemapDir) {
                            $sitemapAbsoluteDir = Yii::getAlias($sitemapDir);
                            $xmlFile = fopen($sitemapAbsoluteDir, 'a') or die('Unable to open file!');
                            if (file_exists($sitemapAbsoluteDir)) {
                                $filter = [
                                    'numPerPage'    => $this->numPerPage,
                                    'pageNum'        => $pageNum,
                                    'asArray'        => true,
                                ];
                                $coll = Yii::$service->cms->article->coll($filter);
                                $data = $coll['coll'];
                                if (is_array($data) && !empty($data)) {
                                    foreach ($data as $one) {
                                        $cms_page_url_key = $one['url_key'];
                                        $cms_page_url = Yii::$service->url->getUrlByDomain($cms_page_url_key, [], $https, $domain, $showScriptName, true);
                                        $str = '<url><loc>'.$cms_page_url.'</loc><lastmod>'.$this->currentDate.'</lastmod></url>';
                                        fwrite($xmlFile, $str);
                                    }
                                }
                            }
                            fclose($xmlFile);
                        }
                    }
                }
            }
        }
    }
}
