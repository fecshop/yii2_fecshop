<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<header class="bar bar-nav">
	<a class="icon icon-menu pull-left open-panel" data-panel="#panel-left-menu"></a>
	<a href="<?= Yii::$service->url->homeUrl();  ?>"  external>
		<h1 class='title header_logo'>
			<img class="lazy" data-src="<?= $logoImgUrl ?>"  />
		</h1>
	</a>
	<div class="pull-right">
		<a  style="padding-right:0.4rem" class="icon icon-me open-panel"  data-panel="#panel-left-account"></a>
		<a  style="padding-right:0.4rem" class="icon icon-cart" href="<?= Yii::$service->url->getUrl('checkout/cart'); ?>" external></a>
	</div>
	
</header>