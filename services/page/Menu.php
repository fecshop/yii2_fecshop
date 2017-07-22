<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\page;

use fec\helpers\CUrl;
use fecshop\services\Service;
use Yii;

/**
 * Page Menu services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Menu extends Service
{
    /**
     * whether display HOME in the menu.
     */
    public $displayHome;
    /**
     * custom menu that display in the front of product category.
     */
    public $frontCustomMenu;
    /**
     * custom menu that display in the behind of product category.
     */
    public $behindCustomMenu;

    protected $_homeUrl;

    /**
     * return menu array data, contains:
     * home,frontCustomMenu,productCategory,behindCustomMenu.
     * 得到网站的分类导航栏菜单。
     */
    protected function actionGetMenuData()
    {
        $this->_homeUrl = CUrl::getHomeUrl();
        $arr = [];
        if ($displayHome = $this->displayHome) {
            $enable = isset($displayHome['enable']) ? $displayHome['enable'] : '';
            $display = isset($displayHome['display']) ? $displayHome['display'] : '';
            if ($enable && $display) {
                $arr[] = [
                    'name' => Yii::$service->page->translate->__($display),
                    'url'    => Yii::$service->url->homeUrl(),
                ];
            }
        }
        $first_custom_menu = $this->customMenuInit($this->frontCustomMenu);
        if (is_array($first_custom_menu) && !empty($first_custom_menu)) {
            foreach ($first_custom_menu as $m) {
                $arr[] = $m;
            }
        }
        $categoryMenuArr = $this->getProductCategoryMenu();
        //var_dump($categoryMenuArr);
        if (is_array($categoryMenuArr) && !empty($categoryMenuArr)) {
            foreach ($categoryMenuArr as $a) {
                $arr[] = $a;
            }
        }

        $behind_custom_menu = $this->customMenuInit($this->behindCustomMenu);
        if (is_array($behind_custom_menu) && !empty($behind_custom_menu)) {
            foreach ($behind_custom_menu as $m) {
                $arr[] = $m;
            }
        }

        return $arr;
    }
    /**
     * @property $customMenu | Array , 自定义菜单部分数组，从配置中取出
     * @return Array，获取处理后的自定义菜单数组
     */
    protected function customMenuInit($customMenu)
    {
        $cMenu = [];
        if (is_array($customMenu) && !empty($customMenu)) {
            foreach ($customMenu as $k=>$menu) {
                $name           = Yii::$service->page->translate->__($menu['name']);
                $menu['name']   = $name;
                $urlPath        = $menu['urlPath'];
                $menu['url']    = Yii::$service->url->getUrl($urlPath);
                $cMenu[$k]      = $menu;
                if (isset($menu['childMenu'])) {
                    $cMenu[$k]['childMenu'] = $this->customMenuInit($menu['childMenu']);
                }
            }
        }

        return $cMenu;
    }

    /**
     * get product category array as menu.
     */
    protected function getProductCategoryMenu()
    {
        return Yii::$service->category->menu->getCategoryMenuArr();
    }
}
