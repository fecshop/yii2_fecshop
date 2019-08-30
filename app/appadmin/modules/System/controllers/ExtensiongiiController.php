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
class ExtensiongiiController extends SystemController
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
        $developer_info = Yii::$service->extension->remoteService->getDeveloperInfo();
        if (!$developer_info) {
            $data = [
                'guest' => true,
            ];
            return $this->render($this->action->id, $data);
        }
        // 如果提交数据，进行生成应用文件
        $param = Yii::$app->request->post('editFormData');
        if (!empty($param) && is_array($param)) {
            if (!Yii::$service->extension->generate->createAddonsFiles($param)) {
                $errors = Yii::$service->helper->errors->get(',');
                echo  json_encode([
                    'statusCode' => '300',
                    'message'    => $errors,
                ]);
                exit;
            }
            // 进行插件数据库更新
            if (!Yii::$service->extension->newLocalCreateInit($param)) {
                $errors = Yii::$service->helper->errors->get(',');
                echo  json_encode([
                    'statusCode' => '300',
                    'message'    => $errors,
                ]);
                exit;
            }
            
            echo  json_encode([
                'statusCode' => '200',
                'message'    => Yii::$service->page->translate->__('创建应用成功,请到@addons文件夹下查看'),
            ]);
            exit;
        }
        // 加载页面
        $data = $this->getBlock()->getLastData($developer_info);

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
