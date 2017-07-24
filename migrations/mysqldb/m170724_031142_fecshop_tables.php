<?php

use yii\db\Migration;

class m170724_031142_fecshop_tables extends Migration
{
    public function safeUp()
    {
        $arr = [
            "
            ALTER TABLE `sales_flat_order` CHANGE `txn_id` `txn_id` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Transaction Id 支付平台唯一交易号,通过这个可以在第三方支付平台查找订单',
            CHANGE `payer_id` `payer_id` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '它是特定PayPal帐户的外部唯一标识符';
            "
            ,
            "
            ALTER TABLE `sales_flat_order` CHANGE `txn_type` `txn_type` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Transaction类型，是在购物车点击支付按钮下单，还是在下单页面填写完货运地址信息下单';
            "
            ,
        ];
    
        foreach ($arr as $sql) {
            $this->execute($sql);
        }
    }

    public function safeDown()
    {
        echo "m170724_031142_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170724_031142_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
