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
                    <div style="clear:both;"></div>
                    <!-- begin -->
                    <div>
                        <?php
                            $filter_price_begin = '';
                            $filter_price_end = '';
                            $priceFilter = \Yii::$app->request->get('price');
                            $priceFilter = \Yii::$service->helper->htmlEncode($priceFilter);
                            if ($priceFilter) {
                                list($filter_price_begin, $filter_price_end) = explode('-', $priceFilter);
                                
                            }
                        ?>
                        <input value="<?= $filter_price_begin ?>" class="filter_price_begin"  type="text" style="width:40px;color:#444;padding-left:4px;" /> - 
                        <input value="<?= $filter_price_end ?>" class="filter_price_end"  type="text" style="width:40px;color:#444;padding-left:4px;" />
                        <form style="display:inline-block; vertical-align: top;" method="get" class="filterPriceForm">
                            <input class="filterPriceP" type="hidden" name="price" value=""  />
                            <button  style="height:21px; line-height: 17px;" class="filter_custom_price">提交</button>
                        </form>
                    </div>
                    <!-- end -->
                </div>
			</div>
<?php            
		endif;
	endforeach;
endif;
?>
</div>


<script>
    <?php $this->beginBlock('category_index_price') ?>
    function isNumber(val) {
    var regPos = /^\d+(\.\d+)?$/; //非负浮点数
    var regNeg = /^(-(([0-9]+\.[0-9]*[1-9][0-9]*)|([0-9]*[1-9][0-9]*\.[0-9]+)|([0-9]*[1-9][0-9]*)))$/; //负浮点数
    if(regPos.test(val) || regNeg.test(val)) {
        return true;
        } else {
        return false;
        }
    }
    $(document).ready(function(){
		$(".filter_custom_price").click(function(){
            var filter_price_begin = $(".filter_price_begin").val();
            var filter_price_end = $(".filter_price_end").val();
            //if (!filter_price_begin || !filter_price_end ) {
            //    
            //    return;
            //}
            if (!isNumber(filter_price_begin) || !isNumber(filter_price_end)) {
                alert('价格过滤区间必须是数字');
                
                return;
            }
            var sPrice = filter_price_begin+'-'+filter_price_end;
            $(".filterPriceP").val(sPrice);
            $(".filterPriceForm").submit();
            
        });
	});
    <?php $this->endBlock(); ?>
    <?php $this->registerJs($this->blocks['category_index_price'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script> 
