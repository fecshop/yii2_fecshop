<style>
						.one_option{background:#e7efef;position:relative;margin:10px;padding:10px;border:1px solid #cddddd}
						.close_panl{position:absolute;right:10px;top:10px;cursor: pointer;}
						.close_panl img:hover{border:1px solid #ccc;}
						.close_panl img{border:1px solid #fff;}
						.one_option tbody tr td img{cursor: pointer;border:1px solid #fff;}
						.one_option tbody tr td img:hover{border:1px solid #ccc;}
					</style>
					<?php  $langs = \Yii::$service->fecshoplang->getAllLangCode(); ?>
					<?php  //var_dump($langs);  ?>
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
								add += '			<img src="<?= \Yii::$service->image->getImgUrl('/images/bkg_btn-close2.gif')  ?>" />';
								add += '		</div>';
								add += '		<table class="option_header">';
								add += '			<tr >';
								add += '				<td style="padding:3px;">Title</td>';
								add += '				<td style="padding:3px;">Is_Required</td>';
								add += '				<td style="padding:3px;">Sort_Order</td>';
								add += '			</tr>';
								add += '			<tr >';
								add += '				<td><input type="text" class="title_header textInput required valid"/></td>';
								add += '				<td><select class="is_require" style="margin:0 24px;"><option value="1">Yes</option><option value="2">No</option>  </select>';
								add += '				<td><input type="text" class="sort_order_header" /></td>';
								add += '			</tr>';
											
								add += '		</table>';
									
								add += '		<table class="list option_content" style="margin:10px;">';
								add += '			<thead>';
								add += '				<tr style="background:#ccc;">';
								<?php  if(is_array($langs) && !empty($langs)){  ?>
								<?php  		foreach($langs as $langCode){  ?>
								<?php  			echo "add += '	<td>$langCode Title</td>	';"  ?>
								<?php  			  ?>
								<?php  		} ?>
								<?php  	} ?>
								add += '					<td>Price</td>';
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
								<?php  if(is_array($langs) && !empty($langs)){  ?>
								<?php  		foreach($langs as $langCode){  ?>
								<?php  			echo "add += '			<td><input rel=\"".\Yii::$service->fecshoplang->GetLangAttrName('title',$langCode)."\" style=\"width:50px;\" type=\"text\" class=\"title_content textInput  valid\" /></td>';"  ?>
								<?php  			  ?>
								<?php  		} ?>
								<?php  	} ?>
								add += '			<td><input type="text" class="price_content" style="width:40px" /></td>';
								add += '			<td><input type="text" class="sort_order_content" style="width:40px" /></td>';
								add += '			<td><img src="<?= \Yii::$service->image->getImgUrl('/images/bkg_btn-close2.gif')  ?>" /></td>';	
								add += '		</tr>';
								i = jQuery(this).attr("rel");
								ee = ".addtbody"+i;
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
					<div class="custom_option">
						<a href="javascript:void(0)"  class=" add_custom_option button"><span>增加自定义属性</span></a>
						<div style="clear:both;"></div>
						<div class="add_custom_option_div">
							<input type="hidden"  class="custom_option_value" name="custom_option" value='' />
							<?php
								$str  = '';
								//var_dump($custom_option);
								if(is_array($custom_option) && !empty($custom_option)){
									$i = 0;
									foreach($custom_option as $k=>$option){
										$title = $option['title'];
										$is_require = $option['is_require'];   //$is_require==1 ? 'selected="selected"' : ''
										$sort_order = $option['sort_order'];
										$data = $option['data'];
										$i ++;
								 $str .='<div rel="'.$i.'" class="one_option">
											<div class="close_panl">
												<img src="'.\Yii::$service->image->getImgUrl('/images/bkg_btn-close2.gif').'">	
											</div>		
											<table class="option_header">	
												<tbody>
													<tr>
														<td style="padding:3px;">Title</td>				
														<td style="padding:3px;">Is_Required</td>				
														<td style="padding:3px;">Sort_Order</td>			
													</tr>			
													<tr>				
														<td><input value="'.$title.'" type="text" class="title_header textInput required valid"></td>
														<td><select style="margin:0 24px;" class="is_require"><option '.($is_require==1 ? 'selected="selected"' : '').' value="1">Yes</option><option '.($is_require==2 ? 'selected="selected"' : '').' value="2">No</option>  </select></td>
														<td><input value="'.$sort_order.'" type="text" class="sort_order_header"></td>			
													</tr>		
												</tbody>
											</table>
											<table style="margin:10px;" class="list option_content">			
												<thead>				
													<tr style="background:#ccc;">';	
												$langs = \Yii::$service->fecshoplang->getAllLangCode();
												if(is_array($langs) && !empty($langs)){
													foreach($langs as $langCode){
														$str .='<td>'.strtolower($langCode).' Title</td>';		
													}
												}
												$str .=	'<td>Price</td>					
														<td>Sort Order</td>					
														<td></td>				
													</tr>			
												</thead>			
												<tbody class="addtbody'.$i.'">';
									
										if(is_array($data) && !empty($data)){
											foreach($data as $k2=>$c_option){
												$c_title = $c_option['title'];
												$c_price = $c_option['price'];
												$c_sort_order = $c_option['sort_order'];
										$str .=	'<tr>';
												if(is_array($langs) && !empty($langs)){
													foreach($langs as $langCode){
														$tkey = \Yii::$service->fecshoplang->GetLangAttrName('title',$langCode);
														$str .=	'<td><input rel="'.$tkey.'" style="width:60px;" value="'.(isset($c_title[$tkey]) ? $c_title[$tkey] : '').'" type="text" class="title_content textInput  valid"></td>		';													
													}
												}
																
										$str .=	'	<td><input   value="'.$c_price.'"  type="text" class="price_content" style="width:40px;"></td>			
													<td><input   value="'.$c_sort_order.'"  type="text" class="sort_order_content" style="width:40px;"></td>			
													<td><img src="'.\Yii::$service->image->getImgUrl('/images/bkg_btn-close2.gif').'"></td>		
												</tr>';
											}
										}
									$str .= '</tbody>			
												<tfoot style="text-align:right;">				
													<tr>					
														<td style="text-align:right;" colspan="100">
															<a class="addchildoption11 button" href="javascript:void(0)" style="text-align:right;" rel="'.$i.'"><span>增加子属性</span></a>
														</td>	
													</tr>
												</tfoot>	
											</table>	
										</div>';
									}
								}
								echo  $str;
							?>
						</div>	
					</div>