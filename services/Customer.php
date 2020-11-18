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
use yii\web\IdentityInterface;

/**
 * Customer service.
 * @property \fecshop\services\Customer\Address $address
 * @property \fecshop\services\customer\Newsletter $newsletter
 * @property \fecshop\services\customer\Affiliate $affiliate
 * @property \fecshop\services\customer\Coupon $coupon
 * @property \fecshop\services\customer\DropShip $dropship
 * @property \fecshop\services\customer\Favorite $favorite
 * @property \fecshop\services\customer\Message $message
 * @property \fecshop\services\customer\Order $order
 * @property \fecshop\services\customer\Point $point
 * @property \fecshop\services\customer\Review $review
 * @property \fecshop\services\customer\Wholesale $wholesale
 * @property \fecshop\services\customer\Facebook $facebook
 * @property \fecshop\services\customer\Google $google
 *
 * @method getPrimaryKey() see [[\fecshop\services\Customer::actionGetPrimaryKey()]]actionGetByPrimaryKey
 * @method \fecshop\models\mysqldb\Customer|null getByPrimarykey($val) see [[\fecshop\services\Customer::actionGetByPrimaryKey()]]
 * @method \fecshop\models\mysqldb\Customer|null getUserIdentityByEmail($email) see [[\fecshop\services\Customer::actionGetUserIdentityByEmail()]]
 * @method loginByAccessToken($type = null) see [[\fecshop\services\Customer::actionLoginByAccessToken()]]
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Customer extends Service
{
    const USER_LOGIN_SUCCESS_REDIRECT_URL_KEY = 'usr_login_success_redirect_url';

    /**
     * @var array
     *
     * example:
     * ```php
     * [
     *     'customer_register' => [
     *         'min_name_length' => 1,
     *     ]
     * ]
     * ```
     */
    public $customer_register;

    protected $_customerModelName = '\fecshop\models\mysqldb\Customer';

    /**
     * @var \fecshop\models\mysqldb\Customer
     */
    protected $_customerModel;

    protected $_customerLoginModelName = '\fecshop\models\mysqldb\customer\CustomerLogin';

    /**
     * @var \fecshop\models\mysqldb\customer\CustomerLogin
     */
    protected $_customerLoginModel;

    protected $_customerRegisterModelName = '\fecshop\models\mysqldb\customer\CustomerRegister';

    /**
     * @var \fecshop\models\mysqldb\customer\CustomerRegister
     */
    protected $_customerRegisterModel;
    
    public function init()
    {
        // 对于 api端口，设置Yii::$app->user->enableSession = false;
        // 下面的代码注释掉，对于使用到user组件的，在相应的模块部分设置 Yii::$app->user->enableSession = false;
        // if(Yii::$service->store->isApiStore()){
        //    Yii::$app->user->enableSession = false;
        //}
        parent::init();
        list($this->_customerModelName, $this->_customerModel) = Yii::mapGet($this->_customerModelName);
        list($this->_customerLoginModelName, $this->_customerLoginModel) = Yii::mapGet($this->_customerLoginModelName);
        list($this->_customerRegisterModelName, $this->_customerRegisterModel) = Yii::mapGet($this->_customerRegisterModelName);
        
        $appName = Yii::$service->helper->getAppName();
        $this->customer_register = [
            'min_name_length' => (int)Yii::$app->store->get($appName.'_account', 'min_name_length'),  // 注册账号的firstname, lastname的最小长度
            'max_name_length' => (int)Yii::$app->store->get($appName.'_account', 'max_name_length'), // 注册账号的firstname, lastname的最大长度
            'min_pass_length' => (int)Yii::$app->store->get($appName.'_account', 'min_pass_length'),  // 注册账号的密码的最小长度
            'max_pass_length' => (int)Yii::$app->store->get($appName.'_account', 'max_pass_length'), // 注册账号的密码的最大长度
        ];
    }

    /**
     * 注册用户名字的最小长度.
     * @return int|null
     */
    public function getRegisterNameMinLength()
    {
        if (isset($this->customer_register['min_name_length'])) {
            
            return $this->customer_register['min_name_length'];
        }
        
        return null;
    }

    /**
     * 注册用户名字的最大长度.
     * @return int|null
     */
    public function getRegisterNameMaxLength()
    {
        if (isset($this->customer_register['max_name_length'])) {
            
            return $this->customer_register['max_name_length'];
        }
        
        return null;
    }

    /**
     * 注册用户密码的最小长度.
     * @return int|null
     */
    public function getRegisterPassMinLength()
    {
        if (isset($this->customer_register['min_pass_length'])) {
            
            return $this->customer_register['min_pass_length'];
        }
        
        return null;
    }

    /**
     * 注册用户密码的最大长度.
     * @return int|null
     */
    public function getRegisterPassMaxLength()
    {
        if (isset($this->customer_register['max_pass_length'])) {
            
            return $this->customer_register['max_pass_length'];
        }
        
        return null;
    }

    /**
     * @param array $data
     *
     * example:
     *
     * ```php
     * $data = ['email' => 'user@example.com', 'password' => 'your password'];
     * $loginStatus = \Yii::$service->customer->login($data);
     * ```
     *
     * @return bool
     */
    public function login($data)
    {
        $model = $this->_customerLoginModel;
        $model->password = $data['password'];
        $model->email = $data['email'];
        $loginStatus = $model->login();
        $errors = $model->errors;
        if (empty($errors)) {
            // 合并购物车数据
            Yii::$service->cart->mergeCartAfterUserLogin();
            // 发送登录信息到trace系统
            Yii::$service->page->trace->sendTraceLoginInfoByApi($data['email']);
        } else {
            Yii::$service->helper->errors->addByModelErrors($errors);
        }

        return $loginStatus;
    }

    /**
     * Register customer account.
     * @param array $param
     * 数据格式如下：
     * ```php
     * [
     *      'email',
     *      'firstname',
     *      'lastname',
     *      'password'
     * ]
     * ```
     * @return bool whether the customer is registered ok
     */
    public function register($param)
    {
        $model = $this->_customerRegisterModel;
        $model->attributes = $param;
        if ($model->validate()) {
            $model->created_at = time();
            $model->updated_at = time();
            if (Yii::$service->email->customer->registerAccountIsNeedEnableByEmail) {
                $model->generateRegisterEnableToken();
                $model->status = $model::STATUS_REGISTER_DISABLE;
            }
            $saveStatus = $model->save();
            if (!$saveStatus) {
                $errors = $model->errors;
                Yii::$service->helper->errors->addByModelErrors($errors);
                
                return false;
            }
            // 如果用户勾选了订阅邮件，那么添加到订阅
            if ($param['is_subscribed'] == 1) {
                Yii::$service->customer->newsletter->subscribe($param['email'], true);
            }
            
            // 发送注册信息到trace系统
            Yii::$service->page->trace->sendTraceRegisterInfoByApi($model->email);
            
            return true;
        } else {
            $errors = $model->errors;
            Yii::$service->helper->errors->addByModelErrors($errors);

            return false;
        }
    }
    

    /**
     * Check whether the given email is registered
     * @param string $email
     * @return bool whether the given email is registered
     */
    public function isRegistered($email)
    {
        $customer = $this->_customerModel->findOne(['email' => $email]);
        if ($customer['email']) {
            
            return true;
        } else {
            
            return false;
        }
    }

    /**
     * Save the customer info.
     * @param array $param
     * 数据格式如下：
     * ['email' => 'xxx', 'password' => 'xxxx','firstname' => 'xxx','lastname' => 'xxx']
     * @return bool
     */
    public function save($param)
    {
        $primaryKey = $this->getPrimaryKey();
        $primaryVal = isset($param[$primaryKey]) ? $param[$primaryKey] : '';
        if ($primaryVal) {
            $model = $this->_customerRegisterModel;
            $model->attributes = $param;
            if (!$model->validate()) {
                $errors = $model->errors;
                Yii::$service->helper->errors->addByModelErrors($errors);
                
                return false;
            }
            $model = $this->getByPrimaryKey($primaryVal);
            if ($model[$primaryKey]) {
                unset($param[$primaryKey]);
                $param['updated_at'] = time();
                $password = isset($param['password']) ? $param['password'] : '';
                if ($password) {
                    $model->setPassword($password);
                    unset($param['password']);
                }
                $saveStatus = Yii::$service->helper->ar->save($model, $param);
                if ($saveStatus) {
                    
                    return true;
                } else {
                    $errors = $model->errors;
                    Yii::$service->helper->errors->addByModelErrors($errors);

                    return false;
                }
            }
        } else {
            if ($this->register($param)) {
                
                return true;
            }
        }
        
        return false;
    }

    /**
     * @param int $customerId
     * @deprecated 该方法已废弃
     */
    public function viewInfo($customerId)
    {
    }

    /**
     * Change customer's password
     * @param string $password
     * @param int|string|IdentityInterface $identity this can be customer id, customer email, or customer
     * @return bool
     * @throws \InvalidArgumentException if $identity is invalid
     */
    public function changePassword($password, $identity)
    {
        if (is_int($identity)) {
            $customer_id = $identity;
            $customerModel = $this->_customerModel->findIdentity($customer_id);
        } elseif (is_string($identity)) {
            $email = $identity;
            $customerModel = $this->_customerModel->findByEmail($email);
        } elseif (is_object($identity) && $identity instanceof IdentityInterface) {
            $customerModel = $identity;
        } else {
            
            throw new \InvalidArgumentException('$identity can only be customer id, customer email, or customer');
        }
        if ($customerModel['email']) {
            $customerModel->updated_at = time();
            $customerModel->setPassword($password);
            $customerModel->save();
            
            return true;
        } else {
            
            return false;
        }
    }

    /**
     * 得到category model的全名.
     */
    public function getModelName()
    {
        $model = new $this->_customerModelName();

        return get_class($model);
    }

    /**
     * @param int $val
     * @return \fecshop\models\mysqldb\Customer
     */
    public function getByPrimaryKey($val)
    {
        $one = $this->_customerModel->findOne($val);
        $primaryKey = $this->getPrimaryKey();
        if ($one[$primaryKey]) {
            
            return $one;
        } else {
            
            return new $this->_customerModelName();
        }
    }

    /**
     * @param $password|string
     * @param $customerId|int or String or Object
     * change  customer password.
     * 更改密码，然后，清空token
     */
    public function changePasswordAndClearToken($password, $identity)
    {
        if (is_int($identity)) {
            $customer_id = $identity;
            $customerModel = $this->_customerModel->findIdentity($customer_id);
        } elseif (is_string($identity)) {
            $email = $identity;
            $customerModel = $this->_customerModel->findByEmail($email);
        } elseif (is_object($identity)) {
            $customerModel = $identity;
        } else {
            Yii::$service->helper->errors->add('identity is not right');

            return null;
        }
        if (strlen($password) < 6  || strlen($password) > 30) {
            Yii::$service->helper->errors->add('Password length must be greater than 6, less than 30');

            return null;
        }
        $customerModel->setPassword($password);
        $customerModel->removePasswordResetToken();
        $customerModel->updated_at = time();
        $customerModel->save();

        return true;
    }

    /**
     * @param $password|string
     * @param $customerId|int or String or Object
     * change  customer password.
     * 更改密码，然后，清空token
     */
    public function registerEnableByTokenAndClearToken($token)
    {
        $identity = $this->findByRegisterEnableToken($token);
        if (!$identity['id']) {
            Yii::$service->helper->errors->add('token is invalid');
             
            return false;
        }
        $identity->status = $identity::STATUS_ACTIVE;
        $identity->updated_at = time();
        
        return $identity->save();
    }

    /**
     * @deprecated 已废弃
     */
    public function changeNameAndPassword($data)
    {
    }

    /**
     * @deprecated 已废弃
     */
    public function getCurrentAccount()
    {
    }

    /**
     * Get customer by email address
     * @param string $email
     * @return \fecshop\models\mysqldb\Customer|null return customer or null if not found
     */
    public function getUserIdentityByEmail($email)
    {
        $one = $this->_customerModel->findByEmail($email);
        if ($one['email']) {
            
            return $one;
        } else {
            
            return null;
        }
    }
    // 得到可用的账户
    public function getAvailableUserIdentityByEmail($email)
    {
        $one = $this->_customerModel->findAvailableByEmail($email);
        if ($one['email']) {
            
            return $one;
        } else {
            
            return null;
        }
    }
    

    /**
     * 生成resetToken，用来找回密码
     * @param string|IdentityInterface $identify identity can be customer email, or customer object
     * @return string|null 生成的resetToken，如果生成失败返回false
     */
    public function generatePasswordResetToken($identify)
    {
        if (is_string($identify)) {
            $email = $identify;
            $one = $this->getUserIdentityByEmail($email);
        } else {
            $one = $identify;
        }
        if ($one) {
            $one->generatePasswordResetToken();
            $one->updated_at = time();
            $one->save();

            return $one->password_reset_token;
        }
        
        return false;
    }
    
    /**
     * 生成resetToken，用来找回密码
     * @param string|IdentityInterface $identify identity can be customer email, or customer object
     * @return string|null 生成的resetToken，如果生成失败返回false
     */
    public function generateRegisterEnableToken($identify)
    {
        if (is_string($identify)) {
            $email = $identify;
            $one = $this->getAvailableUserIdentityByEmail($email);
        } else {
            $one = $identify;
        }
        if ($one) {
            $one->generateRegisterEnableToken();
            $one->updated_at = time();
            $one->save();

            return $one->register_enable_token;
        }
        
        return false;
    }

    /**
     * @param string $token the password reset token
     * 通过PasswordResetToken 得到user.
     * @return \fecshop\models\mysqldb\Customer|null returns customer or null if not found
     */
    public function findByPasswordResetToken($token)
    {
        return $this->_customerModel->findByPasswordResetToken($token);
    }
    
    public function findByRegisterEnableToken($token)
    {
        return $this->_customerModel->findByRegisterEnableToken($token);
    }

    /**
     * @param $url|string
     * **注意**：该方法不能在接口类型里面使用
     * 在一些功能中，需要用户进行登录操作，等用户操作成功后，应该跳转到相应的页面中，这里通过session存储需要跳转到的url。
     * 某些页面 ， 譬如评论页面，需要用户登录后才能进行登录操作，那么可以通过这个方法把url set 进去，登录成功
     * 后，页面不会跳转到账户中心，而是需要操作的页面中。
     */
    public function setLoginSuccessRedirectUrl($url)
    {
        return Yii::$service->session->set($this::USER_LOGIN_SUCCESS_REDIRECT_URL_KEY, $url);
    }

    /**
     * @param $url|string
     * **注意**：该方法不能在接口类型里面使用
     * **注意**：该方法不能在接口类型里面使用
     * 在一些功能中，需要用户进行登录操作，等用户操作成功后，应该跳转到相应的页面中，这里通过session得到需要跳转到的url。
     */
    public function getLoginSuccessRedirectUrl()
    {
        $url = Yii::$service->session->get($this::USER_LOGIN_SUCCESS_REDIRECT_URL_KEY);

        return $url ? $url : '';
    }

    /**
     * @param $urlKey | String
     * **注意**：该方法不能在接口类型里面使用
     * 登录用户成功后，进行url跳转。
     */
    public function loginSuccessRedirect($urlKey = '')
    {
        $url = $this->getLoginSuccessRedirectUrl();
        if ($url) {
            // 这个优先级最高
            // 在跳转之前，去掉这个session存储的值。跳转后，这个值必须失效。
            Yii::$service->session->remove($this::USER_LOGIN_SUCCESS_REDIRECT_URL_KEY);
            
            return Yii::$service->url->redirect($url);
        } elseif ($urlKey) {
            
            return Yii::$service->url->redirectByUrlKey($urlKey);
        } else {
            
            return Yii::$service->url->redirectHome();
        }
    }

    /**
     * 得到status为删除状态的值
     */
    public function getStatusDeleted()
    {
        $model = $this->_customerModel;
        
        return $model::STATUS_DELETED;
    }

    /**
     * 得到status为激活状态的值
     */
    public function getStatusActive()
    {
        $model = $this->_customerModel;
        
        return $model::STATUS_ACTIVE;
    }

    /**
     * Get primary key field name.
     */
    public function getPrimaryKey()
    {
        return 'id';
    }

    /**
     * Get customer collection by filter array
     * @param array $filter
     * filter example:
     * [
     * 		'numPerPage' 	=> 20,
     * 		'pageNum'		=> 1,
     * 		'orderBy'	=> ['_id' => SORT_DESC, 'sku' => SORT_ASC ],
     * 		'where'			=> [
     *			['>','price','1'],
     *			['<','price','10'],
     * 			['sku' => 'uk10001'],
     * 		],
     * 	'asArray' => true,
     * ]
     * @return array
     */
    public function coll($filter = [])
    {
        $query = $this->_customerModel->find();
        $query = Yii::$service->helper->ar->getCollByFilter($query, $filter);
        return [
            'coll' => $query->all(),
            'count'=> $query->limit(null)->offset(null)->count(),
        ];
    }
    
    /**
     * Remove customer by primary key value
     * @param  $ids |  int or array, the primary key value
     * @return bool
     * @throws
     */
    public function remove($ids)
    {
        if (!$ids) {
            Yii::$service->helper->errors->add('remove id is empty');
            
            return false;
        }
        if (is_array($ids) && !empty($ids)) {
            foreach ($ids as $id) {
                $model = $this->_customerModel->findOne($id);
                if (isset($model[$this->getPrimaryKey()]) && !empty($model[$this->getPrimaryKey()])) {
                    $model->delete();
                } else {
                    Yii::$service->helper->errors->add("customer Remove Errors:ID:$id is not exist.");
                    
                    return false;
                }
            }
        } else {
            $id = $ids;
            $model = $this->_customerModel->findOne($id);
            $model->delete();
        }
        
        return true;
    }
    
    /**
     * @param $user_ids | Array ， 子项为Int类型
     * @return Array ，数据格式为：
     * ['id' => 'email']
     * 得到customer id 和customer email的对应数组。
     */
    public function getEmailByIds($user_ids)
    {
        $arr = [];
        if (is_array($user_ids) && !empty($user_ids)) {
            $data = $this->_customerModel->find()->where([
                'in', 'id', $user_ids,
            ])->all();
            if (is_array($data) && !empty($data)) {
                foreach ($data as $one) {
                    $arr[$one['id']] = $one['email'];
                }
            }
        }

        return $arr;
    }

    //2. 创建第三方用户的账户，密码自动生成

    /**
     * @param  $user | Array ,example:
     * ['first_name' => $first_name,'last_name' => $last_name,'email' => $email,]
     * @param  $type | String 代表第三方登录的名称，譬如google，facebook
     * @return bool
     * 如果用户emai存在，则直接登录，成功后返回true
     * 如果用户不存在，则注册用户，然后直接登录，成功后返回true
     */
    public function registerThirdPartyAccountAndLogin($user, $type)
    {
        // 查看邮箱是否存在
        $email = $user['email'];
        $customer_one = Yii::$service->customer->getUserIdentityByEmail($email);
        if ($customer_one) {
            $loginStatus = \Yii::$app->user->login($customer_one);
            if ($loginStatus) {
                $customer_one->generateAccessToken();
                $customer_one->access_token_created_at = time();
                $customer_one->save();

                return $this->setHeaderAccessToken($customer_one->access_token);
            }
            // 不存在，注册。
        } else {
            if (!(isset($user['password']) && $user['password'])) {
                $user['password'] = $this->getRandomPassword();
            }
            $registerData = [
                'email'       => $email,
                'firstname'   => $user['first_name'],
                'lastname'    => $user['last_name'],
                'password'    => $user['password'],
                'type'        => $type,
            ];
            $registerStatus = Yii::$service->customer->register($registerData);
            if ($registerStatus) {
                return Yii::$service->customer->loginAndGetAccessToken($registerData['email'], $registerData['password']);
            }
        }

        return false;
    }

    /**
     * 生成账户密码
     */
    protected function getRandomPassword()
    {
        srand((float) microtime() * 1000000); //create a random number feed.
        $ychar = '0,1,2,3,4,5,6,7,8,9,A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z';
        $list = explode(',', $ychar);
        for ($i = 0; $i < 6; $i++) {
            $randnum = rand(0, 35); // 10+26;
            $authnum .= $list[$randnum];
        }
        
        return $authnum;
    }
    
    /**
      * @param $identity | Object， customer model
      * #param $duration 
      * 通过identity 进行登陆账户
      * 通过
      */
    public function loginByIdentityAndGetAccessToken($identity, $wx_session_key='', $duration = 0)
    {
        $header = Yii::$app->request->getHeaders();
        if (isset($header['access-token']) && $header['access-token']) {
            $accessToken = $header['access-token'];
        }
        // 如果request header中有access-token，则查看这个 access-token 是否有效
        if ($accessToken) {
            $access_token_identity = Yii::$app->user->loginByAccessToken($accessToken);
            if ($access_token_identity !== null) {
                $access_token_created_at = $access_token_identity->access_token_created_at;
                $timeout = Yii::$service->session->timeout;
                if ($access_token_created_at + $timeout > time()) {
                    
                    return $accessToken;
                }
            }
        }
        // 执行登陆
        if (!$duration) {
            if (Yii::$service->session->timeout) {
                $duration = Yii::$service->session->timeout;
            }
        }
        //var_dump($identity);exit;
        if (\Yii::$app->user->login($identity, $duration)) {
            $identity->generateAccessToken();
            $identity->access_token_created_at = time();
            $identity->wx_session_key = $wx_session_key;
            $identity->save();
            // 执行购物车合并等操作。
            Yii::$service->cart->mergeCartAfterUserLogin();
            $this->setHeaderAccessToken($identity->access_token);
            
            return $identity->access_token;
            
        }
    }
    
    /** AppServer 部分使用的函数
     * @param $email | String
     * @param $password | String
     * 无状态登录，通过email 和password进行登录
     * 登录成功后，合并购物车，返回accessToken
     * ** 该函数是未登录用户，通过参数进行登录需要执行的函数。
     */
    public function loginAndGetAccessToken($email, $password)
    {
        $header = Yii::$app->request->getHeaders();
        if (isset($header['access-token']) && $header['access-token']) {
            $accessToken = $header['access-token'];
        }
        // 如果request header中有access-token，则查看这个 access-token 是否有效
        if ($accessToken) {
            $identity = Yii::$app->user->loginByAccessToken($accessToken);
            if ($identity !== null) {
                $access_token_created_at = $identity->access_token_created_at;
                $timeout = Yii::$service->session->timeout;
                if ($access_token_created_at + $timeout > time()) {
                    
                    return $accessToken;
                }
            }
        }
        // 如果上面access-token不存在
        $data = [
            'email'     => $email,
            'password'  => $password,
        ];
        
        if (Yii::$service->customer->login($data)) {
            $identity = Yii::$app->user->identity;
            $identity->generateAccessToken();
            $identity->access_token_created_at = time();
            $identity->save();
            // 执行购物车合并等操作。
            Yii::$service->cart->mergeCartAfterUserLogin();
            $this->setHeaderAccessToken($identity->access_token);
            
            return $identity->access_token;
        }
    }

    /**
     * Logs in a user by the given access token.
     * Token is passed through headers. So you can get it from the key 'access-token'.
     * @param $type
     * @return IdentityInterface|null the identity associated with the given access token. Null is returned if
     * the access token is invalid.
     * @see [[\yii\web\User::loginByAccessToken()]]
     */
    public function loginByAccessToken($type = null)
    {
        $header = Yii::$app->request->getHeaders();
        if (isset($header['access-token']) && $header['access-token']) {
            $accessToken = $header['access-token'];
        } else {
            
            return null;
        }

        /** @var \fecshop\models\mysqldb\Customer|null $identity */
        $identity = Yii::$app->user->loginByAccessToken($accessToken, $type);
        if ($identity !== null) {
            $access_token_created_at = $identity->access_token_created_at;
            $timeout = Yii::$service->session->timeout;
            // 如果时间没有过期，则返回 identity
            if ($access_token_created_at + $timeout > time()) {
                // 如果时间没有过期，但是快要过期了，在过$updateTimeLimit段时间就要过期，那么更新access_token_created_at。
                $updateTimeLimit = Yii::$service->session->updateTimeLimit;
                if ($access_token_created_at + $timeout <= (time() + $updateTimeLimit)) {
                    $identity->access_token_created_at = time();
                    $identity->save();
                }
                
                return $identity;
            } else {
                $this->logoutByAccessToken();
                
                return null;
            }
        }
        return null;
    }
    
    /**
     * @param $openid | string 
     * 通过微信的openid 得到 user
     */
    public function getByWxOpenid($openid)
    {
        $one = $this->_customerModel->findOne(['wx_openid' => $openid]);
        $primaryKey = $this->getPrimaryKey();
        if ($one[$primaryKey]) {
            
            return $one;
        } 
        
        return null;
    }
    
    /**
     * @param $openid | string 
     * 通过微信小程序的openid 得到 user
     */
    public function getByWxMicroOpenid($openid)
    {
        $one = $this->_customerModel->findOne(['wx_micro_openid' => $openid]);
        $primaryKey = $this->getPrimaryKey();
        if ($one[$primaryKey]) {
            
            return $one;
        } 
        
        return null;
    }

    /**
     * 通过accessToek的方式，进行登出从操作。
     */
    public function logoutByAccessToken()
    {
        $userComponent = Yii::$app->user;
        $identity = $userComponent->identity;
        if ($identity !== null) {
            if (!Yii::$app->user->isGuest) {
                $identity->access_token = null;
                $identity->access_token_created_at = null;
                $identity->save();
            }
            $userComponent->switchIdentity(null);
            // 刷新uuid
            Yii::$service->session->reflushUUID();
        }

        return $userComponent->getIsGuest();
    }
    
    public function setHeaderAccessToken($accessToken)
    {
        if ($accessToken) {
            Yii::$app->response->getHeaders()->set('access-token', $accessToken);
            
            return true;
        }
    }
    
    /**
     * @param $days | Int 天数
     * 得到最近X天的注册用户
     * 下面的数据是为了后台的customer 注册数统计
     */
    public function getPreMonthCustomer($days)
    {
        // 得到一个月前的时间戳
        $preMonthTime = strtotime("-$days days");
        $filter = [
            'select' => ['created_at', 'email' ],
            'numPerPage' 	=> 10000000,
            'pageNum'		=> 1,
            'where'			=> [
                ['>=', 'created_at', $preMonthTime]
            ],
            'asArray' => true,
        ];
        $data = $this->coll($filter);
        $coll = $data['coll'];
        $dateArr = Yii::$service->helper->format->getPreDayDateArr($days);
        $customerArr = $dateArr;
        if (is_array($coll) && !empty($coll)) {
            foreach ($coll as $order) {
                $created_at = $order['created_at'];
                $created_at_str = date("Y-m-d", $created_at);
                if (isset($customerArr[$created_at_str])) {
                    $customerArr[$created_at_str] += 1;
                }
            }
        }
        
        return [
            '用户注册数' => $customerArr,
        ];
    }
}
