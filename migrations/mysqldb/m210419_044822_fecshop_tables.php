<?php

use yii\db\Migration;

/**
 * Class m210419_044822_fecshop_tables
 */
class m210419_044822_fecshop_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $arr = [
            "
                ALTER TABLE `full_search_product` ADD INDEX ( `name`,`lang` ) 
            ",
            "
                ALTER TABLE `full_search_product` ADD INDEX ( `product_id`,`lang` ) 
            ",
            "
                ALTER TABLE `product_brand` ADD `remote_id` INT( 12 ) NULL COMMENT '远程同步数据的id', ADD UNIQUE ( `remote_id` )
            ",
            "
                ALTER TABLE `product_brand_category` ADD `remote_id` INT( 12 ) NULL COMMENT '远程同步数据的id', ADD UNIQUE ( `remote_id` )
            ",
            "
                ALTER TABLE `product_flat` ADD `remote_id` INT( 12 ) NULL COMMENT '远程同步数据的id', ADD UNIQUE ( `remote_id` )
            ",
            "
                ALTER TABLE `category` ADD `remote_id` INT( 12 ) NULL COMMENT '远程同步数据的id', ADD UNIQUE ( `remote_id` )
            ",
            "
                ALTER TABLE `category` ADD `remote_parent_id` INT( 12 ) NULL , ADD INDEX ( `remote_parent_id` )
            ",
           
        ];
        
        
        foreach ($arr as $sql) {
            $this->execute($sql);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210419_044822_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210419_044822_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
