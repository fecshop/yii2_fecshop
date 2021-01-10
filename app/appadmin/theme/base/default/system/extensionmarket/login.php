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
<div class="pageContent">
    
    <br/><br/><br/><br/>
	<form style="width:400px;margin:auto" method="post" action="<?= Yii::$service->url->getUrl('system/extensionmarket/login') ?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDoneCloseAndReflush);">
		<?php echo CRequest::getCsrfInputHtml();  ?>	
        <div class="pageFormContent">
			<h1>请填写您的Fecmall应用市场的帐号，进行登陆</h1>
            <br/><br/>
            <p>
				<label>账户: </label>
				<input    value="admin" name="editForm[email]" id="email"  type="text" size="30" />
			</p>
			<p style="margin:10px 0 5px 0">
				<label>密码: </label>
				<input class=" "  title="Password" value="" name="editForm[password]" id="password" type="password" size="30" />
			</p>
            <br/><br/>
            
		</div>	
		<div class="buttonActive" style="margin-left:8px;">
            <div class="buttonContent">
                <button type="submit">登陆</button>
            </div>
            
        </div>
        <div style="float: right;   color: #777; margin: 10px 30px 0 0;">
            没有账户? 点击<a href="http://addons.fecmall.com/customer/account/register" target="_blank">这里注册</a>	
		</div>
	</form>
</div>
