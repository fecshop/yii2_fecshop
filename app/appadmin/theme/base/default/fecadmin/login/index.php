<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license/
 */
use fec\helpers\CUrl;
use fec\helpers\CRequest;
?>
<style>

</style>

<?php if ($error ):  ?>
<div role="alert" class="el-message el-message--error" style="z-index: 2012;">
    <i class="el-message__icon el-icon-error"></i>
    <p class="el-message__content"><?= $error ?></p><!---->
</div>
<?php endif;  ?>
<?php $currentLangCode = Yii::$service->admin->getCurrentLangCode() ; ?>
<form data-v-5cb71550="" class="el-form login-form el-form--label-left" autocomplete="on" action="<?= CUrl::getUrl('fecadmin/login/index', ['lang' => $currentLangCode]); ?>" method="post">
    <?php echo CRequest::getCsrfInputHtml();  ?>
    <div data-v-5cb71550="" class="title-container">
        <h3 data-v-5cb71550="" class="title"><?= Yii::$service->page->translate->__('FECMALL ADMIN'); ?> </h3>
        <div data-v-54d0b3ce="" data-v-5cb71550="" class="international set-language el-dropdown isHide">
            <div data-v-54d0b3ce="" aria-haspopup="list" aria-controls="dropdown-menu-9855" role="button" tabindex="0" class=" el-dropdown-selfdefine ">
                <svg data-v-ab4e0682="" data-v-54d0b3ce="" aria-hidden="true" class="svg-icon international-icon">
                    <svg class="icon" viewBox="0 0 1088 1024" id="icon-language"><path d="M729.6 294.4c19.2 57.6 44.8 102.4 89.6 147.2 38.4-38.4 64-89.6 83.2-147.2h-172.8zM307.2 614.4h166.4L390.4 390.4z" p-id="6280"></path><path d="M947.2 0h-768C108.8 0 51.2 57.6 51.2 128v768c0 70.4 57.6 128 128 128h768c70.4 0 128-57.6 128-128V128c0-70.4-51.2-128-128-128zM633.6 825.6c-12.8 12.8-25.6 12.8-38.4 12.8-6.4 0-19.2 0-25.6-6.4s-12.8 0-12.8-6.4-6.4-12.8-12.8-25.6-6.4-19.2-12.8-32l-25.6-70.4H281.6L256 768c-12.8 25.6-19.2 44.8-25.6 57.6-6.4 12.8-19.2 12.8-38.4 12.8-12.8 0-25.6-6.4-38.4-12.8-12.8-12.8-19.2-19.2-19.2-32 0-6.4 0-12.8 6.4-25.6s6.4-19.2 12.8-32l140.8-358.4c6.4-12.8 6.4-25.6 12.8-38.4s12.8-25.6 19.2-32 12.8-19.2 25.6-25.6c12.8-6.4 25.6-6.4 38.4-6.4 12.8 0 25.6 0 38.4 6.4 12.8 6.4 19.2 12.8 25.6 25.6 6.4 6.4 12.8 19.2 19.2 32 6.4 12.8 12.8 25.6 19.2 44.8l140.8 352c12.8 25.6 19.2 44.8 19.2 57.6-6.4 6.4-12.8 19.2-19.2 32zM985.6 576c-70.4-25.6-121.6-57.6-166.4-96-44.8 44.8-102.4 76.8-172.8 96l-19.2-32c70.4-19.2 128-44.8 172.8-89.6-44.8-44.8-83.2-102.4-96-166.4h-64v-25.6h172.8c-12.8-19.2-25.6-44.8-38.4-64l19.2-6.4c12.8 19.2 32 44.8 44.8 70.4h160v32h-64c-19.2 64-51.2 121.6-89.6 160 44.8 38.4 96 70.4 166.4 89.6l-25.6 32z" p-id="6281"></path></svg>
                </svg>
            </div>
        </div>
    </div>
    <div data-v-5cb71550="" class="el-form-item is-required el-form-item--medium">
        <div class="el-form-item__content">
            <span data-v-5cb71550="" class="svg-container svg-container_login">
                <svg data-v-ab4e0682="" data-v-5cb71550="" aria-hidden="true" class="svg-icon">
                    <svg class="icon" viewBox="0 0 1024 1024" id="icon-user"><path d="M504.951 511.98c93.49 0 169.28-74.002 169.28-165.26 0-91.276-75.79-165.248-169.28-165.248-93.486 0-169.287 73.972-169.279 165.248-0.001 91.258 75.793 165.26 169.28 165.26z m77.6 55.098H441.466c-120.767 0-218.678 95.564-218.678 213.45V794.3c0 48.183 97.911 48.229 218.678 48.229H582.55c120.754 0 218.66-1.78 218.66-48.229v-13.77c0-117.887-97.898-213.45-218.66-213.45z" p-id="7987"></path></svg>
                </svg>
            </span>
            <div data-v-5cb71550="" class="el-input el-input--medium">
                <input autocomplete="on" placeholder="<?= Yii::$service->page->translate->__('username'); ?>" name="login[username]" type="text" rows="2" validateevent="true" class="el-input__inner">
            </div>
        </div>
    </div>

    <div data-v-5cb71550="" class="el-form-item is-required el-form-item--medium">
        <div class="el-form-item__content">
            <span data-v-5cb71550="" class="svg-container">
                <svg data-v-ab4e0682="" data-v-5cb71550="" aria-hidden="true" class="svg-icon">
                  <svg class="icon" viewBox="0 0 1024 1024" id="icon-password"><path d="M780.8 354.579692 665.6 354.579692 665.6 311.689846c0-72.310154-19.849846-193.299692-153.6-193.299692-138.870154 0-153.6 135.049846-153.6 193.299692l0 42.889846L243.2 354.579692 243.2 311.689846C243.2 122.249846 348.790154 0 512 0s268.8 122.249846 268.8 311.689846L780.8 354.579692zM588.8 669.420308C588.8 625.900308 554.220308 590.769231 512 590.769231s-76.8 35.131077-76.8 78.651077c0 29.459692 15.399385 54.468923 38.439385 67.820308l0 89.639385c0 21.740308 17.250462 39.699692 38.4 39.699692s38.4-17.959385 38.4-39.699692l0-89.639385C573.44 723.889231 588.8 698.88 588.8 669.420308zM896 512l0 393.609846c0 65.260308-51.869538 118.390154-115.2 118.390154L243.2 1024c-63.291077 0-115.2-53.129846-115.2-118.390154L128 512c0-65.220923 51.869538-118.390154 115.2-118.390154l537.6 0C844.130462 393.609846 896 446.779077 896 512z" p-id="9230"></path></svg>
                </svg>
            </span>
            <div data-v-5cb71550="" class="el-input el-input--medium">
                <input autocomplete="on" placeholder="<?= Yii::$service->page->translate->__('password'); ?>" name="login[password]" type="password" rows="2" validateevent="true" class="el-input__inner">
            </div>
        </div>
    </div>

    <button data-v-5cb71550="" type="submit" class="el-button el-button--primary el-button--medium" style="width: 100%; margin-bottom: 30px;">
        <span><?= Yii::$service->page->translate->__('Login'); ?></span>
    </button>
    <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
    <div data-v-5cb71550="" style="color: rgb(170, 170, 170); text-align: center;">
        <a target="_blank" href="http://www.fecmall.com/doc/fecshop-guide/develop/cn-2.0/guide-fecshop-2-about-description.html">
            <?= Yii::$service->page->translate->__('Introduction'); ?>
        </a>
        | 
        <a target="_blank" href="http://www.fecmall.com/doc/fecshop-guide/develop/cn-2.0/guide-fecmall-appadmin-about.html">
            <?= Yii::$service->page->translate->__('How to get started quickly?'); ?>
        </a>
            <?= Yii::$service->page->translate->__('Version'); ?>
        </a>
        ：<?= Yii::$service->helper->getVersion()  ?>
        <br data-v-5cb71550="">
        <br data-v-5cb71550="">
        <a href="http://www.fecmall.com/license" target="_blank">
            <?= Yii::$service->page->translate->__('The real open-source E-commerce mall is free for commercial use. Please refer to the license agreement for details'); ?>
        </a>
        <br data-v-5cb71550="">
        <br data-v-5cb71550="">官网：
        <a data-v-5cb71550="" target="_blank" href="http://www.fecmall.com" style="color: rgb(255, 255, 255);">www.Fecmall.com</a>
    </div>
</form>

<script> <!-- 编写script标签是为了编辑器识别js代码，可以省略 -->  
<?php $this->beginBlock('js_end') ?>  
　$(document).ready(function(){$("#login-captcha-image").click();});  
<?php $this->endBlock(); ?>  
</script>  
<?php $this->registerJs($this->blocks['js_end'],\yii\web\View::POS_LOAD); ?>