<?php 	$corrects = Yii::$service->page->message->getCorrects(); ?>
<?php 	$errors   = Yii::$service->page->message->getErrors(); ?>
<?php 	if((is_array($corrects) && !empty($corrects)) || (is_array($errors) && !empty($errors)   )){  ?>
		<div class="fecshop_message">
<?php 		if(is_array($corrects) && !empty($corrects)){  ?>
<?php 			foreach($corrects as $one){ ?>
				<div class="correct-msg">
					<div><?= Yii::$service->page->translate->__($one); ?></div>
				</div>
<?php			} ?>
<?php		} ?>
<?php 		if(is_array($errors) && !empty($errors)){  ?>
<?php 			foreach($errors as $one){ ?>
				<div class="error-msg">
					<div><?= Yii::$service->page->translate->__($one); ?></div>
				</div>
<?php			} ?>
<?php		} ?>
		</div>
<?php 	} ?>