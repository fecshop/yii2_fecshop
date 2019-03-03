<?php

use yii\db\Migration;

/**
 * Class m181124_150715_fecshop_tables
 */
class m181124_150715_fecshop_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $arr = [
           "
            ALTER TABLE  `admin_config` ADD UNIQUE (`key`)
           " ,
            "
            ALTER TABLE  `admin_user_role` ADD INDEX (  `user_id` )
            ",
            "
            ALTER TABLE  `article` ADD UNIQUE (`url_key`)
            ",
            "
            ALTER TABLE  `customer` ADD UNIQUE (`email`)
            ",
            "
            ALTER TABLE  `ipn_message` ADD UNIQUE (`txn_id`)
            ",
            "
            ALTER TABLE  `sales_flat_order` ADD UNIQUE (`increment_id`)
            ",
            "
            ALTER TABLE  `static_block` ADD UNIQUE (`identify`)
            ",
            "
            DELETE FROM url_rewrite WHERE custom_url_key =  '/111111111111111'
            ",
            "
            ALTER TABLE  `url_rewrite` ADD UNIQUE (`custom_url_key`)
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
        echo "m181124_150715_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181124_150715_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
