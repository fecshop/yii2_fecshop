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
    function thissubmit(thiss){
        return validateCallback(thiss, dialogAjaxDoneCloseAndReflush);
    }
</script>

<style>
    .group_resource li ul li{
        float:left;
        margin:5px 0 0 0;
    }
    .group_resource{
        padding-left:10px;
    }
    .clear{
        clear:both;
    }
    .group_resource li{
        margin:25px 10px 0 0 ;
        float:left;
    }
    .line-resources{
        width:100%
    }
</style>

<div class="pageContent">
    <form  method="post" action="<?= $saveUrl ?>" class="pageForm required-validate" onsubmit="return thissubmit(this, dialogAjaxDoneCloseAndReflush);">
        <?php echo CRequest::getCsrfInputHtml();  ?>
        <div class="tabs" currentIndex="0" eventType="click"  layoutH="56">
            <div class="tabsHeader">
                <div class="tabsHeaderContent">
                    <ul>
                        <li><a href="javascript:;"><span><?=  Yii::$service->page->translate->__('Basic Info') ?></span></a></li>
                        <li><a href="javascript:;"><span><?=  Yii::$service->page->translate->__('Role') ?></span></a></li>
                    </ul>
                </div>
            </div>
            <div class="tabsContent" style="height:100%;min-height:450px">
                <div style="background-color:none;height:400px;" class="pageFormContent"  >
                    <?= $editBar; ?>
                </div>
                <div class="menu_tree">
                    <div style=" float:left; display:block; margin:10px; overflow:auto; width:900px; overflow:auto; border:solid 1px #CCC; line-height:21px; background:#FFF;">
                        <ul  class="group_resource" >
                            <?php if (is_array($groupResources)):  ?>
                                <?php foreach ($groupResources as $groupKey => $resources): ?>
                                    <li class="line-resources">
                                        <div><span><?= isset($tags[$groupKey]) ? $tags[$groupKey] : '' ?></span></div>
                                        <ul>
                                            <?php if (is_array($resources)):  ?>
                                                <li class="clear"></li>
                                                <?php foreach ($resources as $resource): ?>
                                                    <li>
                                                        <label>
                                                            <input type="checkbox" name="editFormData[resources][]"  value="<?= $resource['id'] ?>" <?= $resource['selected'] ? 'checked="checked"' : '' ?>    />
                                                            <span><?= Yii::$service->page->translate->__($resource['name']); ?></span>
                                                        </label>
                                                    </li>
                                                <?php endforeach; ?>
                                                    <li class="clear"></li>
                                            <?php endif;  ?>
                                        </ul>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif;  ?>
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
                <li><div class="buttonActive"><div class="buttonContent"><button onclick="func('accept')"  value="accept" name="accept" type="submit"><?=  Yii::$service->page->translate->__('Save') ?></button></div></div></li>

                <li>
                    <div class="button"><div class="buttonContent"><button type="button" class="close"><?=  Yii::$service->page->translate->__('Cancel') ?></button></div></div>
                </li>
            </ul>
        </div>
    </form>
</div>