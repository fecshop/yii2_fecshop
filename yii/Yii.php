<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
$dir = __DIR__ . '/../../../yiisoft/yii2';
require $dir.'/BaseYii.php';
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Yii extends \yii\BaseYii
{
    /**
     * @var \fecshop\services\Application $service
     */
    public static $service;
    /**
     * rewriteMap , like:
     * [
     *    '\fecshop\models\mongodb\Category'  => '\appadmin\models\mongodb\Category'
     * ]
     */
    public static $rewriteMap;
    /**
     * @param $absoluteClassName | String , like: '\fecshop\app\appfront\modules\Cms\block\home\Index'
     * @param $arguments | Array ,数组，里面的每一个子项就是用于实例化的一个参数，多少个子项，就代表有多个参数，用于对象的实例化。
     * 通过$rewriteMap，查找是否存在重写，如果存在，则得到重写的className
     * 然后返回 类名 和 对象
     */
    public static function mapGet($absoluteClassName, $arguments = []){
        $absoluteClassName = self::mapGetName($absoluteClassName);
        if (!empty($arguments) && is_array($arguments)) {
            $class = new ReflectionClass($absoluteClassName);
            $absoluteOb = $class->newInstanceArgs($arguments);
            /**
             * 下面的 ...，是php的语法糖(只能php5.6以上，放弃)，也就是把$paramArray数组里面的各个子项参数，
             *  作为对象生成的参数，详细可以参看：https://segmentfault.com/q/1010000006789348
             */
            //$absoluteOb = new $absoluteClassName(...$arguments);
        } else {
            $absoluteOb = new $absoluteClassName;
        }
        
        return [$absoluteClassName, $absoluteOb];
    }
    /**
     * @param $absoluteClassName | String , like: '\fecshop\app\appfront\modules\Cms\block\home\Index'
     * 通过$rewriteMap，查找是否存在重写，如果存在，则返回重写的className
     */
    public static function mapGetName($absoluteClassName){
        if(isset(self::$rewriteMap[$absoluteClassName]) && self::$rewriteMap[$absoluteClassName]){
            $absoluteClassName = self::$rewriteMap[$absoluteClassName];
        }
        return $absoluteClassName;
    }
    /**
     * @param $className | String , block等className，前面没有`\`, like: 'fecshop\app\appfront\modules\Catalog\block\product\CustomOption'
     * 通过$rewriteMap，查找是否存在重写，如果存在，则返回重写的className
     */
    public static function mapGetClassName($className){
        $absoluteClassName = '\\'.$className;
        if(isset(self::$rewriteMap[$absoluteClassName]) && self::$rewriteMap[$absoluteClassName]){
            $absoluteClassName = self::$rewriteMap[$absoluteClassName];
            return substr($absoluteClassName,1);
        }
        return $className;
    }
}

spl_autoload_register(['Yii', 'autoload'], true, true);
Yii::$classMap = require $dir.'/classes.php';
Yii::$container = new yii\di\Container();
