<?php  if(is_array($parentThis['products']) && !empty($parentThis['products'])){ ?>
<div class="buy_also_buy" >
	<div class="scroll_left">
		<a href=""><?= Yii::$service->page->translate->__('Customers Who Bought This Item Also Bought'); ?></a>
	</div>
	<div class="scrollBox">	
		<div class="viewport" style="overflow: hidden; position: relative;">
			<div id="owl-buy-also-buy" class="owl-carousel">	
				<?php
					//$parentThis['products'] = $parentThis['products'];
					$parentThis['name'] = 'featured';
					$config = [
						'view'  		=> 'cms/home/index/product.php',
					];
					echo Yii::$service->page->widget->renderContent('category_product_price',$config,$parentThis);
				?>
			</div>
		</div>
	</div>
</div>
 
<script>
<?php $this->beginBlock('owl_fecshop_slider') ?>  

<?php $this->endBlock(); ?>  
</script>  
<?php $this->registerJs($this->blocks['owl_fecshop_slider'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
<?php  }  ?>

