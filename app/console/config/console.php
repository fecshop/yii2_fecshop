<?php

$modules = [];
foreach (glob(__DIR__ . '/modules/*.php') as $filename){
	$modules = array_merge($modules,require($filename));
}
return [
	'modules'=>$modules,
];
