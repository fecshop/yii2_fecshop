<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Config\block\appfrontcatalog;

use fec\helpers\CUrl;
use fec\helpers\CRequest;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockEditInterface;
use fecshop\app\appadmin\modules\AppadminbaseBlockEdit;
use Yii;

/**
 * block cms\staticblock.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Manager extends AppadminbaseBlockEdit implements AppadminbaseBlockEditInterface
{
    public $_saveUrl;
    // 需要配置
    public $_key = 'appfront_catalog';
    public $_type;
    protected $_attrArr = [
        'category_breadcrumbs',
        'product_breadcrumbs',
        'category_filter_attr',
        'category_filter_category',
        'category_filter_price',
        'category_query_numPerPage',
        'category_query_priceRange',
        'category_productSpuShowOnlyOneSku',
        'product_small_img_width',
        'product_small_img_height',
        'product_middle_img_width',
        
        'productImgMagnifier',
        'review_add_captcha',
        'review_productPageReviewCount',
        'review_reviewPageReviewCount',
        'review_addReviewOnlyLogin',
        //'review_ifShowCurrentUserNoAuditReview',
        'review_filterByLang',
        'review_OnlyOrderedProduct',
        'review_MonthLimit',
        'favorite_addSuccessRedirectFavoriteList',
    ];
    public function init()
    {
        
         // 需要配置
        $this->_saveUrl = CUrl::getUrl('config/appfrontcatalog/managersave');
        $this->_editFormData = 'editFormData';
        $this->setService();
        $this->_param = CRequest::param();
        $this->_one = $this->_service->getByKey([
            'key' => $this->_key,
        ]);
        if ($this->_one['value']) {
            $this->_one['value'] = unserialize($this->_one['value']);
        }
    }
    
    
    
    // 传递给前端的数据 显示编辑form
    public function getLastData()
    {
        $id = ''; 
        if (isset($this->_one['id'])) {
           $id = $this->_one['id'];
        } 
        return [
            'id'            =>   $id, 
            'editBar'      => $this->getEditBar(),
            'textareas'   => $this->_textareas,
            'lang_attr'   => $this->_lang_attr,
            'saveUrl'     => $this->_saveUrl,
        ];
    }
    public function setService()
    {
        $this->_service = Yii::$service->storeBaseConfig;
    }
    public function getEditArr()
    {
        $deleteStatus = Yii::$service->customer->getStatusDeleted();
        $activeStatus = Yii::$service->customer->getStatusActive();
        
        
        return [
            // 需要配置
            [
                'label' => Yii::$service->page->translate->__('Category Show Breadcrumbs'),
                'name'  => 'category_breadcrumbs',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->enable => 'Yes',
                        Yii::$app->store->disable => 'No',
                    ],
                ],
                'remark' => '分类页面是否显示面包屑导航'
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Category Filter Attr'),
                'name' => 'category_filter_attr',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '注意：1.产品分类Service需要指定为Mongodb存储才生效(参看：services数据库配置)  2.做侧栏分类产品过滤的属性，必须是select（editSelect）类型的，其他的类型请不要用',
            ],
            
            [
                'label' => Yii::$service->page->translate->__('Category Filter Category'),
                'name'  => 'category_filter_category',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->enable => 'Yes',
                        Yii::$app->store->disable => 'No',
                    ],
                ],
                'remark' => '分类侧栏过滤部分，是否显示子分类列表'
            ],
            
            [
                'label' => Yii::$service->page->translate->__('Category Filter Price'),
                'name'  => 'category_filter_price',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->enable => 'Yes',
                        Yii::$app->store->disable => 'No',
                    ],
                ],
                'remark' => '分类侧栏过滤部分，是否显示价格过滤'
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Category NumPerPage'),
                'name' => 'category_query_numPerPage',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '产品显示个数的列举',
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Category Filter PriceRange'),
                'name' => 'category_query_priceRange',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '产品价格区间，英文逗号隔开，中间不要有空格',
            ],
            
            [
                'label' => Yii::$service->page->translate->__('Category SpuShowOnlyOneSku'),
                'name'  => 'category_productSpuShowOnlyOneSku',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->enable => 'Yes',
                        Yii::$app->store->disable => 'No',
                    ],
                ],
                'remark' => '分类产品列表，如果spu下的多个sku,Yes代表只显示一个sku（score最高的Sku）,No代表产品全部显示'
            ],
            [
                'label' => Yii::$service->page->translate->__('Product Breadcrumbs'),
                'name'  => 'product_breadcrumbs',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->enable => 'Yes',
                        Yii::$app->store->disable => 'No',
                    ],
                ],
                'remark' => '产品页面是否显示面包屑导航'
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Product Small Img Width'),
                'name' => 'product_small_img_width',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '产品橱窗图的宽度（px）',
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Product Small Img Height'),
                'name' => 'product_small_img_height',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '产品橱窗图的高度（px）',
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Product Middle Img Width'),
                'name' => 'product_middle_img_width',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '产品中等橱窗图的宽度（px）',
            ],
            
            
        
            [
                'label' => Yii::$service->page->translate->__('productImgMagnifier'),
                'name'  => 'productImgMagnifier',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->enable => 'Yes',
                        Yii::$app->store->disable => 'No',
                    ],
                ],
               'remark' =>'是否已放大镜的方式显示，如果否，则是内窥的方式查看',
            ],
            [
                'label' => Yii::$service->page->translate->__('Review Show Captcha'),
                'name'  => 'review_add_captcha',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->enable => 'Yes',
                        Yii::$app->store->disable => 'No',
                    ],
                ],
                'remark' => 'Review页面是否开启验证码验证',
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Review Rroduct Page ReviewCount'),
                'name' => 'review_productPageReviewCount',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '在产品页面显示的review的个数',
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Review Page ReviewCount'),
                'name' => 'review_reviewPageReviewCount',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '在review列表页面，显示的review的个数',
            ],
            
            [
                'label' => Yii::$service->page->translate->__('Review AddReviewOnlyLogin'),
                'name'  => 'review_addReviewOnlyLogin',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->enable => 'Yes',
                        Yii::$app->store->disable => 'No',
                    ],
                ],
                'remark' => '是否只有登录用户才有资格进行评论'
            ],
            /*
            [
                'label' => Yii::$service->page->translate->__('Review ShowCurrentUserNoAudit'),
                'name'  => 'review_ifShowCurrentUserNoAuditReview',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->enable => 'Yes',
                        Yii::$app->store->disable => 'No',
                    ],
                ],
                'remark' => '当前用户添加的评论，后台未审核的评论，是否显示？这个是通过ip来判断。'
            ],
            */
            [
                'label' => Yii::$service->page->translate->__('Review FilterByLang'),
                'name'  => 'review_filterByLang',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->enable => 'Yes',
                        Yii::$app->store->disable => 'No',
                    ],
                ],
                'remark' => '是否通过语言进行评论过滤？No代表显示所有语言的评论'
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Review MonthLimit'),
                'name' => 'review_MonthLimit',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '订单创建后，多久内可以进行评论，超过这个期限将不能评论产品（单位为月）, 当 reviewOnlyOrderedProduct 设置为true时有效',
            ],
            
             [
                'label' => Yii::$service->page->translate->__('Review OnlyOrderedProduct'),
                'name'  => 'review_OnlyOrderedProduct',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->enable => 'Yes',
                        Yii::$app->store->disable => 'No',
                    ],
                ],
                'remark' => ' Yes：代表用户购物过的产品才能评论，No：代表用户没有购买的产品也可以评论'
            ],
            
            [
                'label' => Yii::$service->page->translate->__('Favorite SuccessRedirectFavoriteList'),
                'name'  => 'favorite_addSuccessRedirectFavoriteList',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->enable => 'Yes',
                        Yii::$app->store->disable => 'No',
                    ],
                ],
                'remark' => '产品收藏成功后是否跳转到账户中心的收藏列表'
            ],
        ];
    }
    
    public function getArrParam(){
        $request_param = CRequest::param();
        $this->_param = $request_param[$this->_editFormData];
        $param = [];
        $attrVals = [];
        foreach($this->_param as $attr => $val) {
            if (in_array($attr, $this->_attrArr)) {
                $attrVals[$attr] = $val;
            } else {
                $param[$attr] = $val;
            }
        }
        $param['value'] = $attrVals;
        $param['key'] = $this->_key;
        
        return $param;
    }
    
    /**
     * save article data,  get rewrite url and save to article url key.
     */
    public function save()
    {
        /*
         * if attribute is date or date time , db storage format is int ,by frontend pass param is int ,
         * you must convert string datetime to time , use strtotime function.
         */
        // 设置 bdmin_user_id 为 当前的user_id
        $this->_service->saveConfig($this->getArrParam());
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
    
    
    
    public function getVal($name, $column){
        if (is_object($this->_one) && property_exists($this->_one, $name) && $this->_one[$name]) {
            
            return $this->_one[$name];
        }
        $content = $this->_one['value'];
        if (is_array($content) && !empty($content) && isset($content[$name])) {
            
            return $content[$name];
        }
        
        return '';
    }
}