<div class="col-left sidebar">
	<div class="block block-account">
		<div class="block-title">
			<strong><span><?= Yii::$service->page->translate->__('My Account'); ?></span></strong>
		</div>
		<div class="block-content">
			<ul>
				<?php  if(!empty($leftMenuArr) && is_array($leftMenuArr)){  ?>
					<?php foreach($leftMenuArr as $one){ ?>
					<li <?= $one['current'] ?>>
						<a href="<?= $one['url'] ?>"  ><?= Yii::$service->page->translate->__($one['name']); ?></a>
					</li>
					<?php } ?>
				<?php } ?>	
			</ul>
		</div>
	</div>
</div>