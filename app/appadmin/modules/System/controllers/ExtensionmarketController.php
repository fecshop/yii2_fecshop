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

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ExtensionmarketController extends SystemController
{
    public $enableCsrfValidation = true;
    
    
    public function actionManager()
    {
        // 如果用户没有登陆。
        if (!Yii::$service->extension->remoteService->isLogin()) {
            $data = [
                'guest' => true,
            ];
            return $this->render($this->action->id, $data);
        }
        // 获取我的应用信息，如果获取失败，说明需要重新登陆
        $info = Yii::$service->extension->remoteService->getMyAddonsInfo();
        if (!$info) {
            $data = [
                'guest' => true,
            ];
            return $this->render($this->action->id, $data);
        }
        // 加载页面
        
        $data = $this->getBlock()->getLastData($info);

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
    
    public function actionInstall()
    {
        $namespace = Yii::$app->request->get('namespace');
        $packageName = Yii::$app->request->get('packageName');
        $addonName = Yii::$app->request->get('addonName');
        
        //  进行zip文件下载到指定的文件路径
        $zipFilePath = Yii::$service->extension->remoteService->downloadAddons($namespace, $packageName, $addonName);
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
        if (!Yii::$service->extension->newInstallInit($data)){
            echo  json_encode([
                'statusCode' => '300',
                'message'    => Yii::$service->page->translate->__('init new install addon to db fail'),
            ]);
            exit;
        }
        // 进行插件的安装
        if (!Yii::$service->extension->administer->install($namespace)) {
            $errors = Yii::$service->helper->errors->get();
            echo  json_encode([
                'statusCode' => '300',
                'message'    => Yii::$service->page->translate->__($errors),
            ]);
            exit;
        }
        
        
        
        
        echo  json_encode([
            'statusCode' => '200',
            'message'    => Yii::$service->page->translate->__('addons install success'),
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
