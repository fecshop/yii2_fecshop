<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\payment;

use fecshop\services\Service;
use Yii;

/**
 * Payment Paypal services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 * 本部分，主要是为个人paypal账户，通过订单信息，得到发送给paypal的url信息
 */
class PaypalCart extends Service
{
    public $businessAccount ; //= 'sb-upoe05899977@business.example.com';
    public $endpoint;
    
    public function init()
    {
        parent::init();
        $this->businessAccount = Yii::$service->payment->paypal->getPaypayLoginAccount();
        $sandbox = Yii::$service->payment->paypal->isSandoxEnv();
        $this->endpoint    = $sandbox ? 'https://www.sandbox.paypal.com/cgi-bin/webscr?test_ipn=1&' : 'https://www.paypal.com/cgi-bin/webscr?';
		
    }
    /**
     * @param $orderInfo | array , 订单信息
     * @param $return_url | string, 支付完成后，返回商城的url地址
     * @param $cancel_url | string，支付取消后，返回商城的url地址
     * @param $ipn_url | string，paypal发送异步支付消息IPN的地址。
     * 得到跳转到paypal的url，url中带有订单等信息
     */
    public function getRequestUrl( $orderInfo, $return_url='', $cancel_url='', $ipn_url='' ) {
		$express_payment_method = Yii::$service->payment->paypal->express_payment_method;
        if (!$return_url) {
            $return_url = Yii::$service->url->getUrl('payment/success');
        }
        if (!$cancel_url) {
            $cancel_url = Yii::$service->payment->getExpressCancelUrl($express_payment_method);
        }
        if (!$ipn_url) {
            $ipn_url = Yii::$service->payment->getExpressIpnUrl($express_payment_method);
        }
        
        $paypal_args       = $this->get_paypal_args( $orderInfo, $return_url, $cancel_url, $ipn_url);
		$paypal_args['bn'] = 'FecThemes_Cart'; // Append  PayPal Partner Attribution ID. This should not be overridden for this gateway.

		return $this->endpoint . http_build_query( $paypal_args, '', '&' );
	}
    /**
     * @param $orderInfo | array , 订单信息
     * @param $return_url | string, 支付完成后，返回商城的url地址
     * @param $cancel_url | string，支付取消后，返回商城的url地址
     * @param $ipn_url | string，paypal发送异步支付消息IPN的地址。
     * 得到跳转到paypal的url，url中带有订单等信息
     */
    protected function get_paypal_args( $orderInfo, $return_url, $cancel_url, $ipn_url ) 
    {
        return array_merge(
			array(
				'cmd'           => '_cart',
				'business'      => $this->businessAccount,
				'no_note'       => 1,
				'currency_code' => Yii::$service->page->currency->getCurrentCurrency(),
				'charset'       => 'utf-8',
				'rm'            => Yii::$app->getRequest()->getIsSecureConnection() ? 2 : 1,
				'upload'        => 1,
                'order' => $orderInfo['increment_id'],
                'order_id'  => $orderInfo['order_id'],
                'notify_url'    => $ipn_url,
				'return'        => $return_url,
				'cancel_return' => $cancel_url ,
				'page_style'    => '',
				'image_url'     => '',
				'paymentaction' => 'sale',
				'invoice'       => $this->limit_length( $orderInfo['increment_id'], 127 ),
				'custom'        => json_encode(
					[
						'order_id'  => $orderInfo['order_id'],
						'order_key' => $orderInfo['increment_id'],
					]
				),
				
				'first_name'    => $this->limit_length( $orderInfo['customer_firstname'], 32 ),
                
				'last_name'     => $this->limit_length( $orderInfo['customer_lastname'], 64 ),
				'address1'      => $this->limit_length( $orderInfo['customer_address_street1'], 100 ),
				'address2'      => $this->limit_length( $orderInfo['customer_address_street2'], 100 ),
				'city'          => $this->limit_length( $orderInfo['customer_address_city'], 40 ),
				'state'         => $orderInfo['customer_address_state_name'],
				'zip'           => $this->limit_length( $orderInfo['customer_address_zip'], 32 ),
				'country'       => $this->limit_length( $orderInfo['customer_address_country'], 2 ),
				'email'         => $this->limit_length( $orderInfo['customer_email'] ),
			),
			$this->get_phone_number_args( $orderInfo ),
			$this->get_shipping_args( $orderInfo ),
            $this->get_line_item_args( $orderInfo )
		);
	}
    /**
     * @param $orderInfo | array , 订单信息
     * 加入订单产品喜喜，物流，优惠券等信息
     */
    protected function get_line_item_args( $orderInfo) {
        $nvp_array = [];
        $i = 1;
        foreach ($orderInfo['products'] as $item) {
            $nvp_array['quantity_'.$i]     = $item['qty'];
            $nvp_array['amount_'.$i]     = Yii::$service->helper->format->numberFormat($item['price']);
            $nvp_array['item_name_'.$i]    = $item['name'];
            $nvp_array['item_number_'.$i] = '';
            $i++;
        }
        
        $nvp_array['tax_cart'] = 0;
        $nvp_array['shipping_1'] = Yii::$service->helper->format->numberFormat($orderInfo['shipping_total']);
        $nvp_array['discount_amount_cart'] = Yii::$service->helper->format->numberFormat($orderInfo['subtotal_with_discount']);
        
        return $nvp_array;
	}
    
    protected function get_phone_number_args( $orderInfo ) 
    {
		$phone_number = $orderInfo['customer_telephone'];
        $phone_number = preg_replace( '/[^\d+]/', '', $phone_number );
        
		if ( in_array( $orderInfo['customer_address_country'], array( 'US', 'CA' ), true ) ) {
			$phone_number = ltrim( $phone_number, '+1' );
			$phone_args   = array(
				'night_phone_a' => substr( $phone_number, 0, 3 ),
				'night_phone_b' => substr( $phone_number, 3, 3 ),
				'night_phone_c' => substr( $phone_number, 6, 4 ),
			);
		} else {
			$calling_code = Yii::$service->helper->country->getCountryCallingCode($orderInfo['customer_address_country']);
			$calling_code = is_array( $calling_code ) ? $calling_code[0] : $calling_code;

			if ( $calling_code ) {
				$phone_number = str_replace( $calling_code, '', preg_replace( '/^0/', '', $orderInfo['customer_telephone'] ) );
			}

			$phone_args = array(
				'night_phone_a' => $calling_code,
				'night_phone_b' => $phone_number,
			);
		}
		return $phone_args;
	}
    
    /**
	 * Get shipping args for paypal request.
	 *
	 * @param  WC_Order $order Order object.
	 * @return array
	 */
	protected function get_shipping_args( $orderInfo ) 
    {
		$shipping_args = array();
		
        $shipping_args['address_override'] = 1;
        $shipping_args['no_shipping']      = 0;
        // If we are sending shipping, send shipping address instead of billing.
        $shipping_args['first_name'] = $this->limit_length( $orderInfo['customer_firstname'], 32 );
        $shipping_args['last_name']  = $this->limit_length( $orderInfo['customer_lastname'], 64 );
        $shipping_args['address1']   = $this->limit_length( $orderInfo['customer_address_street1'], 100 );
        $shipping_args['address2']   = $this->limit_length( $orderInfo['customer_address_street2'], 100 );
        $shipping_args['city']       = $this->limit_length( $orderInfo['customer_address_city'], 40 );
        $shipping_args['state']      = $orderInfo['customer_address_state_name'];
        $shipping_args['country']    = $this->limit_length( $orderInfo['customer_address_country'], 2 );
        $shipping_args['zip']        = $this->limit_length( $orderInfo['customer_address_zip'], 32 );
    
		
		return $shipping_args;
	}
    
    /**
	 * Limit length of an arg.
	 *
	 * @param  string  $string Argument to limit.
	 * @param  integer $limit Limit size in characters.
	 * @return string
	 */
	protected function limit_length( $string, $limit = 127 ) {
		$str_limit = $limit - 3;
		if ( function_exists( 'mb_strimwidth' ) ) {
			if ( mb_strlen( $string ) > $limit ) {
				$string = mb_strimwidth( $string, 0, $str_limit ) . '...';
			}
		} else {
			if ( strlen( $string ) > $limit ) {
				$string = substr( $string, 0, $str_limit ) . '...';
			}
		}
		return $string;
	}
    
    
    /*
    https://www.paypal.com/cgi-bin/webscr?cmd=_cart&business=firstleius@aol.com&no_note=1&
        currency_code=GBP&charset=utf-8&rm=2&upload=1&
        return=https://www.rehairtoupee.com/checkout/order-received/25244/?key=wc_order_SIu33gUoi81N1
        &utm_nooverride=1&
        cancel_return=https://www.rehairtoupee.com/cart/?cancel_order=true
        &order=wc_order_SIu33gUoi81N1&order_id=25244&redirect&_wpnonce=8cfd415d12&page_style=&image_url=&paymentaction=sale&invoice=WC-25244&custom={"order_id":25244,"order_key":"wc_order_SIu33gUoi81N1"}&notify_url=https://www.rehairtoupee.com/wc-api/WC_Gateway_Paypal/&first_name=firstname&last_name=lastname&address1=street1&address2=street2&city=长沙市&state=Beijing / 北京&zip=666666&country=CN&email=235826901466@qq.com&night_phone_a=+86&night_phone_b=18666666666&no_shipping=1&tax_cart=0.00&item_name_1=Daisy Bag Sonia by Sonia Rykiel&quantity_1=2&amount_1=29&item_number_1=&bn=WooThemes_Cart
        
        
        
    https://www.sandbox.paypal.com/cgi-bin/webscr?test_ipn=1&cmd=_cart&business=sb-upoe05899977%40business.example.com&no_note=1&currency_code=GBP&charset=utf-8&rm=2&upload=1&return=https%3A%2F%2Fwww.rehairtoupee.com%2Fcheckout%2Forder-received%2F25246%2F%3Fkey%3Dwc_order_UJ9NsWy2EyRd2%26utm_nooverride%3D1&cancel_return=https%3A%2F%2Fwww.rehairtoupee.com%2Fcart%2F%3Fcancel_order%3Dtrue%26order%3Dwc_order_UJ9NsWy2EyRd2%26order_id%3D25246%26redirect%26_wpnonce%3D8cfd415d12&page_style=&image_url=&paymentaction=sale&invoice=WC-25246&custom=%7B%22order_id%22%3A25246%2C%22order_key%22%3A%22wc_order_UJ9NsWy2EyRd2%22%7D&notify_url=https%3A%2F%2Fwww.rehairtoupee.com%2Fwc-api%2FWC_Gateway_Paypal%2F&
    first_name=firstname&last_name=lastname&address1=street1&address2=street2&
    city=%E9%95%BF%E6%B2%99%E5%B8%82&state=Beijing+%2F+%E5%8C%97%E4%BA%AC&
    zip=666666&country=CN&email=235826901466%40qq.com&night_phone_a=%2B86&
    night_phone_b=18666666666&address_override=0
    &no_shipping=0&tax_cart=0.00&
    item_name_1=Beyond+Top+NLY+Trend&quantity_1=1&
    amount_1=29&item_number_1=&
    item_name_2=Daisy+Bag+Sonia+by+Sonia+Rykiel&quantity_2=1
    &amount_2=29&item_number_2=&bn=WooThemes_Cart"
result: "success"
    */

    
    /*
    ["mc_gross"]=>
  string(5) "60.00"
  ["invoice"]=>
  string(8) "Fec-6667"
  ["protection_eligibility"]=>
  string(8) "Eligible"
  ["address_status"]=>
  string(9) "confirmed"
  ["item_number1"]=>
  string(0) ""
  ["payer_id"]=>
  string(13) "5R5HHQXW64AF4"
  ["tax"]=>
  string(4) "0.00"
  ["address_street"]=>
  string(16) "street1
street2"
  ["payment_date"]=>
  string(25) "00:37:47 Jun 10, 2021 PDT"
  ["payment_status"]=>
  string(9) "Completed"
  ["charset"]=>
  string(6) "gb2312"
  ["address_zip"]=>
  string(6) "666666"
  ["mc_shipping"]=>
  string(4) "0.00"
  ["first_name"]=>
  string(4) "John"
  ["mc_fee"]=>
  string(4) "2.34"
  ["address_country_code"]=>
  string(2) "CN"
  ["address_name"]=>
  string(18) "firstname lastname"
  ["notify_version"]=>
  string(3) "3.9"
  ["custom"]=>
  string(72) "{&quot;order_id&quot;:6667,&quot;order_key&quot;:&quot;1100006667&quot;}"
  ["payer_status"]=>
  string(8) "verified"
  ["business"]=>
  string(36) "sb-upoe05899977@business.example.com"
  ["address_country"]=>
  string(5) "China"
  ["num_cart_items"]=>
  string(1) "1"
  ["address_city"]=>
  string(14) "��ɳ��"
  ["verify_sign"]=>
  string(56) "A8ouwv9nDVYx3U3sUKfMnr0NCxsYA.e8G6D.jlroxuS-HomuM6KEnxjT"
  ["payer_email"]=>
  string(36) "sb-z8uug5884150@personal.example.com"
  ["mc_shipping1"]=>
  string(4) "0.00"
  ["txn_id"]=>
  string(17) "3KW86164PF790120B"
  ["payment_type"]=>
  string(7) "instant"
  ["last_name"]=>
  string(3) "Doe"
  ["address_state"]=>
  string(11) "���ʡ"
  ["item_name1"]=>
  string(17) "test computer1111"
  ["receiver_email"]=>
  string(36) "sb-upoe05899977@business.example.com"
  ["payment_fee"]=>
  string(4) "2.34"
  ["shipping_discount"]=>
  string(4) "0.00"
  ["quantity1"]=>
  string(1) "2"
  ["insurance_amount"]=>
  string(4) "0.00"
  ["receiver_id"]=>
  string(13) "F9JNMDD4HBTHG"
  ["txn_type"]=>
  string(4) "cart"
  ["discount"]=>
  string(4) "0.00"
  ["mc_gross_1"]=>
  string(5) "60.00"
  ["mc_currency"]=>
  string(3) "USD"
  ["residence_country"]=>
  string(2) "CN"
  ["test_ipn"]=>
  string(1) "1"
  ["shipping_method"]=>
  string(7) "Default"
  ["transaction_subject"]=>
  string(0) ""
  ["payment_gross"]=>
  string(5) "60.00"
  ["ipn_track_id"]=>
  string(13) "c06efd25579c7"
}

*/
    
    
    
    
    
    
}