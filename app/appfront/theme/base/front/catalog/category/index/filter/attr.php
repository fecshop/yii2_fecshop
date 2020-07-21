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
//var_dump($parentThis);
//echo 1;
//echo Yii::$service->url->category->urlFormat('df-_ ??&sad');
if(isset($parentThis['filters']) && !empty($parentThis['filters']) && is_array($parentThis['filters'])):
	foreach($parentThis['filters']  as $attr => $filter):
        $attrLabel = $filter['label'];
        $attrName = $filter['name'];
		if(is_array($filter['items']) && !empty($filter['items'])):
			$i = 0;
			foreach($filter['items'] as $item):
            //var_dump($item);exit;
				$itemName    = $item['_id'];
                $itemLabel    = $item['label'];
                $itemCount    = $item['count'];
                $itemUrl    = $item['url'];
                $selected    = $item['selected'];
				if($itemName):
					$i++;
					if($i == 1):
?>                      
						<div class="filter_attr">
                            <div class="filter_attr_title">
                                <?= Yii::$service->page->translate->__($attrLabel); ?>
                            </div>
                            <div class="filter_attr_info">			
                    <?php endif; ?>				
                                <a <?= $selected ? 'class="checked"' : '';?> href="<?= $itemUrl;?>">
                                    <?= $itemLabel; ?>(<?= $itemCount; ?>)
                                </a><br/>
                <?php endif; ?>
			<?php endforeach; ?>
		<?php if($i >= 1): ?>            
                            </div>
                        </div>
        <?php endif; ?>
	<?php endif; ?>
	<?php endforeach; ?>
<?php endif; ?>
</div>