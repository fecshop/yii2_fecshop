<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services;

//use fecshop\models\mysqldb\Customer as CustomerModel;
//use fecshop\models\mysqldb\customer\CustomerLogin;
//use fecshop\models\mysqldb\customer\CustomerRegister;
use Yii;

/**
 * Customer service. 前端用户部分
 * @property Image|\fecshop\services\Product\Image $image ,This property is read-only.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Customer extends Service
{
    public $customer_register;
    const USER_LOGIN_SUCCESS_REDIRECT_URL_KEY = 'usr_login_success_redirect_url';
    
    protected $_customerModelName = '\fecshop\models\mysqldb\Customer';
    protected $_customerModel;
    protected $_customerLoginModelName = '\fecshop\models\mysqldb\customer\CustomerLogin';
    protected $_customerLoginModel;
    protected $_customerRegisterModelName = '\fecshop\models\mysqldb\customer\CustomerRegister';
    protected $_customerRegisterModel;
    
    public function __construct(){
        list($this->_customerModelName,$this->_customerModel) = \Yii::mapGet($this->_customerModelName); 
        list($this->_customerLoginModelName,$this->_customerLoginModel) = \Yii::mapGet($this->_customerLoginModelName);  
        list($this->_customerRegisterModelName,$this->_customerRegisterModel) = \Yii::mapGet($this->_customerRegisterModelName);  
        
    }
    /**
     * 注册用户名字的最小长度.
     */
    protected function actionGetRegisterNameMinLength()
    {
        if (isset($this->customer_register['min_name_length'])) {
            return $this->customer_register['min_name_length'];
        }
    }

    /**
     * 注册用户名字的最大长度.
     */
    protected function actionGetRegisterNameMaxLength()
    {
        if (isset($this->customer_register['max_name_length'])) {
            return $this->customer_register['max_name_length'];
        }
    }

    /**
     * 注册用户密码的最小长度.
     */
    protected function actionGetRegisterPassMinLength()
    {
        if (isset($this->customer_register['min_pass_length'])) {
            return $this->customer_register['min_pass_length'];
        }
    }

    /**
     * 注册用户密码的最大长度.
     */
    protected function actionGetRegisterPassMaxLength()
    {
        if (isset($this->customer_register['max_pass_length'])) {
            return $this->customer_register['max_pass_length'];
        }
    }

    /**
     * @property $data|array
     * 数组格式：['email'=>'xxx@xxx.com','password'=>'xxxx']
     */
    protected function actionLogin($data)
    {
        $model = new $this->_customerLoginModelName();
        $model->email       = $data['email'];
        $model->password    = $data['password'];
        $loginStatus        = $model->login();
        $errors             = $model->errors;
        if (empty($errors)) {
            // 合并购物车数据
            Yii::$service->cart->mergeCartAfterUserLogin();
        } else {
            Yii::$service->helper->errors->addByModelErrors($errors);
        }

        return $loginStatus;
    }

    /**
     * @property $data|array 数据格式如下：
     * [
     *      'email',
     *      'firstname',
     *      'lastname',
     *      'password'
     * ]
     * register customer account，
     */
    protected function actionRegister($param)
    {
        $model = new $this->_customerRegisterModelName();
        $model->attributes = $param;
        if ($model->validate()) {
            $model->created_at = time();
            $model->updated_at = time();

            $model->save();
            return true;
        } else {
            $errors = $model->errors;
            Yii::$service->helper->errors->addByModelErrors($errors);

            return false;
        }
    }
    /**
     * @property $email | String , email字符串
     * 查看该email是否被注册过。
     */
    protected function actionIsRegistered($email)
    {
        $customer = $this->_customerModel->findOne(['email' => $email]);
        if ($customer['email']) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * @property $param | array ，用户的数组
     * 数据格式如下：
     * ['email' => 'xxx', 'password' => 'xxxx','firstname' => 'xxx','lastname' => 'xxx',]
     * 保存customer信息
     */
    protected function actionSave($param)
    {
        $primaryKey = $this->getPrimaryKey();
        $primaryVal = isset($param[$primaryKey]) ? $param[$primaryKey] : '';
        if ($primaryVal) {
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
        }
    }

    /**该方法已废弃
     * @property $customerId|int
     * Get customer info by customerId, if customer id is empty, current customer id will be set,
     * if current customer id is empty , false will be return .
     */
    protected function actionViewInfo($customerId = '')
    {
    }

    /**
     * @property $password|string
     * @property $customerId|int or String or Object
     * 更改用户的密码。
     */
    protected function actionChangePassword($password, $identity)
    {
        if (is_int($identity)) {
            $customer_id = $identity;
            $customerModel = $this->_customerModel->findIdentity($customer_id);
        } elseif (is_string($identity)) {
            $email = $identity;
            $customerModel = $this->_customerModel->findByEmail($email);
        } elseif (is_object($identity)) {
            $customerModel = $identity;
        }
        if($customerModel['email']){
            $customerModel->updated_at = time();
            $customerModel->setPassword($password);
            $customerModel->save();
            return true;
        }else{
            return false;
        }
    }

    /**
     * 得到category model的全名.
     */
    protected function actionGetModelName()
    {
        $model = new $this->_customerModelName();

        return get_class($model);
    }
    /**
     * 通过主键，得到customer model
     */
    protected function actionGetByPrimaryKey($val)
    {
        if ($val) {
            $one = $this->_customerModel->findOne($val);
            $primaryKey = $this->getPrimaryKey();
            if ($one[$primaryKey]) {
                return $one;
            } else {
                return new $this->_customerModelName();
            }
        }
    }

    /**
     * @property $password|string
     * @property $customerId|int or String or Object
     * change  customer password.
     * 更改密码，然后，清空token
     */
    protected function actionChangePasswordAndClearToken($password, $identity)
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

            return;
        }
        //echo $password;exit;
        $customerModel->setPassword($password);
        $customerModel->removePasswordResetToken();
        $customerModel->updated_at = time();
        $customerModel->save();

        return true;
    }

    /**废弃
     * @property $customerId|array
     * ['firstname','lastname','password','customerId']
     */
    protected function actionChangeNameAndPassword($data)
    {
    }

    /**
     * get current customer .
     */
    /* 废弃
    protected function actionGetCurrentAccount()
    {
        return Yii::$app->user->identity->username;
    }
    */

    /**
     * @property $email | string ， email string
     * get $this->_customerModel by Email address.
     */
    protected function actionGetUserIdentityByEmail($email)
    {
        $one = $this->_customerModel->findByEmail($email);
        if ($one['email']) {
            return $one;
        } else {
            return false;
        }
    }

    /**
     * @property $identify|object(customer object) or String
     * @return 生成的resetToken，如果生成失败返回false
     * 生成resetToken，用来找回密码
     */
    protected function actionGeneratePasswordResetToken($identify)
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
     * @property $token | String 
     * 通过PasswordResetToken 得到user.
     */
    protected function actionFindByPasswordResetToken($token)
    {
        return $this->_customerModel->findByPasswordResetToken($token);
    }

    /**
     * @property $url|string
     * **注意**：该方法不能在接口类型里面使用
     * 在一些功能中，需要用户进行登录操作，等用户操作成功后，应该跳转到相应的页面中，这里通过session存储需要跳转到的url。
     * 某些页面 ， 譬如评论页面，需要用户登录后才能进行登录操作，那么可以通过这个方法把url set 进去，登录成功
     * 后，页面不会跳转到账户中心，而是需要操作的页面中。
     */
    protected function actionSetLoginSuccessRedirectUrl($url)
    {
        return Yii::$service->session->set($this::USER_LOGIN_SUCCESS_REDIRECT_URL_KEY, $url);
    }

    /**
     * @property $url|string
     * **注意**：该方法不能在接口类型里面使用
     * **注意**：该方法不能在接口类型里面使用
     * 在一些功能中，需要用户进行登录操作，等用户操作成功后，应该跳转到相应的页面中，这里通过session得到需要跳转到的url。
     */
    protected function actionGetLoginSuccessRedirectUrl()
    {
        $url = Yii::$service->session->get($this::USER_LOGIN_SUCCESS_REDIRECT_URL_KEY);

        return $url ? $url : '';
    }
    /**
     * @property $urlKey | String
     * **注意**：该方法不能在接口类型里面使用
     * 登录用户成功后，进行url跳转。
     */
    protected function actionLoginSuccessRedirect($urlKey = '')
    {
        $url = $this->getLoginSuccessRedirectUrl();

        if ($url) {
            // 这个优先级最高
            // 在跳转之前，去掉这个session存储的值。跳转后，这个值必须失效。
            Yii::$service->session->remove($this::USER_LOGIN_SUCCESS_REDIRECT_URL_KEY);
            //echo Yii::$service->session->get($this::USER_LOGIN_SUCCESS_REDIRECT_URL_KEY);
            //exit;
            return Yii::$service->url->redirect($url);
        } else if($urlKey) {
            return Yii::$service->url->redirectByUrlKey($urlKey);
        } else {
            return Yii::$service->url->redirectHome();
        }
    }

    /**
     * 得到status为删除状态的值
     */
    protected function actionGetStatusDeleted()
    {
        $model = $this->_customerModel;
        return $model::STATUS_DELETED;
    }

    /**
     * 得到status为激活状态的值
     */
    protected function actionGetStatusActive()
    {
        $model = $this->_customerModel;
        return $model::STATUS_ACTIVE;
    }
    /**
     * 得到customer 表的主键（mysql表）
     */
    protected function actionGetPrimaryKey()
    {
        return 'id';
    }
    /**
     * @property $filter|array
     * get  collection by $filter
     * example filter:
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
     * 通过上面的filter数组，得到过滤后的用户数据列表集合。
     */
    protected function actionColl($filter = '')
    {
        $query = $this->_customerModel->find();
        $query = Yii::$service->helper->ar->getCollByFilter($query, $filter);
        //var_dump($query->all());exit;
        return [
            'coll' => $query->all(),
            'count'=> $query->limit(null)->offset(null)->count(),
        ];
    }
    /**
     * @property $user_ids | Array ， 子项为Int类型
     * @return Array ，数据格式为：
     * ['id' => 'email']
     * 得到customer id 和customer email的对应数组。
     */
    protected function actionGetEmailByIds($user_ids)
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
     * @property  $user | Array ,example:
     * ['first_name' => $first_name,'last_name' => $last_name,'email' => $email,]
     * @property  $type | String 代表第三方登录的名称，譬如google，facebook
     * @return bool
     * 如果用户emai存在，则直接登录，成功后返回true
     * 如果用户不存在，则注册用户，然后直接登录，成功后返回true
     */
    protected function actionRegisterThirdPartyAccountAndLogin($user, $type)
    {
        
        // 查看邮箱是否存在
        $email = $user['email'];
        $customer_one = Yii::$service->customer->getUserIdentityByEmail($email);
        if ($customer_one) {
            $loginStatus = \Yii::$app->user->login($customer_one);
            if ($loginStatus) {
                return true;
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
                $loginStatus = Yii::$service->customer->login($registerData);
                if ($loginStatus) {
                    return true;
                }
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
        //return $authnum;
        return $authnum;
    }
    /** AppServer 部分使用的函数
     * @property $email | String
     * @property $password | String
     * 无状态登录，通过email 和password进行登录
     * 登录成功后，合并购物车，返回accessToken
     * ** 该函数是未登录用户，通过参数进行登录需要执行的函数。
     */
    protected function actionLoginAndGetAccessToken($email,$password){
        $header = Yii::$app->request->getHeaders();
        if(isset($header['access-token']) && $header['access-token']){
            $accessToken = $header['access-token'];
        }   
        if($accessToken){
            $identity = Yii::$app->user->loginByAccessToken($accessToken);
            if ($identity !== null) {
                $access_token_created_at = $identity->access_token_created_at;
                $timeout = Yii::$service->session->timeout;
                if($access_token_created_at + $timeout > time()){
                    return $accessToken;
                } 
            }
        }
        
        $data = [
            'email'     => $email,
            'password'  => $password,
        ];
        
        if(Yii::$service->customer->login($data)){
            $identity = Yii::$app->user->identity;
            $identity->generateAccessToken();
            $identity->access_token_created_at = time();
            $identity->save();
            # 执行购物车合并等操作。
            Yii::$service->cart->mergeCartAfterUserLogin();
            return $identity->access_token;
            
        }
    }
    /** AppServer 部分使用的函数
     * @property $type | null or  Object
     * 从request headers中获取access-token，然后执行登录
     * 如果登录成功，然后验证时间是否过期
     * 如果不过期，则返回identity
     * ** 该方法为appserver用户通过access-token验证需要执行的函数。
     */
    protected function actionLoginByAccessToken($type = null){
        $header = Yii::$app->request->getHeaders();
        if(isset($header['access-token']) && $header['access-token']){
            $accessToken = $header['access-token'];
        }
        if($accessToken){
            $identity = Yii::$app->user->loginByAccessToken($accessToken, $type);
            if ($identity !== null) {
                $access_token_created_at = $identity->access_token_created_at;
                $timeout = Yii::$service->session->timeout;
                // 如果时间没有过期，则返回identity
                if($access_token_created_at + $timeout > time()){
                    //如果时间没有过期，但是快要过期了，在过$updateTimeLimit段时间就要过期，那么更新access_token_created_at。
                    $updateTimeLimit = Yii::$service->session->updateTimeLimit;
                    if($access_token_created_at + $timeout <= (time() + $updateTimeLimit )){
                        $identity->access_token_created_at = time();
                        $identity->save();
                    }
                    return $identity;
                }
            }
        }
    }
}
