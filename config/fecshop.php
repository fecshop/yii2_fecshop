<?php
# 本文件在app/web/index.php 处引入。

# fecshop的核心模块
/*
$modules = [];
foreach (glob(__DIR__ . '/modules/*.php') as $filename){
	$modules = array_merge($modules,require($filename));
}
*/
# 服务
$services = [];
foreach (glob(__DIR__ . '/services/*.php') as $filename){
	$services = array_merge($services,require($filename));
}


# 组件
$components = [];
foreach (glob(__DIR__ . '/components/*.php') as $filename){
	$components = array_merge($components,require($filename));
}

    
return [
	//'modules'=>$modules,
    'components' 	=> $components,
	'services' 		=> $services,
	
	/* only config in front web */
	//'bootstrap' => ['store'],
];
