<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
use fec\helpers\CRequest;
?>
<?php
use fecshop\app\apphtml5\helper\Format;
?>
<div class="account-ds">
	<div class="bar bar-nav account-top-m">
		<a external class="button button-link button-nav pull-left" href="<?= Yii::$service->url->getUrl('customer/account/index'); ?>">
			<span class="icon icon-left"></span>
		</a>
		<h1 class='title'><?= Yii::$service->page->translate->__('Product Favorite'); ?></h1>
	</div>
</div>
<?= Yii::$service->page->widget->render('base/flashmessage'); ?>

<div class="account-container">
	<div class="col-main account_center">
		<div class="std">
			<div style="margin:4px 0 0">
				<div style="width:100%;min-height:500px;">
					<div style="width:100%;">
						<?php if(is_array($coll) && !empty($coll)):  ?>
						<table class="product-Reviews"> 
							<?php  foreach($coll as $one):  ?>
							<?php  $main_img = $one['image']['main']['image'];  ?>
							
							<tr>
								<td>
									<a external href="<?= Yii::$service->url->getUrl($one['url_key'])  ?>">
										<p style="text-align:center;"><img src="<?= Yii::$service->product->image->getResize($main_img,[80,80],false) ?>"></p>
									</a>
								</td>
								<td>
									<span class="review_description_right_span"><b>
										<a external  href="<?= Yii::$service->url->getUrl($one['url_key'])  ?>">
											<?= Yii::$service->store->getStoreAttrVal($one['name'],'name')  ?>
										</a>
									</span>
									<div class="review_description_centen">
										<div class="category_product" style="display:inline-block;float:left;">
											<?php
												$diConfig = [
													'price' 		=> $one['price'],
													'special_price' => $one['special_price'],
													'special_from' 	=> $one['special_from'],
													'special_to' 	=> $one['special_to'],
												];
                                                echo Yii::$service->page->widget->DiRender('category/price', $diConfig);
											?>
										</div>
										
										<div class="clear"></div>
										<div style="font-weight:100">
											<?= Yii::$service->page->translate->__('Favorite Date:');?><?= date('Y-m-d H:i:s',$one['updated_at']) ?>
										</div>
									</div>	
								</td>
								<td>
									<div class="favorite-Operation addressbook " style="display:inline-block;float:right; margin-top: 0px;">
										<a href='javascript:doPost("<?= Yii::$service->url->getUrl('customer/productfavorite') ?>", {"type":"remove", "favorite_id":"<?= $one['favorite_id'] ?>", "<?= CRequest::getCsrfName() ?>": "<?= CRequest::getCsrfValue() ?>" })' >
											<span class="icon icon-remove"></span>
										</a>
									</div>
								</td>
							</tr>
								
							<?php  endforeach;  ?>
						</table>
						<?php  else:  ?>
							<?= Yii::$service->page->translate->__('You have no items in your favorite.');?>
						<?php  endif; ?>
						<?php if($pageToolBar): ?>
						<div class="pageToolbar">
							<label class="title">Page:</label><?= $pageToolBar ?>
						</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	
	<div class="clear"></div>
</div>
<script>
    function ShowRemark(eval,id){
        $('#remarkBox_'+id).show("slow");
    }
    function hideRemark(id){
         $('#remarkBox_'+id).hide("slow");
    }
    function ViledateForm(id){
        $val = $('#content_'+id).val();
        //alert($val);
        $url = $(eval).attr('url');
        $.ajax({
             type: "POST",
             data:"remark="+$val,
             url: "/favorite/product/remark?_id="+id,
             success: function(data){
                location.reload([true]);
             }
         });
    }
</script>