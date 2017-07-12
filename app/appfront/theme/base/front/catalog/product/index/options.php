<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<?php $options_attr_arr = $parentThis['options']; ?>

<?php # 这里是 一种类似京东的处理方式。  ?>
<?php if(is_array($options_attr_arr) && !empty($options_attr_arr)):  ?>
	<?php foreach($options_attr_arr as $one):   ?>
   	<div class="pg">
		<div class="label size-label"><?= Yii::$service->page->translate->__(ucfirst($one['label']).':'); ?></div>
		<div class="chose_size rg">
			<ul>
<?php       if(is_array($one['value']) && !empty($one['value'])):  ?>
<?php		    foreach($one['value'] as $info): ?>
<?php		        $attr_val = $info['attr_val']; ?>
<?php		        $active   = $info['active']; ?>
<?php		        $url   = $info['url']; ?>
<?php			//$main_img = isset($info['image']['main']['image']) ? $info['image']['main']['image'] : ''; ?>
<?php			//$url = ''; ?>
<?php			//$active = 'class="active"'; ?>
<?php			//if(isset($attr1_2_attr2[$attr1Val])){ ?>
<?php			//	$url = Yii::$service->url->getUrl($attr1_2_attr2[$attr1Val]['url_key']); ?>
<?php			//}else{ ?>
<?php			//	$url = Yii::$service->url->getUrl($info['url_key']); ?>
<?php			//} ?>
<?php			//if($attr1Val == $current_attr1){ ?>
<?php			//	$active = 'class="current"'; ?>
<?php			    if(isset($info['show_as_img']) && $info['show_as_img']): ?>
                        <li  class="<?=$active ?> show_as_img">
                            <a class="<?=$active ?>" href="javascript:void(0)" rel="<?= $url ?>#product_page_info"><span><img src="<?= Yii::$service->product->image->getResize($info['show_as_img'],[50,55],false); ?>" /></span></a>
                            <b></b>
                        </li>
<?php			    else: ?>
                        <li class="<?=$active ?>">
                            <a class="<?=$active ?>" href="javascript:void(0)" rel="<?= $url ?>#product_page_info"><span><?= Yii::$service->page->translate->__($attr_val); ?></span></a>
                            <b></b>
                        </li>
<?php			    endif; ?>
<?php		    endforeach; ?>
<?php		endif; ?>
			</ul>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
	</div>
<?php	
	endforeach;
endif;
?>

<script>
<?php $this->beginBlock('product_options') ?>  
$(document).ready(function(){
	$(".product_options a").click(function(){
		$url = $(this).attr("rel");
		if($url){
			window.location.href=$url;
		}
	});
});
<?php $this->endBlock(); ?>  
</script>  
<?php $this->registerJs($this->blocks['product_options'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>

