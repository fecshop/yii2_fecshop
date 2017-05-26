<?php

use yii\db\Migration;

class m160607_052627_log_product_view extends Migration
{
    public function up()
    {
		$sql1 = "
				CREATE TABLE `fecshop`.`log_product_view` (
				`id` INT( 25 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
				`date_time` DATETIME NULL ,
				`product_id` INT( 20 ) NULL ,
				`sku` VARCHAR( 100 ) NULL ,
				`image` VARCHAR( 255 ) NULL ,
				`name` VARCHAR( 255 ) NULL ,
				`user_id` INT NULL
				) ENGINE = InnoDB;
		";
		$this->execute($sql1);
		
		$sql2 = "
			ALTER TABLE `log_product_view` ADD INDEX (`user_id` ,  `date_time`  ) ;
		";
		$this->execute($sql2);
		
    }

    public function down()
    {
        echo "m160607_052627_log_product_view cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
