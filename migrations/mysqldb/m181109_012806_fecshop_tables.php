<?php

use yii\db\Migration;

/**
 * Class m181109_012806_fecshop_tables
 */
class m181109_012806_fecshop_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $arr = [

            "update `admin_url_key` set name = 'Account List' where id = 1",

            "update `admin_url_key` set name = 'Main Page' where id = 2",

            "update `admin_url_key` set name = 'My Account' where id = 3",

            "update `admin_url_key` set name = 'Account Edit' where id = 4",

            "update `admin_url_key` set name = 'Account Save' where id = 5",

            "update `admin_url_key` set name = 'Account Delete' where id = 6",

            "update `admin_url_key` set name = 'Role List' where id = 7",

            "update `admin_url_key` set name = 'Role Edit' where id = 8",

            "update `admin_url_key` set name = 'Role Save' where id = 9",

            "update `admin_url_key` set name = 'Role Delete' where id = 10",

            "update `admin_url_key` set name = 'Resource List' where id = 11",

            "update `admin_url_key` set name = 'Resource Edit' where id = 12",

            "update `admin_url_key` set name = 'Resource Save' where id = 13",

            "update `admin_url_key` set name = 'Resource Delete' where id = 14",

            "update `admin_url_key` set name = 'Log List' where id = 15",

            "update `admin_url_key` set name = 'Log Statistics' where id = 16",

            "update `admin_url_key` set name = 'System Cache' where id = 17",

            "update `admin_url_key` set name = 'Config List' where id = 18",

            "update `admin_url_key` set name = 'Config Edit' where id = 19",

            "update `admin_url_key` set name = 'Config Save' where id = 20",

            "update `admin_url_key` set name = 'Config Delete' where id = 21",

            "update `admin_url_key` set name = 'Handle List' where id = 22",

            "update `admin_url_key` set name = 'Handle Info' where id = 23",

            "update `admin_url_key` set name = 'Product List' where id = 24",

            "update `admin_url_key` set name = 'Product Edit' where id = 25",

            "update `admin_url_key` set name = 'Product Category' where id = 26",

            "update `admin_url_key` set name = 'Upload Product Image' where id = 27",

            "update `admin_url_key` set name = 'Product Save' where id = 28",

            "update `admin_url_key` set name = 'Product Delete' where id = 29",

            "update `admin_url_key` set name = 'Product Review List' where id = 30",

            "update `admin_url_key` set name = 'Product Review Edit' where id = 31",

            "update `admin_url_key` set name = 'Product Review Approved' where id = 32",

            "update `admin_url_key` set name = 'Product Review Reject' where id = 33",

            "update `admin_url_key` set name = 'Product Review Delete' where id = 34",

            "update `admin_url_key` set name = 'Product Search' where id = 35",

            "update `admin_url_key` set name = 'Product Favorite' where id = 36",

            "update `admin_url_key` set name = 'Categoru View' where id = 37",

            "update `admin_url_key` set name = 'Catalog Product List' where id = 38",

            "update `admin_url_key` set name = 'Url Rewrite List' where id = 39",

            "update `admin_url_key` set name = 'Order List' where id = 40",

            "update `admin_url_key` set name = 'Order Info' where id = 41",

            "update `admin_url_key` set name = 'Order Export' where id = 42",

            "update `admin_url_key` set name = 'Order Save' where id = 43",

            "update `admin_url_key` set name = 'Coupon List' where id = 44",

            "update `admin_url_key` set name = 'Coupon Edit' where id = 45",

            "update `admin_url_key` set name = 'Coupon Save' where id = 46",

            "update `admin_url_key` set name = 'Coupon Delete' where id = 47",

            "update `admin_url_key` set name = 'Account List' where id = 48",

            "update `admin_url_key` set name = 'Account Edit' where id = 49",

            "update `admin_url_key` set name = 'Account Save' where id = 50",

            "update `admin_url_key` set name = 'Account Delete' where id = 51",

            "update `admin_url_key` set name = 'Newsletter List' where id = 52",

            "update `admin_url_key` set name = 'Page List' where id = 53",

            "update `admin_url_key` set name = 'Page Edit' where id = 54",

            "update `admin_url_key` set name = 'Page Save' where id = 55",

            "update `admin_url_key` set name = 'Page Delete' where id = 56",

            "update `admin_url_key` set name = 'Static Block List' where id = 57",

            "update `admin_url_key` set name = 'Static Block Edit' where id = 58",

            "update `admin_url_key` set name = 'Static Block Save' where id = 59",

            "update `admin_url_key` set name = 'Static Block Delete' where id = 60",

            "update `admin_url_key` set name = 'Category Delete' where id = 61",

            "update `admin_url_key` set name = 'Category Save' where id = 62",

            "update `admin_url_key` set name = 'View All Product(default: only view Self-created products)' where id = 63",

            "update `admin_url_key` set name = 'Edit All Product(default: only Edit Self-created products)' where id = 64",

            "update `admin_url_key` set name = 'Delete All Product(default: only Delete Self-created products)' where id = 66",

            "update `admin_url_key` set name = 'Save All Product(default: only Save Self-created products)' where id = 70",
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
        echo "m181109_012806_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181109_012806_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
