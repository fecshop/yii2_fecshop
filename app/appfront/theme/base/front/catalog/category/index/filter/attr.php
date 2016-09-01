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
//var_dump($parentThis);
//echo 1;
//echo Yii::$service->url->category->urlFormat('df-_ ??&sad');
if(isset($parentThis['filters']) && !empty($parentThis['filters']) && is_array($parentThis['filters'])){
	foreach($parentThis['filters']  as $attr => $filter){
		$attrUrlStr = Yii::$service->url->category->attrValConvertUrlStr($attr);
		if(is_array($filter) && !empty($filter)){
			echo $attr.'<br/>';
			foreach($filter as $item){
				$val = $item['_id'];
				$count = $item['count'];
				$urlInfo = Yii::$service->url->category->getFilterChooseAttrUrl($attrUrlStr,$val,'p');
				$url = $urlInfo['url'];
				$selected = $urlInfo['selected'];
				echo '<a href="'.$url.'">'.$val.'('.$count.')</a><br/>';
			}
		}
	}
}
?>