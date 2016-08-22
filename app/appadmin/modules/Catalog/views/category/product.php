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
<div class="pageContent">
	
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
$(document).ready(function(){

	$(".productSearch").click(function(){
		url 	= $(".j-ajax").attr("href")+"&";
		ajaxProduct(url,'','');
	});
	
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
	
	function ajaxProduct(url,selectName,selectName2=''){
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



