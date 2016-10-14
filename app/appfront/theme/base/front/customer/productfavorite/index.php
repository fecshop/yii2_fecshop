<div class="main container two-columns-left">
	<div class="col-main account_center">
		<div class="std">
			<div style="margin:19px 0 0">
				<div class="page-title">
					<h2>My Favorite</h2>
				</div>
				<div style="width:100%;min-height:500px;">
					<div style="width:100%;">				
						<ul id="review_description" style="padding:0px;">
							<li style="width:100%;min-height:160px;">
								<div class="review_description_left">
									<a target="_blank" href="http://www.intosmile.com/sexy-lace-bandeau-tops-lace-briefs-sexy-lingerie-set.html">
									<p style="text-align:center;"><img src="http://img.intosmile.com/media/catalog/product/cache/110/110/710aa4d924f51b2be23e7fd5eda0d13f/g/l/glj34874onfancy.jpg" style="width:70px;height:70px;"></p>
									</a>
								</div>
								<div class="review_description_right" style="width:600px;">
									<span class="review_description_right_span"><b>
										<a target="_blank" href="http://www.intosmile.com/sexy-lace-bandeau-tops-lace-briefs-sexy-lingerie-set.html">
											Sexy Lace Bandeau Tops + Lace Briefs Sexy Lingerie Set</a></b><a target="_blank" href="http://www.intosmile.com/sexy-lace-bandeau-tops-lace-briefs-sexy-lingerie-set.html">
										</a>
									</span>
									<div class="review_description_centen">
										<span style="display:inline-block;float:left;">
											<div class="old-price">
												<span class="label">USD</span>
												<span class="label-content">$29.99</span>
											</div>
											<div class="new-price">
												<span class="label">USD</span>
												<span class="label-content">$15.99</span>
											</div>
											<div class="clear"></div>
										</span>
										<div class="favorite-Operation" style="display:inline-block;float:right; margin-top: 0px;">
											<span onclick="favorite_del(this)" url="http://www.intosmile.com/favorite/product/del?_id=5580">
											Delete</span>&nbsp;&nbsp;
											<span onclick="ShowRemark(this,5580)">Remark</span>
											 <div class="posr" style="position: relative;">
												<div id="remarkBox_5580" style="position: absolute; left: 0px; top: 10px; z-index: 111; display: none;">
												<div class="favorite-Operation-main"> 
													<input id="hfid_5580" name="hfid" value="1321290" type="hidden">
													<textarea id="content_5580" name="content" cols="" rows="" class="text">bucuo a </textarea>
													<input name="" value="" onclick="return ViledateForm(5580);" class="save" type="button">
													<input name="" value="" onclick="hideRemark(5580)" class="cancel" type="button"></div>
												</div>
											</div>
										</div>
									</div>	
									<div class="review_reply">bucuo a </div>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="col-left ">
		<?php
			$leftMenu = [
				'class' => 'fecshop\app\appfront\modules\Customer\block\LeftMenu',
				'view'	=> 'customer/leftmenu.php'
			];
		?>
		<?= Yii::$service->page->widget->render($leftMenu,$this); ?>
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