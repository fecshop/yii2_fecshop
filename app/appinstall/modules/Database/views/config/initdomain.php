<?php

use fec\helpers\CRequest;

?>
<h1>Fecmall域名配置</h1>
<br/><br/>

<?= $errorInfo ?>


<form action="<?=  Yii::$app->homeUrl . '/database/config/initdomain?database=1' ?>" method="post">
    <?php echo CRequest::getCsrfInputHtml(); ?>
    注意：下面填写的域名，必须和nginx/apache中配置的一致, 如果您使用的是宝塔控制面板，保持和宝塔安装填写的域名一致即可。
    <br/><br/>
    <div class="form-group">
        <label for="name">Pc站域名【必须<code>*</code>】譬如：<code> www.fecmall.com</code>， 电脑pc浏览器访问的商城入口域名</label>
        <input type="text" class="form-control" value="<?= $editForm['appfront_domain'] ? $editForm['appfront_domain'] : $demoDomainList['demo_pc_domain'] ?>"
        name="editForm[appfront_domain]" placeholder="这是电脑pc浏览器访问的商城入口域名">
    </div>
    <!--
    <div class="form-group">
        <label for="name">后台域名【必须<code>*</code>】譬如：<code> appadmin.fecmall.com</code>， 管理后台访问的入口域名</label>
        <input type="text" class="form-control" value="<?= $editForm['appadmin_domain'] ? $editForm['appadmin_domain'] : $demoDomainList['demo_admin_domain'] ?>"
        name="editForm[appadmin_domain]" placeholder="这是管理后台访问的入口域名">
    </div>
    -->
    <div class="form-group">
        <label for="name">图片域名【必须<code>*</code>】譬如：<code> img.fecmall.com</code>， 图片入口域名</label>
        <input type="text" class="form-control" value="<?= $editForm['img_domain'] ? $editForm['img_domain'] : $demoDomainList['demo_img_domain'] ?>"
        name="editForm[img_domain]" placeholder="图片入口域名, 将图片使用单独的子域名，可以提高更高的安全性，以及浏览器加载图片速度">
    </div>
    
    <div class="form-group">
        <label for="name">手机H5域名【选填】譬如：<code> m.fecmall.com</code>，手机浏览器html5入口域名</label>
        <input type="text" class="form-control" value="<?= $editForm['apphtml5_domain'] ? $editForm['apphtml5_domain'] : $demoDomainList['demo_h5_domain'] ?>"
        name="editForm[apphtml5_domain]" placeholder="这是手机浏览器html5入口域名">
    </div>
    
    <div class="form-group">
        <label for="name">Appserver域名【选填】譬如：<code> appserver.fecmall.com</code>， 微信小程序，vue等前后端分析应用，fecmall提供api数据的入口域名</label>
        <input type="text" class="form-control" value="<?= $editForm['appserver_domain'] ? $editForm['appserver_domain'] : $demoDomainList['demo_appserver_domain'] ?>"
        name="editForm[appserver_domain]" placeholder="微信小程序，vue等前后端分析应用，fecmall提供api数据的入口域名">
    </div>
    
    
    <div class="form-group">
        <label for="name">电商类型【必须<code>*</code>】</label>
        <select class="form-control" name="editForm[mall_type]">
            <option value="china">国内中文电商类型【基础货币：CNY（人民币），Store默认语言：zh-CN】</option>
            <option value="global">跨境出口电商【基础货币：USD（美元），Store默认语言：en-US】</option>
        </select>
    </div>
    <!--
    
    <div class="form-group">
        <label for="name">AppApi域名【选填】譬如：：<code> appapi.fecmall.com</code>， fecmall和第三方erp等系统，进行数据对接的入口域名</label>
        <input type="text" class="form-control" value="<?= $editForm['appapi_domain'] ? $editForm['appapi_domain'] : $demoDomainList['demo_appapi_domain'] ?>"
        name="editForm[appapi_domain]" placeholder="fecmall和第三方erp等系统，进行数据对接的入口域名">
    </div>
    
    <div class="form-group">
        <label for="name">AppBdmin域名【选填】譬如：：<code> appbdmin.fecmall.com</code>， 多商户扩展经销商后台域名</label>
        <input type="text" class="form-control" value="<?= $editForm['appbdmin_domain'] ? $editForm['appbdmin_domain'] : $demoDomainList['demo_appbdmin_domain'] ?>"
        name="editForm[appbdmin_domain]" placeholder="多商户扩展经销商后台域名">
    </div>
    -->
    
    <button type="submit" class="btn btn-default">下一步</button>
</form>
