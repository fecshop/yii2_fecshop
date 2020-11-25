<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appserver\modules\Catalog\block\reviewproduct;

//use fecshop\app\apphtml5\modules\Catalog\helpers\Review as ReviewHelper;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Add extends \yii\base\BaseObject
{
    protected $_add_captcha;
    /**
     * 为了可以使用rewriteMap，use 引入的文件统一采用下面的方式，通过Yii::mapGet()得到className和Object
     */
    protected $_reviewHelperName = '\fecshop\app\apphtml5\modules\Catalog\helpers\Review';
    protected $_reviewHelper;
    
    public function __construct()
    {
        /**
         * 通过Yii::mapGet() 得到重写后的class类名以及对象。Yii::mapGet是在文件@fecshop\yii\Yii.php中
         */
        list($this->_reviewHelperName,$this->_reviewHelper) = Yii::mapGet($this->_reviewHelperName);  
        $reviewHelper = $this->_reviewHelper;
        $reviewHelper::initReviewConfig();
    }
    
    /**
     * @return boolean , review页面是否开启验证码验证。
     */
    public function getAddCaptcha()
    {
        if (!$this->_add_captcha) {
            $appName = Yii::$service->helper->getAppName();
            $addCaptcha = Yii::$app->store->get($appName.'_catalog','review_add_captcha');
            // $reviewParam = Yii::$app->getModule('catalog')->params['review'];
            $this->_add_captcha = ($addCaptcha == Yii::$app->store->enable) ? true : false;
        }

        return $this->_add_captcha;
    }

    public function getLastData()
    {
        
        $product_id = Yii::$app->request->get('product_id');
        if (!$product_id) {
            
            $code = Yii::$service->helper->appserver->product_id_not_exist;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        
        $product = Yii::$service->product->getByPrimaryKey($product_id);
        if (!$product['spu']) {
            $code = Yii::$service->helper->appserver->product_not_active;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }

        $price_info = $this->getProductPriceInfo($product);
        $spu = $product['spu'];
        $image = $product['image'];
        $main_img = isset($image['main']['image']) ? $image['main']['image'] : '';
        $imgUrl = Yii::$service->product->image->getResize($main_img,[150,150],false);
        
        $product_name = Yii::$service->store->getStoreAttrVal($product['name'], 'name');
        $customer_name = '';
        if (!Yii::$app->user->isGuest) {
            $identity = Yii::$app->user->identity;
            $customer_name = $identity['firstname'].' '.$identity['lastname'];
        }
        $product = [
            'product_id' => $product_id,
            'spu' => $spu,
            'price_info' => $price_info,
            'imgUrl' => $imgUrl,
            'product_name' => $product_name,
        ];
        
        $code = Yii::$service->helper->appserver->status_success;
        $data = [
            'product'        => $product,
            'customer_name'  => $customer_name,
            'reviewCaptchaActive'    => $this->getAddCaptcha(),
        ];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
    }
    /**
     * @param $editForm | Array
     * @return boolean ，保存评论信息
     */
    public function saveReview($editForm)
    {
        if(Yii::$service->product->review->addReview($editForm)){
            $code = Yii::$service->helper->appserver->status_success;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
            return $responseData;
        }else{
            $code = Yii::$service->helper->appserver->product_save_review_fail;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
            return $responseData;
        }
    }
    /**
     * @param $product | String Or Object
     * 得到产品的价格信息
     */
    protected function getProductPriceInfo($product)
    {
        $price = $product['price'];
        $special_price = $product['special_price'];
        $special_from = $product['special_from'];
        $special_to = $product['special_to'];

        return Yii::$service->product->price->getCurrentCurrencyProductPriceInfo($price, $special_price, $special_from, $special_to);
    }
    // 废弃
    protected function getSpuData()
    {
        $spu = $this->_product['spu'];
        $filter = [
            'select'    => ['size'],
            'where'            => [
                ['spu' => $spu],
            ],
            'asArray' => true,
        ];
        $coll = Yii::$service->product->coll($filter);
        if (is_array($coll['coll']) && !empty($coll['coll'])) {
            foreach ($coll['coll'] as $one) {
                $spu = $one['spu'];
            }
        }
    }
}
