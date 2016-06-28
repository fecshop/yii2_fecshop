<?php

class m160608_061933_mongodb_url_write extends \yii\mongodb\Migration
{
    public function up()
    {
		# url_write: _id,  type ,custom_url, yii_url
		# index: [custom_url] ,   [type]
		$columns = ['custom_url'];
		$this->createIndex('url_rewrite', $columns);
		$columns = ['type'];
		$this->createIndex('url_rewrite', $columns);
 
    }

    public function down()
    {
        echo "m160608_061933_mongodb_url_write cannot be reverted.\n";

        return false;
    }
}
