<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appapi\modules;

use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use fecshop\yii\filters\auth\AppapiQueryParamAuth;  
use yii\rest\Controller;
use yii\web\Response;
use yii\filters\RateLimiter; 

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class AppapiController extends Controller
{
    public $blockNamespace;
    public $enableCsrfValidation = false ;
    
    public function init()
    {
        parent::init();
        Yii::$app->user->enableSession = false;
    }


   public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;

        //$behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
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

        $relativeFile = '\\'.$this->blockNamespace;
        $relativeFile .= '\\'.$this->id.'\\'.ucfirst($blockName);
        //查找是否在rewriteMap中存在重写
        $relativeFile = Yii::mapGetName($relativeFile);
        
        return new $relativeFile();
    }
}
