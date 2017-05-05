<?php   
	$query_item 	= $parentThis['query_item'];
	$product_page 	= $parentThis['product_page'];
?>
<div class="toolbar">
	<div class="tb_le">
		<?php  $frontSort = $query_item['frontSort']; ?>
		<?php if(is_array($frontSort) && !empty($frontSort)){ ?>
			<div class="category_left_filter">
				<div class="filter_attr">
					<div class="filter_attr_title">
						<b><?=  Yii::$service->page->translate->__('Sort By'); ?>:</b>
					</div>
					<div class="filter_attr_info">
						<?php foreach($frontSort as $np){   ?>
							<?php $selected = $np['selected'] ? 'class="checked"' : ''; ?>
							<a <?= $selected ?> href="<?= $np['url']  ?>" external>
								<?= Yii::$service->page->translate->__($np['label']); ?>
							</a><br>
						<?php } ?>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>
	<div class="clear"></div>
</div>
