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


function thissubmit(thiss){	
	return validateCallback(thiss, dialogAjaxDoneReflush);
}
$(".add-category").ready(function(){
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


























	

