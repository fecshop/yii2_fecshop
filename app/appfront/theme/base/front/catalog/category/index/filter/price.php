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
//var_dump($parentThis['filter_price']);
//echo 1;
//echo Yii::$service->url->category->urlFormat('df-_ ??&sad');
if(isset($parentThis['filter_price']) && !empty($parentThis['filter_price']) && is_array($parentThis['filter_price'])){
	
	foreach($parentThis['filter_price']  as $attr => $filter){
		//var_dump($filter);
		echo $attr.'<br/>';
		$attrUrlStr = Yii::$service->url->category->attrValConvertUrlStr($attr);
		if(is_array($filter) && !empty($filter)){
			foreach($filter as $item){
				$val = $item['val'];
				$url = $item['url'];
				$selected = $item['selected'] ? 'class="checked"' : '';
				echo '<a '.$selected.' href="'.$url.'">'.$val.'</a><br/>';
			}
		}
	}
}
?>