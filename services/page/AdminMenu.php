<?php

/*
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
class AdminMenu extends Service
{
    /**
     * @var array 后台菜单配置, 参看@fecshop/config/services/Page.php的配置
     */
    public $menuConfig;

    /**
     * @return Array , 得到后台菜单配置。
     */
    public function getConfigMenu(){
        $menu = $this->menuConfig;

        return $menu;
    }

}
