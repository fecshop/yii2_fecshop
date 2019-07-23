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
	$(".currencys table tbody tr").each(function(){
		currency_code = $(this).find(".currency_code").val();
		currency_symbol = $(this).find(".currency_symbol").val();
        currency_rate = $(this).find(".currency_rate").val();
        //alert(search_engine);
		if (currency_code && currency_symbol && currency_rate){
			langs_input += currency_code+'##'+currency_symbol+'##'+currency_rate + "||";
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
                <label><?=  Yii::$service->page->translate->__('Currency') ?>：</label>
                <input type="hidden" name="editFormData[currencys]" class="langs_input"  />
                <div class="currencys" style="float:left;width:700px;">
                    <table style="">
                        <thead>
                            <tr>
                                <th><?=  Yii::$service->page->translate->__('Currency Code') ?></th>
                                <th><?=  Yii::$service->page->translate->__('Currency Symbol') ?></th>
                                <th><?=  Yii::$service->page->translate->__('Currency Rate') ?></th>
                                <th><?=  Yii::$service->page->translate->__('Action') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(is_array($currencys) && !empty($currencys)){  ?>
                                <?php foreach($currencys as $one){ ?>
                                <tr>
                                    <td>
                                        <input class="currency_code" type="text" value="<?= $one['currency_code'] ?>">
                                    </td>
                                    <td>
                                        <input class="currency_symbol" type="text" value="<?= $one['currency_symbol'] ?>">
                                    </td>
                                    <td>
                                        <input class="currency_rate" type="text" value="<?= $one['currency_rate'] ?>">
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
                                    <a rel="2" style="text-align:right;margin-top:15px;" href="javascript:void(0)" class="addCurrency button">
                                        <span><?=  Yii::$service->page->translate->__('Add Currency') ?></span>
                                    </a>					
                                </td>				
                            </tr>			
                        </tfoot>
                    </table>
                    <script>
                        $(document).ready(function(){
                            $(".addCurrency").click(function(){
                                str = "<tr>";
                                str +="<td><input class=\"currency_code textInput \" type=\"text\"   /></td>";
                                str +="<td><input class=\"currency_symbol textInput\" type=\"text\"   /></td>";
                                str +="<td><input class=\"currency_rate textInput\" type=\"text\"   /></td>";
                                str +="<td><i class='fa fa-trash-o'></i></td>";
                                str +="</tr>";
                                $(".currencys table tbody").append(str);
                            });
                            $(".systemConfig").off("click").on("click",".currencys table tbody tr td .fa-trash-o",function(){
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
.edit_p .currencys input{
	width:100px;
}
.edit_remark p{
    width:500px;font-size:14px;
    line-height:30px;
    height:auto;
    color:#777;
}
.currencys table thead tr th{
	 background: #ddd none repeat scroll 0 0;
    border: 1px solid #ccc;
    padding: 4px 10px;
    width: 100px;
}

.currencys table tbody tr td{
	background: #fff;
    border-right: 1px solid #ccc;
	border-bottom: 1px solid #ccc;
    padding:3px;
    width: 100px;
}

.edit_p .currencys input{width:100px;}
</style>





