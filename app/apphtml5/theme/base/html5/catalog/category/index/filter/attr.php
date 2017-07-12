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
if(isset($parentThis['filters']) && !empty($parentThis['filters']) && is_array($parentThis['filters'])):
	foreach($parentThis['filters']  as $attr => $filter):
		$attrUrlStr = Yii::$service->url->category->attrValConvertUrlStr($attr);
		if(is_array($filter) && !empty($filter)):
			$i = 0;
			foreach($filter as $item):
				$val = $item['_id'];
				$count = $item['count'];
				if($val):
					$i++;
					if($i == 1):
?>
                    <div class="filter_attr">
                        <div class="filter_attr_title">
                            <?= Yii::$service->page->translate->__($attr); ?>
                        </div>
                        <div class="filter_attr_info">
<?php
					endif;
					$urlInfo = Yii::$service->url->category->getFilterChooseAttrUrl($attrUrlStr,$val,'p');
					$url        = $urlInfo['url'];
					$selected = $urlInfo['selected'] ? 'class="checked"' : '';
?>
                            <a external <?= $selected ?> href="<?= $url ?>"><?= Yii::$service->page->translate->__($val); ?>(<?= $count ?>)</a><br/>

<?php		    endif;      ?>
<?php       endforeach;     ?>
<?php		if($i >= 1):    ?>
                        </div>
                    </div>
<?php         
			endif;
		endif;
	endforeach;
endif;
?>
</div>