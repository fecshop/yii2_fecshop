<?php
# 本文件在app/web/index.php 处引入。
# 
# fecshop的核心模块
$modules = [];
foreach (glob(__DIR__ . '/modules/*.php') as $filename){
	$modules = array_merge($modules,require($filename));
}


    
return [
	'modules'=>$modules,
	/* only config in front web */
	'bootstrap' => ['store'],
];
