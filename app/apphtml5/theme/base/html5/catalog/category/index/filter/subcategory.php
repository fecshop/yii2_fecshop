<?php if(isset($parentThis['current_category']) && isset($parentThis['filter_category'])): ?>
<div class="category_left_filter_category">
	<div class="filter_attr_title">
		<?php echo $parentThis['current_category']; ?>
	</div>
	<div class="filter_category_content">
		<?php echo $parentThis['filter_category']; ?>
	</div>
</div>
<?php endif; ?>