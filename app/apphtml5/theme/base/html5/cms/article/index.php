<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<div class="shopping-cart-img">
	<?= $title ?>
</div>
<?= Yii::$service->page->widget->render('base/flashmessage'); ?>	
<div class="mobile-container">
	<div class="col-main">
		<div>
			<?= $content ?>
		</div>
	</div>
</div>