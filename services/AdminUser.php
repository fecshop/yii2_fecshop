<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services;

use Yii;

/**
 * AdminUser services. 用来给后台的用户提供数据。
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class AdminUser extends Service
{
    /**
     * @property $ids | Int Array
     * @return 得到相应用户的数组。
     */
    protected function actionGetIdAndNameArrByIds($ids)
    {
        $user_coll = \fecadmin\models\AdminUser::find()->asArray()->select(['id', 'username'])->where([
            'in', 'id', $ids,
        ])->all();
        $users = [];
        foreach ($user_coll as $one) {
            $users[$one['id']] = $one['username'];
        }

        return $users;
    }
}
