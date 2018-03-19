<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<?php	if(is_array($items) && !empty($items)):  ?>
<div class="product_options">
	<input type="hidden" value="" class="product_custom_options"    />
<?php 	foreach($items as $attr => $v_info):  ?>
<?php 	$info = $v_info['info'];  $require = $v_info['require']; ?>
<?php 	$required = $require ? 'required' : '' ?>
	<div class="pg">
		<div class="label"><?= Yii::$service->page->translate->__(ucwords(str_replace("-"," ",str_replace("_"," ",$attr))).':'); ?></div>
		<div class="chose_<?= $attr  ?> rg  <?= $attr ?>">
			<ul  class="no_chosen_ul <?= $required; ?>" attr="<?= $attr ?>">
<?php  			if(is_array($info) && !empty($info)): ?>
<?php  				foreach($info as $one): ?>
<?php					$val 		= $one['val'];  ?>
<?php					$key 		= $one['key'];  ?>
<?php					$image 		= $one['image'];  ?>
			<?php   	if($image):  ?>
				<li id="gal1">
					<a data-image="<?= Yii::$service->product->image->getResize($image,$middle_img_width,false) ?>"  data-zoom-image="<?= Yii::$service->product->image->getUrl($image);  ?>"  attr="<?= $attr ?>"  class="imgshow active_v"  value="<?= $key ?>">
						<img  src="<?= Yii::$service->product->image->getResize($image,[50,55],false) ?>" /></a>
					<b></b>
				</li>
			<?php   	else: ?>
				<li>
					<a attr="<?= $attr ?>" class="noimgshow active_v" value="<?= $key ?>"><?= Yii::$service->page->translate->__($val); ?></a>
					<b></b>
				</li>
<?php   				endif;  ?>
<?php   			endforeach;  ?>	
<?php   		endif;  ?>
			</ul>
		</div>	
		<div class="clear"></div>
	</div>
<?php	    endforeach;  ?>
</div>
<?php	endif;  ?>

<script>
<?php $this->beginBlock('product_custom_option') ?>  
$(document).ready(function(){
	custom_option_arr = <?= $custom_option_arr ?>;
	$(".product_custom_options ul li a").click(function(){
		if(!$(this).hasClass('no_active')){
			$chosen_custom_option_arr = [];
            $chosen_attr = [];
            if ($(this).hasClass("current")) {
                $(this).removeClass("current");
                $(this).parent().removeClass("current");
                $(this).parent().parent().removeClass("chosen_ul");
                $(this).parent().parent().addClass("no_chosen_ul");
            } else {
                $(this).parent().parent().find("a").removeClass("current");
                $(this).parent().parent().find("li").removeClass("current");
                $(this).addClass("current");
                $(this).parent().addClass("current");
                $(this).parent().parent().removeClass("no_chosen_ul");
                $(this).parent().parent().addClass("chosen_ul");
            }   
			// custom option 被选择的部分的处理 - 开始
			$c_arr = [];
			$c_chosen_custom_option_arr = new Object();;
			$(".product_custom_options ul li a.current").each(function(){
				attr = $(this).attr('attr');
				val  = $(this).attr('value');
				
				for(x in custom_option_arr){
					one = custom_option_arr[x];	
					i = 1;
					$(".product_custom_options ul li a.current").each(function(){
						attr2 = $(this).attr('attr');
						val2  = $(this).attr('value');
						//alert(attr+"###"+val);
						if((attr != attr2) && (one[attr2] != val2)){
							i = 0;
						}
					});
					if(i){
						if($c_chosen_custom_option_arr[attr] == undefined){
							$c_chosen_custom_option_arr[attr] = [];
						}
						$c_chosen_custom_option_arr[attr].push(one);
					}
				}
			});
			
			// 每一个属性对应的允许的值，的出来，譬如 color 允许 red white等
			c_my_arr = new Object();
			for(attr in $c_chosen_custom_option_arr){
				//alert(attr);
				if(c_my_arr[attr] == undefined){
					c_my_arr[attr] = new Object();;
				}
				arr = $c_chosen_custom_option_arr[attr];
				for(x in arr){
					one = arr[x];
					for(y in one){
						//alert(one[y]);
						if(c_my_arr[attr][y] == undefined){
							c_my_arr[attr][y] = [];
						}
						//alert(attr+"##"+y);
						c_my_arr[attr][y].push(one[y]);
					}
				}
			}
			
			$(".product_custom_options ul.chosen_ul").each(function(){
				attr = $(this).attr('attr');
				$(this).find("li a").each(function(){
					val = $(this).attr('value');
					//alert(val);
					//alert(my_arr[attr]);
					if($.inArray(val, c_my_arr[attr][attr]) > -1){
						$(this).removeClass('no_active');
						$(this).addClass('active_v');
					}else{
						//alert(val);
						//alert(222);
						$(this).addClass('no_active');
						$(this).removeClass('active_v');
					}

				});
				
			});
			
			for(x in custom_option_arr){
				one = custom_option_arr[x];	
				i = 1;
				$(".product_custom_options ul li a.current").each(function(){
					attr = $(this).attr('attr');
					val  = $(this).attr('value');
					//alert(attr+"###"+val);
					if(one[attr] != val){
						i = 0;
					}
				});
				if(i){
					$chosen_custom_option_arr.push(one);
				}
				
			}
			//alert(1);
			my_arr = new Object();
			for(x in $chosen_custom_option_arr){
				one = $chosen_custom_option_arr[x];
				for(y in one){
					//alert(one[y]+"###"+y);
					if(my_arr[y] == undefined){
						my_arr[y] = [];
					}
					//alert(y+"__"+one[y]);
					my_arr[y].push(one[y]);
				}
				
			}
			
			$(".product_custom_options ul.no_chosen_ul").each(function(){
				attr = $(this).attr('attr');
				$(this).find("li a").each(function(){
					val = $(this).attr('value');
					//alert(val);
					//alert(my_arr[attr]);
					if($.inArray(val, my_arr[attr]) > -1){
						$(this).removeClass('no_active');
						$(this).addClass('active');
					}else{
						//alert(val);
						//alert(222);
						$(this).addClass('no_active');
						$(this).removeClass('active');
					}

				});
				
			});
			// 如果全部选择完成，需要到ajax请求，得到最后的价格
			i = 1;
			$(".product_custom_options .pg .rg ul.required").each(function(){
				val = $(this).find("li.current a.current").attr("value");
			    attr  = $(this).find("li.current a.current").attr("attr");
				if(!val){
				   i = 0;
				}
			});
			if(i){
				for(x in custom_option_arr){
					one = custom_option_arr[x];	
					j = 1;
					$(".product_custom_options .pg .rg ul.required").each(function(){
						val = $(this).find("li.current a.current").attr("value");
						attr  = $(this).find("li.current a.current").attr("attr");
						if(one[attr] != val){
							j = 0;
							//break;
						}
					});
					if(j){
						getCOUrl = "<?= Yii::$service->url->getUrl('catalog/product/getcoprice'); ?>";
						custom_option_sku = one['sku'];
						product_id = "<?=  $product_id ?>";
						qty = $(".qty").val();
						$data = {
							custom_option_sku:custom_option_sku,
							qty:qty,
							product_id:product_id
						};
						jQuery.ajax({
						async:true,
						timeout: 6000,
						dataType: 'json', 
						type:'get',
						data: $data,
						url:getCOUrl,
						success:function(data, textStatus){ 
							$(".price_info").html(data.price);
						},
						error:function (XMLHttpRequest, textStatus, errorThrown){}
					});
					}
				}
			}
		}
	});
});
<?php $this->endBlock(); ?>  
</script>  
<?php $this->registerJs($this->blocks['product_custom_option'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
