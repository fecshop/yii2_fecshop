<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\config\block\appfrontstore;

use fec\helpers\CRequest;
use fec\helpers\CUrl;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockEditInterface;
use fecshop\app\appadmin\modules\AppadminbaseBlockEdit;
use Yii;

/**
 * block cms\staticblock.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Manageredit extends AppadminbaseBlockEdit implements AppadminbaseBlockEditInterface
{
    public $_saveUrl;

    public function init()
    {
        $this->_saveUrl = CUrl::getUrl('config/appfrontstore/managereditsave');
        parent::init();
    }

    // 传递给前端的数据 显示编辑form
    public function getLastData()
    {
        return [
            'editBar'      => $this->getEditBar(),
            'textareas'   => $this->_textareas,
            'lang_attr'   => $this->_lang_attr,
            'saveUrl'      => $this->_saveUrl,
        ];
    }

    public function setService()
    {
        $this->_service = Yii::$service->storeDomain;
    }

    public function getEditArr()
    {
        $allLangs = Yii::$service->fecshoplang->getAllLangName();
        $allLangArr = [];
        foreach ($allLangs as $k) {
            $allLangArr[$k] = $k;
        }
        $currencys = Yii::$service->page->currency->getCurrencys();
        $currencyArr = [];
        foreach ($currencys as $code => $info) {
            $currencyArr[$code] = $code;
        }
        
        
        return [
            [
                'label'  => Yii::$service->page->translate->__('Store Key'),
                'name' => 'key',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 1,
            ],
            [
                'label'  => Yii::$service->page->translate->__('Status'),
                'name' => 'status',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        1    => Yii::$service->page->translate->__('Enable'),
                        2    => Yii::$service->page->translate->__('Disable'),
                    ],
                ],
                'require' => 1,
                'default' => 1,
            ],
            [
                'label'  => Yii::$service->page->translate->__('Language'),
                'name' => 'lang',
                'display' => [
                    'type' => 'select',
                    'data' => $allLangArr,
                ],
                'require' => 1,
                'default' => 1,
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Language Name'),
                'name' => 'lang_name',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 1,
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Local Theme Dir'),
                'name' => 'local_theme_dir',
                'display' => [
                    'type' => 'inputString',
                ],
            ],
            [
                'label'  => Yii::$service->page->translate->__('Third Theme Dir'),
                'name' => 'third_theme_dir',
                'display' => [
                    'type' => 'inputString',
                ],
            ],
            [
                'label'  => Yii::$service->page->translate->__('Currency'),
                'name' => 'currency',
                'display' => [
                    'type' => 'select',
                    'data' => $currencyArr,
                ],
                'require' => 1,
            ],
            [
                'label'  => Yii::$service->page->translate->__('Mobile Enable'),
                'name' => 'mobile_enable',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        1    => Yii::$service->page->translate->__('Enable'),
                        2    => Yii::$service->page->translate->__('Disable'),
                    ],
                ],
                'require' => 1,
                'default' => 1,
            ],
            [
                'label'  => Yii::$service->page->translate->__('Mobile Condition'),
                'name' => 'mobile_condition',
                'display' => [
                    'type' => 'inputString',
                ],
                'default' => 'phone,tablet',
            ],
            [
                'label'  => Yii::$service->page->translate->__('Mobile Redirect Domain'),
                'name' => 'mobile_redirect_domain',
                'display' => [
                    'type' => 'inputString',
                ],
            ],
            [
                'label'  => Yii::$service->page->translate->__('Mobile Https Enable'),
                'name' => 'mobile_https_enable',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        1    => Yii::$service->page->translate->__('Enable'),
                        2    => Yii::$service->page->translate->__('Disable'),
                    ],
                ],
            ],
            [
                'label'  => Yii::$service->page->translate->__('Mobile Type'),
                'name' => 'mobile_type',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        'apphtml5'    => Yii::$service->page->translate->__('Apphtml5'),
                        'appserver'    => Yii::$service->page->translate->__('Appserver'),
                    ],
                ],
            ],
            [
                'label'  => Yii::$service->page->translate->__('FB Login AppId'),
                'name' => 'facebook_login_app_id',
                'display' => [
                    'type' => 'inputString',
                ],
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('FB Login AppSecret'),
                'name' => 'facebook_login_app_secret',
                'display' => [
                    'type' => 'inputString',
                ],
            ],
            [
                'label'  => Yii::$service->page->translate->__('Google Login Client Id'),
                'name' => 'google_login_client_id',
                'display' => [
                    'type' => 'inputString',
                ],
            ],
            [
                'label'  => Yii::$service->page->translate->__('Google Login Client Secret'),
                'name' => 'google_login_client_secret',
                'display' => [
                    'type' => 'inputString',
                ],
            ],
            [
                'label'  => Yii::$service->page->translate->__('Https Enable'),
                'name' => 'https_enable',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        1    => Yii::$service->page->translate->__('Enable'),
                        2    => Yii::$service->page->translate->__('Disable'),
                    ],
                ],
            ],
            [
                'label'  => Yii::$service->page->translate->__('Sitemap Dir'),
                'name' => 'sitemap_dir',
                'display' => [
                    'type' => 'inputString',
                ],
            ],
            
        ];
    }

    /**
     * save article data,  get rewrite url and save to article url key.
     */
    public function save()
    {
        $request_param = CRequest::param();
        $this->_param = $request_param[$this->_editFormData];
        /*
         * if attribute is date or date time , db storage format is int ,by frontend pass param is int ,
         * you must convert string datetime to time , use strtotime function.
         */
        $this->_param['app_name'] = 'appfront';
        $this->_service->save($this->_param);
        $errors = Yii::$service->helper->errors->get();
        if (!$errors) {
            echo  json_encode([
                'statusCode' => '200',
                'message'    => Yii::$service->page->translate->__('Save Success'),
            ]);
            exit;
        } else {
            echo  json_encode([
                'statusCode' => '300',
                'message'    => $errors,
            ]);
            exit;
        }
    }

    // 批量删除
    public function delete()
    {
        $ids = '';
        if ($id = CRequest::param($this->_primaryKey)) {
            $ids = $id;
        } elseif ($ids = CRequest::param($this->_primaryKey.'s')) {
            $ids = explode(',', $ids);
        }
        $this->_service->remove($ids);
        $errors = Yii::$service->helper->errors->get();
        if (!$errors) {
            echo  json_encode([
                'statusCode' => '200',
                'message'    => Yii::$service->page->translate->__('Remove Success') ,
            ]);
            exit;
        } else {
            echo  json_encode([
                'statusCode' => '300',
                'message'    => $errors,
            ]);
            exit;
        }
    }
}
