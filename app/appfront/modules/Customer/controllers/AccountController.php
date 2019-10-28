<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appfront\modules\Customer\controllers;

use fecshop\app\appfront\modules\AppfrontController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class AccountController extends AppfrontController
{
    //protected $_registerSuccessRedirectUrlKey = 'customer/account';

    public $enableCsrfValidation = true;

    public function init()
    {
        parent::init();
    }

    /**
     * 账户中心.
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return Yii::$service->url->redirectByUrlKey('customer/account/login');
        }
        $data = $this->getBlock()->getLastData();

        return $this->render($this->action->id, $data);
    }

    /**
     * 登录.
     */
    public function actionLogin()
    {
        if (Yii::$service->store->isAppServerMobile()) {
            $urlPath = 'customer/account/login';
            Yii::$service->store->redirectAppServerMobile($urlPath);
        }
        if (!Yii::$app->user->isGuest) {
            return Yii::$service->url->redirectByUrlKey('customer/account');
        }
        $param = Yii::$app->request->post('editForm');
        if (!empty($param) && is_array($param)) {
            $this->getBlock()->login($param);
            if (!Yii::$app->user->isGuest) {
                return Yii::$service->customer->loginSuccessRedirect('customer/account');
            }
        }
        $data = $this->getBlock()->getLastData($param);

        return $this->render($this->action->id, $data);
    }

    /**
     * 注册.
     */
    public function actionRegister()
    {
        if (Yii::$service->store->isAppServerMobile()) {
            $urlPath = 'customer/account/register';
            Yii::$service->store->redirectAppServerMobile($urlPath);
        }
        if (!Yii::$app->user->isGuest) {
            return Yii::$service->url->redirectByUrlKey('customer/account');
        }
        $param = Yii::$app->request->post('editForm');
        if (!empty($param) && is_array($param)) {
            $param = \Yii::$service->helper->htmlEncode($param);
            $registerStatus = $this->getBlock()->register($param);
            //echo $registerStatus;exit;
            if ($registerStatus) {
                // $params_register = Yii::$app->getModule('customer')->params['register'];
                $appName = Yii::$service->helper->getAppName();
                $registerSuccessAutoLogin = Yii::$app->store->get($appName.'_account', 'registerSuccessAutoLogin');
                $registerSuccessRedirectUrlKey = Yii::$app->store->get($appName.'_account', 'registerSuccessRedirectUrlKey');
                // 是否需要邮件激活？
                if (Yii::$service->email->customer->registerAccountIsNeedEnableByEmail) {
                    $correctMessage = Yii::$service->page->translate->__("Your account registration is successful, we sent an email to your email, you need to login to your email and click the activation link to activate your account. If you have not received the email, you can resend the email by {url_click_here_before}clicking here{url_click_here_end} {end_text}", ['url_click_here_before' => '<span  class="email_register_resend" >',  'url_click_here_end' => '</span>', 'end_text'=> '<span class="resend_text"></span>' ]);
                    Yii::$service->page->message->AddCorrect($correctMessage);                  
                } else { // 如果不需要邮件激活？
                    // 注册成功后，是否自动登录
                    if ($registerSuccessAutoLogin == Yii::$app->store->enable) {
                        Yii::$service->customer->login($param);
                    }
                    if (!Yii::$app->user->isGuest) {
                        // 注册成功后，跳转的页面，如果值为false， 则不跳转。
                        $urlKey = 'customer/account';
                        if ($registerSuccessRedirectUrlKey) {
                            $urlKey = $registerSuccessRedirectUrlKey;
                        }

                        return Yii::$service->customer->loginSuccessRedirect($urlKey);
                    }
                }
                
            }
        }
        $data = $this->getBlock()->getLastData($param);

        return $this->render($this->action->id, $data);
    }
    
    
    public function actionResendregisteremail()
    {
        $email = Yii::$app->request->get('email');
        $identity = Yii::$service->customer->getAvailableUserIdentityByEmail($email);
        
        if ($identity['status'] != $identity::STATUS_REGISTER_DISABLE) {
            echo json_encode([
                'resendStatus' => 'fail',
            ]);
            exit;
        }
        
        $this->getBlock('register')->sendRegisterEmail($identity);
        
        echo json_encode([
            'resendStatus' => 'success',
        ]);
        exit;
    }

    /**
     * 登出账户.
     */
    public function actionLogout()
    {
        $rt = Yii::$app->request->get('rt');
        if (!Yii::$app->user->isGuest) {
            Yii::$app->user->logout();
            Yii::$service->cart->clearCart();
        }
        if ($rt) {
            $redirectUrl = base64_decode($rt);
            Yii::$service->url->redirect($redirectUrl);
        } else {
            Yii::$service->url->redirect(Yii::$service->url->HomeUrl());
        }
    }

    /**
     * ajax 请求 ，得到是否登录账户的信息.
     */
    public function actionLogininfo()
    {
        if (!Yii::$app->user->isGuest) {
            echo json_encode([
                'loginStatus' => true,
            ]);
            exit;
        }
    }

    /**
     * 忘记密码？
     */
    public function actionForgotpassword()
    {
        if (!Yii::$app->user->isGuest) {
            return Yii::$service->url->redirectByUrlKey('customer/account');
        }
        $data = $this->getBlock()->getLastData();

        return $this->render($this->action->id, $data);
    }

    public function actionForgotpasswordsubmit()
    {
        $editForm = Yii::$app->request->post('editForm');
        $data = [
            'forgotPasswordUrl' => Yii::$service->url->getUrl('customer/account/forgotpassword'),
            'contactUrl'        => Yii::$service->url->getUrl('customer/contacts'),
        ];
        if (!empty($editForm)) {
            $identity = $this->getBlock('forgotpassword')->sendForgotPasswordMailer($editForm);
            //var_dump($identity);
            if ($identity) {
                $data['identity'] = $identity;
            } else {
                $redirectUrl = Yii::$service->url->getUrl('customer/account/forgotpassword');
                return Yii::$service->url->redirect($redirectUrl);
            }
        }
        $this->breadcrumbs(Yii::$service->page->translate->__('Reset Password Submit'));
        return $this->render($this->action->id, $data);
    }
    
     // 面包屑导航
    protected function breadcrumbs($name)
    {
        if (Yii::$app->controller->module->params['forgot_reset_password_submit_breadcrumbs']) {
            Yii::$service->page->breadcrumbs->addItems(['name' => $name]);
        } else {
            Yii::$service->page->breadcrumbs->active = false;
        }
    }

    public function actionResetpassword()
    {
        $editForm = Yii::$app->request->post('editForm');
        if (!empty($editForm)) {
            $resetStatus = $this->getBlock()->resetPassword($editForm);
            if ($resetStatus) {
                // 重置成功，跳转
                $resetSuccessUrl = Yii::$service->url->getUrl('customer/account/resetpasswordsuccess');
                return Yii::$service->url->redirect($resetSuccessUrl);
            }
        }
        $data = $this->getBlock()->getLastData();

        return $this->render($this->action->id, $data);
    }
    
    // registerenable?enableToken
    public function actionRegisterenable()
    {
        $data = $this->getBlock()->getLastData();

        return $this->render($this->action->id, $data);
    }
    

    public function actionResetpasswordsuccess()
    {
        $data = $this->getBlock()->getLastData();

        return $this->render($this->action->id, $data);
    }

    public function actionFacebook()
    {
    }

    public function actionGoogle()
    {
    }
}
