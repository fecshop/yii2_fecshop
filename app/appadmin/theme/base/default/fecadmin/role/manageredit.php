<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\core\helper\Producthelper;
use backend\models\core\helper\Base;
use backend\models\core\Url;
use fec\helpers\CRequest;
$this->title = 'Dashboard';
?>


<script>
    function func(type){
        cate_str = "";
        jQuery(".menu_tree div.ckbox.checked").each(function(){
            cate_id = jQuery(this).find("input").val();
            cate_str += cate_id+",";
        });

        jQuery("#resultBox").val(cate_str);



    }
    function thissubmit(thiss){

        return validateCallback(thiss, dialogAjaxDoneCloseAndReflush);

    }

</script>



<div class="pageContent">
    <form  method="post" action="<?= $saveUrl ?>" class="pageForm required-validate" onsubmit="return thissubmit(this, dialogAjaxDoneCloseAndReflush);">

        <?php echo CRequest::getCsrfInputHtml();  ?>

        <input id="resultBox"  name="menu[select_menus]" type="hidden" value="<?= $menu_ids_str ?>" />
        <div class="tabs" currentIndex="0" eventType="click"  layoutH="56">
            <div class="tabsHeader">
                <div class="tabsHeaderContent">
                    <ul>
                        <li><a href="javascript:;"><span>基本信息</span></a></li>
                        <li><a href="javascript:;"><span>Role</span></a></li>
                    </ul>
                </div>
            </div>
            <div class="tabsContent" style="height:100%;min-height:450px">
                <div style="background-color:none;height:400px;" class="pageFormContent"  >
                    <?= $editBar; ?>
                </div>
                <div class="menu_tree">

                    <div style=" float:left; display:block; margin:10px; overflow:auto; width:900px; height:400px; overflow:auto; border:solid 1px #CCC; line-height:21px; background:#FFF;">
                        <ul  class="men_str  tree treeFolder treeCheck expand" >

                            <?= $menu ?>
                        </ul>
                    </div>



                </div>
            </div>
            <div class="tabsFooter">
                <div class="tabsFooterContent"></div>
            </div>
        </div>





        <div class="formBar">
            <ul >
                <!--<li><a class="buttonActive" href="javascript:;"><span>保存</span></a></li>-->
                <li><div class="buttonActive"><div class="buttonContent"><button onclick="func('accept')"  value="accept" name="accept" type="submit">保存</button></div></div></li>

                <li>
                    <div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div>
                </li>
            </ul>
        </div>

    </form>
</div>