<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
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
	langs_input = "";
	$(".category_sorts table tbody tr").each(function(){
		sort_key = $(this).find(".sort_key").val();
		sort_label = $(this).find(".sort_label").val();
        sort_db_columns = $(this).find(".sort_db_columns").val();
        sort_direction = $(this).find(".sort_direction").val();
        //alert(search_engine);
		if (sort_key && sort_label && sort_db_columns && sort_direction){
			langs_input += sort_key+'##'+sort_label+'##'+sort_db_columns +'##'+sort_direction + "||";
		} else {
            fill = false
        }
	});
    if (fill == false) {
        alert('<?= Yii::$service->page->translate->__('can not empty'); ?>');
        return false;
    }
	jQuery(".langs_input").val(langs_input);
	return validateCallback(thiss, navTabAjaxDone);
}

</script>
<div class="pageContent systemConfig"> 
	<form  method="post" action="<?= $saveUrl ?>" class="pageForm required-validate" onsubmit="return thissubmit(this);">
		<?php echo CRequest::getCsrfInputHtml();  ?>	
		<div layouth="56" class="pageFormContent" style="height: 240px; overflow: auto;">
        
            <div class="edit_p">
                <label><?=  Yii::$service->page->translate->__('Cateory Sort') ?>：</label>
                <input type="hidden" name="editFormData[category_sorts]" class="langs_input"  />
                <div class="category_sorts" style="float:left;width:700px;">
                    <table style="">
                        <thead>
                            <tr>
                                <th><?=  Yii::$service->page->translate->__('Cateory Sort Key') ?></th>
                                <th><?=  Yii::$service->page->translate->__('Cateory Sort Label') ?></th>
                                <th><?=  Yii::$service->page->translate->__('Cateory Sort Db Columns') ?></th>
                                <th><?=  Yii::$service->page->translate->__('Cateory Sort Direction') ?></th>
                                <th><?=  Yii::$service->page->translate->__('Remove') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(is_array($category_sorts) && !empty($category_sorts)){  ?>
                                <?php foreach($category_sorts as $one){ ?>
                                <tr>
                                    <td>
                                        <input class="sort_key" type="text" value="<?= $one['sort_key'] ?>">
                                    </td>
                                    <td>
                                        <input class="sort_label" type="text" value="<?= $one['sort_label'] ?>">
                                    </td>
                                    <td>
                                        <input class="sort_db_columns" type="text" value="<?= $one['sort_db_columns'] ?>">
                                    </td>
                                    <td>
                                        <select class="sort_direction">
                                            <?php if (is_array($sort_directions)): ?>
                                                <?php foreach ($sort_directions as $sort_direction): ?>
                                                        
                                                    <?php if ($one['sort_direction'] == $sort_direction): ?>
                                                        <option  selected="selected" value="<?= $sort_direction?>"><?= Yii::$service->page->translate->__($sort_direction) ?></option>
                                                    <?php else: ?>
                                                        <option value="<?= $sort_direction?>"><?= Yii::$service->page->translate->__($sort_direction) ?></option>
                                                    <?php endif; ?>
                                                    
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                            
                                        </select>
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
                                    <a rel="2" style="text-align:right;margin-top:15px;" href="javascript:void(0)" class="addCategorySort button">
                                        <span><?=  Yii::$service->page->translate->__('Add Category Sort') ?></span>
                                    </a>					
                                </td>				
                            </tr>			
                        </tfoot>
                    </table>
                    <script>
                        $(document).ready(function(){
                            $(".addCategorySort").click(function(){
                                str = "<tr>";
                                str +="<td><input class=\"sort_key textInput \" type=\"text\"   /></td>";
                                str +="<td><input class=\"sort_label textInput\" type=\"text\"   /></td>";
                                str +="<td><input class=\"sort_db_columns textInput\" type=\"text\"   /></td>";
                                str +="<td><?= $sort_directions_select ?></td>";
                                str +="<td><i class='fa fa-trash-o'></i></td>";
                                str +="</tr>";
                                $(".category_sorts table tbody").append(str);
                            });
                            $(".systemConfig").off("click").on("click",".category_sorts table tbody tr td .fa-trash-o",function(){
                                $(this).parent().parent().remove();
                            });
                            
                        });
                    </script>
                </div>
            </div>
            

            <div class="edit_remark" style="width:500px;margin-right:50px;float:right;font-size:14px;">
                <p > 
                </p>
            
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
.edit_p .category_sorts input{
	width:100px;
}
.edit_remark p{
    width:500px;font-size:14px;
    line-height:30px;
    height:auto;
    color:#777;
}
.category_sorts table thead tr th{
	 background: #ddd none repeat scroll 0 0;
    border: 1px solid #ccc;
    padding: 4px 10px;
    width: 100px;
}

.category_sorts table tbody tr td{
	background: #fff;
    border-right: 1px solid #ccc;
	border-bottom: 1px solid #ccc;
    padding:3px;
    width: 100px;
}

.edit_p .category_sorts input{width:100px;}
</style>





