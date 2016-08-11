<?php
use fec\helpers\CUrl;
?>
<div class="tabs" >
	<div class="tabsHeader">
		<div class="tabsHeaderContent">
			<ul>
				<li><a href="javascript:;"><span>基本信息</span></a></li>
				<li><a href="javascript:;"><span>Meta信息</span></a></li>
				<li><a href="<?= CUrl::getUrl('catalog/category/product');  ?>" class="j-ajax"><span>产品</span></a></li>
			</ul>
		</div>
	</div>
	<div class="tabsContent">
		<div  layoutH="54">
			<?= $base_info; ?>
		</div>
		
		<div  layoutH="54">
		<?= $meta_info; ?>
		</div>
		<div id="jbsxBox_product"  layoutH="54">
			
		</div>
		
	</div>
	<div class="tabsFooter">
		<div class="tabsFooterContent"></div>
	</div>
</div>