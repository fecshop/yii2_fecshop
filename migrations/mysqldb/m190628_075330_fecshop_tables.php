<?php

use yii\db\Migration;

/**
 * Class m190628_075330_fecshop_tables
 */
class m190628_075330_fecshop_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $arr = [
            "
                ALTER TABLE  `customer` ADD  `wx_session_key` VARCHAR( 200 ) NULL COMMENT  '微信session key'
            ",
            
            "
                ALTER TABLE  `customer` ADD  `wx_openid` VARCHAR( 200 ) NULL COMMENT  '微信的openid'
            ",
            
            "
                ALTER TABLE  `customer` ADD INDEX (  `wx_openid` )
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
        echo "m190628_075330_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190628_075330_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
