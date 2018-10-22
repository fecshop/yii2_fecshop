<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appfront\modules\Payment\controllers\paypal;

use fecshop\app\appfront\modules\AppfrontController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class StandardController extends AppfrontController
{
    
    public $enableCsrfValidation = false;

    public function actionStart()
    {
        $payment_method = Yii::$service->payment->paypal->standard_payment_method;
        Yii::$service->payment->setPaymentMethod($payment_method);
        
        return $this->getBlock()->startPayment();
    }

    // 2.Review  从paypal确认后返回
    public function actionReview()
    {
        $payment_method = Yii::$service->payment->paypal->standard_payment_method;
        Yii::$service->payment->setPaymentMethod($payment_method);
        
        $this->getBlock('placeorder')->getLastData();
    }
    
    
    public function actionIpn()
    {
        \Yii::info('paypal ipn begin standard', 'fecshop_debug');
        $payment_method = Yii::$service->payment->paypal->standard_payment_method;
        Yii::$service->payment->setPaymentMethod($payment_method);
        $post = Yii::$app->request->post();
        if (is_array($post) && !empty($post)) {
            $post = \Yii::$service->helper->htmlEncode($post);
            ob_start();
            ob_implicit_flush(false);
            var_dump($post);
            $post_log = ob_get_clean();
            \Yii::info($post_log, 'fecshop_debug');
            Yii::$service->payment->paypal->receiveIpn($post);
        }
    }
    
    
    public function actionCancel()
    {
        $innerTransaction = Yii::$app->db->beginTransaction();
		try {
            if(Yii::$service->order->cancel()){
                $innerTransaction->commit();
            }else{
                $innerTransaction->rollBack();
            }
		} catch (\Exception $e) {
			$innerTransaction->rollBack();
		}
        return Yii::$service->url->redirectByUrlKey('checkout/onepage');
    }
    
    
    
    
    
    
    
    /*
    public $enableCsrfValidation = false;
    private $use_local_certs = true;

    public function actionStart()
    {
        Yii::$service->page->theme->layoutFile = 'blank.php';
        $data = $this->getBlock()->getLastData();
        if (is_array($data) && !empty($data)) {
            return $this->render($this->action->id, $data);
        } else {
            return Yii::$service->url->redirectByUrlKey('checkout/onepage');
        }
    }

    public function actionIpn()
    {
        \Yii::info('paypal ipn begin', 'fecshop_debug');

        $post = Yii::$app->request->post();
        if (is_array($post) && !empty($post)) {
            $post = \Yii::$service->helper->htmlEncode($post);

            ob_start();
            ob_implicit_flush(false);
            var_dump($post);
            $post_log = ob_get_clean();
            \Yii::info($post_log, 'fecshop_debug');

            //Yii::$service->payment->paypal->receiveIpn($post);
        }
    }

    

    public function actionTest()
    {
        $url = 'https://www.sandbox.paypal.com/cgi-bin/webscr?invoice=1000000124&first_name=test&discount=7.11&mc_shipping=0.00&mc_currency=EUR&payer_status=verified&shipping_discount=0.00&payment_fee=&address_status=unconfirmed&payment_gross=&settle_currency=USD&address_zip=212121&address_country_code=FR&txn_type=cart&num_cart_items=3&mc_handling=0.00&verify_sign=AuRlNZvMOhdn8iDWY5YoMB9iRTDzAZppqkxKbGiGEUvmwrFAdvscLpXK&payer_id=FKL4V7D5GCACY&option_selection2_1=L&option_selection2_2=L&charset=windows-1252&tax1=0.00&receiver_id=H4KXD885J8LV2&tax2=0.00&tax3=0.00&mc_handling1=0.00&mc_handling2=0.00&mc_handling3=0.00&item_name1=Reindeer+Pattern+Glitter+Christmas+Dress&tax=0.00&item_name2=Sweet+Polka+Dot+Open+Back+Summer+Dress+For+Women&item_name3=fast_shipping&payment_type=instant&mc_shipping1=0.00&address_street=2121%0D%0A23232&mc_shipping2=0.00&mc_shipping3=0.00&txn_id=9NN80505PR451120Y&exchange_rate=1.29364&mc_gross_1=40.34&quantity1=2&mc_gross_2=30.69&quantity2=1&item_number1=22221&protection_eligibility=Eligible&mc_gross_3=18.51&quantity3=1&item_number2=sk0003&item_number3=Fast+Shipping%28+5-10+work+days%29&custom=&option_selection1_1=black&option_selection1_2=red&business=zqy234api1-facilitator%40126.com&residence_country=US&last_name=facilitator&address_state=Hautes-Alpes&payer_business_name=test+facilitator%27s+Test+Store&payer_email=zqy234api1-facilitator-1%40126.com&option_name2_1=My+size&option_name2_2=size&settle_amount=103.09&address_city=2121&payment_status=Completed&payment_date=22%3A40%3A06+Feb+20%2C+2017+PST&transaction_subject=&receiver_email=zqy234api1-facilitator%40126.com&mc_fee=2.74&notify_version=3.8&shipping_method=Default&address_country=France&mc_gross=82.43&test_ipn=1&insurance_amount=0.00&address_name=1111+22&option_name1_1=My+color&option_name1_2=color&ipn_track_id=26d73da3782c3&cmd=_notify-validate';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSLVERSION, 6);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

        // This is often required if the server is missing a global cert bundle, or is using an outdated one.
        if ($this->use_local_certs) {
            curl_setopt($ch, CURLOPT_CAINFO, __DIR__ . '/cert/cacert.pem');
        }
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Connection: Close']);
        $res = curl_exec($ch);
        echo $res;
    }
    */
}
