<div class="list-block" style="margin-top:80px;">
    <ul>
		<?php  if(!empty($leftMenuArr) && is_array($leftMenuArr)){  ?>
			<?php foreach($leftMenuArr as $one){ ?>
			
			<li class="item-content item-link">
				<div class="item-media"><i class="icon icon-f7"></i></div>
				<div class="item-inner">
					<div class="item-title">
						<a external href="<?= $one['url'] ?>"  ><?= Yii::$service->page->translate->__($one['name']); ?></a>
					</div>
				</div>
			</li>
			<?php } ?>
		<?php } ?>	
    </ul>
</div>

<div class="account_footer">
	<a   external  href="<?= Yii::$service->url->getUrl("customer/account/logout");?> " class="button button-fill button-bbb">Logout</a>
</div>