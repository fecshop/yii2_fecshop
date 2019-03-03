<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appserver\modules;

use yii\rest\Controller;
use Yii;
use yii\web\Response;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class AppserverController extends Controller
{
    public $blockNamespace;

    public function init()
    {
        parent::init();
        Yii::$service->page->translate->category = 'appserver';
        // 如果用户登录，会在header中传递access-token，这个函数就会登录用户。
        Yii::$service->customer->loginByAccessToken();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
        $behaviors["corsFilter"] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => Yii::$service->helper->appserver->getCors(),
        ];
        return $behaviors;
    }
    
    /**
     * get current block
     * you can change $this->blockNamespace.
     */
    public function getBlock($blockName = '')
    {
        if (!$blockName) {
            $blockName = $this->action->id;
        }
        if (!$this->blockNamespace) {
            $this->blockNamespace = Yii::$app->controller->module->blockNamespace;
        }
        if (!$this->blockNamespace) {
            throw new \yii\web\HttpException(406, 'blockNamespace is empty , you should config it in module->blockNamespace or controller blockNamespace ');
        }
        $viewId = $this->id;
        $viewId = str_replace('/', '\\', $viewId);
        $relativeFile = '\\'.$this->blockNamespace;
        $relativeFile .= '\\'.$viewId.'\\'.ucfirst($blockName);
        //查找是否在rewriteMap中存在重写
        $relativeFile = Yii::mapGetName($relativeFile);
        
        return new $relativeFile();
    }
    
   
}
