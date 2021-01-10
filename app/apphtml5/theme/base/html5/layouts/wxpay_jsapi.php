<?php $this->beginPage() ?>
<html>
<?= $content; ?>
<?= Yii::$service->page->widget->render('base/trace',$this); ?>
</html>
<?php $this->endPage() ?>