<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<?php $i = 0; ?>
<?php  if(is_array($parentThis['products']) && !empty($parentThis['products'])): ?>
	<?php foreach($parentThis['products'] as $product): ?>
		<?php  if(isset($product['sku']) && $product['sku']): ?> 
            <?php if($i%2 == 0):  ?>
                <div class="row">
            <?php endif; ?>
                <div class="col-50 product_list">
                    <a href="<?= $product['url'] ?>" external>
                        <img width="100%" src="<?= Yii::$service->image->getImgUrl('images/lazyload.gif'); ?>"  class="lazy" data-src="<?= Yii::$service->product->image->getResize($product['image'],296,false) ?>"  />
                    </a> 
                    <p class="product_name" style="">
                        <a href="<?= $product['url'] ?>" external>
                            <?= $product['name'] ?>
                        </a>
                    </p>
                    <p style="color: #333;">
                        <?php
                            $diConfig = [
                                'price' 		=> $product['price'],
                                'special_price' => $product['special_price'],
                            ];
                            echo Yii::$service->page->widget->DiRender('home/product_price',$diConfig);
                        ?>
                    </p>
                </div>
            <?php $i++; ?>
            <?php if($i%2 == 0):  ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
	<?php  endforeach;  ?>
    
	<?php if($i%2 != 0):  ?>
		</div>
	<?php endif; ?>
    
<?php  endif;  ?>
