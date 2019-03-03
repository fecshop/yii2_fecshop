<?php
use fec\helpers\CUrl;
use fec\helpers\CRequest;
?>
<div class="tabs" >
	<div class="tabsHeader">
		<div class="tabsHeaderContent">
			<ul>
				<li><a href="javascript:;"><span><?= Yii::$service->page->translate->__('Basic Info') ?></span></a></li>
				<li><a href="javascript:;"><span><?= Yii::$service->page->translate->__('Meta Info') ?></span></a></li>
				<li><a href="<?= $product_url;  ?>" class="j-ajax"><span><?= Yii::$service->page->translate->__('Product') ?></span></a></li>
			</ul>
		</div>
	</div>
	<div class="tabsContent">
		<div  layoutH="54">
			<?= $base_info; ?>
			<p class="edit_p">  
			<label >Thumbnail Image:</label>
			<input type="hidden" class="textInput thumbnail_image" value="<?=  $thumbnail_image ?>" name="thumbnail_image" style="width:550px;">
			
			<img style="width:70px;height:70px" class="cat_thumbnail_image" src="<?=  $thumbnail_imageurl ?>" />
			<button style="" onclick="getElementById('inputthumbnail_image').click()" class="scalable" type="button" title="Duplicate" id=""><span><span><span><?= Yii::$service->page->translate->__('Browse Files') ?></span></span></span></button>

			<input type="file"  id="inputthumbnail_image" style="height:0;width:0;z-index: -1; position: absolute;left: 10px;top: 5px;"/>

			</p>
			<p class="edit_p">
				<label >Image:</label>
				<input type="hidden" class="textInput image" value="<?=  $image ?>" name="image" style="width:550px;">
				<img style="width:70px;height:70px" class="cat_image" src="<?=  $imageurl ?>" />
				<button style="" onclick="getElementById('inputimage').click()" class="scalable" type="button" title="Duplicate" id=""><span><span><span><?= Yii::$service->page->translate->__('Browse Files') ?></span></span></span></button>
				<input type="file"  id="inputimage" style="height:0;width:0;z-index: -1; position: absolute;left: 10px;top: 5px;"/>
			</p>
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

<script>
jQuery(document).ready(function(){
	jQuery("#inputthumbnail_image").change(function(){
		var data = new FormData();
		jQuery.each(jQuery('#inputthumbnail_image')[0].files, function(i, file) {
			data.append('upload_file', file);
		});
        data.append("<?= CRequest::getCsrfName() ?>", "<?= CRequest::getCsrfValue() ?>");
		$.ajax({
			url:'<?= CUrl::getUrl('catalog/category/imageupload'); ?>',
			type:'POST',
			data:data,
			async:false,
			dataType: 'json', 
			timeout: 80000,
			cache: false,
			contentType: false,		//不可缺参数
			processData: false,		//不可缺参数
			success:function(data, textStatus){
				if(data.return_status == "success"){
					jQuery(".thumbnail_image").val(data.relative_path);
					jQuery(".cat_thumbnail_image").attr("src",data.img_url);
				}
			},
			error:function(){
				alert('<?= Yii::$service->page->translate->__('Upload Error') ?>');
			}
		});
	});
	
	jQuery("#inputimage").change(function(){
		var data = new FormData();
		jQuery.each(jQuery('#inputimage')[0].files, function(i, file) {
			data.append('upload_file', file);
		});
        data.append("<?= CRequest::getCsrfName() ?>", "<?= CRequest::getCsrfValue() ?>");
		$.ajax({
			url:'<?= CUrl::getUrl('catalog/category/imageupload'); ?>',
			type:'POST',
			data:data,
			async:false,
			dataType: 'json', 
			timeout: 80000,
			cache: false,
			contentType: false,		//不可缺参数
			processData: false,		//不可缺参数
			success:function(data, textStatus){
				if(data.return_status == "success"){
					jQuery(".image").val(data.relative_path);
					jQuery(".cat_image").attr("src",data.img_url);
				}
			},
			error:function(){
				alert('<?= Yii::$service->page->translate->__('Upload Error') ?>');
			}
		});
	});
});
</script> 



