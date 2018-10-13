<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\models\mysqldb\cart;

use yii\db\ActiveRecord;

/**
 * Cart item.
 *
 * @property int $item_id
 * @property string $store
 * @property int $cart_id
 * @property int $created_at
 * @property int $updated_at
 * @property string $product_id
 * @property int $qty
 * @property string $custom_option_sku
 * @property int $active
 *
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Item extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%sales_flat_cart_item}}';
    }
}
