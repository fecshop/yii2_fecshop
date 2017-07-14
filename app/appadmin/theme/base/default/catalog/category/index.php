<?php
use fec\helpers\CRequest;
use fec\helpers\CUrl;
?>

<style type="text/css">
	ul.rightTools {float:right; display:block;}
	ul.rightTools li{float:left; display:block; margin-left:5px}
	p.edit_p{min-height:30px;margin:6px 4px 0px;}
	.edit_p label{float:left;width:140px;display:block;line-height:24px;}
	.edit_p   input{width:600px;}
	
</style>

<script>

function array_unique(arr)
{
	arr.sort();
	var re=[arr[0]];
	for(var i = 1; i < arr.length; i++)
	{
		if( arr[i] !== re[re.length-1])
		{
			re.push(arr[i]);
		}
	}
	return re;
}


function array_quchu(arr1,arr2){
    var arr3 = [];
    for (var i = 0; i < arr1.length; i++) {
		var flag = true;
		for (var j = 0; j < arr2.length; j++) {
			if (arr2[j] == arr1[i]) {
				flag = false;
			}
		}
		if (flag) {
			arr3.push(arr1[i]);
		}
	}
	return arr3;
} 

//选择产品。
function product_select(){
	product_select_info = $('#jbsxBox_product input[name="product_select_info"]').val();
	if(product_select_info){
		var select_arr = product_select_info.split(","); 
	}else{
		var select_arr = []; 
	} 
	
	product_unselect_info = $('#jbsxBox_product input[name="product_unselect_info"]').val();
	if(product_unselect_info){
		var un_select_arr = product_unselect_info.split(","); 
	}else{
		var un_select_arr = []; 
	} 
	
	$('.gridTbody input:checkbox:checked').each(function(){
		val = $(this).val();
		select_arr.push(val);
	});
	select_arr = array_unique(select_arr);
	//alert(select_arr);
	
	$('.gridTbody input:checkbox:unchecked').each(function(){
		val = $(this).val();
		un_select_arr.push(val);
	});
	un_select_arr = array_unique(un_select_arr);
	
	select_arr = array_quchu(select_arr,un_select_arr);
	selected_product = select_arr.join(",");
	//alert(selected_product); 
	$('#jbsxBox_product input[name="product_select_info"]').val(selected_product);		
	
	un_select_arr = array_quchu(un_select_arr,select_arr);
	un_selected_product = un_select_arr.join(",");
	//alert(un_selected_product); 
	$('#jbsxBox_product input[name="product_unselect_info"]').val(un_selected_product);
}

function thissubmit(thiss){	
	ifproductinfoisload = $(".ifproductinfoisload").val();
	if(ifproductinfoisload){
		product_select();
	}
	return validateCallback(thiss, dialogAjaxDoneReflush);
}

$(document).ready(function(){
	$(document).off("click").on("click",".addcategory",function(){
		parentId = $(".treeFolder .selected > .category_one").attr("key");
		if(parentId){
			$(".parent_id").val(parentId);
		}else{
			parentId = $(".parent_id").val();
		}
		ajaxxurl = $(".add_category_a").attr("ajaxxurl");
		url = ajaxxurl+'?parent_id='+parentId;
		$(".add_category_a").attr("href",url);
		$(".add_category_a").click();
	});
	
	$(".del-category").off("click").on("click",".delcategory",function(){
		delCateUrl = "<?= CUrl::getUrl('catalog/category/remove');  ?>";
		delCateId  = $(".treeFolder .selected > .category_one").attr("key");
		if(!delCateId){
			alertMsg.warn('请选择您要删除的分类');
		}else{
			delCateUrl += "?<?= Yii::$service->category->getPrimaryKey() ?>="+delCateId;
			alertMsg.confirm("您确定要删除这个分类吗？",
			{
				okCall:function(){
					$.post(delCateUrl,DWZ.ajaxDone,"json");
					navTab.reload();
				}
			});
		}
		
			
	});
});
	
	
	

</script>

<form  method="post" action="<?= $save_url ?>" class="pageForm required-validate"  onsubmit="return thissubmit(this);">
	<input type="hidden" name="parent_id"  class="parent_id"  value="0"  />
	<div style=" display: none;">
		<a href="" ajaxxurl="<?= CUrl::getUrl('catalog/category/index'); ?>" target="ajax" rel="jbsxBox"  class="add_category_a">add</a>
	</div>
	
	<div class="pageContent" style="padding:5px">
		<div layoutH="16" style="float:left; display:block; overflow:auto; width:240px; border:solid 1px #CCC; line-height:21px; background:#fff">
			<div style="">
				<div class="formBar">
					<ul style="float:left;">
						<!--<li><a class="buttonActive" href="javascript:;"><span>保存</span></a></li>-->
						<li class="add-category"><a href="javascript:void(0)" class="addcategory button"><span>增加</span></a></li>
						
						<li><div class="buttonActive"><div class="buttonContent"><button type="submit" name="accept" value="accept" >保存</button></div></div></li>
						
						<li  class="del-category"><a href="javascript:void(0)" class="delcategory button" ><span>删除</span></a></li>
						
					</ul>
				</div>
			</div>
			<?=  $category_tree ;?>
		</div>
		
		<?php echo CRequest::getCsrfInputHtml();  ?>	
		<div id="jbsxBox">
			
		</div>
	</div>
</form>


























	

