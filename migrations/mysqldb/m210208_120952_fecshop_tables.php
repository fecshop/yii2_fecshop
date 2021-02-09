<?php

use yii\db\Migration;

/**
 * Class m210208_120952_fecshop_tables
 */
class m210208_120952_fecshop_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $arr = [
            "
                ALTER TABLE `product_flat` ADD `third_refer_url` VARCHAR( 255 ) NULL COMMENT '对应的第三方平台的url，一般采集的产品数据对应的外部来源url',
                ADD `third_refer_code` VARCHAR( 50 ) NULL COMMENT '对应的第三方平台的产品外部编码'
            ",
            "
                ALTER TABLE `product_flat` ADD `third_product_code` VARCHAR( 50 ) NULL COMMENT '货号（采集的第三方平台的货号）',
                ADD INDEX ( `third_product_code` )
            ",
            "
                ALTER TABLE `product_flat` ADD INDEX ( `third_refer_code` )
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
        echo "m210208_120952_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210208_120952_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
