<?php

use yii\db\Migration;

class m170619_014655_fecshop_tables extends Migration
{
    public function safeUp()
    {
        $arr = [
            "
                CREATE TABLE IF NOT EXISTS `product_flat_qty` (
                `id` int(20) NOT NULL AUTO_INCREMENT,
                `product_id` VARCHAR( 50 ) NOT NULL COMMENT '产品表的id',
                `qty` INT( 20 ) NOT NULL COMMENT '产品表的个数',
                PRIMARY KEY (`id`)
                ) ENGINE = InnoDB;
            "
            ,
            "ALTER TABLE `product_flat_qty` ADD UNIQUE INDEX(`product_id`);"
            ,
            
            "
                CREATE TABLE IF NOT EXISTS `product_custom_option_qty` (
                `id` int(20) NOT NULL AUTO_INCREMENT,
                `product_id` VARCHAR( 50 ) NOT NULL COMMENT '产品id',
                `custom_option_sku` VARCHAR( 50 ) NOT NULL COMMENT '产品自定义属性sku',
                `qty` INT( 20 ) NOT NULL COMMENT '产品个数。',
                PRIMARY KEY (`id`)
                ) ENGINE = InnoDB;
            "
            ,
            
            "ALTER TABLE `product_custom_option_qty` ADD UNIQUE INDEX(`product_id`,`custom_option_sku`);"
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

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
