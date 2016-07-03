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
class IEAsset extends AssetBundle
{
    public $sourcePath = '@fecshop/app/appfront/theme/base/front/assets';
	public $cssOptions = ['condition' => 'if IE'];
	public $css = [
		'css/ie.css',
	];	
    
}
