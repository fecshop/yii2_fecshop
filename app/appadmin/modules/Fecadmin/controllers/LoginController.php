<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appadmin\modules\Fecadmin\controllers;
use Yii;
use fec\helpers\CConfig;
use fecadmin\FecadminbaseController;
use fecshop\app\appadmin\modules\AppadminController;

use yii\helpers\Url;
use fec\helpers\CModel;
use fec\helpers\CDate;
use fecadmin\models\AdminUser\AdminUserLogin;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class LoginController extends \fecadmin\controllers\LoginController
{
	public $enableCsrfValidation = true;
    public $blockNamespace;
    
    /**
     * init theme component property : $fecshopThemeDir and $layoutFile
     * $fecshopThemeDir is appfront base theme directory.
     * layoutFile is current layout relative path.
     */
    public function init()
    {
        parent::init();
        if (!Yii::$service->page->theme->fecshopThemeDir) {
            Yii::$service->page->theme->fecshopThemeDir = Yii::getAlias(CConfig::param('appadminBaseTheme'));
        }
        if (!Yii::$service->page->theme->layoutFile) {
            Yii::$service->page->theme->layoutFile = CConfig::param('appadminBaseLayoutName');
        }
        // 设置本地模板路径
        $localThemeDir = Yii::$app->params['localThemeDir'];
        if($localThemeDir){
            Yii::$service->page->theme->setLocalThemeDir($localThemeDir);
        }
        /*
         *  set i18n translate category.
         */
        Yii::$service->page->translate->category = 'appadmin';
        Yii::$service->page->theme->layoutFile = 'login.php';  
    }
    
    public function actionIndex()
    {
        $langCode = Yii::$app->request->get('lang');
        if (!$langCode) {
            $langCode = 'zh';
        }
        Yii::$service->admin->setCurrentLangCode($langCode);
        $isGuest = Yii::$app->user->isGuest;
        //echo $isGuest;exit;
        if(!$isGuest){
            //$this->redirect("/",200);
            Yii::$app->getResponse()->redirect("/")->send();
            return;
        }
        $errors = '';
        $loginParam = Yii::$app->request->post('login');
        if($loginParam){
            $AdminUserLogin = new AdminUserLogin;
            $AdminUserLogin->attributes = $loginParam;
            if($AdminUserLogin->login()){
                //\fecadmin\helpers\CSystemlog::saveSystemLog();
                Yii::$service->admin->systemLog->save();
                //$this->redirect("/",200)->send();
                Yii::$app->getResponse()->redirect("/")->send();

                return;
            }else{
                $errors = CModel::getErrorStr($AdminUserLogin->errors);
            }
        }

        return $this->render('index',[
            'error' => $errors,
        ]);
    }
    
    public function actionChangelang(){
        $langCode = Yii::$app->request->get('lang');
        if ($langCode) {
            $status = Yii::$service->admin->setCurrentLangCode($langCode);
            if ($status) {
                echo json_encode([
                    'status' => 'success'
                ]);
                exit;
            }
        }  
        
        echo json_encode([
            'status' => 'fail'
        ]);
        exit;
    }
   
    /**
     * @param $view|string , (only) view file name ,by this module id, this controller id , generate view relative path.
     * @param $params|Array,
     * 1.get exist view file from mutil theme by theme protity.
     * 2.get content by yii view compontent  function renderFile()  ,
     */
    public function render($view, $params = [])
    {
        $viewFile = Yii::$service->page->theme->getViewFile($view);
        $content = Yii::$app->view->renderFile($viewFile, $params, $this);

        return $this->renderContent($content);
    }

    /**
     * Get current layoutFile absolute path from mutil theme dir by protity.
     */
    public function findLayoutFile($view)
    {
        $layoutFile = '';
        $relativeFile = 'layouts/'.Yii::$service->page->theme->layoutFile;
        $absoluteDir = Yii::$service->page->theme->getThemeDirArr();
        foreach ($absoluteDir as $dir) {
            if ($dir) {
                $file = $dir.'/'.$relativeFile;
                if (file_exists($file)) {
                    $layoutFile = $file;
                    return $layoutFile;
                }
            }
        }
        throw new InvalidValueException('layout file is not exist!');
    }
    
}








