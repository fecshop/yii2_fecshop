<?php

class m160608_062040_mongodb_log_product_view extends \yii\mongodb\Migration
{
    public function up()
    {
		$columns = ['user_id','date_time'];
		$this->createIndex('log_product_view', $columns);
    }

    public function down()
    {
        echo "m160608_062040_mongodb_log_product_view cannot be reverted.\n";

        return false;
    }
}
