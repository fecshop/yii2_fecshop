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
.edit_p label{float:left;line-height: 20px;min-width:110px;}
.edit_p input{width:700px;}
.tabsContent .tabsContent .edit_p label{min-width:104px;}
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
	//$(".tabs").off("click").on("click",".tabsHeaderContent a",function(){
	//	
	//	initUI("#fieldset_table_qbe");
	//});
	
});


function thissubmit(thiss){
	
	main_image_image 		=  $('.productimg input[type=radio]:checked').val();
	main_image_label 		=  $('.productimg input[type=radio]:checked').parent().parent().find(".image_label").val();
	main_image_sort_order 	=  $('.productimg input[type=radio]:checked').parent().parent().find(".sort_order").val();
	//alert(main_image_image+main_image_label+main_image_sort_order);
	if(main_image_image){
		image_main = main_image_image+'#####'+main_image_label+'#####'+main_image_sort_order;
		$(".tabsContent .image_main").val(image_main);
	}
	image_gallery = '';
	$('.productimg input[type=radio]').each(function(){
		if(!$(this).is(':checked')){
			
			gallery_image_image 		= $(this).val();
			gallery_image_label 		= $(this).parent().parent().find(".image_label").val();
			gallery_image_sort_order 	= $(this).parent().parent().find(".sort_order").val();
			//alert(gallery_image_image+gallery_image_label+gallery_image_sort_order);
			image_gallery += gallery_image_image+'#####'+gallery_image_label+'#####'+gallery_image_sort_order+'|||||';
		}
	});
	$(".tabsContent .image_gallery").val(image_gallery);
	
	
	return validateCallback(thiss, dialogAjaxDoneCloseAndReflush);
	
}
</script>

<div class="pageContent"> 
	<form  method="post" action="<?= $saveUrl ?>" class="pageForm required-validate" onsubmit="return thissubmit(this, dialogAjaxDoneCloseAndReflush);">
		<?php echo CRequest::getCsrfInputHtml();  ?>	
		<input type="hidden" class="primary_info"  value="<?= $primaryInfo ?>" />
		<div class="tabs" >
			<div class="tabsHeader">
				<div class="tabsHeaderContent">
					<ul>
						<li><a href="javascript:;"><span>基本信息</span></a></li>
						<li><a href="javascript:;"><span>Meta信息</span></a></li>
						<li><a href="javascript:;"><span>描述信息</span></a></li>
						<li><a href="javascript:;"><span>图片信息</span></a></li>
						<li><a href="javascript:;"><span>分类信息</span></a></li>
						<li><a href="javascript:;"><span>属性组信息</span></a></li>
						<li><a href="javascript:;"><span>自定义信息</span></a></li>
					</ul>
				</div>
			</div>
			<div class="tabsContent" style="height:450px;overflow:auto;">
				<div>
					<input type="hidden"  value="<?=  $product_id; ?>" size="30" name="product_id" class="textInput ">
				
					<fieldset id="fieldset_table_qbe">
						<legend style="color:#cc0000">产品属性组</legend>
						<div>
							<p class="edit_p">
								<?= $attrGroup ?>
							</p>
						</div>
					</fieldset>
					
					
					<?= $baseInfo ?>
				</div>
				
				<div><?= $metaInfo ?>
				</div>
				
				<div ><?= $descriptionInfo ?>
				</div>
				
				
				
				<div >
					<input type="hidden" name="image_main" class="image_main"  />
					<input type="hidden" name="image_gallery" class="image_gallery"  />
					<?=  $img_html ?>	
					<div id="addpicContainer">
						<!-- 利用multiple="multiple"属性实现添加多图功能 -->
						<!-- position: absolute;left: 10px;top: 5px;只针对本用例将input隐至图片底下。-->
						<!-- height:0;width:0;z-index: -1;是为了隐藏input，因为Chrome下不能使用display:none，否则无法添加文件 -->
						<!-- onclick="getElementById('inputfile').click()" 点击图片时则点击添加文件按钮 -->
						<button style="" onclick="getElementById('inputfile').click()" class="scalable" type="button" title="Duplicate" id=""><span><span><span>Browse Files</span></span></span></button>
						
						<input type="file" multiple="multiple" id="inputfile" style="height:0;width:0;z-index: -1; position: absolute;left: 10px;top: 5px;"/>
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
										alert('上传出错');
										//$(".loading").hide();	//加载失败移除加载图片
									}
								});
							});
						});
					</script>
				</div>
				
				<div >
				</div>
				<div ><?= $groupAttr ?>
				</div>
				
				<div >
					
					<div class="custom_option">
								<style>
									.one_option{background:#e7efef;position:relative;margin:10px;padding:10px;border:1px solid #cddddd}
									.close_panl{position:absolute;right:10px;top:10px;cursor: pointer;}
									.close_panl img:hover{border:1px solid #ccc;}
									.close_panl img{border:1px solid #fff;}
									.one_option tbody tr td img{cursor: pointer;border:1px solid #fff;}
									.one_option tbody tr td img:hover{border:1px solid #ccc;}
								</style>
								<script>
									jQuery(document).ready(function(){
										jQuery(".add_custom_option").click(function(){
											i = 1;
											jQuery(".one_option").each(function(){
												thisi = parseInt(jQuery(this).attr("rel"));
												if(thisi>i){
													i = thisi;
												}
											});
											i++;
											add = '<div class="one_option" rel="'+i+'">';
											add += '		<div class="close_panl" >';
											add += '			<img src="http://admin.intosmile.com/images/bkg_btn-close2.gif" />';
											add += '		</div>';
											add += '		<table class="option_header">';
											add += '			<tr >';
											add += '				<td style="padding:3px;">Title</td>';
											add += '				<td style="padding:3px;">Is_Required</td>';
											add += '				<td style="padding:3px;">Sort_Order</td>';
											add += '			</tr>';
											add += '			<tr >';
											add += '				<td><input type="text" class="title_header textInput required valid"/></td>';
											add += '				<td><select class="is_require" style="margin:0 24px;"><option value="1">Yes</option><option value="0">No</option>  </select>';
											add += '				<td><input type="text" class="sort_order_header" /></td>';
											add += '			</tr>';
														
											add += '		</table>';
												
											add += '		<table class="list option_content" style="margin:10px;">';
											add += '			<thead>';
											add += '				<tr style="background:#ccc;">';
											add += '	<td>en Title</td>	';add += '	<td>fr Title</td>	';add += '	<td>es Title</td>	';add += '	<td>de Title</td>	';add += '	<td>it Title</td>	';add += '	<td>nl Title</td>	';add += '	<td>ru Title</td>	';add += '	<td>pt Title</td>	';											add += '					<td>Price</td>';
											add += '					<td>Sort Order</td>';
											add += '					<td></td>';
											add += '				</tr>';
											add += '			</thead>';
											add += '			<tbody class="addtbody'+i+'">';
															
											add += '				</tr>';
															
											add += '			</tbody>';
											add += '			<tfoot style="text-align:right;">';
											add += '				<tr>';
											add += '					<td  colspan="100"  style="text-align:right;">';
											add += '						<a rel="'+i+'"  style="text-align:right;" href="javascript:void(0)"  class="addchildoption11 button"><span>增加子属性</span></a>';
											add += '					</td>';
											add += '				</tr>';
											add += '			</tfoot>';
														
											add += '		</table>';
											add += '	</div>';
											jQuery(".add_custom_option_div").append(add);
										});
										
										
										jQuery(document).off("click",".one_option tfoot a.addchildoption11");
										jQuery(document).on("click",".one_option tfoot a.addchildoption11",function(){
												add = '<tr>';
												add += '			<td><input rel="en_title" style="width:50px;" type="text" class="title_content textInput  valid" /></td>';add += '			<td><input rel="fr_title" style="width:50px;" type="text" class="title_content textInput  valid" /></td>';add += '			<td><input rel="es_title" style="width:50px;" type="text" class="title_content textInput  valid" /></td>';add += '			<td><input rel="de_title" style="width:50px;" type="text" class="title_content textInput  valid" /></td>';add += '			<td><input rel="it_title" style="width:50px;" type="text" class="title_content textInput  valid" /></td>';add += '			<td><input rel="nl_title" style="width:50px;" type="text" class="title_content textInput  valid" /></td>';add += '			<td><input rel="ru_title" style="width:50px;" type="text" class="title_content textInput  valid" /></td>';add += '			<td><input rel="pt_title" style="width:50px;" type="text" class="title_content textInput  valid" /></td>';												add += '			<td><input type="text" class="price_content" style="width:40px" /></td>';
												add += '			<td><input type="text" class="sort_order_content" style="width:40px" /></td>';
												add += '			<td><img src="http://admin.intosmile.com/images/bkg_btn-close2.gif" /></td>';	
												add += '		</tr>';
												
												i = jQuery(this).attr("rel");
												ee = ".addtbody"+i;
												//alert(ee);
												jQuery(ee).append(add);
											
										});
										
										jQuery(document).on("click",".one_option tbody tr td img",function(){
											jQuery(this).parent().parent().remove();
										});
										
										jQuery(document).on("click",".close_panl img",function(){
											jQuery(this).parent().parent().remove();
										});
										
										
										
									});
								</script>
								<a href="javascript:void(0)"  class=" add_custom_option button"><span>增加自定义属性</span></a>
								<div style="clear:both;"></div>
								
								<div class="add_custom_option_div">
									<input type="hidden"  class="custom_option_value" name="custom_option" value='' />
									
																		
														
												
												
											
									
									
									
								</div>	
							</div>
						
						
				</div>
				
			</div>
			<div class="tabsFooter">
				<div class="tabsFooterContent"></div>
			</div>
		</div>
		
		
	
		<div class="formBar">
			<ul>
				<!--<li><a class="buttonActive" href="javascript:;"><span>保存</span></a></li>-->
				<li><div class="buttonActive"><div class="buttonContent"><button onclick="func('accept')"  value="accept" name="accept" type="submit">保存</button></div></div></li>
			
				<li>
					<div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div>
				</li>
			</ul>
		</div>
	</form>
</div>	

