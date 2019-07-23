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
	$(".langs table tbody tr").each(function(){
		lang_code = $(this).find(".lang_select").val();
		lang_name = $(this).find(".lang_name").val();
        //alert(search_engine);
		if (lang_name && lang_code){
			langs_input += lang_name+'##'+lang_code + "||";
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
                <label><?=  Yii::$service->page->translate->__('Language') ?>：</label>
                <input type="hidden" name="editFormData[langs]" class="langs_input"  />
                <div class="langs" style="float:left;width:700px;">
                    <table style="">
                        <thead>
                            <tr>
                                <th><?=  Yii::$service->page->translate->__('Lang Code') ?></th>
                                <th><?=  Yii::$service->page->translate->__('Lang Name') ?></th>
                                <th><?=  Yii::$service->page->translate->__('Action') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(is_array($langs) && !empty($langs)){  ?>
                                <?php foreach($langs as $one){ ?>
                                <tr>
                                    <td>
                                        <select class="lang_select">
                                            <?php if (is_array($configLangs)): ?>
                                                <?php foreach ($configLangs as $configLangCode => $configLangName): ?>
                                                        
                                                    <?php if ($one['code'] == $configLangCode): ?>
                                                        <option  selected="selected" value="<?= $configLangCode?>"><?= Yii::$service->page->translate->__($configLangName) ?></option>
                                                    <?php else: ?>
                                                        <option value="<?= $configLangCode?>"><?= Yii::$service->page->translate->__($configLangName) ?></option>
                                                    <?php endif; ?>
                                                    
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                            
                                        </select>
                                    </td>
                                    <td>
                                        <input class="lang_name" type="text" value="<?= $one['languageName'] ?>">
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
                                    <a rel="2" style="text-align:right;margin-top:15px;" href="javascript:void(0)" class="addLanguage button">
                                        <span><?=  Yii::$service->page->translate->__('Add Language') ?></span>
                                    </a>					
                                </td>				
                            </tr>			
                        </tfoot>
                    </table>
                    <script>
                        $(document).ready(function(){
                            $(".addLanguage").click(function(){
                                str = "<tr>";
                                str +="<td><?= $configLangsSelect ?></td>";
                                str +="<td><input class=\"lang_name textInput\" type=\"text\"   /></td>";
                                str +="<td><i class='fa fa-trash-o'></i></td>";
                                str +="</tr>";
                                $(".langs table tbody").append(str);
                            });
                            $(".systemConfig").off("click").on("click",".langs table tbody tr td .fa-trash-o",function(){
                                $(this).parent().parent().remove();
                            });
                            
                        });
                    </script>
                </div>
            </div>
            

            <div class="edit_remark" style="width:500px;margin-right:50px;float:right;font-size:14px;">
                <p > 
                    1.此处为语言编辑部分，可以添加或者编辑<b>语言</b>，并为每个语言指定相应的<b>搜索引擎</b>，
                    您可以在<b>搜索引擎配置</b>部分，开启相应搜索引擎。
                </p>  
                <p >            
                    2.保存后，在<b>Store设置</b>中，为Store指定相应的语言，因为<b>语言和搜索引擎是绑定的</b>，因此
                    在指定语言的同时，也指定了相应的搜索引擎
                </p >  
                <p > 
                    3.更改设置后，您需要跑一下<b>同步数据</b>的脚本   ./vendor/fancyecommerce/fecshop/shell/search/fullSearchSync.sh
                 </p>  
                <p >    
                    4.如果是xunsearch，里面有一些其他的历史数据，您可以通过脚本 ./vendor/fancyecommerce/fecshop/shell/search/deleteXunSearchAllData.sh
                    来清空xunsearch里面的数据。
                </p>  
                <p >    
                    5.如果您没有安装mongodb和xunsearch，那么您只能使用<b>mysql搜索</b>，mysql的搜索使用的是like的模糊匹配。
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
.edit_p .langs input{
	width:100px;
}
.edit_remark p{
    width:500px;font-size:14px;
    line-height:30px;
    height:auto;
    color:#777;
}
.langs table thead tr th{
	 background: #ddd none repeat scroll 0 0;
    border: 1px solid #ccc;
    padding: 4px 10px;
    width: 100px;
}

.langs table tbody tr td{
	background: #fff;
    border-right: 1px solid #ccc;
	border-bottom: 1px solid #ccc;
    padding:3px;
    width: 100px;
}

.edit_p .langs input{width:100px;}
</style>





