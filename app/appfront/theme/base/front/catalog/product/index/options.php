<?php list($current_attr1,$current_attr2,$all_attr1,$all_attr2,$attr1_2_attr2,$attr2_2_attr1,$attr1,$attr2) = $parentThis['options']; ?>

<?php # 这里是 一种类似京东的处理方式。  ?>
<?php if(is_array($all_attr1) && !empty($all_attr1)){  ?>
	<div class="pg">
		<div class="label"><?= Yii::$service->page->translate->__(ucfirst($attr1).':'); ?></div>
		<div class="chose_color rg">
			<ul>
<?php		foreach($all_attr1 as $attr1Val => $info){ ?>
<?php			$main_img = isset($info['image']['main']['image']) ? $info['image']['main']['image'] : ''; ?>
<?php			$url = ''; ?>
<?php			$active = 'class="active"'; ?>
<?php			if(isset($attr1_2_attr2[$attr1Val])){ ?>
<?php				$url = Yii::$service->url->getUrl($attr1_2_attr2[$attr1Val]['url_key']); ?>
<?php			}else{ ?>
<?php				$url = Yii::$service->url->getUrl($info['url_key']); ?>
<?php			} ?>
<?php			if($attr1Val == $current_attr1){ ?>
<?php				$active = 'class="current"'; ?>
<?php			} ?>
			<li <?= $active ?>>
				<a title="<?= $attr1Val ?>" <?= $active ?> href="javascript:void(0)" rel="<?= $url ?>"><img src="<?= Yii::$service->product->image->getResize($main_img,[50,55],false) ?>"/></a>
				<b></b>
			</li>
<?php		} ?>
			</ul>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
	</div>
<?php	
	}
?>

<?php	if(is_array($all_attr2) && !empty($all_attr2)){ ?>
	<div class="pg">
		<div class="label size-label"><?= Yii::$service->page->translate->__(ucfirst($attr2).':'); ?></div>
		<div class="chose_size rg">
			<ul>
<?php		foreach($all_attr2 as $attr2Val => $info){ ?>
<?php			$url = ''; ?>
<?php			$active = 'class="noactive"'; ?>
<?php			if(isset($attr2_2_attr1[$attr2Val])){ ?>
<?php				$url = Yii::$service->url->getUrl($attr2_2_attr1[$attr2Val]['url_key']); ?>
<?php				$active = 'class="active"'; ?>
<?php			} ?>
<?php			if($attr2Val == $current_attr2){ ?>
<?php				$active = 'class="current"'; ?>
<?php			} ?>
				<li <?= $active ?>>
					<a <?=$active ?> href="javascript:void(0)" rel="<?= $url ?>"><span><?= Yii::$service->page->translate->__($attr2Val); ?></span></a>
					<b></b>
				</li>
<?php		}	?>
			</ul>
			<div class="clear"></div>
		</div>
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

