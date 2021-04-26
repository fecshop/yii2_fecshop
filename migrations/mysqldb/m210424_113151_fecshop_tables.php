<?php

use yii\db\Migration;

/**
 * Class m210424_113151_fecshop_tables
 */
class m210424_113151_fecshop_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $arr = [
            "
                CREATE TABLE IF NOT EXISTS `script_date_control` (
                  `id` int(12) NOT NULL AUTO_INCREMENT,
                  `begin_at` int(12) DEFAULT NULL COMMENT '脚本开始时间',
                  `end_at` int(12) DEFAULT NULL COMMENT '脚本结束时间',
                  `type` varchar(30) DEFAULT NULL COMMENT '脚本类型',
                  `status` int(12) DEFAULT NULL COMMENT '脚本状态',
                  `script_created_at` int(12) DEFAULT NULL COMMENT '脚本创建时间',
                  `script_updated_at` int(12) DEFAULT NULL COMMENT '脚本更新时间',
                  `created_at` int(12) DEFAULT NULL COMMENT '数据第一次的创建时间',
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `type` (`type`)
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
        echo "m210424_113151_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210424_113151_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
