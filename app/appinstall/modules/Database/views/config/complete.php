<?php
    use fec\helpers\CRequest;
?>
<h1>完成安装</h1>
<br/>

<?=  $errorInfo  ?>
<?=  $successInfo  ?>

<br/>
<br/>

<?php echo CRequest::getCsrfInputHtml();  ?>
<p style="font-size:16px;line-height:25px;"><b>您还需要进行如下的步骤：</b></p>

<p style="font-size:16px;line-height:25px;">设置安全权限(window不需要执行）,Fecmall安装<code>根目录下</code>执行命令行：<code>chmod 644 common/config/main-local.php</code></p>


<p style="font-size:16px;line-height:25px;">对于本地环境无所谓，但是线上环境为了安全，请必须设置数据库配置文件644</p>

<br/><br/>

<a target="_blank" href="<?=  'http://'.$_SERVER['SERVER_NAME']  ?>" class="btn btn-default btn-lg">访问PC商城</a>

<br/>
<br/>

