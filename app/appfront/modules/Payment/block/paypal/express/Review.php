<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appfront\modules\Payment\block\paypal\express;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Review
{
    protected $_payment_method;
    protected $_shipping_method;
    protected $_address_view_file;
    protected $_address_id;
    protected $_address_list;
    protected $_country;
    protected $_state;
    protected $_stateHtml;
    protected $_cartAddress;
    protected $_cart_address;
    protected $_cart_info;

    public function getLastData()
    {
        $cartInfo = Yii::$service->cart->getCartInfo(true);

        if (!isset($cartInfo['products']) || !is_array($cartInfo['products']) || empty($cartInfo['products'])) {
            return Yii::$service->url->redirectByUrlKey('checkout/cart');
        }
        $currency_info = Yii::$service->page->currency->getCurrencyInfo();
        $this->initAddress();
        $this->expressReview(); // 通过接口得到paypal的地址，覆盖到网站的地址。
        $this->initCountry();
        $this->initState();
        $shippings = $this->getShippings();
        $last_cart_info = $this->getCartInfo(true, $this->_shipping_method, $this->_country, $this->_state);
        //echo $this->_address_view_file;exit;
        return [
            'payments'                    => $this->getPayment(),
            'shippings'                => $shippings,
            'current_payment_method'    => $this->_payment_method,
            'cart_info'                => $last_cart_info,
            'currency_info'            => $currency_info,
            'address_view_file'        => $this->_address_view_file,
            'cart_address'                => $this->_address,
            'cart_address_id'            => $this->_address_id,
            'address_list'                => $this->_address_list,
            'country_select'            => $this->_countrySelect,
            //'state_select'			=> $this->_stateSelect,
            'state_html'                => $this->_stateHtml,
        ];
    }

    /**
     * 初始化地址信息，首先从当前用户里面取值，然后从cart表中取数据覆盖
     * 1. 初始化 $this->_address，里面保存的各个地址信息。
     * 2. 如果是登录用户，而且.
     */
    public function initAddress()
    {
        $this->_address_list = Yii::$service->customer->address->currentAddressList();
        if (is_array($this->_address_list) && !empty($this->_address_list)) {
            // 用户存在地址列表，但是，cart中没有customer_address_id
            // 这种情况下，从用户地址列表中取出来默认地址，然后设置成当前的地址。
            foreach ($this->_address_list as $adss_id => $info) {
                if ($info['is_default'] == 1) {
                    $this->_address_id = $adss_id;
                    //$this->_address_view_file = 'checkout/onepage/index/address_select.php';
                    $addressModel = Yii::$service->customer->address->getByPrimaryKey($this->_address_id);
                    if ($addressModel['country']) {
                        $this->_country = $addressModel['country'];
                        $this->_address['country'] = $this->_country;
                    }
                    if ($addressModel['state']) {
                        $this->_state = $addressModel['state'];
                        $this->_address['state'] = $this->_state;
                    }
                    if ($addressModel['first_name']) {
                        $this->_address['first_name'] = $addressModel['first_name'];
                    }

                    if ($addressModel['last_name']) {
                        $this->_address['last_name'] = $addressModel['last_name'];
                    }

                    if ($addressModel['email']) {
                        $this->_address['email'] = $addressModel['email'];
                    }

                    if ($addressModel['telephone']) {
                        $this->_address['telephone'] = $addressModel['telephone'];
                    }

                    if ($addressModel['street1']) {
                        $this->_address['street1'] = $addressModel['street1'];
                    }
                    if ($addressModel['street2']) {
                        $this->_address['street2'] = $addressModel['street2'];
                    }
                    if ($addressModel['city']) {
                        $this->_address['city'] = $addressModel['city'];
                    }
                    if ($addressModel['zip']) {
                        $this->_address['zip'] = $addressModel['zip'];
                    }
                    break;
                }
            }
        } else {
            $cart = Yii::$service->cart->quote->getCart();
            $address_info = [];
            if (!Yii::$app->user->isGuest) {
                $identity = Yii::$app->user->identity;
                $address_info['email'] = $identity['email'];
                $address_info['first_name'] = $identity['firstname'];
                $address_info['last_name'] = $identity['lastname'];
            }
            if (isset($cart['customer_email']) && !empty($cart['customer_email'])) {
                $address_info['email'] = $cart['customer_email'];
            }

            if (isset($cart['customer_firstname']) && !empty($cart['customer_firstname'])) {
                $address_info['first_name'] = $cart['customer_firstname'];
            }

            if (isset($cart['customer_lastname']) && !empty($cart['customer_lastname'])) {
                $address_info['last_name'] = $cart['customer_lastname'];
            }

            if (isset($cart['customer_telephone']) && !empty($cart['customer_telephone'])) {
                $address_info['telephone'] = $cart['customer_telephone'];
            }

            if (isset($cart['customer_address_country']) && !empty($cart['customer_address_country'])) {
                $address_info['country'] = $cart['customer_address_country'];
                $this->_country = $address_info['country'];
            }

            if (isset($cart['customer_address_state']) && !empty($cart['customer_address_state'])) {
                $address_info['state'] = $cart['customer_address_state'];
            }

            if (isset($cart['customer_address_city']) && !empty($cart['customer_address_city'])) {
                $address_info['city'] = $cart['customer_address_city'];
            }

            if (isset($cart['customer_address_zip']) && !empty($cart['customer_address_zip'])) {
                $address_info['zip'] = $cart['customer_address_zip'];
            }

            if (isset($cart['customer_address_street1']) && !empty($cart['customer_address_street1'])) {
                $address_info['street1'] = $cart['customer_address_street1'];
            }

            if (isset($cart['customer_address_street2']) && !empty($cart['customer_address_street2'])) {
                $address_info['street2'] = $cart['customer_address_street2'];
            }
            $this->_address = $address_info;
        }
        if (!$this->_country) {
            $this->_country = Yii::$service->helper->country->getDefaultCountry();
            $this->_address['country'] = $this->_country;
        }
    }

    /**
     * 初始化国家下拉条。
     */
    public function initCountry()
    {
        $this->_countrySelect = Yii::$service->helper->country->getAllCountryOptions('', '', $this->_country);
    }

    /**
     * 初始化省市
     */
    public function initState($country = '')
    {
        $state = isset($this->_address['state']) ? $this->_address['state'] : '';
        if (!$country) {
            $country = $this->_country;
        }
        $stateHtml = Yii::$service->helper->country->getStateOptionsByContryCode($country, $state);
        if (!$stateHtml) {
            $stateHtml = '<input id="state" name="billing[state]" value="'.$state.'" title="State" class="address_state input-text" style="" type="text">';
        } else {
            $stateHtml = '<select id="address:state" class="address_state validate-select" title="State" name="billing[state]">
							<option value="">Please select region, state or province</option>'
                        .$stateHtml.'</select>';
        }
        $this->_stateHtml = $stateHtml;
    }

    /**
     * 当改变国家的时候，ajax获取省市信息.
     */
    public function ajaxChangecountry()
    {
        $country = Yii::$app->request->get('country');
        $country = \Yii::$service->helper->htmlEncode($country);
        $state = $this->initState($country);
        echo json_encode([
            'state' => $this->_stateHtml,
        ]);
        exit;
    }

    /**
     * @return $cart_info | Array
     *                    本函数为从数据库中得到购物车中的数据，然后结合产品表
     *                    在加入一些产品数据，最终补全所有需要的信息。
     */
    public function getCartInfo($shipping_method, $country, $state)
    {
        if (!$this->_cart_info) {
            $cart_info = Yii::$service->cart->getCartInfo(true, $shipping_method, $country, $state);
            if (isset($cart_info['products']) && is_array($cart_info['products'])) {
                foreach ($cart_info['products'] as $k=>$product_one) {
                    // 设置名字，得到当前store的语言名字。
                    $cart_info['products'][$k]['name'] = Yii::$service->store->getStoreAttrVal($product_one['product_name'], 'name');
                    // 设置图片
                    if (isset($product_one['product_image']['main']['image'])) {
                        $cart_info['products'][$k]['image'] = $product_one['product_image']['main']['image'];
                    }
                    // 产品的url
                    $cart_info['products'][$k]['url'] = Yii::$service->url->getUrl($product_one['product_url']);
                    $custom_option = isset($product_one['custom_option']) ? $product_one['custom_option'] : '';
                    $custom_option_sku = $product_one['custom_option_sku'];
                    // 将在产品页面选择的颜色尺码等属性显示出来。
                    $custom_option_info_arr = $this->getProductOptions($product_one, $custom_option_sku);
                    $cart_info['products'][$k]['custom_option_info'] = $custom_option_info_arr;
                    // 设置相应的custom option 对应的图片
                    $custom_option_image = isset($custom_option[$custom_option_sku]['image']) ? $custom_option[$custom_option_sku]['image'] : '';
                    if ($custom_option_image) {
                        $cart_info['products'][$k]['image'] = $custom_option_image;
                    }
                }
            }
            $this->_cart_info = $cart_info;
        }

        return $this->_cart_info;
    }

    /**
     * 将产品页面选择的颜色尺码等显示出来，包括custom option 和spu options部分的数据.
     */
    public function getProductOptions($product_one, $custom_option_sku)
    {
        $custom_option_info_arr = [];
        $custom_option = isset($product_one['custom_option']) ? $product_one['custom_option'] : '';
        $custom_option_sku = $product_one['custom_option_sku'];
        if (isset($custom_option[$custom_option_sku]) && !empty($custom_option[$custom_option_sku])) {
            $custom_option_info = $custom_option[$custom_option_sku];
            foreach ($custom_option_info as $attr=>$val) {
                if (!in_array($attr, ['qty', 'sku', 'price', 'image'])) {
                    $attr = str_replace('_', ' ', $attr);
                    $attr = ucfirst($attr);
                    $custom_option_info_arr[$attr] = $val;
                }
            }
        }

        $spu_options = $product_one['spu_options'];
        if (is_array($spu_options) && !empty($spu_options)) {
            foreach ($spu_options as $label => $val) {
                $custom_option_info_arr[$label] = $val;
            }
        }

        return $custom_option_info_arr;
    }

    /**
     * @param $current_shipping_method | String  当前选择的货运方式
     * @return Array，数据格式为：
     *                                    [
     *                                    'method'=> $method,
     *                                    'label' => $label,
     *                                    'name'  => $name,
     *                                    'cost'  => $symbol.$currentCurrencyCost,
     *                                    'check' => $check,
     *                                    'shipping_i' => $shipping_i,
     *                                    ]
     *                                    根据选择的货运方式，得到费用等信息。
     */
    public function getShippings($custom_shipping_method = '')
    {
        $country = $this->_country;
        if (!$this->_state) {
            $region = '*';
        } else {
            $region = $this->_state;
        }
        $cartProductInfo = Yii::$service->cart->quoteItem->getCartProductInfo();
        $product_weight = $cartProductInfo['product_weight'];
        $product_volume_weight = $cartProductInfo['product_volume_weight'];
        $product_final_weight = max($product_weight, $product_volume_weight);
        $cartShippingMethod = $this->_cart_info['shipping_method'];
        // 当前的货运方式
        $current_shipping_method = Yii::$service->shipping->getCurrentShippingMethod($custom_shipping_method, $cartShippingMethod, $country, $region, $product_final_weight);
        $this->_shipping_method = $current_shipping_method;
        // 得到所有，有效的shipping method
        $shippingArr = $this->getShippingArr($product_final_weight, $current_shipping_method, $country, $region = '*');
        
        return $shippingArr;
    }

    /**
     * @return 得到所有的支付方式
     *                                     在获取的同时，判断$this->_payment_method 是否存在，不存在则取
     *                                     第一个支付方式，作为$this->_payment_method的值。
     */
    public function getPayment()
    {
        $paymentArr = Yii::$service->payment->getStandardPaymentArr();
        $pArr = [];
        if (!$this->_payment_method) {
            if (isset($this->_cart_info['payment_method']) && !empty($this->_cart_info['payment_method'])) {
                $this->_payment_method = $this->_cart_info['payment_method'];
            }
            //echo $this->_payment_method;
            if (!$this->_payment_method) {
                $i = 0;
                foreach ($paymentArr as $k => $v) {
                    $i++;
                    if ($i == 1) {
                        $this->_payment_method = $k;
                        $v['checked'] = true;
                    }
                    $pArr[$k] = $v;
                }
            } else {
                foreach ($paymentArr as $k => $v) {
                    if ($this->_payment_method == $k) {
                        $v['checked'] = true;
                    }
                    $pArr[$k] = $v;
                }
                //var_dump($paymentArr);
            }
        }

        return $pArr;
    }

    /**
     * @param $weight | Float , 总量
     * @param $shipping_method | String  $shipping_method key
     * @param $country | String  国家
     * @return array ， 通过上面的三个参数，得到各个运费方式对应的运费等信息。
     */
    public function getShippingArr($weight, $current_shipping_method, $country, $region)
    {
        $available_shipping = Yii::$service->shipping->getAvailableShippingMethods($country, $region, $weight);
        $sr = '';
        $shipping_i = 1;
        $arr = [];
        if (is_array($available_shipping) && !empty($available_shipping)) {
            foreach ($available_shipping as $method=>$shipping) {
                $label = $shipping['label'];
                $name = $shipping['name'];
                // 得到运费的金额
                $cost = Yii::$service->shipping->getShippingCost($method, $shipping, $weight, $country, $region);
                $currentCurrencyCost = $cost['currCost'];
                $symbol = Yii::$service->page->currency->getCurrentSymbol();
                if ($current_shipping_method == $method) {
                    $checked = true;
                } else {
                    $checked = '';
                }
                $arr[] = [
                    'method'=> $method,
                    'label' => $label,
                    'name'  => $name,
                    'cost'  => $currentCurrencyCost,
                    'symbol' => $symbol,
                    'checked' => $checked,
                    'shipping_i' => $shipping_i,
                ];

                $shipping_i++;
            }
        }
        return $arr;
    }

    public function expressReview()
    {
        $getToken = Yii::$service->payment->paypal->getToken();
        $getPayerID = Yii::$service->payment->paypal->getPayerID();
        if (!$getToken) {
            Yii::$service->page->message->AddError('paypal express token is empty');

            return [];
        }
        if (!$getPayerID) {
            Yii::$service->page->message->AddError('paypal express PayerID is empty');

            return [];
        }

        $methodName_ = 'GetExpressCheckoutDetails';
        $nvpStr_ = Yii::$service->payment->paypal->getExpressAddressNvpStr();
        $expressCheckoutReturn = Yii::$service->payment->paypal->PPHttpPost5($methodName_, $nvpStr_);

        if (strtolower($expressCheckoutReturn['ACK']) == 'success') {
            $this->setValue($expressCheckoutReturn);
        }
    }

    /**
     * 初始化信息。
     */
    public function setValue($getExpressCheckoutReturn)
    {
        if ($getExpressCheckoutReturn['FIRSTNAME']) {
            $this->_address['first_name'] = $getExpressCheckoutReturn['FIRSTNAME'];
        }
        if ($getExpressCheckoutReturn['LASTNAME']) {
            $this->_address['last_name'] = $getExpressCheckoutReturn['LASTNAME'];
        }
        if ($getExpressCheckoutReturn['EMAIL']) {
            $this->_address['email'] = $getExpressCheckoutReturn['EMAIL'];
        }
        if ($getExpressCheckoutReturn['SHIPTOCOUNTRYCODE']) {
            $this->_address['country'] = $getExpressCheckoutReturn['SHIPTOCOUNTRYCODE'];
        }
        if ($getExpressCheckoutReturn['SHIPTOSTATE']) {
            $this->_address['state'] = $getExpressCheckoutReturn['SHIPTOSTATE'];
        }
        if ($getExpressCheckoutReturn['SHIPTOCITY']) {
            $this->_address['city'] = $getExpressCheckoutReturn['SHIPTOCITY'];
        }
        if ($getExpressCheckoutReturn['SHIPTOSTREET']) {
            $this->_address['street1'] = $getExpressCheckoutReturn['SHIPTOSTREET'];
        }
        if ($getExpressCheckoutReturn['SHIPTOSTREET2']) {
            $this->_address['street2'] = $getExpressCheckoutReturn['SHIPTOSTREET2'];
        }
        if ($getExpressCheckoutReturn['SHIPTOZIP']) {
            $this->_address['zip'] = $getExpressCheckoutReturn['SHIPTOZIP'];
        }
    }
}
