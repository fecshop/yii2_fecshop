<?php

use yii\db\Migration;

/**
 * Class m190809_154516_fecshop_tables
 */
class m190809_154516_fecshop_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // 1
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Catalog Product Upload', 'catalog_product_upload_manager', 1, '/catalog/productupload/manager', 1564621499, 1564621499, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 2
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Catalog Product Upload Post', 'catalog_product_upload_manager', 2, '/catalog/productupload/managerupload', 1564621554, 1564621554, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");
        
        // 3
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Payment Paypal Manager', 'config_payment_manager', 1, '/config/paymentpaypal/manager', 1564717843, 1564720693, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 4
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Payment Paypal Save', 'config_payment_manager', 2, '/config/paymentpaypal/managersave', 1564717900, 1564720710, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 5
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Payment Alipay Manager', 'config_payment_manager', 3, '/config/paymentalipay/manager', 1564725881, 1564725881, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 6
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Payment Alipay Save', 'config_payment_manager', 4, '/config/paymentalipay/managersave', 1564725913, 1564725913, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 7
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Payment Wxpay Manager', 'config_payment_manager', 5, '/config/paymentwxpay/manager', 1564725957, 1564725957, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 8
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Payment Wxpay Save', 'config_payment_manager', 6, '/config/paymentwxpay/managersave', 1564725978, 1564725978, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 9
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Appfront Base Manager', 'config_appfront_manager', 1, '/config/appfrontbase/manager', 1564883961, 1565356703, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 10
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Appfront Base Save', 'config_appfront_manager', 2, '/config/appfrontbase/managersave', 1564883985, 1565356715, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");
        
        
        // 11
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Apphtml5 Base Manager', 'config_apphtml5_manager', 1, '/config/apphtml5base/manager', 1564886672, 1565363546, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 12
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Apphtml5 Base Save', 'config_apphtml5_manager', 3, '/config/apphtml5base/managersave', 1564886787, 1565363555, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 13
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Appfront Payment Manager', 'config_appfront_manager', 40, '/config/appfrontpayment/manager', 1564919890, 1565356845, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 14
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Appfront Payment Save', 'config_appfront_manager', 41, '/config/appfrontpayment/managersave', 1564919923, 1565356854, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 15
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Apphtml5 Payment Manager', 'config_apphtml5_manager', 40, '/config/apphtml5payment/manager', 1564930186, 1565363705, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 16
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Apphtml5 Payment Save', 'config_apphtml5_manager', 41, '/config/apphtml5payment/managersave', 1564930216, 1565363712, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 17
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Appserver Payment Manager', 'config_appserver_manager', 41, '/config/appserverpayment/manager', 1564930260, 1565363893, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 18
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Appserver Payment Save', 'config_appserver_manager', 42, '/config/appserverpayment/managersave', 1564930286, 1565363898, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 19
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Appserver Base Manager', 'config_appserver_manager', 1, '/config/appserverbase/manager', 1564965073, 1564965073, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 20
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Appserver Base Save', 'config_appserver_manager', 2, '/config/appserverbase/managersave', 1564965100, 1564965100, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");
        
        // 21
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Appserver Home Manager', 'config_appserver_manager', 10, '/config/appserverhome/manager', 1564967602, 1564967602, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 22
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Appserver Home Save', 'config_appserver_manager', 11, '/config/appserverhome/managersave', 1564967628, 1564967628, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 23
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Appfront Home Manager', 'config_appfront_manager', 5, '/config/appfronthome/manager', 1564967667, 1565356748, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");
        
        // 24
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Appfront Home Save', 'config_appfront_manager', 6, '/config/appfronthome/managersave', 1564967721, 1565356775, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 25
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Apphtml5 Home Manager', 'config_apphtml5_manager', 5, '/config/apphtml5home/manager', 1564967787, 1565363583, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 26
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Apphtml5 Home Save', 'config_apphtml5_manager', 6, '/config/apphtml5home/managersave', 1564967813, 1565363590, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 27
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Category Sort Manager', 'catalog_category_manager', 11, '/config/categorysort/manager', 1564983401, 1564983446, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 28
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Category Sort Save', 'catalog_category_manager', 12, '/config/categorysort/managersave', 1564983435, 1564983435, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 29
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Appfront Catalog Manager', 'config_appfront_manager', 20, '/config/appfrontcatalog/manager', 1565056176, 1565056176, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 30
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Appfront Catalog Save', 'config_appfront_manager', 21, '/config/appfrontcatalog/managersave', 1565056218, 1565056218, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");
        
        // 31
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Apphtml5 Catalog Manager', 'config_apphtml5_manager', 24, '/config/apphtml5catalog/manager', 1565056253, 1565056253, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 32
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Apphtml5 Catalog Save', 'config_apphtml5_manager', 21, '/config/apphtml5catalog/managersave', 1565056218, 1565056218, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 33
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Appserver Catalog Manager', 'config_appserver_manager', 24, '/config/appservercatalog/manager', 1565056369, 1565056369, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 34
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Appserver Catalog Save', 'config_appserver_manager', 25, '/config/appservercatalog/managersave', 1565056395, 1565056395, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 35
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Product Param Manager', 'catalog_product_info_manager', 30, '/config/product/manager', 1565077385, 1565077419, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 36
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Product Param Save', 'catalog_product_info_manager', 31, '/config/product/managersave', 1565077410, 1565077410, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 37
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Cart Manager', 'sales_cart_manager', 1, '/config/cart/manager', 1565164201, 1565164337, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 38
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Cart Save', 'sales_cart_manager', 2, '/config/cart/managersave', 1565164226, 1565164226, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 39
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Order Manager', 'sales_order_manager', 11, '/config/order/manager', 1565164419, 1565164419, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 40
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Order Save', 'sales_order_manager', 12, '/config/order/managersave', 1565164440, 1565164440, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        
        // 41
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Email Manager', 'config_base_manager', 25, '/config/email/manager', 1565245220, 1565356118, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 42
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Email Save', 'config_base_manager', 26, '/config/email/managersave', 1565245244, 1565356127, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 43
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Appfront Account Manager', 'config_appfront_manager', 33, '/config/appfrontaccount/manager', 1565271401, 1565271401, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 44
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Appfront Account Save', 'config_appfront_manager', 34, '/config/appfrontaccount/managersave', 1565271430, 1565271430, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 45
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Apphtml5 Account Manager', 'config_apphtml5_manager', 33, '/config/apphtml5account/manager', 1565276208, 1565276208, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 46
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Apphtml5 Account Save', 'config_apphtml5_manager', 34, '/config/apphtml5account/managersave', 1565276236, 1565276236, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 47
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Appserver Account Manager', 'config_appserver_manager', 33, '/config/appserveraccount/manager', 1565276265, 1565276265, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");

        // 48
        
        $this->execute("INSERT INTO `admin_url_key` (`name`, `tag`, `tag_sort_order`, `url_key`, `created_at`, `updated_at`, `can_delete`) VALUES ('Config Appserver Account Save', 'config_appserver_manager', 34, '/config/appserveraccount/managersave', 1565276293, 1565276293, 1)");
        
        $lastInsertId = $this->db->getLastInsertID() ;
        
        $this->execute("INSERT INTO `admin_role_url_key` (`role_id`, `url_key_id`, `created_at`, `updated_at`) VALUES (4, " . $lastInsertId . ", 1541129239, 1541129239)");
        
        $sql = 'INSERT INTO `store_base_config` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
            (12, \'payment_paypal\', \'a:4:{s:10:"paypal_env";s:6:"sanbox";s:14:"paypal_account";s:35:"zqy234api1-facilitator_api1.126.com";s:15:"paypal_password";s:16:"HF4TNTTXUD6YQREH";s:16:"paypal_signature";s:56:"An5ns1Kso7MWUdW4ErQKJJJ4qi4-ANB-xrkMmTHpTszFaUx2v4EHqknV";}\', 1564726240, 1564918978),
            (13, \'payment_alipay\', \'a:7:{s:10:"alipay_env";s:6:"sanbox";s:6:"app_id";s:16:"2016080500172713";s:9:"seller_id";s:16:"2088102170055546";s:15:"rsa_private_key";s:1592:"MIIEpAIBAAKCAQEApIw+Hsk65Z+mieDsEiTkhtf7ZNBgks83DLUDb1yh2d/HDB0s9zHFzsgQGny0kUTM0fJ43h7WydyUG9Kuv4fxD5iVfM2xkUYW5bvfTXVaj5LLj8rTKL+nnFybzzM5rewqh2u1Gzd7BbpOnhMn4Y+7JyyaWXsnRFBxIrmRAqQJVlVUG4RclLHfplFkMVcEMzoRda2UV54oQDMg8ZxignCqxgIKr7bpwpgdpdqZArHtmyEjhQfIblCLDjVk0rKxGsaz+ATYVt3eQozdyNEuKFRhy0VGmwmdQYhQFbge7SS6bVqXZHsq2fNZ6hMJ2XNOZajFm5jXMksnaX85PzdJ58HFewIDAQABAoIBAAn/c27Pb0Kwdp/+CJn5n+EJkn7HonaJHKErBnBnwnXIgQGdbDQA1DICOehCF36UHZXME8f7O7W8L0uZe4Crs9vsu3h/zwAysAV5atH8BWqf0rqD6lyZeIepoNXwGNsWdGcSBkkHD/SDI2+7Xjr4TrjMnvw83V/rO1SOzd7JNMAICj6NZ2tteIqQCn+BriEEawRDimSAWvVaCbwnbCDF8y40MxZ4K6picBQ0gsbC6eQuXRqzB6CoFBkQsXGtK0VXvlJXVmKRzRqPxjD6Cer21tF1CDryVedSWKsdwEXvOdO8LdPZpnmQMvwyTuhM0V9L3rif4spIK9ML3lZLzM47rpECgYEA2XzyRUEni4jKmWcE3oSZjCvp5BJwi6DSRkAphGTwoW/8oTCJhx1B43Qusxv0bUwGzN/KlRHwgNRerQ9xqWMYnIIfBJLOqASunB8eHMBDN+zC6TnUKOu43CpZ+fGVVm2VUbWLHr5h93AOBSQhtvvegbEk9hbNRCCbcY6jbZZmgkUCgYEAwa9v5Bk8q0obGonDUd5LZkHBt7mfT12cUPkfBClz8/tpv7rirCg5I4XaQHerEo+iCOpn3iIl37ix6V7LcspjJuJwpTn0OzugO7MzEyRi0zAqkNAB1voeJL/hl08rHVkA9fZ2AVuOhUvG2A1pqB8BjY9AW1/2W/EXH16qCKzfhL8CgYEAjK/QoJAHHrH8LMOBWNf547y8bfanqwr7OspikOwi5Ktmhna5YBfC+Xm8g8w/jzww4fKaP1f9dbjrDZQB+IrL7uIVYoX8/J8avI88kWilktWrN+daoKXrTTBwR8jIy8HTZ6nCNr787G0mBJlc3duMEeUffbk+SyW0p/6XJVq3MOkCgYEAhXqPJOZTjkRS63YXels1ITKd+yzcYojDynX07xxWQcV4+l4kCrrprdZ4M8eEyRTdeUF59XcZHNYfHhJrKR/bNxgEw4luDEgqRBpaT43a4WonW4dOTUYv8eme4XT45I/K/rcsWgEr9ibj0U9lCizcGB+qHY7DrFc5NTA7BCGHJOcCgYAN82UigyX4qyqpQDofP/fQOybE2QJuG4pG3x3k/nMxCYm6DAcDS9WyRIAlNwOLXDFLICPa3SlaFjC4A0hLh1CU0465Bau+/q8Avs2/Hz1SMoeqyKf8Sq3RyCFFSb0Zsq26Tr8BtyRjHfFRDiZe5O9H7lOCGqiQEgUuAE9aCgCYVA==";s:14:"rsa_public_key";s:392:"MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAt5egD1BQCK5fCQXKsgWh+VFj9zanV9cdwVmM/MOQ/zrwMBHMIRO0IdJMft351iXtyACKVX+noK1qzkiVOdg3MxLjbGoMDKR+/1PDxoxtWSVUJBywoYHH/Dh7TCi5GWGasOlXV4qWi0e5Yfa2x/Wi0cxqx76aY5izXEyabHAvWgTWNv121ZRNhl4qcuoWZYiMIQpTst6hEhRn/isUMgdtLRQ1a06q+qOkLmJ99vq8cqbfduAdOuhzbZNWqLV76CSc0meurlVtDoIn5kVAZdzjNTA2rlqSCgs/OZxaL8s/qrIynhLoB6U6i0fj4RsIsbrvoSnrPWo98rsM0RrlU8fpdwIDAQAB";s:23:"alipay_aop_sdk_work_dir";s:4:"/tmp";s:23:"alipay_aop_sdk_dev_mode";s:1:"1";}\', 1564752512, 1564931510),
            (14, \'payment_wxpay\', \'a:6:{s:21:"wechat_service_app_id";s:18:"wxb508f3849c440445";s:25:"wechat_service_app_secret";s:32:"696f03956777e0a172f4adb5afe8d805";s:19:"wechat_micro_app_id";s:18:"wxedc77529191bc54f";s:23:"wechat_micro_app_secret";s:32:"3b97c3fb4edeb0ca25e5386d753347a9";s:15:"merchant_mch_id";s:10:"1537420921";s:12:"merchant_key";s:32:"8934e7d15453e97507ef794cf7b0519e";}\', 1564755650, 1564918986),
            (15, \'appfront_base\', \'a:3:{s:14:"assetForceCopy";s:1:"1";s:10:"js_version";s:1:"6";s:11:"css_version";s:1:"6";}\', 1564884131, 1564918098),
            (16, \'apphtml5_base\', \'a:3:{s:14:"assetForceCopy";s:1:"1";s:10:"js_version";s:1:"6";s:11:"css_version";s:1:"6";}\', 1564886968, 1564918104),
            (17, \'appfront_payment\', \'a:7:{s:11:"check_money";s:1:"1";s:15:"paypal_standard";s:1:"1";s:14:"paypal_express";s:1:"1";s:15:"alipay_standard";s:1:"1";s:14:"wxpay_standard";s:1:"1";s:11:"wxpay_jsapi";s:1:"2";s:8:"wxpay_h5";s:1:"2";}\', 1564920063, 1564926070),
            (18, \'apphtml5_payment\', \'a:7:{s:11:"check_money";s:1:"1";s:15:"paypal_standard";s:1:"1";s:14:"paypal_express";s:1:"1";s:15:"alipay_standard";s:1:"1";s:14:"wxpay_standard";s:1:"2";s:11:"wxpay_jsapi";s:1:"1";s:8:"wxpay_h5";s:1:"1";}\', 1564930355, 1564931302),
            (19, \'appserver_payment\', \'a:7:{s:11:"check_money";s:1:"1";s:15:"paypal_standard";s:1:"1";s:14:"paypal_express";s:1:"1";s:15:"alipay_standard";s:1:"1";s:14:"wxpay_standard";s:1:"2";s:11:"wxpay_jsapi";s:1:"1";s:8:"wxpay_h5";s:1:"1";}\', 1564930640, 1564931474),
            (20, \'appserver_base\', \'a:2:{s:29:"customer_access_token_timeout";s:5:"86400";s:39:"customer_access_token_update_time_limit";s:3:"600";}\', 1564966594, 1564966594),
            (21, \'appfront_home\', \'a:5:{s:15:"best_seller_sku";s:79:"p10001-kahaki-xl,sk10003-001,sk10005,sk1000-khak,sk0003,men0003,men0002,men0001";s:16:"best_feature_sku";s:82:"men0003,men0002,men0001,computer001-xinghao2-cpu3,22221,sk10005,sk1000-khak,222212";s:10:"meta_title";a:8:{s:13:"meta_title_en";s:20:"Fecmall Home Page En";s:13:"meta_title_zh";s:20:"Fecshop Home Page Zh";s:13:"meta_title_fr";s:20:"Fecshop Home Page FR";s:13:"meta_title_de";s:20:"Fecshop Home Page DE";s:13:"meta_title_es";s:20:"Fecshop Home Page ES";s:13:"meta_title_pt";s:20:"Fecshop Home Page PT";s:13:"meta_title_ru";s:20:"Fecshop Home Page RU";s:13:"meta_title_it";s:20:"Fecshop Home Page IT";}s:13:"meta_keywords";a:8:{s:16:"meta_keywords_en";s:36:"Fecmall , Fashion E-Commerce Mall En";s:16:"meta_keywords_zh";s:36:"Fecshop , Fashion E-Commerce Shop ZH";s:16:"meta_keywords_fr";s:36:"Fecshop , Fashion E-Commerce Shop FR";s:16:"meta_keywords_de";s:36:"Fecshop , Fashion E-Commerce Shop DE";s:16:"meta_keywords_es";s:36:"Fecshop , Fashion E-Commerce Shop ES";s:16:"meta_keywords_pt";s:36:"Fecshop , Fashion E-Commerce Shop PT";s:16:"meta_keywords_ru";s:36:"Fecshop , Fashion E-Commerce Shop RU";s:16:"meta_keywords_it";s:36:"Fecshop , Fashion E-Commerce Shop IT";}s:16:"meta_description";a:8:{s:19:"meta_description_en";s:51:"Fashion E-Commerce Shop , Base On Yii2 Framework En";s:19:"meta_description_zh";s:51:"Fashion E-Commerce Shop , Base On Yii2 Framework ZH";s:19:"meta_description_fr";s:51:"Fashion E-Commerce Shop , Base On Yii2 Framework FR";s:19:"meta_description_de";s:51:"Fashion E-Commerce Shop , Base On Yii2 Framework DE";s:19:"meta_description_es";s:51:"Fashion E-Commerce Shop , Base On Yii2 Framework ES";s:19:"meta_description_pt";s:51:"Fashion E-Commerce Shop , Base On Yii2 Framework PT";s:19:"meta_description_ru";s:51:"Fashion E-Commerce Shop , Base On Yii2 Framework RU";s:19:"meta_description_it";s:51:"Fashion E-Commerce Shop , Base On Yii2 Framework IT";}}\', 1564974730, 1565230071),
            (22, \'apphtml5_home\', \'a:4:{s:16:"best_feature_sku";s:85:"p10001-kahaki-xl,32332,432432,sk2001-blue-zo,sk0008,sk0004,sk0003,sk0002,sk1000-black";s:10:"meta_title";a:8:{s:13:"meta_title_en";s:20:"Fecmall Home Page En";s:13:"meta_title_zh";s:20:"Fecshop Home Page Zh";s:13:"meta_title_fr";s:20:"Fecshop Home Page FR";s:13:"meta_title_de";s:20:"Fecshop Home Page DE";s:13:"meta_title_es";s:20:"Fecshop Home Page ES";s:13:"meta_title_pt";s:20:"Fecshop Home Page PT";s:13:"meta_title_ru";s:20:"Fecshop Home Page RU";s:13:"meta_title_it";s:20:"Fecshop Home Page IT";}s:13:"meta_keywords";a:8:{s:16:"meta_keywords_en";s:36:"Fecmall , Fashion E-Commerce Shop EN";s:16:"meta_keywords_zh";s:36:"Fecshop , Fashion E-Commerce Shop ZH";s:16:"meta_keywords_fr";s:36:"Fecshop , Fashion E-Commerce Shop FR";s:16:"meta_keywords_de";s:36:"Fecshop , Fashion E-Commerce Shop DE";s:16:"meta_keywords_es";s:36:"Fecshop , Fashion E-Commerce Shop ES";s:16:"meta_keywords_pt";s:36:"Fecshop , Fashion E-Commerce Shop PT";s:16:"meta_keywords_ru";s:36:"Fecshop , Fashion E-Commerce Shop RU";s:16:"meta_keywords_it";s:36:"Fecshop , Fashion E-Commerce Shop IT";}s:16:"meta_description";a:8:{s:19:"meta_description_en";s:51:"Fashion E-Commerce Shop , Base On Yii2 Framework EN";s:19:"meta_description_zh";s:51:"Fashion E-Commerce Shop , Base On Yii2 Framework ZH";s:19:"meta_description_fr";s:51:"Fashion E-Commerce Shop , Base On Yii2 Framework FR";s:19:"meta_description_de";s:51:"Fashion E-Commerce Shop , Base On Yii2 Framework DE";s:19:"meta_description_es";s:51:"Fashion E-Commerce Shop , Base On Yii2 Framework ES";s:19:"meta_description_pt";s:51:"Fashion E-Commerce Shop , Base On Yii2 Framework PT";s:19:"meta_description_ru";s:51:"Fashion E-Commerce Shop , Base On Yii2 Framework RU";s:19:"meta_description_it";s:51:"Fashion E-Commerce Shop , Base On Yii2 Framework IT";}}\', 1564976603, 1565230082),
            (23, \'appserver_home\', \'a:1:{s:16:"best_feature_sku";s:85:"p10001-kahaki-xl,32332,432432,sk2001-blue-zo,sk0008,sk0004,sk0003,sk0002,sk1000-black";}\', 1564981483, 1564981483),
            (24, \'category_sort\', \'a:7:{i:0;a:4:{s:8:"sort_key";s:3:"hot";s:10:"sort_label";s:3:"Hot";s:15:"sort_db_columns";s:5:"score";s:14:"sort_direction";s:4:"desc";}i:1;a:4:{s:8:"sort_key";s:12:"review_count";s:10:"sort_label";s:6:"Review";s:15:"sort_db_columns";s:12:"review_count";s:14:"sort_direction";s:4:"desc";}i:2;a:4:{s:8:"sort_key";s:14:"favorite_count";s:10:"sort_label";s:8:"Favorite";s:15:"sort_db_columns";s:14:"favorite_count";s:14:"sort_direction";s:4:"desc";}i:3;a:4:{s:8:"sort_key";s:3:"new";s:10:"sort_label";s:3:"New";s:15:"sort_db_columns";s:10:"created_at";s:14:"sort_direction";s:4:"desc";}i:4;a:4:{s:8:"sort_key";s:5:"stock";s:10:"sort_label";s:5:"Stock";s:15:"sort_db_columns";s:3:"qty";s:14:"sort_direction";s:4:"desc";}i:5;a:4:{s:8:"sort_key";s:11:"low-to-high";s:10:"sort_label";s:13:"$ Low to High";s:15:"sort_db_columns";s:11:"final_price";s:14:"sort_direction";s:3:"asc";}i:6;a:4:{s:8:"sort_key";s:11:"high-to-low";s:10:"sort_label";s:13:"$ High to Low";s:15:"sort_db_columns";s:11:"final_price";s:14:"sort_direction";s:4:"desc";}}\', 1564984002, 1564992725),
            (25, \'appfront_catalog\', \'a:20:{s:20:"category_breadcrumbs";s:1:"1";s:20:"category_filter_attr";s:10:"color,size";s:24:"category_filter_category";s:1:"1";s:21:"category_filter_price";s:1:"1";s:25:"category_query_numPerPage";s:11:"12,30,60,90";s:25:"category_query_priceRange";s:68:"0-10,10-20,20-30,30-50,50-100,100-150,150-300,300-500,500-1000,1000-";s:33:"category_productSpuShowOnlyOneSku";s:1:"2";s:19:"product_breadcrumbs";s:1:"2";s:23:"product_small_img_width";s:2:"80";s:24:"product_small_img_height";s:3:"110";s:24:"product_middle_img_width";s:3:"400";s:19:"productImgMagnifier";s:1:"2";s:18:"review_add_captcha";s:1:"1";s:29:"review_productPageReviewCount";s:2:"10";s:28:"review_reviewPageReviewCount";s:2:"20";s:25:"review_addReviewOnlyLogin";s:1:"1";s:19:"review_filterByLang";s:1:"2";s:17:"review_MonthLimit";s:1:"6";s:25:"review_OnlyOrderedProduct";s:1:"1";s:39:"favorite_addSuccessRedirectFavoriteList";s:1:"2";}\', 1565058247, 1565076351),
            (26, \'product\', \'a:6:{s:11:"imageFloder";s:21:"media/catalog/product";s:14:"maxUploadMSize";s:1:"5";s:19:"pngCompressionLevel";s:1:"8";s:20:"jpegCompressionLevel";s:2:"80";s:33:"ifSpecialGtPriceFinalPriceEqPrice";s:1:"1";s:13:"zeroInventory";s:1:"2";}\', 1565080955, 1565081739),
            (27, \'apphtml5_catalog\', \'a:20:{s:20:"category_breadcrumbs";s:1:"2";s:20:"category_filter_attr";s:10:"color,size";s:24:"category_filter_category";s:1:"1";s:21:"category_filter_price";s:1:"1";s:25:"category_query_numPerPage";s:11:"12,30,60,90";s:25:"category_query_priceRange";s:68:"0-10,10-20,20-30,30-50,50-100,100-150,150-300,300-500,500-1000,1000-";s:33:"category_productSpuShowOnlyOneSku";s:1:"2";s:19:"product_breadcrumbs";s:1:"2";s:23:"product_small_img_width";s:2:"80";s:24:"product_small_img_height";s:3:"110";s:24:"product_middle_img_width";s:3:"400";s:19:"productImgMagnifier";s:1:"2";s:18:"review_add_captcha";s:1:"1";s:29:"review_productPageReviewCount";s:2:"10";s:28:"review_reviewPageReviewCount";s:2:"20";s:25:"review_addReviewOnlyLogin";s:1:"1";s:19:"review_filterByLang";s:1:"2";s:17:"review_MonthLimit";s:1:"6";s:25:"review_OnlyOrderedProduct";s:1:"1";s:39:"favorite_addSuccessRedirectFavoriteList";s:1:"2";}\', 1565093698, 1565147466),
            (28, \'appserver_catalog\', \'a:15:{s:20:"category_filter_attr";s:10:"color,size";s:24:"category_filter_category";s:1:"1";s:21:"category_filter_price";s:1:"1";s:25:"category_query_numPerPage";s:11:"12,30,60,90";s:25:"category_query_priceRange";s:68:"0-10,10-20,20-30,30-50,50-100,100-150,150-300,300-500,500-1000,1000-";s:33:"category_productSpuShowOnlyOneSku";s:1:"2";s:19:"product_breadcrumbs";s:1:"2";s:24:"product_middle_img_width";s:3:"400";s:18:"review_add_captcha";s:1:"2";s:29:"review_productPageReviewCount";s:2:"10";s:28:"review_reviewPageReviewCount";s:2:"20";s:25:"review_addReviewOnlyLogin";s:1:"1";s:19:"review_filterByLang";s:1:"2";s:17:"review_MonthLimit";s:1:"6";s:25:"review_OnlyOrderedProduct";s:1:"1";}\', 1565093708, 1565163140),
            (29, \'cart\', \'a:2:{s:20:"addToCartCheckSkuQty";s:1:"2";s:17:"maxCountAddToCart";s:3:"100";}\', 1565166740, 1565172102),
            (30, \'order\', \'a:7:{s:12:"increment_id";s:10:"1100000000";s:19:"requiredAddressAttr";s:67:"first_name,email,telephone,street1,country,city,state,zip";s:24:"orderProductSaleInMonths";s:1:"3";s:34:"minuteBeforeThatReturnPendingStock";s:2:"60";s:32:"orderCountThatReturnPendingStock";s:2:"30";s:20:"orderRemarkStrMaxLen";s:4:"1000";s:10:"guestOrder";s:1:"2";}\', 1565174313, 1565233817),
            (31, \'email\', \'a:33:{s:13:"baseStoreName";s:7:"FecMall";s:17:"baseContactsPhone";s:11:"186xxxxxxxx";s:17:"baseContactsEmail";s:17:"2358269014@qq.com";s:17:"default_smtp_host";s:11:"smtp.qq.com";s:21:"default_smtp_username";s:17:"2420577683@qq.com";s:21:"default_smtp_password";s:16:"shrlkrtqsokwebjh";s:17:"default_smtp_port";s:3:"587";s:23:"default_smtp_encryption";s:3:"tls";s:14:"registerEnable";s:1:"1";s:34:"registerAccountIsNeedEnableByEmail";s:1:"2";s:32:"registerAccountEnableTokenExpire";s:5:"86400";s:14:"registerWidget";s:61:"fecshop\\\\services\\\\email\\\\widgets\\\\customer\\\\account\\\\register\\\\Body";s:16:"registerViewPath";s:55:"@fecshop/services/email/views/customer/account/register";s:11:"loginEnable";s:1:"2";s:11:"loginWidget";s:58:"fecshop\\\\services\\\\email\\\\widgets\\\\customer\\\\account\\\\login\\\\Body";s:13:"loginViewPath";s:52:"@fecshop/services/email/views/customer/account/login";s:20:"forgotPasswordEnable";s:1:"1";s:20:"forgotPasswordWidget";s:67:"fecshop\\\\services\\\\email\\\\widgets\\\\customer\\\\account\\\\forgotpassword\\\\Body";s:22:"forgotPasswordViewPath";s:61:"@fecshop/services/email/views/customer/account/forgotpassword";s:30:"forgotPasswordResetTokenExpire";s:5:"86400";s:14:"contactsEnable";s:1:"1";s:14:"contactsWidget";s:53:"fecshop\\\\services\\\\email\\\\widgets\\\\customer\\\\contacts\\\\Body";s:16:"contactsViewPath";s:47:"@fecshop/services/email/views/customer/contacts";s:20:"contactsEmailAddress";s:17:"2358269014@qq.com";s:16:"newsletterEnable";s:1:"1";s:16:"newsletterWidget";s:55:"fecshop\\\\services\\\\email\\\\widgets\\\\customer\\\\newsletter\\\\Body";s:18:"newsletterViewPath";s:49:"@fecshop/services/email/views/customer/newsletter";s:16:"orderGuestEnable";s:1:"1";s:16:"orderGuestWidget";s:48:"fecshop\\\\services\\\\email\\\\widgets\\\\order\\\\create\\\\Body";s:18:"orderGuestViewPath";s:48:"@fecshop/services/email/views/order/create/guest";s:16:"orderLoginEnable";s:1:"1";s:16:"orderLoginWidget";s:48:"fecshop\\\\services\\\\email\\\\widgets\\\\order\\\\create\\\\Body";s:18:"orderLoginViewPath";s:50:"@fecshop/services/email/views/order/create/logined";}\', 1565250095, 1565258341),
            (32, \'appfront_account\', \'a:10:{s:19:"registerPageCaptcha";s:1:"1";s:24:"registerSuccessAutoLogin";s:1:"1";s:29:"registerSuccessRedirectUrlKey";s:16:"customer/account";s:16:"loginPageCaptcha";s:1:"2";s:21:"forgotPasswordCaptcha";s:1:"1";s:15:"contactsCaptcha";s:1:"1";s:15:"min_name_length";s:1:"1";s:15:"max_name_length";s:2:"30";s:15:"min_pass_length";s:1:"6";s:15:"max_pass_length";s:2:"30";}\', 1565272703, 1565273822),
            (33, \'appserver_account\', \'a:9:{s:19:"registerPageCaptcha";s:1:"1";s:24:"registerSuccessAutoLogin";s:1:"1";s:16:"loginPageCaptcha";s:1:"2";s:21:"forgotPasswordCaptcha";s:1:"1";s:15:"contactsCaptcha";s:1:"1";s:15:"min_name_length";s:1:"1";s:15:"max_name_length";s:2:"30";s:15:"min_pass_length";s:1:"6";s:15:"max_pass_length";s:2:"30";}\', 1565277344, 1565279181),
            (34, \'apphtml5_account\', \'a:10:{s:19:"registerPageCaptcha";s:1:"1";s:24:"registerSuccessAutoLogin";s:1:"1";s:29:"registerSuccessRedirectUrlKey";s:16:"customer/account";s:16:"loginPageCaptcha";s:1:"2";s:21:"forgotPasswordCaptcha";s:1:"1";s:15:"contactsCaptcha";s:1:"1";s:15:"min_name_length";s:1:"1";s:15:"max_name_length";s:2:"30";s:15:"min_pass_length";s:1:"6";s:15:"max_pass_length";s:2:"30";}\', 1565277378, 1565277378);
        ';
        $this->execute($sql);
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190809_154516_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190809_154516_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
