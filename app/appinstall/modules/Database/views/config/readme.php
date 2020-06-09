<?php

use fec\helpers\CRequest;

?>
<h1>Fecmall 安装须知</h1>
<br/>

<?= $errorInfo ?>

<div class="container">
    
    <p >Fecmall 全称为Fancy ECommerce Shop，<a target="_blank" href="http://www.fecmall.com">Fecmall官网</a>， 着重于电商架构的研发优化，全新定义商城的架构体系，是基于php Yii2框架之上开发的一款优秀的开源电商系统，易于系统升级，二次开发以及第三方扩展，代码100%开源
    ，详细参看<a target="_blank" href="http://www.fecmall.com/fecmall">Fecmall功能详细</a>
    ，Fecmall作为开源流商城，允许商用免费授权，详细参看：<a target="_blank" href="http://www.fecmall.com/license">Fecmall授权协议</a>
    ，在安装过程中遇到问题，可以去<a  target="_blank" href="http://www.fecmall.com/topic">Fecmall论坛</a>搜帖子，找不到相关帖子可以发帖求助Terry解答，
    
    在安装前，请仔细阅读<a  target="_blank" href="http://www.fecmall.com/doc/fecshop-guide/develop/cn-2.0/guide-fecshop-2-graphical-install.html">Fecmall 安装文档</a>。
    </p>

    <hr class="half-rule">
    
    <hr class="half-rule">

    <p class="lead">
        准备Mysql数据库信息
    </p>
    
    <ol>
        <li>Mysql数据库名称：    Database name</li>
        <li>Mysql数据库用户名： Database username</li>
        <li>Mysql数据库密码：    Database password</li>
        <li>Mysql数据库Host：   Database host</li>
    </ol>
    
    <hr class="half-rule">
    
    <hr class="half-rule">
    <p class="lead">
        准备各个子域名，并解析到您的服务器ip （Fecmall为了安全性能因素，将各个入口使用单独的域名）
    </p>
    
    <ol>
        <li>Pc站域名【必须】譬如：<code> www.fecmall.com</code>，这是电脑pc浏览器访问的商城入口域名</li>
        
        <li>后台域名【必须】譬如：<code>appadmin.fecmall.com</code>，这是管理后台访问的入口域名</li>
        <li>图片域名【必须】譬如：<code>img.fecmall.com</code>，这是图片入口域名, 将图片使用单独的子域名，可以提高更高的安全性，以及浏览器加载图片速度</li>
        <li>手机H5域名【选填】譬如：<code>m.fecmall.com</code>，这是手机浏览器html5入口，如果您需要手机h5，那么需要解析改子域名</li>
        <li>Appserver域名【选填】譬如：<code>appserver.fecmall.com</code>，微信小程序，vue等前后端分析应用，fecmall提供api数据的入口域名</li>
        <li>Api域名【选填】譬如：<code>appapi.fecmall.com</code>， fecmall和第三方erp等系统，进行数据对接的入口域名，</li>
        <li>多商户后台域名【选填】譬如：<code>appbdmin.fecmall.com</code>， 如果您想安装fecbbc多商户扩展，那么需要解析该子域名</li>
    </ol>
    
    <hr class="half-rule">
    
    <hr class="half-rule">
    <p class="lead">
        Apache / Nginx 配置
    </p>
    
    
    <ol>
        <li>如果您使用的是宝塔控制面板，apache和nginx在安装的时候会自动配置好，按照宝塔应用配置的域名和fecmall安装配置的域名一致即可</li>
        <li>如果您不使用宝塔，而是自己搭建的环境，您需要在apache或nginx进行域名配置，详细参看<a target="_blank" href="http://www.fecmall.com/doc/fecshop-guide/develop/cn-2.0/guide-fecshop-2-graphical-install.html#nginxapache">Fecmall Nginx/Apache 域名配置</a></li>
    </ol>
    <hr class="half-rule">
    
    <hr class="half-rule">
    
    <a href="<?=  Yii::$app->homeUrl . '/database/config/index?database=1' ?>" class="btn btn-default btn-lg">开始Fecmall安装之旅</a>
    <br/><br/><br/><br/><br/><br/>
  </div>

  
  
  
  
  
  
  
  
  