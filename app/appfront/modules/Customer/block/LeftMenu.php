<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appfront\modules\Customer\block;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class LeftMenu
{
    public function getLastData()
    {
        $leftMenu = \Yii::$app->getModule('customer')->params['leftMenu'];
        $leftMenuArr = [];
        if (is_array($leftMenu) && !empty($leftMenu)) {
            $current_url_key = Yii::$app->request->getPathInfo();
            $arr = explode('/', $current_url_key);
            if (count($arr) >= 2) {
                $current_url_key_sub = $arr[0].'/'.$arr[1];
            } else {
                $current_url_key_sub = $arr[0];
            }

            foreach ($leftMenu as $menu_name => $menu_url_key) {
                $currentClass = '';
                $url = Yii::$service->url->getUrl($menu_url_key);
                if (strstr($menu_url_key, $current_url_key_sub)) {
                    //echo "$menu_url_key,$current_url_key_sub <br>";
                    $currentClass = 'class="current"';
                }
                $leftMenuArr[] = [
                    'name'    => $menu_name,
                    'url'    => $url,
                    'current'=> $currentClass,
                ];
            }
        }
        //var_dump($leftMenuArr);
        return [
            'leftMenuArr' => $leftMenuArr,
        ];
    }
}
