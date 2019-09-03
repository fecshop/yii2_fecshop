<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<div class="main container one-column">
    <?= Yii::$service->page->widget->render('base/breadcrumbs',$this); ?>
	<?php
		$param = ['logUrlB' => '<a href="'.$loginUrl.'">','logUrlE' => '</a> '];
	?>
	<?= Yii::$service->page->translate->__('reset you account success, you can {logUrlB} click here {logUrlE} to login .',$param); ?>

</div>