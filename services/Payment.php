<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services;

use Yii;

/**
 * Payment services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Payment extends Service
{
    public $paymentConfig;

    /**
     * Array
     * 不需要释放库存的支付方式。譬如货到付款，在系统中
     * pending订单，如果一段时间未付款，会释放产品库存，但是货到付款类型的订单不会释放，
     * 如果需要释放产品库存，客服在后台取消订单即可释放产品库存。
     */
    public $noRelasePaymentMethod;

    protected $_currentPaymentMethod;

    /**
     * @property $payment_method | string
     * 设置当前的支付方式
     */
    public function setPaymentMethod($payment_method)
    {
        $this->_currentPaymentMethod = $payment_method;
    }

    /**
     * @return $payment_method | string
     *                         得到当前的支付方式
     */
    public function getPaymentMethod()
    {
        return $this->_currentPaymentMethod;
    }
    /**
     * @return 得到支付方式，以及对应的label，譬如：
     * [
     *  'check_money' => 'Check / Money Order',
     *  'alipay_standard' => '支付宝支付',
     * ]                                                                                                  #从配置信息中获取
     */
    public function getPaymentLabels(){
        $arr = [];
        $paymentConfig = $this->paymentConfig;
        if (is_array($paymentConfig['standard'])) {
            foreach ($paymentConfig['standard'] as $payment_method => $one) {
                if (isset($one['label']) && !empty($one['label']) && $payment_method) {
                    $arr[$payment_method] = $one['label'];
                }
            }
        }

        return $arr;
    }

    /**
     * @property $payment_method | String 支付方式。
     * @return 返回提交订单信息跳转到的第三方支付url，也就是第三方支付的url。
     *                                                                                                    #从配置信息中获取
     */
    public function getStandardStartUrl($payment_method = '', $type = '')
    {
        if (!$payment_method) {
            $payment_method = $this->getPaymentMethod();
        }
        if ($payment_method) {
            $paymentConfig = $this->paymentConfig;
            if (isset($paymentConfig['standard'][$payment_method]['start_url'])) {
                if (!empty($paymentConfig['standard'][$payment_method]['start_url'])) {
                    if ($type == 'appserver') {
                        return $this->getAppServerUrl($paymentConfig['standard'][$payment_method]['start_url']);
                    } else {
                        return $this->getUrl($paymentConfig['standard'][$payment_method]['start_url']);
                    }
                }
            }
        }
    }
    
    public function getAppServerUrl($url)
    {
        $url = str_replace('@homeUrl', '', $url);

        return trim($url);
    }
    
    /**
     * @property $url | String url的字符串
     * @return string 根据传递的字符串格式，得到相应的url
     */
    protected function getUrl($url)
    {
        $homeUrl = Yii::$service->url->homeUrl();
        $url = str_replace('@homeUrl', $homeUrl, $url);

        return trim($url);
    }

    /**
     * @property $payment_method | String 支付方式。
     * @return 第三方支付成功后，返回到网站的url
     *                                                          #从配置信息中获取
     */
    public function getStandardSuccessRedirectUrl($payment_method = '')
    {
        if (!$payment_method) {
            $payment_method = $this->getPaymentMethod();
        }
        if ($payment_method) {
            $paymentConfig = $this->paymentConfig;
            if (isset($paymentConfig['standard'][$payment_method]['success_redirect_url'])) {
                if (!empty($paymentConfig['standard'][$payment_method]['success_redirect_url'])) {
                    return $this->getUrl($paymentConfig['standard'][$payment_method]['success_redirect_url']);
                }
            }
        }
    }

    /**
     * @property $payment_method | String 支付方式。
     * @return string 支付取消的url。
     *                #从配置信息中获取
     */
    public function getStandardCancelUrl($payment_method = '')
    {
        if (!$payment_method) {
            $payment_method = $this->getPaymentMethod();
        }
        if ($payment_method) {
            $paymentConfig = $this->paymentConfig;
            if (isset($paymentConfig['standard'][$payment_method]['cancel_url'])) {
                if (!empty($paymentConfig['standard'][$payment_method]['cancel_url'])) {
                    return $this->getUrl($paymentConfig['standard'][$payment_method]['cancel_url']);
                }
            }
        }
    }

    /**
     * @property $payment_method | String 支付方式。
     * @return string 用户名
     *                #从配置信息中获取
     */
    public function getStandardAccount($payment_method = '')
    {
        if (!$payment_method) {
            $payment_method = $this->getPaymentMethod();
        }
        if ($payment_method) {
            $paymentConfig = $this->paymentConfig;
            if (isset($paymentConfig['standard'][$payment_method]['account'])) {
                if (!empty($paymentConfig['standard'][$payment_method]['account'])) {
                    return $paymentConfig['standard'][$payment_method]['account'];
                }
            }
        }
    }

    /**
     * @property $payment_method | String 支付方式。
     * @return string Password
     *                #从配置信息中获取
     */
    public function getStandardPassword($payment_method = '')
    {
        if (!$payment_method) {
            $payment_method = $this->getPaymentMethod();
        }
        if ($payment_method) {
            $paymentConfig = $this->paymentConfig;
            if (isset($paymentConfig['standard'][$payment_method]['password'])) {
                if (!empty($paymentConfig['standard'][$payment_method]['password'])) {
                    return $paymentConfig['standard'][$payment_method]['password'];
                }
            }
        }
    }

    /**
     * @property $payment_method | String 支付方式。
     * @return string Signature
     *                #从配置信息中获取
     */
    public function getStandardSignature($payment_method = '')
    {
        if (!$payment_method) {
            $payment_method = $this->getPaymentMethod();
        }
        if ($payment_method) {
            $paymentConfig = $this->paymentConfig;
            if (isset($paymentConfig['standard'][$payment_method]['signature'])) {
                if (!empty($paymentConfig['standard'][$payment_method]['signature'])) {
                    return $paymentConfig['standard'][$payment_method]['signature'];
                }
            }
        }
    }
    
    /**
     * @property $payment_method | String 支付方式。
     * @return 返回进行数据交互的express的api地址。
     */
    public function getStandardWebscrUrl($payment_method = '')
    {
        if (!$payment_method) {
            $payment_method = $this->getPaymentMethod();
        }
        if ($payment_method) {
            $paymentConfig = $this->paymentConfig;
            if (isset($paymentConfig['standard'][$payment_method]['webscr_url'])) {
                if (!empty($paymentConfig['standard'][$payment_method]['webscr_url'])) {
                    return $paymentConfig['standard'][$payment_method]['webscr_url'];
                }
            }
        }
    }

    /**
     * @return array 得到所有支付的数组，数组含有三个字段。
     */
    public function getStandardPaymentArr()
    {
        $arr = [];
        if (
            isset($this->paymentConfig['standard']) &&
            is_array($this->paymentConfig['standard'])
        ) {
            foreach ($this->paymentConfig['standard'] as $payment_type => $info) {
                $label = $info['label'];
                $imageUrl = '';
                if (is_array($info['image'])) {
                    list($iUrl, $l) = $info['image'];
                    if ($iUrl) {
                        $imageUrl = Yii::$service->image->getImgUrl($iUrl, $l);
                    }
                }
                $supplement = $info['supplement'];
                $arr[$payment_type] = [
                    'label' => $label,
                    'imageUrl' => $imageUrl,
                    'supplement' => $supplement,
                ];
            }
        }

        return $arr;
    }

    /**
     * @property $payment_method | String ， 支付方式
     * @return bool 判断传递的支付方式，是否在配置中存在，如果存在返回true。
     */
    protected function actionIfIsCorrectStandard($payment_method)
    {
        $paymentConfig = $this->paymentConfig;
        $standard = isset($paymentConfig['standard']) ? $paymentConfig['standard'] : '';
        if (isset($standard[$payment_method]) && !empty($standard[$payment_method])) {
            return true;
        } else {
            return false;
        }
    }
    
    public function getStandardNvpUrl($payment_method = '')
    {
        if (!$payment_method) {
            $payment_method = $this->getPaymentMethod();
        }
        if ($payment_method) {
            $paymentConfig = $this->paymentConfig;
            if (isset($paymentConfig['standard'][$payment_method]['nvp_url'])) {
                if (!empty($paymentConfig['standard'][$payment_method]['nvp_url'])) {
                    return $paymentConfig['standard'][$payment_method]['nvp_url'];
                }
            }
        }
    }

    /**
     * @property $payment_method | String 支付方式。
     * @return 返回支付方式的label
     */
    public function getPaymentLabelByMethod($payment_method = '')
    {
        $payment_method_label = $this->getStandardLabel($payment_method);
        if (!$payment_method_label) {
            $payment_method_label = $this->getExpressLabel($payment_method);
        }
        if ($payment_method_label) {
            return $payment_method_label;
        } else {
            return $payment_method;
        }
    }
    
    /**
     * @property $payment_method | String 支付方式。
     * @return 返回进行数据交互的express的label。
     */
    public function getStandardLabel($payment_method = '')
    {
        if (!$payment_method) {
            $payment_method = $this->getPaymentMethod();
        }
        if ($payment_method) {
            $paymentConfig = $this->paymentConfig;
            if (isset($paymentConfig['standard'][$payment_method]['label'])) {
                if (!empty($paymentConfig['standard'][$payment_method]['label'])) {
                    return $paymentConfig['standard'][$payment_method]['label'];
                }
            }
        }
    }
    
    /**
     * @property $payment_method | String 支付方式。
     * @return 返回进行数据交互的express的signature。
     */
    public function getStandardIpnUrl($payment_method = '')
    {
        if (!$payment_method) {
            $payment_method = $this->getPaymentMethod();
        }
        if ($payment_method) {
            $paymentConfig = $this->paymentConfig;
            if (isset($paymentConfig['standard'][$payment_method]['ipn_url'])) {
                if (!empty($paymentConfig['standard'][$payment_method]['ipn_url'])) {
                    return $this->getUrl($paymentConfig['standard'][$payment_method]['ipn_url']);
                }
            }
        }
    }
    
    /**
     * @property $payment_method | String 支付方式。
     * @return 返回进行数据交互的express的signature。
     */
    public function getStandardReturnUrl($payment_method = '')
    {
        if (!$payment_method) {
            $payment_method = $this->getPaymentMethod();
        }
        if ($payment_method) {
            $paymentConfig = $this->paymentConfig;
            if (isset($paymentConfig['standard'][$payment_method]['return_url'])) {
                if (!empty($paymentConfig['standard'][$payment_method]['return_url'])) {
                    return $this->getUrl($paymentConfig['standard'][$payment_method]['return_url']);
                }
            }
        }
    }

    //###################
    //# Express 部分   ##
    //###################

    /**
     * @property $payment_method | String 支付方式。
     * @return 返回获取token的url地址。
     */
    public function getExpressNvpUrl($payment_method = '')
    {
        if (!$payment_method) {
            $payment_method = $this->getPaymentMethod();
        }
        if ($payment_method) {
            $paymentConfig = $this->paymentConfig;
            if (isset($paymentConfig['express'][$payment_method]['nvp_url'])) {
                if (!empty($paymentConfig['express'][$payment_method]['nvp_url'])) {
                    return $paymentConfig['express'][$payment_method]['nvp_url'];
                }
            }
        }
    }

    /**
     * @property $payment_method | String 支付方式。
     * @return 返回进行数据交互的express的api地址。
     */
    public function getExpressWebscrUrl($payment_method = '')
    {
        if (!$payment_method) {
            $payment_method = $this->getPaymentMethod();
        }
        if ($payment_method) {
            $paymentConfig = $this->paymentConfig;
            if (isset($paymentConfig['express'][$payment_method]['webscr_url'])) {
                if (!empty($paymentConfig['express'][$payment_method]['webscr_url'])) {
                    return $paymentConfig['express'][$payment_method]['webscr_url'];
                }
            }
        }
    }

    /**
     * @property $payment_method | String 支付方式。
     * @return 返回进行数据交互的express的account。
     */
    public function getExpressAccount($payment_method = '')
    {
        if (!$payment_method) {
            $payment_method = $this->getPaymentMethod();
        }
        if ($payment_method) {
            $paymentConfig = $this->paymentConfig;
            if (isset($paymentConfig['express'][$payment_method]['account'])) {
                if (!empty($paymentConfig['express'][$payment_method]['account'])) {
                    return $paymentConfig['express'][$payment_method]['account'];
                }
            }
        }
    }

    /**
     * @property $payment_method | String 支付方式。
     * @return 返回进行数据交互的express的password。
     */
    public function getExpressPassword($payment_method = '')
    {
        if (!$payment_method) {
            $payment_method = $this->getPaymentMethod();
        }
        if ($payment_method) {
            $paymentConfig = $this->paymentConfig;
            if (isset($paymentConfig['express'][$payment_method]['password'])) {
                if (!empty($paymentConfig['express'][$payment_method]['password'])) {
                    return $paymentConfig['express'][$payment_method]['password'];
                }
            }
        }
    }

    /**
     * @property $payment_method | String 支付方式。
     * @return 返回进行数据交互的express的signature。
     */
    public function getExpressSignature($payment_method = '')
    {
        if (!$payment_method) {
            $payment_method = $this->getPaymentMethod();
        }
        if ($payment_method) {
            $paymentConfig = $this->paymentConfig;
            if (isset($paymentConfig['express'][$payment_method]['signature'])) {
                if (!empty($paymentConfig['express'][$payment_method]['signature'])) {
                    return $paymentConfig['express'][$payment_method]['signature'];
                }
            }
        }
    }

    /**
     * @property $payment_method | String 支付方式。
     * @return 返回进行数据交互的express的label。
     */
    public function getExpressLabel($payment_method = '')
    {
        if (!$payment_method) {
            $payment_method = $this->getPaymentMethod();
        }
        if ($payment_method) {
            $paymentConfig = $this->paymentConfig;
            if (isset($paymentConfig['express'][$payment_method]['label'])) {
                if (!empty($paymentConfig['express'][$payment_method]['label'])) {
                    return $paymentConfig['express'][$payment_method]['label'];
                }
            }
        }
    }

    /**
     * @property $payment_method | String 支付方式。
     * @return 返回进行数据交互的express的signature。
     */
    public function getExpressReturnUrl($payment_method = '')
    {
        if (!$payment_method) {
            $payment_method = $this->getPaymentMethod();
        }
        if ($payment_method) {
            $paymentConfig = $this->paymentConfig;
            if (isset($paymentConfig['express'][$payment_method]['return_url'])) {
                if (!empty($paymentConfig['express'][$payment_method]['return_url'])) {
                    return $this->getUrl($paymentConfig['express'][$payment_method]['return_url']);
                }
            }
        }
    }

    /**
     * @property $payment_method | String 支付方式。
     * @return 返回进行数据交互的express的signature。
     */
    public function getExpressCancelUrl($payment_method = '')
    {
        if (!$payment_method) {
            $payment_method = $this->getPaymentMethod();
        }
        if ($payment_method) {
            $paymentConfig = $this->paymentConfig;
            if (isset($paymentConfig['express'][$payment_method]['cancel_url'])) {
                if (!empty($paymentConfig['express'][$payment_method]['cancel_url'])) {
                    return $this->getUrl($paymentConfig['express'][$payment_method]['cancel_url']);
                }
            }
        }
    }

    /**
     * @property $payment_method | String 支付方式。
     * @return 第三方支付成功后，返回到网站的url
     *                                                          #从配置信息中获取
     */
    public function getExpressSuccessRedirectUrl($payment_method = '')
    {
        if (!$payment_method) {
            $payment_method = $this->getPaymentMethod();
        }
        if ($payment_method) {
            $paymentConfig = $this->paymentConfig;
            if (isset($paymentConfig['express'][$payment_method]['success_redirect_url'])) {
                if (!empty($paymentConfig['express'][$payment_method]['success_redirect_url'])) {
                    return $this->getUrl($paymentConfig['express'][$payment_method]['success_redirect_url']);
                }
            }
        }
    }
    
    /**
     * @property $payment_method | String 支付方式。
     * @return 返回进行数据交互的express的signature。
     */
    public function getExpressIpnUrl($payment_method = '')
    {
        if (!$payment_method) {
            $payment_method = $this->getPaymentMethod();
        }
        if ($payment_method) {
            $paymentConfig = $this->paymentConfig;
            if (isset($paymentConfig['express'][$payment_method]['ipn_url'])) {
                if (!empty($paymentConfig['express'][$payment_method]['ipn_url'])) {
                    return $this->getUrl($paymentConfig['express'][$payment_method]['ipn_url']);
                }
            }
        }
    }
}
