<?php

namespace fecshop\app\appinstall\modules\Database\controllers;

use Yii;

class ConfigController extends \yii\web\Controller
{
    public function init()
    {
        parent::init();
    }
    
    public $_migrateLog = '';
    
    // 数据库管理
    public function actionMigrate()
    {
        $successInfo = Yii::$app->session->getFlash('database-success');
        $successInfo = $this->getSuccessHtml($successInfo);
        
        $errorInfo = Yii::$app->session->getFlash('migrate-errors');
        $errorInfo = $this->getErrorHtml($errorInfo);
        
        return $this->render($this->action->id, [
            'successInfo' => $successInfo,
            'errorInfo' => $errorInfo,
            'initUrl' => Yii::$app->homeUrl . '/database/config/migrateprocess',
            'nextUrl' => Yii::$app->homeUrl . '/database/config/addtestdata',
            'migrateLog'  => $this->_migrateLog
        ]);
    }
    
    public function actionComplete()
    {
        // 执行 chmod 644 @common/config/main-local
        
        // 删除掉install.php文件
         return $this->render($this->action->id, [
        ]);
        
    }
    
    // 进行测试数据sql的安装和测试图片的复制
    public function actionAddtestdata()
    {
        $errorInfo = Yii::$app->session->getFlash('add-test-errors');
        $errorInfo = $this->getErrorHtml($errorInfo);
        
        return $this->render($this->action->id, [
            'errorInfo' => $errorInfo,
            'initUrl' => Yii::$app->homeUrl . '/database/config/addtestdatainit',
            'nextUrl' => Yii::$app->homeUrl . '/database/config/complete',
            //'migrateLog'  => $this->_migrateLog
        ]);
        
    }
    
    public function actionAddtestdatainit()
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
            echo json_encode([
                'status' => 'success',
            ]);exit;
            
        } catch (\Exception $e) {
            $innerTransaction->rollBack();
            $message = $e->getMessage();
            echo json_encode([
                'status' => 'fail',
                'info' => $message ,
            ]);exit;
            
        }
        
        echo json_encode([
                'status' => 'fail',
                'info' => 'error' ,
            ]);
        exit;
    }
    
   
    
    // 进行数据库的migrate操作
    public function actionMigrateprocess()
    {
        $this->runMigrate();
        exit;
    }
    
    
    // 安装默认第一步页面
    public function actionIndex()
    {
        
        $editForm = Yii::$app->request->post('editForm');
        
        if ($editForm && $this->checkDatabaseData($editForm) 
            && $this->updateDatabase($editForm)) {
                
            Yii::$app->session->setFlash('database-success', 'mysql config set success, mysql config file path: @common/config/main-local.php');
            // 进行跳转
            $homeUrl = Yii::$app->homeUrl;
            return $this->redirect($homeUrl.'/database/config/migrate'); 
        }
        $errorInfo = Yii::$app->session->getFlash('database-errors');
        $errorInfo = $this->getErrorHtml($errorInfo);
        return $this->render($this->action->id, [
            'errorInfo' => $errorInfo,
            'editForm' => $editForm,
        ]);
    }
    // 进行数据库的信息的检查，以及将数据库信息写入文件
    public function updateDatabase($editForm)
    {
        $host = $editForm['host'];
        $database = $editForm['database'];
        $user = $editForm['user'];
        $password = $editForm['password'];
        $dbConfig = [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host='.$host.';dbname='.$database,
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
        $mainLocalInfo = file_get_contents($mainLocalFile);
        //$mainLocalInfo = require($mainLocalFile);
        $mainLocalInfo = str_replace('{mysql_host}', $host, $mainLocalInfo);
        $mainLocalInfo = str_replace('{mysql_database}', $database, $mainLocalInfo);
        $mainLocalInfo = str_replace('{mysql_user}', $user, $mainLocalInfo);
        $mainLocalInfo = str_replace('{mysql_password}', $password, $mainLocalInfo);
        //var_dump($mainLocalInfo);exit;
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
    
    public function runMigrate()
    {
        $oldApp = \Yii::$app;
        Yii::$app = new \yii\console\Application([
            'id' => 'install-console',
            'basePath' => '@appfront',
            'components' => [
                'db' => $oldApp->db,
            ],
        ]);
        $dd = \Yii::$app->runAction('migrate/up', ['migrationPath' => '@fecshop/migrations/mysqldb', 'interactive' => false]);
        \Yii::$app = $oldApp;
        
        return $dd ;
    }
    
    /*
    public function runMigrate()
    {
        
        // 通过ob函数截取输出字符
        ob_start();
        ob_implicit_flush(false);
        extract(['oldApp' => \Yii::$app], EXTR_OVERWRITE);
        \Yii::$app = new \yii\console\Application([
            'id' => 'install-console',
            'basePath' => '@appfront',
            'components' => [
                'db' => $oldApp->db,
            ],
        ]);
        $result = \Yii::$app->runAction('migrate/up', ['migrationPath' => '@fecshop/migrations/mysqldb', 'interactive' => false]);
        \Yii::$app = $oldApp;
        $this->_migrateLog = ob_get_clean();
        
        return true;
    }
    */
    
    
    
    public function dir_mkdir($path = '', $mode = 0777, $recursive = true)
    {
        clearstatcache();
        if (!is_dir($path))
        {
            mkdir($path, $mode, $recursive);
            return chmod($path, $mode);
        }
     
        return true;
    }
     /**
     * 文件夹文件拷贝
     *
     * @param string $sourcePath 来源文件夹
     * @param string $targetPath 目的地文件夹
     * @param boolean $isForce 是否强制复制
     * @return bool
     */
    public function copyDir($sourcePath, $targetPath, $isForce = true)
    {
        if (empty($sourcePath) || empty($targetPath))
        {
            return false;
        }
     
        $dir = opendir($sourcePath);
        $this->dir_mkdir($targetPath);
        while (false !== ($file = readdir($dir)))
        {
            if (($file != '.') && ($file != '..')) {
                $sourcePathFile = $sourcePath . '/' . $file;
                $targetPathFile = $targetPath . '/' . $file;
                if (is_dir( $sourcePathFile)) {
                    $this->copyDir( $sourcePathFile, $targetPathFile);
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
