<?php  //var_dump($items);exit; ?>
<?php	if(is_array($items) && !empty($items)){  ?>
<div class="product_options">
				
<?php 	foreach($items as $attr => $v_info){  ?>
<?php 	$info = $v_info['info'];  $require = $v_info['require']; ?>
<?php 	$required = $require ? 'required' : '' ?>
	<div class="pg">
		<input type="hidden" value="" class="product_custom_options"    />
		<div class="label"><?= ucwords(str_replace("-"," ",str_replace("_"," ",$attr))) ?>:</div>
		<div class="chose_<?= $attr  ?> rg  <?= $attr ?>">
			<ul class="no_chosen_ul <?= $required; ?>" attr="<?= $attr ?>">
<?php  			if(is_array($info) && !empty($info)){ ?>
<?php  				foreach($info as $one){ ?>
<?php					$val 		= $one['val'];  ?>
<?php					$key 		= $one['key'];  ?>
<?php					$image 		= $one['image'];  ?>
			<?php   	if($image){  ?>
				<li>
					<a attr="<?= $attr ?>"  class="imgshow active" value="<?= $key ?>"><img src="<?= Yii::$service->product->image->getResize($image,[50,55],false) ?>" /></a>
					<b></b>
				</li>
			<?php   	}else{ ?>
				<li>
					<a attr="<?= $attr ?>" class="noimgshow active" value="<?= $key ?>"><?= $val ?></a>
					<b></b>
				</li>
<?php   				}  ?>
<?php   			}  ?>	
<?php   		}  ?>
			</ul>
		</div>	
		<div class="clear"></div>
	</div>
<?php		}  ?>
</div>
<?php	}  ?>

<script>
	
	
</script>
<script>
<?php $this->beginBlock('product_custom_option') ?>  
$(document).ready(function(){
	custom_option_arr = <?= $custom_option_arr ?>;
	//for(x in custom_option_arr){
	//	alert(custom_option_arr[x]['sku']);
	//}
	
	$(".product_custom_options ul li a").click(function(){
		
		if(!$(this).hasClass('no_active')){
			$chosen_custom_option_arr = [];
			$(this).parent().parent().find("a").removeClass("current");
			$(this).parent().parent().find("li").removeClass("current");
			$(this).addClass("current");
			$(this).parent().addClass("current");
			$(this).parent().parent().removeClass("no_chosen_ul");
			$(this).parent().parent().addClass("chosen_ul");
			$chosen_attr = [];
			
			// 选择后的部分
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
						$(this).addClass('active');
					}else{
						//alert(val);
						//alert(222);
						$(this).addClass('no_active');
						$(this).removeClass('active');
					}

				});
				
			});
			
			
				
			///////
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
		}
	});
	
	
	
	
	
	
	
});
<?php $this->endBlock(); ?>  
</script>  
<?php $this->registerJs($this->blocks['product_custom_option'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
