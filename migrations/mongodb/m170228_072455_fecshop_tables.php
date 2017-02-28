<?php

class m170228_072455_fecshop_tables extends \yii\mongodb\Migration
{
    public function up()
    {
		\fecshop\models\mongodb\Product::create_index();
		
		\fecshop\models\mongodb\cms\Article::create_index();
		\fecshop\models\mongodb\cms\StaticBlock::create_index();
		
		\fecshop\models\mongodb\customer\Newsletter::create_index();
		
		\fecshop\models\mongodb\product\Favorite::create_index();
		\fecshop\models\mongodb\product\Review::create_index();
		
		\fecshop\models\mongodb\url\UrlRewrite::create_index();
		
    }

    public function down()
    {
        echo "m170228_072455_fecshop_tables cannot be reverted.\n";

        return false;
    }
}
