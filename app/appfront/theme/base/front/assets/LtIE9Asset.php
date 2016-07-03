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
class LtIE9Asset extends AssetBundle
{
    public $sourcePath = '@fecshop/app/appfront/theme/base/front/assets';
	public $cssOptions = ['condition' => 'lt IE 9'];
	public $css = [
		'css/ltie9.css',
	];
	/*
	public $jsOptions = [ 
		//'position' => \yii\web\View::POS_END ,
		'condition' => 'lt IE 9'
	];
	
    public $js = [
        //'dwz_jui-master/js/speedup.js',
		//'dwz_jui-master/jquery-1.11.3.min.js',
    ];
    public $depends = [
        
    ];	
	*/
    
}
