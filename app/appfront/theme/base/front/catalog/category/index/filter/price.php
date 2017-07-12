<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<div class="category_left_filter">
<?php
//var_dump($parentThis['filter_price']);
//echo 1;
//echo Yii::$service->url->category->urlFormat('df-_ ??&sad');
if(isset($parentThis['filter_price']) && !empty($parentThis['filter_price']) && is_array($parentThis['filter_price'])):	
	foreach($parentThis['filter_price']  as $attr => $filter):
		$attrUrlStr = Yii::$service->url->category->attrValConvertUrlStr($attr);
		if(is_array($filter) && !empty($filter)):
?>
            <div class="filter_attr">
                <div class="filter_attr_title">
                    <?= Yii::$service->page->translate->__($attr);?>
                </div>
                <div class="filter_attr_info">
<?php
			foreach($filter as $item):
				$val = $item['val'];
				$url = $item['url'];
				$selected = $item['selected'] ? 'class="checked"' : '';
				if($val && $url):
?>                    
					<a <?= $selected ?>  href="<?= $url ?>"><?= $val ?></a><br/>
<?php
                endif;
			endforeach;
?>
                </div>
			</div>
<?php            
		endif;
	endforeach;
endif;
?>
</div>