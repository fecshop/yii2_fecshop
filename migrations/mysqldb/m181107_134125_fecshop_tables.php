<?php

use yii\db\Migration;

/**
 * Class m181107_134125_fecshop_tables
 */
class m181107_134125_fecshop_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $arr = [
            '
            ALTER TABLE  `admin_url_key` ADD UNIQUE (`url_key`) 
            ' ,
            
            '
            ALTER TABLE  `admin_role_url_key` ADD INDEX (  `role_id` )
            ' ,
            
            '
            update `admin_url_key` set url_key = "/catalog/category/remove" where url_key = "catalog/category/remove"

            ' ,
            
            
            '
            update `admin_url_key` set url_key = "/catalog/category/save" where url_key = "catalog/category/save"
            ' ,
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
        echo "m181107_134125_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181107_134125_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
