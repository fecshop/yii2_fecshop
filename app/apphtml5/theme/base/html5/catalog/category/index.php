<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>

<div class="content">
	<div class="content-block">
		<?= Yii::$service->page->widget->render('breadcrumbs',$this); ?>
		<div class="category_page">
			<div class="category_img">
				<a href="#">
					<?=  $image ? '<img  style="width:100%;" src="'.$image.'"/>' : '';?>
				<a>
			</div>
			<div class="category_description" >
				<h1><?=  $name ?></h1>
				<?=  $description ?>
			</div>
			<div class="sort_filter">
				<a href="#" class="category-open open-sort">Sort &nbsp;<span class="icon icon-caret"></span></a>
				<a href="#" class="category-open open-filter">Filter &nbsp;<span class="icon icon-caret"></span></a>
				<div class="clear"></div>
			</div>
			<div > 
				<!-- 添加 class infinite-scroll 和 data-distance  向下无限滚动可不加infinite-scroll-bottom类，这里加上是为了和下面的向上无限滚动区分-->
				<div class=" infinite-scroll infinite-scroll-bottom" data-distance="10">
					<div class="list-block">
						<div class="list-container">
							<?php
								$parentThis['products'] = $products;
								$config = [
									'view'  		=> 'cms/home/index/product.php',
								];
								echo Yii::$service->page->widget->renderContent('category_product_price',$config,$parentThis);
							?>
						
						</div>
						<!-- 加载提示符 -->
						<div class="infinite-scroll-preloader">
							<div class="preloader"></div>
						</div>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>

<div class="popup popup-filter">
	<div class="content-block">
	<div class="close_popup">
		<a href="#" class="close-popup">×</a></p>
	</div>
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
</div>

<div class="popup popup-sort">
	<div class="content-block">
		<div class="close_popup">
			<a href="#" class="close-popup">×</a></p>
		</div>
		<div>
			<?php
				$parentThis = [
					'query_item' => $query_item,
					'product_page'=>$product_page,
				];
				$config = [
					'view'  		=> 'catalog/category/index/toolbar.php',
				];
				$toolbar = Yii::$service->page->widget->renderContent('category_toolbar',$config,$parentThis);
				echo $toolbar;
			?>
		</div>
	</div>
</div>
<script>
<?php $this->beginBlock('category_product_filter') ?>  
$(document).ready(function(){
	$(".product_sort").change(function(){	
		url = $(this).find('option').not(function() {return !this.selected}).attr('url');
		window.location.href = url;
	});
	$(".product_num_per_page").change(function(){
		//url = $(this).find("option:selected").attr('url');
		url = $(this).find('option').not(function() {return !this.selected}).attr('url');
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

$(document).on('click','.open-filter', function () {
  $.popup('.popup-filter');
});
 
$(document).on('click','.open-sort', function () {
  $.popup('.popup-sort');
});



$(document).on("pageInit", "#page-infinite-scroll-bottom", function(e, id, page) {
	var loading = false;
	var pageNum = 1;
	var maxPage = <?= $page_count ? $page_count : 1 ?>;
	if(maxPage <= pageNum){
		$('.infinite-scroll-preloader').remove();
	}
	function addItems() {
		//alert(pageNum);
		pageNum++;
		var html = '';
		url =  window.location.href;
		$.ajax({
			async:true,
			timeout: 60000,
			dataType: 'json', 
			type:'get',
			data: {
				'p':pageNum
			},
			url: url,
			success:function(data, textStatus){ 
				//alert(data);
				html = data.html;
				//alert(html);
				$('.infinite-scroll .list-container').append(html);
			},
			error:function (XMLHttpRequest, textStatus, errorThrown){}
		});
	}
	
	$(page).on('infinite', function() {
		//alert(222);
		if (loading) return;
		loading = true;	
		if (pageNum >= maxPage) {
			$.detachInfiniteScroll($('.infinite-scroll'));
 
			$('.infinite-scroll-preloader').remove();
			return;
		}
		addItems();
		//alert(pageNum);
		loading = false;
		$.refreshScroller();
		
	});
});
$.init();

<?php $this->endBlock(); ?>  
</script>  
<?php $this->registerJs($this->blocks['category_product_filter'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
