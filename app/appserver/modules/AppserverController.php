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
use fec\helpers\CConfig;
use Yii;
use yii\web\Response;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\base\InvalidValueException;

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
        Yii::$app->user->enableSession = false;
    }
    
    public function behaviors()
    {
        $fecshop_uuid = Yii::$service->session->fecshop_uuid;
        $cors_allow_headers = [$fecshop_uuid,'fecshop-lang','fecshop-currency'];
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
        $behaviors["corsFilter"] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                // restrict access to
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                // Allow only POST and PUT methods
                'Access-Control-Request-Headers' => $cors_allow_headers,
                // Allow only headers 'X-Wsse'
                //'Access-Control-Allow-Credentials' => null,
                // Allow OPTIONS caching
                'Access-Control-Max-Age' => 86400,
                // Allow the X-Pagination-Current-Page header to be exposed to the browser.
                'Access-Control-Expose-Headers' => $cors_allow_headers,
            ],
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
