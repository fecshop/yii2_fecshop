<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appfront\modules\Checkout\block\onepage;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index
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
        $this->initCountry();
        $this->initState();
        $shippings = $this->getShippings();
        $last_cart_info = $this->getCartInfo(true, $this->_shipping_method, $this->_country, $this->_state);
        $this->breadcrumbs(Yii::$service->page->translate->__('Checkout Onepage'));
        return [
            'payments'                 => $this->getPayment(),
            'shippings'                => $shippings,
            'current_payment_method'   => $this->_payment_method,
            'cart_info'                => $last_cart_info,
            'currency_info'            => $currency_info,
            'address_view_file'        => $this->_address_view_file,
            'cart_address'             => $this->_address,
            'cart_address_id'          => $this->_address_id,
            'address_list'             => $this->_address_list,
            'country_select'           => $this->_countrySelect,
            //'state_select'		   => $this->_stateSelect,
            'state_html'               => $this->_stateHtml,
        ];
    }
    
    // 面包屑导航
    protected function breadcrumbs($name)
    {
        if (Yii::$app->controller->module->params['checkout_onepage_breadcrumbs']) {
            Yii::$service->page->breadcrumbs->addItems(['name' => $name]);
        } else {
            Yii::$service->page->breadcrumbs->active = false;
        }
    }
    
    /**
     * 初始化地址信息，首先从当前用户里面取值，然后从cart表中取数据覆盖
     * 1. 初始化 $this->_address，里面保存的各个地址信息。
     * 2. 如果是登录用户，而且.
     */
    public function initAddress()
    {
        //$this->_cart_address = Yii::$service->cart->quote->getCartAddress();

        $cart = Yii::$service->cart->quote->getCart();
        $address_id = $cart['customer_address_id'];

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
        $this->_address_list = Yii::$service->customer->address->currentAddressList();
        //var_dump($this->_address_list);
        // 如果购物车存在customer_address_id，而且用户地址中也存在customer_address_id
        // 则执行if{}内代码。
        if ($address_id && isset($this->_address_list[$address_id]) && !empty($this->_address_list[$address_id])) {
            $this->_address_id = $address_id;
            $this->_address_view_file = 'checkout/onepage/index/address_select.php';
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
        } elseif (is_array($this->_address_list) && !empty($this->_address_list)) {
            // 用户存在地址列表，但是，cart中没有customer_address_id
            // 这种情况下，从用户地址列表中取出来默认地址，然后设置成当前的地址。
            foreach ($this->_address_list as $adss_id => $info) {
                if ($info['is_default'] == 1) {
                    $this->_address_id = $adss_id;
                    $this->_address_view_file = 'checkout/onepage/index/address_select.php';
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
            $this->_address_view_file = 'checkout/onepage/index/address.php';
            // 从购物车里面取出来数据。 $_cartAddress
            //$cart_info = Yii::$service->cart->getCartInfo();
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
    public function getCartInfo($activeProduct, $shipping_method, $country, $state)
    {
        if (!$this->_cart_info) {
            $cart_info = Yii::$service->cart->getCartInfo($activeProduct, $shipping_method, $country, $state);
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
     * [
     *      'method'=> $method,
     *      'label' => $label,
     *      'name'  => $name,
     *      'cost'  => $symbol.$currentCurrencyCost,
     *      'check' => $check,
     *      'shipping_i' => $shipping_i,
     * ]
     * 得到所有的，有效shipping method数组。
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
                $checked_payment = 0;
                foreach ($paymentArr as $k => $v) {
                    if ($this->_payment_method == $k) {
                        $v['checked'] = true;
                        $checked_payment = 1;
                    }
                    $pArr[$k] = $v;
                }
                if (!$checked_payment) {
                    foreach ($paymentArr as $k => $v) {
                        $this->_payment_method = $k;
                        $pArr[$k]['checked'] = true;
                        break;
                    }
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

    /**
     * js函数 ajaxreflush() 执行后，就会执行这个函数
     * 在
     * 1.切换address list,
     * 2.取消coupon，
     * 3.切换国家和省市信息，
     * 4.更改货运方式等
     * 集中情况下，就会触发执行当前函数，
     * 该函数会根据传递的参数，重新计算shipping 和order 部分信息，返回
     * 给前端。
     * @proeprty Array，
     * @return json_encode(Array)，Array格式如下：
     *                                                   [
     *                                                   'status' 		=> 'success',
     *                                                   'shippingHtml' 	=> $shippingHtml,
     *                                                   'reviewOrderHtml' 	=> $reviewOrderHtml,
     *                                                   ]
     *                                                   返回给js后，js根据数据将信息更新到相应的部分。
     */
    public function ajaxUpdateOrderAndShipping()
    {
        $country = Yii::$app->request->get('country');
        $shipping_method = Yii::$app->request->get('shipping_method');
        $address_id = Yii::$app->request->get('address_id');
        $state = Yii::$app->request->get('state');
        $country = \Yii::$service->helper->htmlEncode($country);
        $shipping_method = \Yii::$service->helper->htmlEncode($shipping_method);
        $address_id = \Yii::$service->helper->htmlEncode($address_id);
        $state = \Yii::$service->helper->htmlEncode($state);
        if ($address_id) {
            $this->_address_id = $address_id;
            $addressModel = Yii::$service->customer->address->getByPrimaryKey($this->_address_id);
            if ($addressModel['country']) {
                $country = $addressModel['country'];
                $this->_country = $addressModel['country'];
            }
            if ($addressModel['state']) {
                $state = $addressModel['state'];
                $this->_state = $addressModel['state'];
            }
        } elseif ($country) {
            $this->_country = $country;
            if (!$state) {
                $state = '*';
            }
            $this->_state = $state;
        }
        if ($this->_country && $this->_state) {
            $shippings = $this->getShippings($shipping_method);
            $payments = $this->getPayment();
            /**
             * 下面是Fecshop的widget，通过一个一个数据数组+第一个view文件
             * 组合得到对应的html代码，返回给$shippingHtml
             * 由于fecshop多多模板系统，预先从高级别的模板路径中依次查找view文件，存在则使用该view文件.
             */
            $shippingView = [
                'view'    => 'checkout/onepage/index/shipping.php',
            ];
            $shippingParam = [
                'shippings' => $shippings,
            ];
            $shippingHtml = Yii::$service->page->widget->render($shippingView, $shippingParam);
            /**
             * 先通过item计算出来重量,得到运费 然后setShippingCost($shippingCost)，将当前根据传递参数计算
             * 出来的运费结果set到quote.shippingCost中，
             * 原因：这里是用户勾选切换国家地址进行的运费计算反馈给用户，但是该信息不更新到数据库，仅仅显示出来
             * 相应的费用给用户看，因此，shippingCost通过传递的参数计算出来设置到quote中，而不是通过数据库中保存
             * 的信息计算。
             */
            $quoteItem = Yii::$service->cart->quoteItem->getCartProductInfo();
            $product_weight = $quoteItem['product_weight'];
            // 计算运费。
            $avaiable_method = Yii::$service->shipping->getAvailableShippingMethods($country,$region,$product_weight);
            $shippingInfo = $avaiable_method[$shipping_method];
            $shippingCost = Yii::$service->shipping->getShippingCost($shipping_method, $shippingInfo, $product_weight, $country, $state);
            Yii::$service->cart->quote->setShippingCost($shippingCost);
            /**
             * 下面通过当前的货币，购物车信息等数组数据，+上view文件
             * 返回order部分的html内容。
             */
            // 得到当前货币
            $currency_info = Yii::$service->page->currency->getCurrencyInfo();
            $reviewOrderView = [
                'view'    => 'checkout/onepage/index/review_order.php',
            ];
            $cart_info = $this->getCartInfo(true, $shipping_method, $this->_country, $this->_state);

            $reviewOrderParam = [
                'cart_info' => $cart_info,
                'currency_info' => $currency_info,
            ];
            $reviewOrderHtml = Yii::$service->page->widget->render($reviewOrderView, $reviewOrderParam);
            echo json_encode([
                'status'        => 'success',
                'shippingHtml'    => $shippingHtml,
                'reviewOrderHtml'    => $reviewOrderHtml,
            ]);
            exit;
        }
    }
}
