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
    <?php if ($enable): ?>
        <?php
            $param = ['logUrlB' => '<a href="'.$loginUrl.'">','logUrlE' => '</a> '];
        ?>
        <?= Yii::$service->page->translate->__('your register account enable success, you can {logUrlB} click here {logUrlE} to login .',$param); ?>
    <?php else: ?>
        <?= Yii::$service->page->translate->__('your register account enable token is invalid or expired'); ?>
    
    <?php endif; ?>
</div>