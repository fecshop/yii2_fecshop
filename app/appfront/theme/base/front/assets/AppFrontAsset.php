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
class AppFrontAsset extends AssetBundle
{
    public $sourcePath = '@fecshop/app/appfront/theme/base/front/assets';
	public $css = [
		'css/style.css',
	];	
    public $js = [
        'js/jquery-3.0.0.min.js',
    ];
}
