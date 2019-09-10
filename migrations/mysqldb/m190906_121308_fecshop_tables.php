<?php

use yii\db\Migration;

/**
 * Class m190906_121308_fecshop_tables
 */
class m190906_121308_fecshop_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // 语言格式的更新
        $sql = '
                UPDATE `store_base_config` SET `value` = \'a:8:{i:0;a:3:{s:9:"lang_name";s:5:"en-US";s:9:"lang_code";s:2:"en";s:13:"search_engine";s:11:"mysqlSearch";}i:1;a:3:{s:9:"lang_name";s:5:"zh-CN";s:9:"lang_code";s:2:"zh";s:13:"search_engine";s:11:"mysqlSearch";}i:2;a:3:{s:9:"lang_name";s:5:"fr-FR";s:9:"lang_code";s:2:"fr";s:13:"search_engine";s:11:"mysqlSearch";}i:3;a:3:{s:9:"lang_name";s:5:"de-DE";s:9:"lang_code";s:2:"de";s:13:"search_engine";s:11:"mysqlSearch";}i:4;a:3:{s:9:"lang_name";s:5:"es-ES";s:9:"lang_code";s:2:"es";s:13:"search_engine";s:11:"mysqlSearch";}i:5;a:3:{s:9:"lang_name";s:5:"pt-PT";s:9:"lang_code";s:2:"pt";s:13:"search_engine";s:11:"mysqlSearch";}i:6;a:3:{s:9:"lang_name";s:5:"ru-RU";s:9:"lang_code";s:2:"ru";s:13:"search_engine";s:11:"mysqlSearch";}i:7;a:3:{s:9:"lang_name";s:5:"it-IT";s:9:"lang_code";s:2:"it";s:13:"search_engine";s:11:"mysqlSearch";}}\' WHERE `key` = "mutil_lang";
            ';
        $this->execute($sql);
        // 1
        //$sql = " 
        //    UPDATE `store_domain` SET `lang` = 'en-US' WHERE `lang` = 'en_US';
        //";
        //$this->execute($sql);
        // 2
        //$sql = " 
        //    UPDATE `store_domain` SET `lang` = 'fr-FR' WHERE `lang` = 'fr_FR';
        //";
        //$this->execute($sql);
        // 3
        //$sql = " 
        //    UPDATE `store_domain` SET `lang` = 'es-ES' WHERE `lang` = 'es_ES';
        //";
        //$this->execute($sql);
        // 4
        //$sql = " 
        //    UPDATE `store_domain` SET `lang` = 'zh-CN' WHERE `lang` = 'zh_CN';
       // ";
        //$this->execute($sql);
        // 5
        //$sql = " 
        //    UPDATE `store_domain` SET `lang` = 'it-IT' WHERE `lang` = 'it_IT';
        //";
        //$this->execute($sql);
        // 6
        //$sql = " 
         //   UPDATE `store_domain` SET `lang` = 'de-DE' WHERE `lang` = 'de_DE';
        //";
        //$this->execute($sql);
        // appserver_store
        $sql = '
            UPDATE `store_base_config` SET `value` = \'a:9:{s:3:"key";s:36:"fecshop.appserver.fancyecommerce.com";s:4:"lang";s:5:"en-US";s:9:"lang_name";s:7:"English";s:8:"currency";s:3:"USD";s:12:"https_enable";s:1:"1";s:21:"facebook_login_app_id";s:16:"1108618299786621";s:25:"facebook_login_app_secret";s:32:"420b56da4f4664a4d1065a1d31e5ec73";s:22:"google_login_client_id";s:72:"380372364773-qdj1seag9bh2n0pgrhcv2r5uoc58ltp3.apps.googleusercontent.com";s:26:"google_login_client_secret";s:24:"ei8RaoCDoAlIeh1nHYm0rrwO";}\' WHERE `key` ="appserver_store";
        ';
        $this->execute($sql);
        //  appserver_store_lang
        $sql = '
            UPDATE `store_base_config` SET `value` = \'a:4:{i:0;a:3:{s:12:"languageName";s:9:"Français";s:4:"code";s:2:"fr";s:8:"language";s:5:"fr-FR";}i:1;a:3:{s:12:"languageName";s:7:"English";s:4:"code";s:2:"en";s:8:"language";s:5:"en-US";}i:2;a:3:{s:12:"languageName";s:8:"Español";s:4:"code";s:2:"es";s:8:"language";s:5:"es-ES";}i:3;a:3:{s:12:"languageName";s:6:"中文";s:4:"code";s:2:"zh";s:8:"language";s:5:"zh-CN";}}\' WHERE `key` = "appserver_store_lang";
        ';
        $this->execute($sql);
        
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190906_121308_fecshop_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190906_121308_fecshop_tables cannot be reverted.\n";

        return false;
    }
    */
}
