<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
use fec\helpers\CRequest;
/** 
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
?>
<style>
.pagination .j-first , 
.pagination .j-prev ,
.pagination .j-next ,
.pagination .j-last {display:none;}
.grid .gridTbody td div{height:auto;}
.searchContent input{width:60px;}
</style>
<div id="pagerForm2" onsubmit="return divSearch(this, 'jbsxBox_product');"  method="post" action="<?= \fec\helpers\CUrl::getCurrentUrl();  ?>">
	<?=  CRequest::getCsrfInputHtml();  ?>
	<?=  $pagerForm;  ?>
	
</div>
<div class="pageHeader">
	<div rel="pagerForm2" onsubmit="return divSearch(this, 'jbsxBox_product');"  action="<?= \fec\helpers\CUrl::getCurrentUrl();  ?>" method="post">
		<?php echo CRequest::getCsrfInputHtml();  ?>
		<div class="searchBar">
			<?php  echo $searchBar; ?>
		</div>
	</div>
</div>
<div class="pageContent category-product-list">
	
	<div class="panelBar">
		<?= $toolBar; ?>
	</div>
	<table class="table" width="100%" layoutH="138">
		<?= $thead; ?>
		<tbody>
			<?= $tbody; ?>
		</tbody>
	</table>
</div>

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



$(document).ready(function(){
	//搜索
	$(".searchBar").off("click").on("click",".productSearch",function(){
		url 	= $(".j-ajax").attr("href")+"&";
		$("#pagerForm2 input[name='productfiltertype']").val("");
		ajaxProduct(url,'','');
	});
	// 重置搜索
	$(".searchBar").on("click",".productReset",function(){
		url 	= $(".j-ajax").attr("href")+"&";
		$("#pagerForm2 input[name='productfiltertype']").val("reset");
		ajaxProduct(url,'','');
	});
	
	//排序
	$("#jbsxBox_product").off("click").on("click",".grid table tr th",function(){
		//alert(111);
		orderfield 	= $(this).attr("orderfield");
		if(orderfield){
			orderDirection = $("#pagerForm2 input[name='orderDirection']").val();
			if(orderDirection == 'desc'){
				orderDirection = 'asc';
			}else{
				orderDirection = 'desc';
			}
			selectVal 	= orderfield;
			selectName 	= "orderField";
			url 	= $(".j-ajax").attr("href");
			url 	+= "&"+selectName+"="+selectVal+"&orderDirection="+orderDirection+"&";
			ajaxProduct(url,selectName,'orderDirection');
		}
	});
	
	//$("#jbsxBox_product").on("click","input:checkbox",function(){
	//	product_select();
	//});
	
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
	
	
	
	function ajaxProduct(url,selectName,selectName2=''){
		
		product_select();
		$("#pagerForm2 input").each(function(){
			val = $(this).val();
			name = $(this).attr("name");
			if((name != selectName ) && (name != selectName2 ) ){
				url += name+"="+val+"&";
			}
		});
		
		$(".searchContent input").each(function(){	
			val = $(this).val();
			name = $(this).attr("name");
			url += name+"="+val+"&";
		});
		
		$(".searchContent select").each(function(){	
			val = $(this).val();
			name = $(this).attr("name");
			url += name+"="+val+"&";
		});
		
		
		 $.ajax({
			url: url,
			type: 'POST',
			data:  {	
                <?=  CRequest::getCsrfName(); ?>: "<?= CRequest::getCsrfValue(); ?>"
			},
			success: function(data, textStatus, jqXHR)
			{
				$("#jbsxBox_product").html(data);
				initUI("#jbsxBox_product");
			},
			error: function(jqXHR, textStatus, errorThrown) {
			//alert("error");
			}          
		});
	}
	$("#jbsxBox_product").off("change").on("change","select[name='numPerPage']",function(){
		selectVal = $(this).val();
		selectName = "numPerPage";
		url = $(".j-ajax").attr("href");
		url += "&"+selectName+"="+selectVal+"&";
		
		ajaxProduct(url,selectName);
		
	});
	
	$(".pagination").off("click").on("click","li.j-num a",function(){
		//alert(111);
		selectVal = $(this).text();
		selectName = "pageNum";
		url = $(".j-ajax").attr("href");
		url += "&"+selectName+"="+selectVal+"&";
		ajaxProduct(url,selectName);
	});
	
	
	
	
	
	
	
});

</script>
<input type="hidden"  class="ifproductinfoisload" value="1"  />


