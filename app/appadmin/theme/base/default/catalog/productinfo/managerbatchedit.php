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
		url = '<?= CUrl::getUrl("catalog/productinfo/managerbatchedit"); ?>'+pm+currentPrimayInfo;
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
	//i = 0;
	//custom_option = new Object();
	//jQuery(".custom_option_list tbody tr").each(function(){
	//	option_header = new Object();
	//	$(this).find("td").each(function(){
	//		rel = $(this).attr("rel");
	//		
	//		if(rel != 'image'){
	//			if(rel){
	//				option_header[rel] = $(this).attr('val');
	//			}
	//		}else{
	//			rel = $(this).find("img").attr("rel");
	//			option_header['image'] = rel;
	//		}
	//		
	//	});
	//	custom_option[i] = option_header;
	//	i++;
	//});
	//	
	//custom_option = JSON.stringify(custom_option);
	//alert(custom_option);
	//jQuery(".custom_option_value").val(custom_option);
	
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
    
    spuStr = '';
    isSkuPriceQtyEmpty = false;
    $(".sell-sku-body-table tr ").each(function(){
        skuStr = '';
        iss = 0;
        $(this).find("td.sell-sku-cell .cell-inner  p.spu_attr_content").each(function(){
            sAttr = $(this).attr('rel');
            sAttrVal = $(this).attr('title');
            if (sAttr && sAttrVal) {
                skuStr += sAttr+ '###' + sAttrVal + '|||';
                iss = 1;
            }
        });
        if (iss) {
            sSkuCodeVal = $(this).find("td.sell-sku-cell .sku_code").val();
            sSkuPriceVal = $(this).find("td.sell-sku-cell .sku_price").val();
            sSkuQtyVal = $(this).find("td.sell-sku-cell .sku_qty").val();
            if (sSkuCodeVal && sSkuPriceVal && sSkuQtyVal) {
                skuStr += 'sku###' + sSkuCodeVal + '|||';
                skuStr += 'price###' + sSkuPriceVal + '|||';
                skuStr += 'qty###' + sSkuQtyVal;
                spuStr += skuStr + '***';
            } else {
                isSkuPriceQtyEmpty = true;
                
            }
        }
        
    });
    if (isSkuPriceQtyEmpty) {
        alert("sku,价格，库存不能为空");
        return false;
    }
    if (!spuStr) {
        alert("您应该至少添加一行spu属性");
        return false;
    }
    $(".spu_attrs").val(spuStr);
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
       <input type="hidden" class="spu_attrs"  name="spu_attrs" value="" />
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
						
                        <li><a href="javascript:;"><span><?=  Yii::$service->page->translate->__('Spu Attr') ?></span></a></li>
						<!--<li><a href="javascript:;"><span><?=  Yii::$service->page->translate->__('Custom Option') ?></span></a></li>
						-->
                        <li><a href="javascript:;"><span><?=  Yii::$service->page->translate->__('Relate Product') ?></span></a></li>
					</ul>
				</div>
			</div>
			<div class="tabsContent" style="height:550px;overflow:auto;">
				<div>
					<input type="hidden"  value="<?=  $product_id; ?>" size="30" name="product_id" class="textInput ">
				
					<fieldset id="fieldset_table_qbe">
						<legend style="color:#009688"><?=  Yii::$service->page->translate->__('Product attribute group switching: Please switch the product attribute group before editing') ?></legend>
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
						<button style="" onclick="getElementById('inputfile').click()" class="scalable upload-image" type="button" title="Duplicate" id=""><span><span><span><?=  Yii::$service->page->translate->__('Browse Files') ?></span></span></span></button>
						
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
					<?= $groupGeneralAttr ?>
				</div>
                <div >
                    <div class="group_spu_attr">
                        <?php if (is_array($groupSpuAttr) && !empty($groupSpuAttr)): $iu = 0;  ?>
                            <?php foreach ($groupSpuAttr as $spuName => $spuData):   $iu++; ?>
                                <div class="spu_attr_one" style="margin-top:10px;margin-bottom:20px;" rel="<?= $spuName ?>">
                                    <div style="margin-bottom: 10px;">
                                        <label style="text-transform: capitalize;"><?= $spuName ?></label>
                                        <input type="text" style="width:100px;"  class="spu_attr_input spu_attr_input_<?= $iu  ?>" />
                                        
                                        <a  rel="<?= $iu  ?>" style="text-align:right; float:none;" href="javascript:void(0)" class="add_spu_attr button">
												<span> <?=  Yii::$service->page->translate->__('Add') ?></span>
											</a>
                                    </div>
                                    <div class="spu_attr_info spu_attr_info_<?= $iu  ?>">
                                        <?php foreach ($spuData as $sd): ?>
                                            <span style="    margin-right: 10px;  font-size: 14px;  height: 30px; line-height: 30px; min-width: 105px;display: inline-block;">
                                                <input class="spuAttrCheck" type="checkbox"  id="<?=  $sd?>" rel="<?=  $sd?>">
                                                <label for="<?=  $sd?>"  style="text-transform: capitalize;font-size:14px;"><?=  $sd?></label>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <script>
                            $(document).ready(function(){
                                $(".dialog").on("click",".spuAttrCheck",function(){
                                    var htmlStr = '<tr>';
                                    var attrArr = [];
                                    var attrRows = {};
                                    $columnsAllRows = 0;
                                    $(".spu_attr_one").each(function(){
                                        var obj = {};
                                        var spuName = $(this).attr('rel');
                                        obj.name = spuName;
                                        htmlStr += '<td class="sell-sku-cell sell-sku-cell-text">' + spuName + '</td>';
                                        rows = 0;
                                        var spuData = [];
                                        $(this).find(".spuAttrCheck:checked").each(function(){
                                           var val = $(this).attr('rel');
                                           spuData.push(val);
                                           rows++;
                                        });
                                        // 计算rows数
                                        for (var x in attrRows){
                                            v = attrRows[x];
                                            attrRows[x] = v * rows;
                                        }
                                        attrRows[spuName] = 1;
                                        obj.data = spuData;
                                        attrArr.push(obj);
                                    });
                                    for (x in attrArr) {
                                        obj = attrArr[x];
                                        obj.rowSize = attrRows[obj.name];
                                    }
                                    htmlStr += '<td class="sell-sku-cell sell-sku-cell-text">Sku编码</td>';
                                    htmlStr += '<td class="sell-sku-cell sell-sku-cell-text">价格</td>';
                                    htmlStr += '<td class="sell-sku-cell sell-sku-cell-text">库存</td>';
                                    htmlStr += '</tr>';
                                    i = 0;
                                    hStr = '';
                                    htmlStr += getTableStr(attrArr, i, hStr); 
                                    $(".sell-sku-body-table tbody").html(htmlStr);
                                });
                                
                                function getTableStr(attrArr, i, hStr) {
                                    var attrObj = attrArr[i];
                                    var htmlStr = '';
                                    for (var j = 0; j < attrObj.data.length; j++) {
                                        rowspan = attrObj.rowSize;
                                        spuName = attrObj.name;
                                        vData = attrObj.data;
                                        attrVal = vData[j];
                                        shStr = hStr;
                                        ii = i + 1;
                                        if ( ii >= attrArr.length) {
                                            if (j > 0) {
                                                reallyDo = 'sell-sku-cell-text'; 
                                                replaceWith = "sell-sku-cell-text hide";
                                                shStr = shStr.replace(new RegExp(reallyDo, 'g'), replaceWith);
                                            }
                                        } else if(ii != 1){
                                            if (j > 0) {
                                                reallyDo = 'sell-sku-cell-text'; 
                                                replaceWith = "sell-sku-cell-text hide";
                                                shStr = shStr.replace(new RegExp(reallyDo, 'g'), replaceWith);
                                            }
                                        }
                                        shStr += '<td class="sell-sku-cell sell-sku-cell-text" rowspan="'+ rowspan +'">';
                                        shStr += '<div class="cell-inner" style="min-width: 78px;">';
                                        shStr += '    <div class="sell-sku-cell-text">';
                                        shStr += '        <p class="spu_attr_content  sell-sku-cell-text-content" rel="'+spuName+'" title="'+attrVal+'">'+attrVal+'</p>';
                                        shStr += '    </div>';
                                        shStr += '</div>';
                                        shStr += '</td>';
                                        if ( ii < attrArr.length) {
                                            htmlStr += getTableStr(attrArr, ii, shStr);
                                        } else {
                                            htmlStr += '<tr>' + shStr;
                                            htmlStr += '<td class="sell-sku-cell sell-sku-cell-input" rowspan="1">';
                                            htmlStr += '    <div class="cell-inner" style="min-width: 160px;">';
                                            htmlStr += '    <span class="sell-o-input"><span class="input-wrap">';
                                            htmlStr += '    <span class="next-input next-input-single next-input-medium fusion-input">';
                                            htmlStr += '    <input class="textInput valid sku_code" type="text" label="商家编码" name="skuOuterId" value="" maxlength="64" height="100%">';
                                            htmlStr += '    </span></span></span></div>';
                                            htmlStr += '</td>';
                                            htmlStr += '<td class="sell-sku-cell sell-sku-cell-money" rowspan="1">';
                                            htmlStr += '    <div class="cell-inner" style="min-width: 90px;">';
                                            htmlStr += '       <span class="sell-o-number">';
                                            htmlStr += '        <span class="input-wrap">';
                                            htmlStr += '        <span class="next-input next-input-single next-input-medium fusion-input">';
                                            htmlStr += '        <input class="textInput valid sku_price" type="text" label="价格（元）" required="" value="" name="skuPrice" maxlength="15" height="100%">';
                                            htmlStr += '        </span></span></span></div>';
                                            htmlStr += '</td>';
                                            htmlStr += '<td class="sell-sku-cell sell-sku-cell-positiveNumber" rowspan="1">';
                                            htmlStr += '    <div class="cell-inner" style="min-width: 90px;">';
                                            htmlStr += '    <span class="sell-o-number">';
                                            htmlStr += '    <span class="input-wrap">';
                                            htmlStr += '    <span class="next-input next-input-single next-input-medium fusion-input">';
                                            htmlStr += '    <input class="textInput valid sku_qty" type="text" label="数量（件）" required="" value="0" name="skuStock" maxlength="15" height="100%">';
                                            htmlStr += '    </span></span></span></div>';
                                            htmlStr += '</td>';
                                            htmlStr += '</tr>';
                                        }
                                    }
                                    
                                    return htmlStr;
                                }
                                
                                $(".dialog").on("click",".add_spu_attr",function(){
                                    var rel = $(this).attr('rel');
                                    var str1 = ".spu_attr_input_" + rel;
                                    var str2 = ".spu_attr_info_" + rel ;
                                    var addVal = $(str1).val();
                                    addVal = addVal.toLowerCase();
                                    if (!addVal) {
                                        alert("请填写值");
                                    } else {
                                        var isCF = 0;
                                        $(str2 + " input").each(function(){
                                            v = $(this).attr('rel');
                                            v = v.toLowerCase();
                                            if (v == addVal) {
                                                alert("添加的值重复");
                                                isCF = 1;
                                            }
                                        });
                                        if (isCF == 0) {
                                            appendStr = '<span style="    margin-right: 10px;  font-size: 14px;  height: 30px; line-height: 30px; min-width: 105px;display: inline-block;"><input class="spuAttrCheck" type="checkbox" id="'+addVal+'" rel="'+addVal+'"><label for="'+addVal+'" style="text-transform: capitalize;font-size:14px;">'+addVal+'</label> </span>';
                                            //alert(appendStr);
                                            $(str2).append(appendStr);
                                        }
                                    }
                                    
                                });
                                
                            });
                        </script>
                        <table class="sell-sku-inner-table sell-sku-body-table " style="transform: translateY(0px);">
                            <colgroup>
                                <col width="111px">
                                <col width="113px">
                                <col width="109px">
                                <col width="111px">
                                <col width="194px">
                                <col width="151px">
                            </colgroup>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>    
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


<style>

.sell-sku-body-table td.sell-sku-cell {
    position: relative;
    line-height: 1.5;
    padding: 0 8px;
}


.sell-sku-body-table td {
    color: #323b44;
    font-size: 12px;
    border-right: 1px solid #c6d1db;
    text-overflow: ellipsis;
    word-break: break-all;
    text-align: left;
    vertical-align: middle;
    min-width: 40px;
    border-bottom: 1px solid #c6d1db;
    
    
}

.sell-sku-body-table .hide {
    display: none!important;
}

.sell-sku-body-table .textInput{
    border:none;
}


.sell-sku-body-table {
    border-top: 1px solid #c6d1db;
    border-left: 1px solid #c6d1db;
}









</style>
