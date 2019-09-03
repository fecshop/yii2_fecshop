<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<div class="main container two-columns-left">
<?php // echo count($products); ?>
<?php  $count = 4; $end = $count-1; ?>
	<div class="col-main">
		<?= Yii::$service->page->widget->render('base/breadcrumbs',$this); ?>
		<div class="menu_category">
			<div class="category_img">
				<a href="#"><?=  $image ? '<img style="width:980px;" src="'.$image.'"/>' : '';?><a>
			</div>
			<div class="category_description">
				<h1><?=  $name ?></h1>
				<?=  $description ?>
			</div>
			<div class="panelBar">
				<?php  if(is_array($products) && !empty($products)): ?>
					<?php
						$parentThis = [
							'query_item' => $query_item,
							'product_page'=>$product_page,
						];
						echo Yii::$service->page->widget->render('category/toolbar', $parentThis);
					?>
				<?php endif; ?>
			</div>
			<div class="category_product">
				<?php  if(is_array($products) && !empty($products)): ?>
					<?php $i = 0;  foreach($products as $product): ?>
						<?php  if($i%$count == 0): ?>
						<ul>
						<?php  endif; ?>
							<li>
                                <div style="display:none">
                                    <?= $currentOff = Yii::$service->product->price->currentOff; // 通过这个函数也可以得到特价折扣值  OFF ?>
                                </div>
								<div class="c_img">
									<a href="<?= $product['url'] ?>">
										<img class="js_lazy" src="<?= Yii::$service->image->getImgUrl('images/lazyload.gif');   ?>" data-original="<?= Yii::$service->product->image->getResize($product['image'],[230,230],false) ?>"  />
									</a>
								</div>
								<div class="c_name">
									<a href="<?= $product['url'] ?>">
										<?= $product['name'] ?>
									</a>
								</div>
								<?php
									$diConfig = [
										'price' 		=> $product['price'],
										'special_price' => $product['special_price'],
										'special_from' => $product['special_from'],
										'special_to' => $product['special_to'],
									];
									echo Yii::$service->page->widget->DiRender('category/price', $diConfig);
								?>
							</li>
						<?php  if($i%$count == $end): ?>
						</ul>
						<?php  endif; ?>
						<?php  $i++; ?>
					<?php  endforeach;  ?>
					<?php  if($i%$count != $end): ?>
						</ul>
					<?php  endif; ?>
				<?php  endif;  ?>
			</div>
			<div class="clear"></div>
			<div class="panelBar">
				<?php  if(is_array($products) && !empty($products)): ?>
					<?php echo $toolbar; ?>
				<?php  endif;  ?>
			</div>
		</div>
	</div>
	<div class="col-left ">

		<?php
			# Refind By
			$parentThis = [
				'refine_by_info' => $refine_by_info,
			];
			echo Yii::$service->page->widget->render('category/filter_refineby', $parentThis);
		?>
		<?php
			# Category Left Filter subCategory
			$parentThis = [
				'filter_category' => $filter_category,
				'current_category'=> $name,
			];
			echo Yii::$service->page->widget->render('category/filter_subcategory', $parentThis);
		?>
		<?php
			# Category Left Filter Product Attributes
			$parentThis = [
				'filters' => $filter_info,
			];
			echo Yii::$service->page->widget->render('category/filter_attr', $parentThis);
		?>
		<?php
			# Category Left Filter Product Price
			$parentThis = [
				'filter_price' => $filter_price,
			];
			echo Yii::$service->page->widget->render('category/filter_price', $parentThis);
		?>
	</div>
	<div class="clear"></div>
</div>
<script>
<?php $this->beginBlock('category_product_filter') ?>
$(document).ready(function(){
	$(".product_sort").change(function(){
		url = $(this).find("option:selected").attr('url');
		window.location.href = url;
	});
	$(".product_num_per_page").change(function(){
		url = $(this).find("option:selected").attr('url');
		window.location.href = url;
	});

	$(".filter_attr_info a").click(function(){
		if($(this).hasClass("checked")){
			$(this).removeClass("checked");
		}else{
			$(this).parent().find("a.checked").removeClass("checked");
			$(this).addClass("checked");
		}
	});
});
<?php $this->endBlock(); ?>
</script>
<?php $this->registerJs($this->blocks['category_product_filter'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
<?= Yii::$service->page->trace->getTraceCategoryJsCode($name_default_lang)  ?>