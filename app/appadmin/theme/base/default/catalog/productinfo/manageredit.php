<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
use yii\helpers\Html;
use fec\helpers\CRequest;
use fec\helpers\CUrl;
use fecadmin\models\AdminRole;
/** 
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
?>
<style>
.checker{float:left;}
.dialog .pageContent {background:none;}
.dialog .pageContent .pageFormContent{background:none;}
.edit_p{display:block;height:35px;}
.edit_p label{float:left;line-height: 20px;min-width:200px;}
.edit_p input{width:700px;}
.tabsContent .tabsContent .edit_p label{min-width:194px;}
.edit_p .tier_price input{
	width:100px;
}

.tier_price table thead tr th{
	 background: #ddd none repeat scroll 0 0;
    border: 1px solid #ccc;
    padding: 4px 10px;
    width: 100px;
}

.tier_price table tbody tr td{
	background: #fff;
    border-right: 1px solid #ccc;
	border-bottom: 1px solid #ccc;
    padding:3px;
    width: 100px;
}

.custom_option_list table thead tr th{
	 background: #ddd none repeat scroll 0 0;
    border: 1px solid #ccc;
    padding: 4px 10px;
    width: 100px;
}

.custom_option_list table tbody tr td{
	background: #fff;
    border-right: 1px solid #ccc;
	border-bottom: 1px solid #ccc;
    padding:3px;
    width: 100px;
}



.edit_p .tier_price input.tier_qty{width:30px;}
.custom_option{padding:10px 5px;}
.custom_option span{margin:0 2px 0 10px;}

.custom_option .nps{float:left;margin:0 0 10px 0}
.custom_option_img_list img {cursor:pointer;}
</style>

<script>

$(document).ready(function(){
	$(document).off("change").on("change",".attr_group",function(){
		//alert(2222);
		options = {};
		val = $(this).val();
		pm = "?attr_group="+val;
		currentPrimayInfo = $(".primary_info").val();
		currentPrimayInfo = currentPrimayInfo ? '&'+currentPrimayInfo : '';
		url = '<?= CUrl::getUrl("catalog/productinfo/manageredit"); ?>'+pm+currentPrimayInfo;
		$.pdialog.reload(url,options);
	});
});


function getCategoryData(product_id,i){												
	$.ajax({
		url:'<?= CUrl::getUrl("catalog/productinfo/getproductcategory",['product_id'=>$product_id]); ?>',
		async:false,
		timeout: 80000,
		dataType: 'json', 
		type:'get',
		data:{
			'product_id':product_id,
		},
		success:function(data, textStatus){
			if(data.return_status == "success"){
				jQuery(".category_tree").html(data.menu);
				// $.fn.zTree.init($(".category_tree"), subMenuSetting, json);
				if(i){
					$("ul.tree", ".dialog").jTree();
				}
			}
		},
		error:function(){
			alert("<?=  Yii::$service->page->translate->__('load category info error') ?>");
		}
	});
}

function thissubmit(thiss){
	// product image
	main_image_image 		=  $('.productimg input[type=radio]:checked').val();
	main_image_label 		    =  $('.productimg input[type=radio]:checked').parent().parent().find(".image_label").val();
	main_image_sort_order 	=  $('.productimg input[type=radio]:checked').parent().parent().find(".sort_order").val();
	main_image_is_thumbnails    =  $('.productimg input[type=radio]:checked').parent().parent().find(".is_thumbnails").val();
    main_image_is_detail 	    =  $('.productimg input[type=radio]:checked').parent().parent().find(".is_detail").val();
    //alert(main_image_image+main_image_label+main_image_sort_order);
	if(main_image_image){
		image_main = main_image_image+'#####'+main_image_label+'#####'+main_image_sort_order  +'#####'+main_image_is_thumbnails  +'#####'+main_image_is_detail;
		$(".tabsContent .image_main").val(image_main);
	}else{
		alert('<?=  Yii::$service->page->translate->__('You upload and select at least one main image') ?>');
		//DWZ.ajaxDone;
		return false;
	}
	image_gallery = '';
	$('.productimg input[type=radio]').each(function(){
		if(!$(this).is(':checked')){
			gallery_image_image 		= $(this).val();
			gallery_image_label 		= $(this).parent().parent().find(".image_label").val();
			gallery_image_sort_order 	= $(this).parent().parent().find(".sort_order").val();
            gallery_image_is_thumbnails = $(this).parent().parent().find(".is_thumbnails").val();
            gallery_image_is_detail 	= $(this).parent().parent().find(".is_detail").val();
			//alert(gallery_image_image+gallery_image_label+gallery_image_sort_order);
			image_gallery += gallery_image_image+'#####'+gallery_image_label+'#####'+gallery_image_sort_order +'#####'+gallery_image_is_thumbnails  +'#####'+gallery_image_is_detail+'|||||';
		}
	});
	$(".tabsContent .image_gallery").val(image_gallery);
	//custom_option
	i = 0;
	custom_option = new Object();
	jQuery(".custom_option_list tbody tr").each(function(){
		option_header = new Object();
		$(this).find("td").each(function(){
			rel = $(this).attr("rel");
			
			if(rel != 'image'){
				if(rel){
					option_header[rel] = $(this).attr('val');
				}
			}else{
				rel = $(this).find("img").attr("rel");
				option_header['image'] = rel;
			}
			
		});
		custom_option[i] = option_header;
		i++;
	});
		
	custom_option = JSON.stringify(custom_option);
	//alert(custom_option);
	jQuery(".custom_option_value").val(custom_option);
	
	cate_str = "";
	jQuery(".category_tree div.ckbox.checked").each(function(){
		cate_id = jQuery(this).find("input").val();
		cate_str += cate_id+",";
	});
	
	
	
	jQuery(".category_tree div.ckbox.indeterminate").each(function(){
		cate_id = jQuery(this).find("input").val();
		cate_str += cate_id+",";
	});
	
	jQuery(".inputcategory").val(cate_str);
	
	tier_price_str = "";
	$(".tier_price table tbody tr").each(function(){
		tier_qty = $(this).find(".tier_qty").val();
		tier_price = $(this).find(".tier_price").val();
		if(tier_qty && tier_price){
			tier_price_str += tier_qty+'##'+tier_price+"||";
		}
	});
	//alert(tier_price_str);
	jQuery(".tier_price_input").val(tier_price_str);
	//alert($(".tier_price_input").val());
	return validateCallback(thiss, dialogAjaxDoneCloseAndReflush);
}
</script>

<div class="pageContent"> 
	<form  method="post" action="<?= $saveUrl ?>" class="pageForm required-validate" onsubmit="return thissubmit(this, dialogAjaxDoneCloseAndReflush);">
		<?php echo CRequest::getCsrfInputHtml();  ?>	
		<input type="hidden" name="operate"  value="<?=  $operate ?>" />
		<input type="hidden" class="primary_info"  value="<?= $primaryInfo ?>" />
		<div class="tabs" >
			<div class="tabsHeader">
				<div class="tabsHeaderContent">
					<ul>
						<li><a href="javascript:;"><span><?=  Yii::$service->page->translate->__('Basic Info') ?></span></a></li>
						<li><a href="javascript:;"><span><?=  Yii::$service->page->translate->__('Price Info') ?></span></a></li>
						<li><a href="javascript:;"><span><?=  Yii::$service->page->translate->__('Meta Info') ?></span></a></li>
						<li><a href="javascript:;"><span><?=  Yii::$service->page->translate->__('Description Info') ?></span></a></li>
						<li><a href="javascript:;"><span><?=  Yii::$service->page->translate->__('Image Info') ?></span></a></li>
						<li><a href="javascript:;"><span><?=  Yii::$service->page->translate->__('Category Info') ?></span></a></li>
						<li><a href="javascript:;"><span><?=  Yii::$service->page->translate->__('Attr Group') ?></span></a></li>
						<li><a href="javascript:;"><span><?=  Yii::$service->page->translate->__('Custom Option') ?></span></a></li>
						<li><a href="javascript:;"><span><?=  Yii::$service->page->translate->__('Relate Product') ?></span></a></li>
					</ul>
				</div>
			</div>
			<div class="tabsContent" style="height:550px;overflow:auto;">
				<div>
					<input type="hidden"  value="<?=  $product_id; ?>" size="30" name="product_id" class="textInput ">
				
					<fieldset id="fieldset_table_qbe">
						<legend style="color:#cc0000"><?=  Yii::$service->page->translate->__('Product attribute group switching: Please switch the product attribute group before editing') ?></legend>
						<div>
							<p class="edit_p">
								<?= $attrGroup ?>
							</p>
						</div>
					</fieldset>
					<?= $baseInfo ?>
				</div>
				<div>
					<?= $priceInfo ?>
					<div class="edit_p">
						<label><?=  Yii::$service->page->translate->__('Tier Price') ?>：</label>
						<input type="hidden" name="editFormData[tier_price]" class="tier_price_input"  />
						<div class="tier_price" style="float:left;width:700px;">
							<table style="">
								<thead>
									<tr>
										<th><?=  Yii::$service->page->translate->__('Qty') ?></th>
										<th><?=  Yii::$service->page->translate->__('Price') ?></th>
										<th><?=  Yii::$service->page->translate->__('Action') ?></th>
									</tr>
								</thead>
								<tbody>
									<?php if(is_array($tier_price) && !empty($tier_price)){  ?>
										<?php foreach($tier_price as $one){ ?>
										<tr>
											<td>
												<input class="tier_qty" type="text" value="<?= $one['qty'] ?>"> <?=  Yii::$service->page->translate->__('And Above') ?>
											</td>
											<td>
												<input class="tier_price" type="text" value="<?= $one['price'] ?>">
											</td>
											<td>
                                                <i class="fa fa-trash-o"></i>
											</td>
										</tr>
										<?php } ?>
									<?php } ?>
								</tbody>
								<tfoot style="text-align:right;">
									<tr>
										<td colspan="100" style="text-align:right;">						
											<a rel="2" style="text-align:right;" href="javascript:void(0)" class="addProductTierPrice button">
												<span><?=  Yii::$service->page->translate->__('Add Tier Price') ?></span>
											</a>					
										</td>				
									</tr>			
								</tfoot>
							</table>
							<script>
								$(document).ready(function(){
									$(".addProductTierPrice").click(function(){
										str = "<tr>";
										str +="<td><input class=\"tier_qty textInput \" type=\"text\"   /> <?=  Yii::$service->page->translate->__('And Above') ?> </td>";
										str +="<td><input class=\"tier_price textInput\" type=\"text\"   /></td>";
										str +="<td><i class='fa fa-trash-o'></i></td>";
										str +="</tr>";
										$(".tier_price table tbody").append(str);
									});
									$(".dialog").off("click").on("click",".tier_price table tbody tr td .fa-trash-o",function(){
                                        $(this).parent().parent().remove();
                                    });
                                    
								});
							</script>
						</div>
					</div>
				</div>
				<div>
					<?= $metaInfo ?>
				</div>
				<div >
					<?= $descriptionInfo ?>
				</div>
				<div >
					<input type="hidden" name="image_main" class="image_main"  />
					<input type="hidden" name="image_gallery" class="image_gallery"  />
					<?=  $img_html ?>	
					<div id="addpicContainer" style="padding-bottom:20px;">
						<!-- 利用multiple="multiple"属性实现添加多图功能 -->
						<!-- position: absolute;left: 10px;top: 5px;只针对本用例将input隐至图片底下。-->
						<!-- height:0;width:0;z-index: -1;是为了隐藏input，因为Chrome下不能使用display:none，否则无法添加文件 -->
						<!-- onclick="getElementById('inputfile').click()" 点击图片时则点击添加文件按钮 -->
						<button style="" onclick="getElementById('inputfile').click()" class="scalable" type="button" title="Duplicate" id=""><span><span><span><?=  Yii::$service->page->translate->__('Browse Files') ?></span></span></span></button>
						
						<input type="file" multiple="multiple" id="inputfile" style="margin:10px;height:0;width:0;z-index: -1; position: absolute;left: 10px;top: 5px;"/>
						<span class="loading"></span>
					</div>
					<script>
						jQuery(document).ready(function(){
							jQuery("body").on('click',".delete_img",function(){
								jQuery(this).parent().parent().remove();
							});
							//jQuery(".delete_img").click(function(){
							//	jQuery
							//});
						
							//响应文件添加成功事件
							$("#inputfile").change(function(){
								//创建FormData对象
								var thisindex = 0;
								jQuery(".productimg tbody tr").each(function(){
									rel = parseInt(jQuery(this).attr("rel"));
									//alert(rel);
									if(rel > thisindex){
										thisindex = rel;
									}
								});
								//alert(thisindex);
								var data = new FormData();
								data.append('thisindex', thisindex);
								
								//为FormData对象添加数据
								$.each($('#inputfile')[0].files, function(i, file) {
									data.append('upload_file'+i, file);
								});
								//$(".loading").show();	//显示加载图片
								//发送数据
								data.append("<?= CRequest::getCsrfName() ?>", "<?= CRequest::getCsrfValue() ?>");
								$.ajax({
									url:'<?= CUrl::getUrl('catalog/productinfo/imageupload')  ?>',
									type:'POST',
									data:data,
									async:false,
									dataType: 'json', 
									timeout: 80000,
									cache: false,
									contentType: false,		//不可缺参数
									processData: false,		//不可缺参数
									success:function(data, textStatus){
										//data = $(data).html();
										//第一个feedback数据直接append，其他的用before第1个（ .eq(0).before() ）放至最前面。
										//data.replace(/&lt;/g,'<').replace(/&gt;/g,'>') 转换html标签，否则图片无法显示。
										//if($("#feedback").children('img').length == 0) $("#feedback").append(data.replace(/&lt;/g,'<').replace(/&gt;/g,'>'));
										//else $("#feedback").children('img').eq(0).before(data.replace(/&lt;/g,'<').replace(/&gt;/g,'>'));
									//	alert(data.return_status);
										if(data.return_status == "success"){
										//	alert("success");
											jQuery(".productimg tbody ").append(data.img_str);
											//alert(data.img_str);
										}
										//$(".loading").hide();	//加载成功移除加载图片
									},
									error:function(){
										alert('<?=  Yii::$service->page->translate->__('Upload Error') ?>');
										//$(".loading").hide();	//加载失败移除加载图片
									}
								});
							});
						});
					</script>
				</div>
				<div>
					<script>
									
                        $(document).ready(function(){
                            id = '<?= $product_id; ?>' ;
                            
                            getCategoryData(id,0);  
                        });
                    </script>
                    <input type="hidden" value="" name="category"  class="inputcategory"/>
                    <ul class="category_tree tree treeFolder treeCheck expand" >
                    </ul>
				</div>
				<div >
					<?= $groupAttr ?>
				</div>
				<div class="custom_option">
					<div>
						<?= $custom_option_add ?>
					</div>
					<div style="clear:both"></div>
					<div class="custom_option_img_list" style="display:none;border: 1px dashed #888;margin: 15px 0;padding: 8px 0;">
						<?=  $custom_option_img  ?>
					</div>
					<div style="clear:both"></div>
					
					<div class="custom_option_list" style="margin:20px 2px;">
						<?= $custom_option_list ?>
						
					</div>
					<input type="hidden" class="custom_option_value" name="custom_option"  value=""  />
					<script>
					$(document).ready(function(){
						jQuery(document).off("click",".chose_custom_op_img");
						jQuery(document).on("click",".chose_custom_op_img",function(){
							$(".custom_option_img_list").slideDown("slow");
							
						});
						jQuery(document).off("click",".custom_option_img_list img");
						jQuery(document).on("click",".custom_option_img_list img",function(){
							rel = $(this).attr('rel');
							src = $(this).attr('src');
							$(".chosened_img").html('<img style="width:80px;" rel="'+rel+'" src="'+src+'">');
							$(".custom_option_img_list").slideUp("slow");
						});
						
						
						jQuery(document).off("click",".deleteCustomList");
						jQuery(document).on("click",".deleteCustomList",function(){
							$(this).parent().parent().remove();
							
						});
						jQuery(document).off("click",".add_custom_option");
						jQuery(document).on("click",".add_custom_option",function(){
							i = 0;
							$str = '<tr>';
							general_sku = '';
							$(".custom_option_attr").each(function(){
								attr = $(this).attr("atr");
								val = $(this).val();
                                label_v = $(this).find("option:selected").text();
								if(!val){
									i = 1;
									alert("<?=  Yii::$service->page->translate->__('can not select empty') ?>");
								}
								$str += '<td rel="'+attr+'" val="'+val+'" >'+label_v+'</td>';
								val = val.replace(/ /g, "*")
								if(!general_sku){
									general_sku = val;
								}else{
									general_sku += "-"+val;
								}
							});
							custom_option_sku = general_sku;
							custom_option_sku = custom_option_sku.toLowerCase();   
							$(".custom_option_sku").val(custom_option_sku);
							$str += '<td class="custom_option_sku" rel="sku" val="'+custom_option_sku+'">'+custom_option_sku+'</td>';
							custom_option_qty = $(".custom_option_qty").val();
							if(!custom_option_qty){
								custom_option_qty = 99999;
							}
							$str += '<td rel="qty" val="'+custom_option_qty+'" >'+custom_option_qty+'</td>';
							custom_option_price = $(".custom_option_price").val();
							if(!custom_option_price){
								custom_option_price = 0;
							}
							$(".custom_option_price").val(custom_option_price);
							$str += '<td rel="price" val="'+custom_option_price+'" >'+custom_option_price+'</td>';
							chosened_img_src = $(".chosened_img img").attr('src');
							chosened_img_rel = $(".chosened_img img").attr('rel');
							if(!chosened_img_src || !chosened_img_rel){
								i = 1;
								alert("<?= Yii::$service->page->translate->__('you must chose a image');  ?>");
							}
							$str += '<td rel="image"><img style="width:30px;" rel="'+chosened_img_rel+'" src="'+chosened_img_src+'"/></td>';
							$str += '<td><a title="<?=  Yii::$service->page->translate->__('Delete') ?>"  href="javascript:void(0)" class="btnDel deleteCustomList"><i class="fa fa-trash-o"></i></a></td>'
							// 检查这个sku是否已经存在 
							$(".custom_option_sku").each(function(){
								sku = $(this).html();
								if(sku == custom_option_sku){
									i = 1;
									alert("<?= Yii::$service->page->translate->__('this custom_option sku is exist');  ?>");
								}
							});
							if(!i){
								//alert(11);
								$(".custom_option_list table tbody").append($str);
							}
						});
						
					});
					</script>
				</div>
				<div class="relation_list" style="margin:20px 2px;">
						<?= $relation ?>	
				</div>
			</div>
			<div class="tabsFooter">
				<div class="tabsFooterContent"></div>
			</div>
		</div>
		<div class="formBar">
			<ul>
				<!--<li><a class="buttonActive" href="javascript:;"><span>保存</span></a></li>-->
				<li><div class="buttonActive"><div class="buttonContent"><button onclick=""  value="accept" name="accept" type="submit"><?=  Yii::$service->page->translate->__('Save') ?></button></div></div></li>
				<li>
					<div class="button"><div class="buttonContent"><button type="button" class="close"><?=  Yii::$service->page->translate->__('Cancel') ?></button></div></div>
				</li>
			</ul>
		</div>
	</form>
</div>	

