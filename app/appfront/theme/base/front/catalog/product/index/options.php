<?php
	list($current_color,$current_size,$all_color,$all_size,$color_2_size,$size_2_color) = $parentThis['options'];
?>

<?php  ?>
<?php	if(is_array($all_color) && !empty($all_color)){  ?>
		<div class="chose_color">
<?php		foreach($all_color as $color => $info){ ?>
<?php			$main_img = isset($info['image']['main']['image']) ? $info['image']['main']['image'] : ''; ?>
<?php			$url = ''; ?>
<?php			$active = 'class="active"'; ?>
<?php			if(isset($color_2_size[$color])){ ?>
<?php				$url = Yii::$service->url->getUrl($color_2_size[$color]['url_key']); ?>
<?php			}else{ ?>
<?php				$url = Yii::$service->url->getUrl($info['url_key']); ?>
<?php			} ?>
<?php			if($color == $current_color){ ?>
<?php				$active = 'class="current"'; ?>
<?php			} ?>

			<a <?= $active ?> href="javascript:void(0)" rel="<?= $url ?>"><img src="<?= Yii::$service->product->image->getResize($main_img,[50,55],false) ?>"/></a>
<?php		} ?>
			<div class="clear"></div>
		</div>
<?php	
	}
?>
<?php	if(is_array($all_size) && !empty($all_size)){ ?>
		<div class="chose_size">
<?php		foreach($all_size as $size => $info){ ?>
<?php			$url = ''; ?>
<?php			$active = 'class="noactive"'; ?>
<?php			if(isset($size_2_color[$size])){ ?>
<?php				$url = Yii::$service->url->getUrl($size_2_color[$size]['url_key']); ?>
<?php				$active = 'class="active"'; ?>
<?php			} ?>
<?php			if($size == $current_size){ ?>
<?php				$active = 'class="current"'; ?>
<?php			} ?>
				<a <?=$active ?> href="javascript:void(0)" rel="<?= $url ?>"><span><?= $size ?></span></a>
<?php		}	?>
			<div class="clear"></div>
		</div>
<?php	}	?>
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

