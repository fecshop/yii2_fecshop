<?php

use yii\db\Migration;

class m170628_052812_fecshop_tables extends Migration
{
    public function safeUp()
    {
        $arr = [
            "
            ALTER TABLE `sales_flat_order` CHANGE `total_weight` `total_weight` DECIMAL(12,2) NULL DEFAULT '0.0000' COMMENT '总重量', 
            CHANGE `grand_total` `grand_total` DECIMAL(12,2) NULL DEFAULT NULL COMMENT '当前订单的总额', 
            CHANGE `base_grand_total` `base_grand_total` DECIMAL(12,2) NULL DEFAULT NULL COMMENT '当前订单的默认货币总额', 
            CHANGE `subtotal` `subtotal` DECIMAL(12,2) NULL DEFAULT NULL COMMENT '当前订单的产品总额', 
            CHANGE `base_subtotal` `base_subtotal` DECIMAL(12,2) NULL DEFAULT NULL COMMENT '当前订单的产品默认货币总额', 
            CHANGE `subtotal_with_discount` `subtotal_with_discount` DECIMAL(12,2) NULL DEFAULT NULL COMMENT '当前订单的去掉的总额', 
            CHANGE `base_subtotal_with_discount` `base_subtotal_with_discount` DECIMAL(12,2) NULL DEFAULT NULL COMMENT '当前订单的去掉的默认货币总额', 
            CHANGE `shipping_total` `shipping_total` DECIMAL(12,2) NULL DEFAULT NULL COMMENT '运费总额', 
            CHANGE `base_shipping_total` `base_shipping_total` DECIMAL(12,2) NULL DEFAULT NULL COMMENT '默认货币运费总额', 
            CHANGE `payment_fee` `payment_fee` DECIMAL(12,2) NULL DEFAULT NULL COMMENT '交易服务费',
            CHANGE `base_payment_fee` `base_payment_fee` DECIMAL(12,2) NULL DEFAULT NULL COMMENT '交易费用，基础货币值，通过货币进行的转换'
            "
            ,
            "
            ALTER TABLE `sales_flat_order_item` CHANGE `weight` `weight` DECIMAL( 12, 2 ) NULL DEFAULT NULL COMMENT '重量',
            CHANGE `row_weight` `row_weight` DECIMAL( 12, 2 ) NULL DEFAULT NULL COMMENT '一个产品重量*个数',
            CHANGE `price` `price` DECIMAL( 12, 2 ) NULL DEFAULT NULL COMMENT '产品价格',
            CHANGE `base_price` `base_price` DECIMAL( 12, 2 ) NULL DEFAULT NULL COMMENT '默认货币价格',
            CHANGE `row_total` `row_total` DECIMAL( 12, 2 ) NULL DEFAULT NULL COMMENT '一个产品价格*个数',
            CHANGE `base_row_total` `base_row_total` DECIMAL( 12, 2 ) NULL DEFAULT NULL COMMENT '一个产品默认货币价格*个数'
            "
            ,
            "
            ALTER TABLE `sales_flat_order`  ADD `version` INT(5) NOT NULL DEFAULT '0' COMMENT '订单支付成功后，需要更改订单状态和扣除库存，为了防止多次执行扣除库存，进而使用版本号，默认为0，执行一次更改订单状态为processing，则累加1，执行完查询version是否为1，如果不为1，则说明执行过了，事务则回滚。'
            "
            ,
        ];
    
        foreach ($arr as $sql) {
            $this->execute($sql);
        }
    }

    public function safeDown()
    {
        echo "m170619_014655_fecshop_tables cannot be reverted.\n";

        return false;
    }
}
