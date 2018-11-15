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

<div layouth="56" class="pageFormContent" style="height: 485px; overflow: auto;">
    <div>
        <input value="5a211ce8bfb7ae649c0e7976" size="30" name="editFormData[_id]" class="textInput " type="hidden">							<p class="edit_p">
            <label><?= Yii::$service->page->translate->__('Category') ?>：</label>
            <input value="<?= $category  ?>" size="30" name="editFormData[category]" class="textInput" type="text">
        </p>							<p class="edit_p">
            <label><?= Yii::$service->page->translate->__('Status Code') ?>：</label>
            <input value="<?= $code  ?>" size="30" name="editFormData[code]" class="textInput" type="text">
        </p>							
        <p class="edit_p">
            <label><?= Yii::$service->page->translate->__('Line') ?>：</label>
            <input value="<?= $line  ?>" size="30" name="editFormData[line]" class="textInput" type="text">
        </p>							<p class="edit_p">
            <label><?= Yii::$service->page->translate->__('Ip') ?>：</label>
            <input value="<?= $ip  ?>" size="30" name="editFormData[ip]" class="textInput" type="text">
        </p>							<p class="edit_p">
            <label><?= Yii::$service->page->translate->__('Error Name') ?>：</label>
            <input value="<?= $name  ?>" size="30" name="editFormData[name]" class="textInput" type="text">
        </p>
                            
        <div style="clear:both"></div>
    </div>
    <div>
        <label><?= Yii::$service->page->translate->__('Url') ?>：</label>
         <input value="<?= $url  ?>"  style="width:80%"   />
    </div>
    
    <div style="clear:both"></div>
    <br/>
    <div>
        <label><?= Yii::$service->page->translate->__('File') ?>File：</label>
         <input value="<?= $file  ?>"  style="width:80%"   />
    </div>
    <div style="clear:both"></div>
    <br/>
    <div>
        <label><?= Yii::$service->page->translate->__('Message') ?>: </label>
         <input value="<?= $message  ?>"  style="width:80%"   />
    </div>
    <div style="clear:both"></div>
    <div style="padding:10px 0">
        <label><?= Yii::$service->page->translate->__('Request Info') ?></label>
        
        <div style="width:80%;float:left;line-height:20px;background:#fff;padding:5px;" >
            <?php
                if (is_array($request_info)) {
                    foreach ($request_info as $k =>$v){
                        if (is_array($v)) {
                            $v = json_encode($v);
                        } 
            ?>
                        <?= $k; ?> : <?= $v  ?><br/>
            <?php
                    }
                } else {
                    echo 'null';
                }
            ?>
        </div>
    </div>
    <div style="clear:both"></div>
    <div style="padding:10px 0;margin:10px 0 0;">
        <label><?= Yii::$service->page->translate->__('Trace Info') ?></label>
        
        <div style="width:80%;float:left;line-height:20px;background:#fff;padding:5px;" >
            <?php
                $trace_string = explode('#',$trace_string);
                foreach ($trace_string as $k =>$v){
                    
                    $v = trim($v);
                    if(!$v) {
                        continue;
                    }
            ?>
                #<?= $v  ?><br/>
            <?php
                }
            ?>
        </div>
    </div>
</div>
