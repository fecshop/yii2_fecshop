<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license/
 */
namespace fecshop\app\appinstall\modules\Database\controllers;

//use fecshop\models\mysqldb\AdminUser;
use Yii;
use yii\base\Exception;
use yii\web\Controller;
use fecshop\models\mysqldb\StoreDomain;
use fecshop\models\mysqldb\StoreBaseConfig;


/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ConfigController extends Controller
{
    /**
     * 增加前置动作，检查指定目录是否存在 install.lock 锁文件，存在则不允许再次安装
     * @param \yii\base\Action $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     * @author Yanlongli<ahlyl94@gmail.com>
     */
    public function beforeAction($action)
    {
        if ($this->action->id  != 'complete') {
            $installLockFilePath = $this->getInstallLockFilePath();
            try {
                if (file_exists($installLockFilePath) && "1" === file_get_contents($installLockFilePath)) {
                    Yii::$app->response->data = "您的项目可能已安装成功，如果需要重新安装，请手动删除项目目录的 <code>".$installLockFilePath."</code> 文件";
                    return false;
                } else {
                    file_put_contents($installLockFilePath, "0");
                }
            } catch (\Exception $exception) {
                Yii::$app->response->data = $exception->getMessage();
                return false;
            }
        }
        

        return parent::beforeAction($action);
    }
    
    protected function getInstallLockFilePath()
    {
        return Yii::getAlias('@appfront/runtime/install.lock');
    }

    public function init()
    {
        parent::init();
    }

    // public $_migrateLog = '';

    // 安装默认第一步页面
    public function actionIndex()
    {
        $database = Yii::$app->request->get('database');
        if (!$database) {
            return $this->render('readme', []);
        }
        
        
        $editForm = Yii::$app->request->post('editForm');
        if ($editForm && $this->checkDatabaseData($editForm)
            && $this->updateDatabaseConfig($editForm)) {
            Yii::$app->session->setFlash('database-success', 'Mysql配置成功，写入的配置文件路径为: @common/config/main-local.php');
            // 进行跳转
            $homeUrl = Yii::$app->homeUrl;
            return $this->redirect($homeUrl . '/database/config/migrate');
        }
        $errorInfo = Yii::$app->session->getFlash('database-errors');
        $errorInfo = $this->getErrorHtml($errorInfo);
        
        
        return $this->render($this->action->id, [
            'errorInfo' => $errorInfo,
            'editForm' => $editForm,
        ]);
    }
    
    

    // 数据库migrate页面
    public function actionMigrate()
    {

        $isPost = Yii::$app->request->post('isPost');
        if ($isPost ) {
            // 进行数据库初始化
            if ($this->runMigrate()) {
                
                $successInfo = '数据库migrate初始化完成';
                $successInfo = $this->getSuccessHtml($successInfo);
                //exit;
                return $this->render('migratesuccess', [
                    'successInfo' => $successInfo,
                    'nextUrl' => Yii::$app->homeUrl . '/database/config/addtestdata',
                    'skipUrl' => Yii::$app->homeUrl . '/database/config/initdomain',
                ]);
            } else {
                $errors = 'migrate 失败，你可以在logs文件中查看具体原因（@appfront/config/main.php中log组件，对应的logFile配置，查看该log文件，如果没有可以手动创建该log文件，清空数据库，重新执行该操作）';
                Yii::$app->session->setFlash('migrate-errors', $errors);
            }
        }

        $successInfo = Yii::$app->session->getFlash('database-success');
        $successInfo = $this->getSuccessHtml($successInfo);
        $errorInfo = Yii::$app->session->getFlash('migrate-errors');
        $errorInfo = $this->getErrorHtml($errorInfo);

        return $this->render($this->action->id, [
            'successInfo' => $successInfo,
            'errorInfo' => $errorInfo,
        ]);
    }
    
    
    
   /*
                $superUserInfo = Yii::$app->session->get('super_account', [
                    'username' => 'admin',
                    'userpassword' => 'admin123',
                    'useremail' => 'admin@fecmall.com',
                ]);
                
                $param = [
                    'username' =>  $superUserInfo['username'],
                    'password' =>  $superUserInfo['userpassword'],
                    'email' =>  $superUserInfo['useremail'],
                ];
                $this->installUpdateUser($param);
                
                Yii::$app->session->remove('super_account');
*/
                
                
    /**
     * 因为安装部分没有引入services，因此无法使用Yii::$service
     * 安装更新User
     */
    protected $_install_errors;
    
    public function installUpdateUser($param)
    {
        // $userFormModelName = '\fecshop\models\mysqldb\adminUser\AdminUserForm';
        // list($userFormModelName, $userFormModel) = \Yii::mapGet($userFormModelName);
        $userFormModel = new \fecshop\models\mysqldb\adminUser\AdminUserForm;
        $model = $userFormModel->findOne(['id' => 2]); // 初始化admin表的user为2
        if (!$model) {
            //Yii::$service->helper->errors->add('admin user[id=2] is not exist');
            $this->_install_errors = 'admin user[id=2] is not exist';
            return false;
        }
        
        $model->attributes = $param;
        if ($model->validate()) {
            $model->save();
            
            return true;
        } else {
            $errors = $model->errors;
            if (is_array($errors) && !empty($errors)) {
                foreach ($errors as $one) {
                    $this->_install_errors .= implode(',', $one);
                }
            }
            
            
            return false;
        }    
        
        
        
        echo $param['username'];echo $param['password'];
        $model->username = $param['username'];
        $model->setPassword($param['password']); // = Yii::$app->security->generatePasswordHash($superUserInfo['userpassword'], 6);;
        //$model->email = $param['email'];
        //$model->updated_at = time();
        if ($model->validate()) {
            //var_dump($model);
            echo 55;
            exit;
            return $model->save();
            
            return true;
        } else {
            $errors = $model->errors;
            $this->_install_errors = $errors;
			//Yii::$service->helper->errors->addByModelErrors($errors);
            var_dump($this->_install_errors);
            exit;
            return false;
        }    
    }

    // 产品测试数据添加
    public function actionAddtestdata()
    {
        if ($this->addProductData()) {
            $successInfo = $this->getSuccessHtml('产品测试数据添加成功');
        } else {
            $errorInfo = Yii::$app->session->getFlash('add-test-data-errors');
            $errorInfo = $this->getErrorHtml($errorInfo);
        }
         
        return $this->render($this->action->id, [
            'errorInfo' => $errorInfo,
            'successInfo' => $successInfo,
            'initUrl' => Yii::$app->homeUrl . '/database/config/addtestdatainit',
            'nextUrl' => Yii::$app->homeUrl . '/database/config/initdomain',
            //'migrateLog'  => $this->_migrateLog
        ]);

    }
    
    // 域名部分
    public function actionInitdomain()
    {
        $editForm = Yii::$app->request->post('editForm');
        if (is_array($editForm) && !empty($editForm)) {
            $appfront_domain = $editForm['appfront_domain'];
            $img_domain = $editForm['img_domain'];
            $mall_type = $editForm['mall_type'];
            if ($appfront_domain && $img_domain && $mall_type) {
                $this->updateDomainConfig($editForm);
                
                return $this->redirect(Yii::$app->homeUrl . '/database/config/initadminuser');
            } else {
                $this->_install_errors .= 'pc域名,图片域名, 商城类型 不能为空';
            }
        }
        $errorInfo = $this->getErrorHtml($this->_install_errors);
        return $this->render($this->action->id, [
            'errorInfo' => $errorInfo,
            'editForm' => $editForm,
            'demoDomainList' => $this->getDemoDomainList(),
        ]);
        
    }
    
    // 将提交的信息更新到数据库
    public function updateDomainConfig($editForm)
    {
        $appfront_domain = $editForm['appfront_domain'];
        $img_domain = $editForm['img_domain'];
        $apphtml5_domain = $editForm['apphtml5_domain'];
        $appserver_domain = $editForm['appserver_domain'];
        $mall_type = $editForm['mall_type'];   // 商城类型
        $baseCurrencyCode = '';
        $storeLanguageCode = '';
        $storeLanguageName = '';
        
        if ($mall_type == 'china') {
            $baseCurrencyCode = 'CNY';
            $storeLanguageCode = 'zh-CN';
            $storeLanguageName = '中文';
        } else {
            $baseCurrencyCode = 'USD';
            $storeLanguageCode = 'en-US';
            $storeLanguageName = 'English';
        }
        // pc 
        if ($appfront_domain) {
            // appfront 更新
            $appfrontOne = StoreDomain::findOne([
                'app_name' => 'appfront',
                'status' => 1,
            ]);
            if ($appfrontOne['id']) {
                $appfrontOne['key'] = $appfront_domain;
                $appfrontOne['lang'] = $storeLanguageCode;
                $appfrontOne['lang_name'] = $storeLanguageName;
                $appfrontOne['currency'] = $baseCurrencyCode;
                $appfrontOne['mobile_type'] = 'apphtml5';
                if ($apphtml5_domain) {
                    $appfrontOne['mobile_redirect_domain'] = $apphtml5_domain;
                } else {
                    $appfrontOne['mobile_enable'] = 2;
                }
                $appfrontOne['updated_at'] = time();
                
                $appfrontOne->save();
            }
        }
        if ($apphtml5_domain) {
            // apphtml5 更新
            $apphtml5One = StoreDomain::findOne([
                'app_name' => 'apphtml5',
                'status' => 1,
            ]);
            if ($apphtml5One['id']) {
                $apphtml5One['key'] = $apphtml5_domain;
                $apphtml5One['lang'] = $storeLanguageCode;
                $apphtml5One['lang_name'] = $storeLanguageName;
                $apphtml5One['currency'] = $baseCurrencyCode;
                
                $apphtml5One['updated_at'] = time();
                $apphtml5One->save();
            }
        }
        // base_info
        if ($img_domain) {
            $baseConfig = StoreBaseConfig::findOne([
                'key' => 'base_info',
            ]);
            if ($baseConfig['id']) {
                $configArr = unserialize($baseConfig['value']);
                $configArr['image_domain'] = 'http://'.$img_domain;
                $configArr['base_currency'] = $baseCurrencyCode;
                
                $baseConfig['value'] = serialize($configArr);
                $baseConfig['updated_at'] = time();
                $baseConfig->save();
            }
        }
        // 设置汇率  // {s:13:"currency_code";s:3:"CNY";s:15:"currency_symbol";s:3:"￥";s:13:"currency_rate";s:3:"6.3";}}
        if ($mall_type == 'china') {
            $baseConfig = StoreBaseConfig::findOne([
                'key' => 'currency',
            ]);
            if ($baseConfig['id']) {
                $configArr = [
                    [
                        'currency_code' => $baseCurrencyCode,
                        'currency_symbol' => '￥',
                        'currency_rate' => 1,
                    ]
                ];
                
                $baseConfig['value'] = serialize($configArr);
                $baseConfig['updated_at'] = time();
                $baseConfig->save();
            }
        }
        
        
        if ($appserver_domain) {
            $baseConfig = StoreBaseConfig::findOne([
                'key' => 'appserver_store',
            ]);
            if ($baseConfig['id']) {
                $configArr = unserialize($baseConfig['value']);
                $configArr['key'] = $appserver_domain;
                $configArr['lang'] = $storeLanguageCode;
                $configArr['lang_name'] = $storeLanguageName;
                $configArr['currency'] = $baseCurrencyCode;
                
                $baseConfig['value'] = serialize($configArr);
                $baseConfig['updated_at'] = time();
                $baseConfig->save();
            }
        }
        
        
    }
    
    public function getDemoDomainList()
    {
        $domainStr = $_SERVER['SERVER_NAME'];
        $domainArr = explode('.', $domainStr);
        $subDomainStr = '';
        if (count($domainArr) == 2) {
            $subDomainStr = $domainStr;
        } else if  (count($domainArr) == 3) {
            $subDomainStr = $domainArr[1].'.'.$domainArr[2];
        } else {
            
            return [
                'demo_pc_domain' => '',
                'demo_admin_domain' => '',
                'demo_img_domain' => '',
                'demo_h5_domain' => '',
                'demo_appserver_domain' => '',
                'demo_appapi_domain' => '',
                'demo_appbdmin_domain' => '',
            ];
        }
        
        return [
            'demo_pc_domain' => 'www.' . $subDomainStr,
            'demo_admin_domain' => 'appadmin.' . $subDomainStr,
            'demo_img_domain' => 'img.' . $subDomainStr,
            'demo_h5_domain' => 'm.' . $subDomainStr,
            'demo_appserver_domain' => 'appserver.' . $subDomainStr,
            'demo_appapi_domain' => 'appapi.' . $subDomainStr,
            'demo_appbdmin_domain' => 'appbdmin.' . $subDomainStr,
        ];
    }
    
    
    
    
    // 安装默认第一步页面
    public function actionInitadminuser()
    {
        $editForm = Yii::$app->request->post('editForm');
        if (is_array($editForm) && !empty($editForm)) {
            $param = [
                'username' => $editForm['username'],
                'password' => $editForm['password'],
            ];
            if ($this->installUpdateUser($param)) {
                $homeUrl = Yii::$app->homeUrl;
                return $this->redirect($homeUrl . '/database/config/complete');
                
            }
        }
        $errorInfo = $this->getErrorHtml($this->_install_errors);
        return $this->render($this->action->id, [
            'errorInfo' => $errorInfo,
            'editForm' => $editForm,
        ]);
        
    }

    // 进行sql migrate ，产品图片的复制
    public function addProductData()
    {
        // 1. 图片的复制
        $sourcePath = dirname(Yii::getAlias('@common')) . '/environments/test_data/appimage';
        //$sourcePath = Yii::getAlias('@fectmelani/app/appimage');
        $targetPath = Yii::getAlias('@appimage');
        $this->copyDir($sourcePath, $targetPath);
        // 2. sql文件的执行
        $sqlFile = dirname(Yii::getAlias('@common')) . '/environments/test_data/fecshop.sql';
        $sqlStr = file_get_contents($sqlFile);
        $conn = Yii::$app->db;
        $innerTransaction = $conn->beginTransaction();
        try {
            $result = $conn->createCommand($sqlStr)->execute();
            $innerTransaction->commit();

            return true;
        } catch (\Exception $e) {
            $innerTransaction->rollBack();
            $message = $e->getMessage();
            Yii::$app->session->setFlash('add-test-data-errors', $message);
        }

        return false;
    }

    // 完成页面
    public function actionComplete()
    {
        
        // 锁定安装向导
        $installLockFilePath = $this->getInstallLockFilePath();
        file_put_contents($installLockFilePath, "1");

        return $this->render($this->action->id, []);
    }

    // 进行数据库的信息的检查，以及将数据库信息写入文件
    public function updateDatabaseConfig($editForm)
    {
        $host = $editForm['host'];
        $database = $editForm['database'];
        $user = $editForm['user'];
        $port = $editForm['port'];
        if (!$port) {
            $port = '3306';
        }
        $password = $editForm['password'];
        $dbConfig = [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host='.$host.';port='.$port.';dbname='.$database,
            'username' => $user,
            'password' => $password,
            'charset' => 'utf8',
        ];
        $connection = Yii::createObject($dbConfig);
        $connError = '';
        try {
            $connection->open();
        } catch (\Exception $e) {
            $connError = $e->getMessage();
            Yii::$app->session->setFlash('database-errors', $connError);

            return false;
        }
        // 将信息写入配置文件
        $mainLocalFile = Yii::getAlias("@common/config/main-local.php");
        if(!file_exists($mainLocalFile)){
            $errors = 'config file[@common/config/main-local.php] is not exist, you exec init command before install';
            Yii::$app->session->setFlash('database-errors', $errors);

            return false;
        }
        // 得到文件的内容
        $mainLocalInfo = file_get_contents($mainLocalFile);
        //$mainLocalInfo = require($mainLocalFile);
        // 进行文件替换
        $mainLocalInfo = str_replace('{mysql_host}', $host, $mainLocalInfo);
        $mainLocalInfo = str_replace('{mysql_port}', $port, $mainLocalInfo);
        $mainLocalInfo = str_replace('{mysql_database}', $database, $mainLocalInfo);
        $mainLocalInfo = str_replace('{mysql_user}', $user, $mainLocalInfo);
        $mainLocalInfo = str_replace('{mysql_password}', $password, $mainLocalInfo);
        // 写入配置文件
        if (@file_put_contents($mainLocalFile, $mainLocalInfo) === false) {
            $errors = 'Unable to write the file '.$mainLocalFile;
            Yii::$app->session->setFlash('database-errors', $errors);

            return false;
        }
        // 设置数据库文件644, 需要shell手动设置文件权限。
        /*
        if (@chmod($mainLocalFile,0644) === false) {
            $errors = 'Unable to set mainLocalFile 644, please change it';
            Yii::$app->session->setFlash('database', $errors);

            return false;
        }
        */
        return true;
    }

    // 检查前端传递的参数
    public function checkDatabaseData($editForm)
    {
        $session = Yii::$app->session;
        if (!$editForm['host']) {
            $session->setFlash('database-errors', 'Mysql数据库Host为空');

            return false;
        }
        if (!$editForm['database']) {
            $session->setFlash('database-errors', 'Mysql数据库名称为空');

            return false;
        }
        if (!$editForm['user']) {
            $session->setFlash('database-errors', 'Mysql数据库账户为空');

            return false;
        }
        if (!$editForm['password']) {
            $session->setFlash('database-errors', 'Mysql数据库密码为空');

            return false;
        }
        //if (!$editForm['username']) {
        //    $session->setFlash('database-errors', '超级管理员账户为空');
//
        //    return false;
        //}
        //if (!$editForm['useremail']) {
        //    $session->setFlash('database-errors', '超级账户邮箱为空');
//
        //    return false;
       // }
        //if (!$editForm['userpassword']) {
       //     $session->setFlash('database-errors', '超级账户密码为空');
//
        //    return false;
       // }

        return true;
    }

    public function getSuccessHtml($successInfo){
        if ($successInfo) {
            return '
            <div class="fecshop_message">
                    <div class="correct-msg">
                        <div>'. $successInfo .'</div>
                    </div>
            </div>
            ';
        }

        return '';
    }

    public function getErrorHtml($errorInfo){
        if ($errorInfo) {
            return '
                <div class="fecshop_message">
                        <div class="error-msg">
                            <div>'. $errorInfo .'</div>
                        </div>
                </div>
            ';
        }

        return '';
    }

    /**
     * 在yii web环境，执行console中的命令，
     * 该函数，相当于执行console命令行 `./yii migrate --interactive=0 --migrationPath=@fecshop/migrations/mysqldb`
     */
    public function runMigrate()
    {
        $bashPath = dirname(Yii::getAlias('@appfront'));
        $oldApp = Yii::$app;
        $aliases = Yii::$aliases;
        Yii::$app = new \yii\console\Application([
            'id' => 'install-console',
            'basePath' => $bashPath,
            'components' => [
                'db' => $oldApp->db,
            ],
        ]);
        ob_start();
        ob_implicit_flush(false);
        $runResult = Yii::$app->runAction('migrate/up', ['migrationPath' => '@fecshop/migrations/mysqldb', 'interactive' => false]);
        $post_log = ob_get_clean();
        Yii::info($post_log, 'fecshop_debug');
        Yii::$app = $oldApp;
        /**
         * aliases 需要重新设置，否则，将会导致配置文件中的  aliases 无法获取，譬如main.php中的
         *  'aliases' => [
         *     '@bower' => '@vendor/bower-asset',
         *     '@npm'   => '@vendor/npm-asset',
         *  ],
         */
        Yii::$aliases = $aliases;
        // $runResult 返回值，0代表执行完成，1代表执行出错。
        return $runResult === 0 ? true : false ;
    }

    // 创建文件夹，在图片文件复制的过程中使用。
    public function dirMkdir($path = '', $mode = 0777, $recursive = true)
    {
        clearstatcache();
        if (!is_dir($path)) {
            mkdir($path, $mode, $recursive);
            return chmod($path, $mode);
        }

        return true;
    }

    /**
     * 文件夹文件拷贝（递归）
     *
     * @param string $sourcePath 来源文件夹
     * @param string $targetPath 目的地文件夹
     * @param boolean $isForce 是否强制复制
     * @return bool
     */
    public function copyDir($sourcePath, $targetPath, $isForce = true)
    {
        if (empty($sourcePath) || empty($targetPath)) {
            return false;
        }
        $dir = opendir($sourcePath);
        $this->dirMkdir($targetPath);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                $sourcePathFile = $sourcePath . '/' . $file;
                $targetPathFile = $targetPath . '/' . $file;
                if (is_dir($sourcePathFile)) {
                    $this->copyDir($sourcePathFile, $targetPathFile);
                } else {
                    //copy($sourcePath . '/' . $file, $targetPath . '/' . $file);
                    if ($isForce) {
                        copy($sourcePathFile, $targetPathFile);
                    } else if (!file_exists($targetPathFile)) {
                        copy($sourcePathFile, $targetPathFile);
                    } else {
                        //Yii::$service->helper->errors->add('target path:' . $targetPathFile . ' is exist.');
                    }
                }
            }
        }
        closedir($dir);

        return true;
    }
}
