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
class AdminurlkeyController extends SystemController
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
        $adminUrlKeyInfo = Yii::$service->extension->remoteService->getDeveloperInfo();
        if (!$adminUrlKeyInfo) {
            $data = [
                'guest' => true,
            ];
            return $this->render($this->action->id, $data);
        }
        $generateStr = '';
        $sqlStr = '';
        // 如果提交数据，进行生成应用文件
        $param = Yii::$app->request->post('editFormData');
        if (!empty($param) && is_array($param)) {
            $sqlStr = $param['sql'];
            $generateStr = Yii::$service->extension->generate->adminUrlKeyInstallSqlGenerate($sqlStr);
            /*
            echo  json_encode([
                'statusCode' => '200',
                'message'    => Yii::$service->page->translate->__('创建应用成功,请到@addons文件夹下查看'),
            ]);
            exit;
            */
            
        }
        // 加载页面
        $data = $this->getBlock()->getLastData($adminUrlKeyInfo);
        $data['generateStr'] = $generateStr;
        $data['sqlStr'] = $sqlStr;
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
    
}
