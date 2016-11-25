<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<?php
$modules = [];
foreach (glob(__DIR__ . '/modules/*.php') as $filename){
	$modules = array_merge($modules,require($filename));
}
return [
	'modules'=>$modules,
	'params' => [
		'appName' => 'console',
	],
];
