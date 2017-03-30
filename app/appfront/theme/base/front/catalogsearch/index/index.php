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
		<?= Yii::$service->page->widget->render('breadcrumbs',$this); ?>
		<div class="menu_category">
			
				
			<?php  if(is_array($products) && !empty($products)){ ?>
				<div class="panelBar">
					
					<?php
						$parentThis = [
							'query_item' => $query_item,
							'product_page'=>$product_page,
						];
						$config = [
							'view'  		=> 'catalogsearch/index/index/toolbar.php',
						];
						$toolbar = Yii::$service->page->widget->renderContent('category_toolbar',$config,$parentThis);
						echo $toolbar;
					?>
					
				</div>
				<div class="category_product">
					<?php $i = 0;  foreach($products as $product){ ?>
						<?php  if($i%$count == 0){ ?>
						<ul>
						<?php  } ?>
							<li>
								<div class="c_img">
									<a href="<?= $product['url'] ?>">
										<img  class="js_lazy" src="<?= Yii::$service->image->getImgUrl('images/lazyload.gif');   ?>" data-original="<?= Yii::$service->product->image->getResize($product['image'],[230,230],false) ?>"  />
									</a>
								</div>
								<div class="c_name">
									<a href="<?= $product['url'] ?>">
										<?= $product['name'] ?>
									</a>
								</div>
								<?php
									$config = [
										'class' 		=> 'fecshop\app\appfront\modules\Catalog\block\category\Price',
										'view'  		=> 'catalog/category/price.php',
										'price' 		=> $product['price'],
										'special_price' => $product['special_price'],
									];
									echo Yii::$service->page->widget->renderContent('category_product_price',$config);
								?>
							</li>
						<?php  if($i%$count == $end){ ?>
						</ul>
						<?php  } ?>
						<?php  $i++; ?>
					<?php  }  ?>
					<?php  if($i%$count != $end){ ?>
						</ul>
						<?php  } ?>
					
				</div>
				<div class="clear"></div>
				<div class="panelBar">
					<?php echo $toolbar; ?>
				</div>
			<?php }else{ ?>
				<?= Yii::$service->page->translate->__('Search results for \'{searchText}\' returns no results',['searchText' => $searchText]); ?>
				
			<?php } ?>
		</div>
	</div>
	<div class="col-left ">
		
		<?php
			# Refind By
			$parentThis = [
				'refine_by_info' => $refine_by_info,
			];
			$config = [
				'view'  		=> 'catalog/category/index/filter/refineby.php',
			];
			echo Yii::$service->page->widget->renderContent('category_product_filter_refine_by',$config,$parentThis);
		?>
		<?php
			# Category Left Filter subCategory
			$parentThis = [
				'filter_category' => $filter_category,
				'current_category'=> $name,
			];
			$config = [
				'view'  		=> 'catalog/category/index/filter/subcategory.php',
			];
			echo Yii::$service->page->widget->renderContent('category_product_filter_sub_category',$config,$parentThis);
		?>
		<?php
			# Category Left Filter Product Attributes
			$parentThis = [
				'filters' => $filter_info,
			];
			$config = [
				'view'  		=> 'catalog/category/index/filter/attr.php',
			];
			echo Yii::$service->page->widget->renderContent('category_product_filter_attr',$config,$parentThis);
		?>
		<?php
			# Category Left Filter Product Price
			$parentThis = [
				'filter_price' => $filter_price,
			];
			$config = [
				'view'  		=> 'catalog/category/index/filter/price.php',
			];
			echo Yii::$service->page->widget->renderContent('category_product_filter_price',$config,$parentThis);
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
