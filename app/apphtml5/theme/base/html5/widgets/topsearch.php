<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<form method="get" name="searchFrom" class="js_topSeachForm" action="<?= Yii::$service->url->getUrl('catalogsearch/index');   ?>">
	<div class="top_seachBox">
		<div class="searchInput fl">
			<input type="text"  value="<?=  \Yii::$service->helper->htmlEncode(Yii::$app->request->get('q'));  ?>" maxlength="150" placeholder="<?= Yii::$service->page->translate->__('Products keyword'); ?>" class="searchArea js_k2 ac_input" name="q">
		</div>
		<button class="fl js_topSearch seachBtn" type="submit"><span class="t_hidden">search</span></button>
		<!-- <input type="hidden" class="category" value="0" name="category"> -->
	</div><!--end .top_seachBox-->
</form>
