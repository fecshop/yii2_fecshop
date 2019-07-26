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
    
    var display_type_select = $(".display_type_select").val();
    if (display_type_select == 'select' || display_type_select == 'editSelect') {
        $(".pageContent .items table tbody tr").each(function(){
            key_name = $(this).find(".key_name").val();
            //alert(search_engine);
            if (key_name){
                items_input += key_name + "||";
            } else {
                fill = false
            }
        });
        if (fill == false) {
            alert('<?= Yii::$service->page->translate->__('can not empty'); ?>');
            return false;
        }
        $(".items_input").val(items_input);
    } else {
        $(".items_input").val();
    }
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
                
                <fieldset id="fieldset_table_qbe">
					<legend style="color:#009688"><?= Yii::$service->page->translate->__('Attr Display') ?></legend>
					<div>
						<p class="edit_p" style="width:100%">
                            <label><?=  Yii::$service->page->translate->__('Display Type');?>：</label>
                            
                            <select  class=" required display_type_select" name="editFormData[display_type]" >
                                <?php if (is_array($display_types)): ?>
                                    <?php foreach ($display_types as $d_type): ?>
                                            
                                        <?php if ($display_type == $d_type): ?>
                                            <option  selected="selected" value="<?= $d_type?>"><?= Yii::$service->page->translate->__($d_type) ?></option>
                                        <?php else: ?>
                                            <option value="<?= $d_type ?>"><?= Yii::$service->page->translate->__($d_type) ?></option>
                                        <?php endif; ?>
                                        
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <span class="remark-text"></span>
                            
                            <div class="edit_p display_itemcs" style="display:<?= ($display_type=='select' || $display_type=='editSelect')    ? 'block' : 'none'  ?>">
                                <label><?=  Yii::$service->page->translate->__('Display Items') ?>：</label>
                                <input type="hidden" name="editFormData[display_data]" class="items_input"  />
                                <div class="items" style="float:left;width:700px;">
                                    <table style="">
                                        <thead>
                                            <tr>
                                                <th><?=  Yii::$service->page->translate->__('Items') ?></th>
                                                <th><?=  Yii::$service->page->translate->__('Action') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(is_array($display_data) && !empty($display_data)){  ?>
                                                <?php foreach($display_data as $one){ ?>
                                                <tr>
                                                    <td>
                                                        <input class="key_name" type="text" value="<?= $one['key'] ?>">
                                                    </td>
                                                    <td>
                                                        <i class="fa fa-trash-o"></i>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot style="text-align:right;">
                                            <tr>
                                                <td colspan="100" style="text-align:right;">						
                                                    <a rel="2" style="text-align:right;margin-top:15px;" href="javascript:void(0)" class="addItems button">
                                                        <span><?=  Yii::$service->page->translate->__('Add Items') ?></span>
                                                    </a>					
                                                </td>				
                                            </tr>			
                                        </tfoot>
                                    </table>
                                    <script>
                                        $(document).ready(function(){
                                            $(".addItems").click(function(){
                                                str = "<tr>";
                                                str +="<td><input class=\"key_name textInput\" type=\"text\"   /></td>";
                                                str +="<td><i class='fa fa-trash-o'></i></td>";
                                                str +="</tr>";
                                                $(".items table tbody").append(str);
                                            });
                                            $("body").off("click").on("click",".pageContent .items table tbody tr td .fa-trash-o",function(){
                                                $(this).parent().parent().remove();
                                            });
                                            
                                            $("body").off("change").on("change",".pageContent .select_attr_type",function(){
                                                var val = $(this).val();
                                                if (val == 'spu_attr') {
                                                    $(".pageContent .display_type_select").val('select');
                                                    $(".display_itemcs").show;
                                                    $(".display_itemcs").show();
                                                }
                                            });
                                            
                                            $("body").on("change",".pageContent .display_type_select",function(){
                                                var val = $(this).val();
                                                if (val != 'select' && val != 'editSelect' ) {
                                                    $(".display_itemcs").hide();
                                                    
                                                } else {
                                                    $(".display_itemcs").show();
                                                }
                                            });
                                        });
                                    </script>
                                </div>
                            </div>
                        </p>
					</div>
				</fieldset>
                
				<?= $lang_attr ?>
				<?= $textareas ?>
                
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