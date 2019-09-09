<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\System\controllers;

use fecshop\app\appadmin\modules\System\SystemController;
use Yii;
use fec\helpers\CRequest;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ExtensionmarketController extends SystemController
{
    public $enableCsrfValidation = true;
    public $_param = [];
    public $_pageNum = 1;
    public $_numPerPage = 10;
    
    
    public function actionManager()
    {
        // 如果用户没有登陆。
        if (!Yii::$service->extension->remoteService->isLogin()) {
            $data = [
                'guest' => true,
            ];
            return $this->render($this->action->id, $data);
        }
        
        // 加载页面
        $param = CRequest::param();
        if (empty($param['pageNum'])) {
            $param['pageNum'] = $this->_pageNum;
        }
        if (empty($param['numPerPage'])) {
            $param['numPerPage'] = $this->_numPerPage;
        }
         
        if (is_array($param) && !empty($param)) {
            $this->_param = array_merge($this->_param, $param);
        }
        
        
        // 获取我的应用信息，如果获取失败，说明需要重新登陆
        $info = Yii::$service->extension->remoteService->getMyAddonsInfo($this->_param['pageNum'], $this->_param['numPerPage'] );
        if (!$info) {
            $data = [
                'guest' => true,
            ];
            return $this->render($this->action->id, $data);
        }
       
        
        $data = $this->getBlock()->getLastData($this->_param, $info);

        return $this->render($this->action->id, $data);
    }

    public function actionLogin()
    {
        // 是否post，如果是post，那么进行远程登陆。
        $param = Yii::$app->request->post('editForm');
        if (!empty($param) && is_array($param)) {
            $this->getBlock()->login($param);
        }
        
        $data = $this->getBlock()->getLastData();

        return $this->render($this->action->id, $data);
    }
    
    public function actionAdministertest()
    {
        $namespace = Yii::$app->request->get('namespace');
        $packageName = Yii::$app->request->get('packageName');
        $folderName = Yii::$app->request->get('folderName');
        $addonName = Yii::$app->request->get('addonName');
        $pType = Yii::$app->request->get('p_type');
        if ($pType == 'install') {
            // 进行插件的安装
            if (!Yii::$service->extension->administer->testInstall($namespace)) {
                $errors = Yii::$service->helper->errors->get(',');
                echo  json_encode([
                    'statusCode' => '300',
                    'message'    => Yii::$service->page->translate->__($errors),
                ]);
                exit;
            }
        } else if ($pType == 'upgrade'){
            if (!Yii::$service->extension->administer->testUpgrade($namespace)) {
                $errors = Yii::$service->helper->errors->get(',');
                echo  json_encode([
                    'statusCode' => '300',
                    'message'    => Yii::$service->page->translate->__($errors),
                ]);
                exit;
            }
        } else if ($pType == 'uninstall'){
            if (!Yii::$service->extension->administer->testUninstall($namespace)) {
                $errors = Yii::$service->helper->errors->get(',');
                echo  json_encode([
                    'statusCode' => '300',
                    'message'    => Yii::$service->page->translate->__($errors),
                ]);
                exit;
            }
        } else {
            echo  json_encode([
                'statusCode' => '300',
                'message'    => Yii::$service->page->translate->__('error param'),
            ]);
            exit;
        }
        // 输入安装成功信息。
        echo  json_encode([
            'statusCode' => '200',
            'message'    => Yii::$service->page->translate->__('administer test {pType} success', ['pType' => $pType]),
        ]);
        exit;
    }
    
    public function actionInstall()
    {
        $namespace = Yii::$app->request->get('namespace');
        $packageName = Yii::$app->request->get('packageName');
        $folderName = Yii::$app->request->get('folderName');
        $addonName = Yii::$app->request->get('addonName');
        
        //  进行zip文件下载到指定的文件路径
        $zipFilePath = Yii::$service->extension->remoteService->downloadAddons($namespace, $packageName, $folderName, $addonName);
        if (!$zipFilePath) {
            echo  json_encode([
                'statusCode' => '300',
                'message'    => Yii::$service->page->translate->__('download remote addons fail'),
            ]);
            exit;
        }
        // 进行zip文件的解压
        $dest_dir = dirname($zipFilePath);
        if (!Yii::$service->helper->zipFile->unzip($zipFilePath, $dest_dir, true, true)) {
            echo  json_encode([
                'statusCode' => '300',
                'message'    => Yii::$service->page->translate->__('unzip addons fail'),
            ]);
            exit;
        }
        // 删除zip压缩包 
        unlink($zipFilePath);
        
        /**
          * 对于某些比较大的应用插件，下载时间可能需要几分钟
          * 对于mysql，如果设置了超时时间，会超时导致无法执行sql，进行mysql重连。报错
          * 因此下面对mysql进行了关闭，重新打开
          */
        \Yii::$app->db->close();
        \Yii::$app->db->open();
        
        // 将addons信息写入数据库
        /*
        array(6) {
            ["id"]=>
            string(2) "50"
            ["namespace"]=>
            string(13) "fectfurnilife"
            ["package"]=>
            string(7) "fecmall"
            ["name"]=>
            string(15) "furnilife_theme"
            ["folder"]=>
            string(26) "furnilife_theme"
            ["version"]=>
            string(5) "1.0.0"
        }
        */
        $data = Yii::$service->extension->remoteService->getAddonsInfoByNamespace($namespace);
        if (!is_array($data)) {
            echo  json_encode([
                'statusCode' => '300',
                'message'    => Yii::$service->page->translate->__('get remote addons info by namespace fail'),
            ]);
            exit;
        }
        // 将远程获取的数据，保存到数据库中。
        if (!Yii::$service->extension->newInstallInit($data)){
            $errors = Yii::$service->helper->errors->get(',');
            echo  json_encode([
                'statusCode' => '300',
                'message'    => Yii::$service->page->translate->__($errors),
            ]);
            exit;
        }
        // 进行插件的安装
        if (!Yii::$service->extension->administer->install($namespace)) {
            $errors = Yii::$service->helper->errors->get(',');
            echo  json_encode([
                'statusCode' => '300',
                'message'    => Yii::$service->page->translate->__($errors),
            ]);
            exit;
        }
        // 进行插件的升级
        if (!Yii::$service->extension->administer->upgrade($namespace)) {
            $errors = Yii::$service->helper->errors->get(',');
            echo  json_encode([
                'statusCode' => '300',
                'message'    => Yii::$service->page->translate->__($errors),
            ]);
            exit;
        }
        // 输入安装成功信息。
        echo  json_encode([
            'statusCode' => '200',
            'message'    => Yii::$service->page->translate->__('addons install success'),
        ]);
        exit;
    }
    
    // 升级
    public function actionUpgrade()
    {
        $namespace = Yii::$app->request->get('namespace');
        $packageName = Yii::$app->request->get('packageName');
        $addonName = Yii::$app->request->get('addonName');
        $folderName = Yii::$app->request->get('folderName');
        //  进行zip文件下载到指定的文件路径
        $zipFilePath = Yii::$service->extension->remoteService->downloadAddons($namespace, $packageName, $folderName, $addonName);
        if (!$zipFilePath) {
            echo  json_encode([
                'statusCode' => '300',
                'message'    => Yii::$service->page->translate->__('download remote addons fail'),
            ]);
            exit;
        }
        // 进行zip文件的解压
        $dest_dir = dirname($zipFilePath);
        if (!Yii::$service->helper->zipFile->unzip($zipFilePath, $dest_dir, true, true)) {
            echo  json_encode([
                'statusCode' => '300',
                'message'    => Yii::$service->page->translate->__('unzip addons fail'),
            ]);
            exit;
        }
        // 删除zip压缩包 
        unlink($zipFilePath);
        /**
          * 对于某些比较大的应用插件，下载时间可能需要几分钟
          * 对于mysql，如果设置了超时时间，会超时导致无法执行sql，进行mysql重连。报错
          * 因此下面对mysql进行了关闭，重新打开
          */
        \Yii::$app->db->close();
        \Yii::$app->db->open();
        
        // 将addons信息写入数据库
        /*
        array(6) {
            ["id"]=>
            string(2) "50"
            ["namespace"]=>
            string(13) "fectfurnilife"
            ["package"]=>
            string(7) "fecmall"
            ["name"]=>
            string(15) "furnilife_theme"
            ["config_file_path"]=>
            string(26) "furnilife_theme/config.php"
            ["version"]=>
            string(5) "1.0.0"
        }
        */
        $data = Yii::$service->extension->remoteService->getAddonsInfoByNamespace($namespace);
        if (!is_array($data)) {
            echo  json_encode([
                'statusCode' => '300',
                'message'    => Yii::$service->page->translate->__('get remote addons info by namespace fail'),
            ]);
            exit;
        }
        // 将远程获取的数据，保存到数据库中。
        if (!Yii::$service->extension->upgradeInit($data)){
            echo  json_encode([
                'statusCode' => '300',
                'message'    => Yii::$service->page->translate->__('init new install addon to db fail'),
            ]);
            exit;
        }
        // 进行插件的升级
        if (!Yii::$service->extension->administer->upgrade($namespace)) {
            $errors = Yii::$service->helper->errors->get(',');
            echo  json_encode([
                'statusCode' => '300',
                'message'    => Yii::$service->page->translate->__($errors),
            ]);
            exit;
        }
        // 输入安装成功信息。
        echo  json_encode([
            'statusCode' => '200',
            'message'    => Yii::$service->page->translate->__('addons install success'),
        ]);
        exit;
    }
    
    
    
    // 卸载
    public function actionUninstall()
    {
        $namespace = Yii::$app->request->get('namespace');
        $packageName = Yii::$app->request->get('packageName');
        $addonName = Yii::$app->request->get('addonName');
        $folderName = Yii::$app->request->get('folderName');
        
        // 进行插件的卸载
        if (!Yii::$service->extension->administer->uninstall($namespace)) {
            $errors = Yii::$service->helper->errors->get(',');
            echo  json_encode([
                'statusCode' => '300',
                'message'    => Yii::$service->page->translate->__($errors),
            ]);
            exit;
        }
        // 输出卸载成功信息。
        echo  json_encode([
            'statusCode' => '200',
            'message'    => Yii::$service->page->translate->__('addons uninstall success'),
        ]);
        exit;
    }
    /*
    public function actionManagereditsave()
    {
        $data = $this->getBlock('manageredit')->save();
    }

    public function actionManagerdelete()
    {
        $this->getBlock('manageredit')->delete();
    }
    */
}
