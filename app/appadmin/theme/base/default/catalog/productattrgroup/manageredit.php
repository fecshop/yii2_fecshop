<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
use yii\helpers\Html;
use fec\helpers\CRequest;
use fecadmin\models\AdminRole;
/** 
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
?>
<style>
.checker{float:left;}
.dialog .pageContent {background:none;}
.dialog .pageContent .pageFormContent{background:none;}
</style>

<script>


function thissubmit(thiss){
	var fill = true;
	items_input = "";
    
    var selected_product_attr = '';
    $(".selected_product_attr:checked").each(function(){
        attrId = $(this).val();
        
        sortOrderI = ".selected_product_sort_order_" + attrId;
        sort_order = $(sortOrderI).val();
        selected_product_attr += attrId + '##' + sort_order + "||";
    });
    //alert(selected_product_attr);
    $(".attr_ids_c").val(selected_product_attr);
    
	return validateCallback(thiss, dialogAjaxDoneCloseAndReflush);
}

</script>

<div class="pageContent"> 
	<form  method="post" action="<?= $saveUrl ?>" class="pageForm required-validate" onsubmit="return thissubmit(this)">
		<?php echo CRequest::getCsrfInputHtml();  ?>	
		<div layouth="56" class="pageFormContent" style="height: 240px; overflow: auto;">
			    <input type="hidden"  value="<?=  $product_id; ?>" size="30" name="product_id" class="textInput ">
				<fieldset id="fieldset_table_qbe">
					<legend style="color:#009688"><?= Yii::$service->page->translate->__('Edit Info') ?></legend>
					<div>
						<?= $editBar; ?>
					</div>
				</fieldset>
                
				<?= $lang_attr ?>
				<?= $textareas ?>
                
                <input type="hidden" name="editFormData[attr_ids]"  class="attr_ids_c"  />
                <div>
                    <table class="table" width="100%" layoutH="138">
                        <thead>
                            <tr>
                                <th width="22"><input type="checkbox" group="ids" class="checkboxCtrl"></th>
                                <th width="110" align="left" orderField="sort_order" class="">Sort Order</th>
                                
                                <th width="110" align="left" orderField="name" class="">属性名称</th>
                                <th width="100" align="left" orderField="attr_type" class="">属性类型</th>
                                
                                <th width="50" align="center" orderField="status" class="">状态</th>
                                <th width="110" align="center" orderField="db_type" class="">数据类型</th>
                                <th width="50" align="center" orderField="show_as_img" class="">图片显示</th>
                                <th width="110" align="center" orderField="display_type" class="">类型</th>
                                <th width="50" align="center" orderField="is_require" class="">必填</th>
                                <th width="110" align="center" orderField="default" class="">默认值</th>
                                </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($attrs  as $attr): ?>
                            <?php 
                                $select_checkbox = '';
                                $s_sort_order = '';
                                if (is_array($select_attr_ids)) {
                                    foreach ($select_attr_ids as $select_attr_id) {
                                        if ($select_attr_id['attr_id'] == $attr['id']) {
                                            $select_checkbox = 'checked="checked"';
                                            $s_sort_order = $select_attr_id['sort_order'];
                                        }
                                    }
                                }
                                
                            ?>
                            <tr target="sid_user" rel="<?= $attr['id'] ?>">
                                <td>
                                    <input class="selected_product_attr" <?= $select_checkbox ?> name="ids" value="<?= $attr['id'] ?>" type="checkbox">
                                </td>
                                <td><span title=""><input  value="<?= $s_sort_order ?>" class="selected_product_sort_order_<?= $attr['id'] ?>" type="text"  style="width: 50px;height: 10px;margin-top: 2px;"  /></span></td>
                                
                                <td><span title=""><?= $attr['name'] ?></span></td>
                                <td><span title=""><?= Yii::$service->page->translate->__($attr['attr_type']); ?></span></td>
                                
                                <td><span title=""><?= $attr['status'] == 1 ? Yii::$service->page->translate->__('Enable') :  Yii::$service->page->translate->__('Disable')  ?></span></td>
                                <td><span title=""><?= $attr['db_type'] ?></span></td>
                                <td><span title=""><?= $attr['show_as_img'] == 1 ? Yii::$service->page->translate->__('Yes') :  Yii::$service->page->translate->__('No')  ?></span></td>
                                <td><span title=""><?= $attr['display_type'] ?></span></td>
                                <td><span title=""><?= $attr['is_require'] == 1 ? Yii::$service->page->translate->__('Yes') :  Yii::$service->page->translate->__('No')  ?></span></td>
                                <td><span title=""><?= $attr['default'] ?></span></td>
                                
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
		</div>
		<div class="formBar">
			<ul>
				<!--<li><a class="buttonActive" href="javascript:;"><span>保存</span></a></li>-->
				<li>
                    <div class="buttonActive"><div class="buttonContent"><button onclick="func('accept')"  value="accept" name="accept" type="submit"><?= Yii::$service->page->translate->__('Save') ?></button></div></div>
                </li>
				<li>
					<div class="button"><div class="buttonContent"><button type="button" class="close"><?= Yii::$service->page->translate->__('Cancel') ?></button></div></div>
				</li>
			</ul>
		</div>
	</form>
</div>	

<style>
.edit_p .items input{
	width:100px;
}
.edit_remark p{
    width:500px;font-size:14px;
    line-height:30px;
    height:auto;
    color:#777;
}
.items table thead tr th{
	 background: #ddd none repeat scroll 0 0;
    border: 1px solid #ccc;
    padding: 4px 10px;
    width: 100px;
}

.items table tbody tr td{
	background: #fff;
    border-right: 1px solid #ccc;
	border-bottom: 1px solid #ccc;
    padding:3px;
    width: 100px;
}

.edit_p .items input{width:100px;}
</style>