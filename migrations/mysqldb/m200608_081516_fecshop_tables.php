<?php

use yii\db\Migration;

/**
 * Class m200608_081516_fecshop_tables
 */
class m200608_081516_fecshop_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $arr = [
            "
            CREATE TABLE IF NOT EXISTS `customer_contacts` (
              `id` int(12) NOT NULL AUTO_INCREMENT,
              `name` varchar(100) DEFAULT NULL COMMENT '联系人姓名',
              `telephone` varchar(60) DEFAULT NULL COMMENT '联系人电话',
              `email` varchar(150) DEFAULT NULL COMMENT '联系人邮箱',
              `comment` text COMMENT '评价内容',
              `updated_at` int(12) DEFAULT NULL,
              `created_at` int(12) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `email` (`email`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
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
        echo "m200608_081516_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200608_081516_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
