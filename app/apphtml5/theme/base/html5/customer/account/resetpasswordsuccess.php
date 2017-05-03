<div class="main container one-column">
	<?php
		$param = ['logUrlB' => '<a href="'.$loginUrl.'">','logUrlE' => '</a> '];
	?>
	<?= Yii::$service->page->translate->__('reset you account success, you can {logUrlB} click here {logUrlE} to login .',$param); ?>

</div>