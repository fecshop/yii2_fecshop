<?php
/**
 * FecShop file.
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appfront\theme\base\front\assets;
use yii\web\AssetBundle;
/**
 * Page services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class AppAsset extends AssetBundle
{
    public $basePath 	= '@webroot';
    public $baseUrl 	= '@web';
	public $css 		= [];
	public $cssOptions = [ 'position' => \yii\web\View::POS_HEAD ];
	public $js 			= [];
	public $jsOptions = [ 'position' => \yii\web\View::POS_END ]; # POS_HEAD
    public $depends = [
		//'fecshop\app\appfront\theme\BaseAsset',
		'fecshop\app\appfront\theme\base\front\assets\AppFrontAsset',
		'fecshop\app\appfront\theme\base\front\assets\IEAsset',
		'fecshop\app\appfront\theme\base\front\assets\LtIE9Asset',
	];
}










