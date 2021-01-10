<?php
/**
 * Fecmall file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 Fecmall Software LLC
 * @license http://www.fecmall.com/license/
 */
/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use fecadmin\myassets\AppAsset;
use fecadmin\myassets\AppZhAsset;
use common\widgets\Alert;
use fec\helpers\CUrl;
use fec\helpers\CRequest;
use fecadmin\views\layouts\Head;

$currentLangCode = Yii::$service->admin->getCurrentLangCode();
if ($currentLangCode == 'zh') {
    AppZhAsset::register($this);
    $publishedPath = $this->assetManager->publish('@fecadmin/myassets/dwz_jui-master/dwz.frag.zh.xml');
} else {
    AppAsset::register($this);
    $publishedPath = $this->assetManager->publish('@fecadmin/myassets/dwz_jui-master/dwz.frag.xml');
}
?>
<?php
// Fecmall 多模板机制的js和css部分
$jsOptions = [
	# js config 1
	[
		'js'	=>[
			'js/appadmin.js',
            'js/jquery-editable-select.js',
             'js/select2.min.js',
		],
        // js 放到尾部
        'options' => [
			'position' => \yii\web\View::POS_END,  //POS_HEAD,
		],
        
	],
    [
		'js'	=>[
            'js/echarts.min.js',
           
		],
        // js 放到尾部
        'options' => [
			'position' => \yii\web\View::POS_HEAD,  //POS_HEAD,
		],
        
	],
	# js config 2
	//[
	//	'options' => [
	//		'condition'=> 'lt IE 9',
	//	],
	//	'js'	=>[
	//		'js/ie9js.js'
	//	],
	//],
];

# css config
$cssOptions = [
	# css config 1.
	[
		'css'	=>[
			'css/appadmin.css',
			'css/bootstrap-appadmin.css',
            'css/jquery-editable-select.css',
            'css/select2.min.css',
            'font-awesome/css/font-awesome.min.css',
		],
        // 将css放到最后面
        'options' => [
            'depends'=>['fecadmin\myassets\CustomAsset'],
        ]
	],
];
\Yii::$service->page->asset->jsOptions 	= \yii\helpers\ArrayHelper::merge($jsOptions, \Yii::$service->page->asset->jsOptions);
\Yii::$service->page->asset->cssOptions = \yii\helpers\ArrayHelper::merge($cssOptions, \Yii::$service->page->asset->cssOptions);				
\Yii::$service->page->asset->register($this);
$logoPath = $this->assetManager->publish('@fecshop/app/appadmin/theme/base/default/assets/images/blue_logo.png');
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>"  xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?= Html::csrfMetaTags() ?>
    <title><?= $this->title ? Html::encode($this->title) : Yii::$service->page->translate->__('Fecmall Admin Manager System') ?></title>
    
	<?php $this->head() ?>
<script> 

　$(function(){
	DWZ.init("<?= $publishedPath[1]; ?>", {
		loginUrl:"login_dialog.html", loginTitle:"登录",	// 弹出登录对话框
//		loginUrl:"login.html",	// 跳到登录页面
		statusCode:{ok:200, error:300, timeout:301}, //【可选】
		pageInfo:{pageNum:"pageNum", numPerPage:"numPerPage", orderField:"orderField", orderDirection:"orderDirection"}, //【可选】
		keys: {statusCode:"statusCode", message:"message"}, //【可选】
		ui:{hideMode:'offsets'}, //【可选】hideMode:navTab组件切换的隐藏方式，支持的值有’display’，’offsets’负数偏移位置的值，默认值为’display’
		debug:false,	// 调试模式 【true|false】
		callback:function(){
			initEnv();
			$("#themeList").theme({themeBase:"themes"}); // themeBase 相对于index页面的主题base路径
		}
	});
});
</script> 
</head>
<body>
<?php $this->beginBody() ?>
	<div id="layout">
		<div id="leftside">
			
			<div id="sidebar">
				<div class="toggleCollapse" style="background:rgb(48, 65, 86) !important;overflow: visible;">
					<h2 style="font-size:15px;font-weight:100;    padding-top: 10px;padding-bottom: 10px;box-shadow: none;color:#fff"><?= Yii::$service->page->translate->__('FECMALL'); ?></h2>
					<div><i class="fa fa-list"></i></div>
                    
				</div>

				<div class="accordion" fillSpace="sidebar">
                    
					<?= Yii::$service->admin->menu->getLeftMenuHtml();  ?>
				</div>
			</div>
		</div>
        <?= Yii::$service->helper->echart->setDashboardBaseI(); ?>
		<div id="container">
			<div id="navTab" class="tabsPage" style="position: relative;">
				<div class="tabsPageHeader">
					<div class="tabsPageHeaderContent"><!-- 显示左右控制时添加 class="tabsPageHeaderMargin" -->
						<ul class="navTab-tab">
							<li tabid="main" class="main">
                            
                                <a href="javascript:;">
                                    <span style="width: auto; padding: 0 10px;   text-align: left;">
                                        <span class="home_icon" style="padding-left:0;"><?= Yii::$service->page->translate->__('My Main Page'); ?></span>
                                    </span>
                                </a>
                                /
                            </li>
                            
						</ul>
					</div>
					
				</div>
                
                  <div class="headerNav" style="position: absolute;right: 25px; top: 0; width: 200px;  height: 30px; z-index: 99;">
                        <span style="font-size:14px;line-height: 40px;  color: #97a8be;  display: block;   height: 21px;  position: absolute; right: 125px; top: 0px;  z-index: 31; width: 100px;">
                        <?= Yii::$service->page->translate->__('Hello'); ?>: <?= \fec\helpers\CUser::getCurrentUsername();   ?>
                        </span>
                      <?php $currentLangCode = Yii::$service->admin->getCurrentLangCode() ?>
                      <?php $langArr = Yii::$service->admin->getLangArr() ?>
                      <select class="store_langs" style="    font-size: 14px;  color: #97a8be;  margin-top: 10px;">
                           <?php foreach ($langArr as $code => $name): ?>
                               <option  value="<?= $code ?>" <?= ($code == $currentLangCode) ? 'selected="selected"' : ''  ?>>
                                   <?= $name ?>
                               </option>
                          <?php endforeach; ?>
                       </select>
                        <a style="font-size:14px; line-height: 40px;color:#97a8be; display: block; height: 40px;position: absolute; right: 5px;top: 0;z-index: 31;" href='javascript:doPost("<?= Yii::$service->url->getUrl("fecadmin/logout") ?>", {"<?= CRequest::getCsrfName() ?>": "<?= CRequest::getCsrfValue() ?>", "islogout": "1"}) '>
                            <?= Yii::$service->page->translate->__('Logout'); ?>
                        </a>
                  </div>
                
				<ul class="tabsMoreList">
					<li><a href="javascript:;"><?= Yii::$service->page->translate->__('My Main Page'); ?></a></li>
				</ul>
				<div class="navTab-panel tabsPageContent layoutBox">
                        <div class="page unitBox">
                             <?php  $recentStatisticsInfo = Yii::$service->systemhelper->getRecentStatisticsInfo();?>
                            <div class="row page-home">
                                <div class="am-u-sm-12 am-u-md-12 am-u-lg-12 am-margin-bottom">
                                    <div class="widget am-cf">
                                        <div class="widget-head">
                                            <div class="widget-title">商城统计</div>
                                        </div>
                                        <div class="widget-body am-cf">
                                            <div class="widget-4 am-u-sm-12 am-u-md-6 am-u-lg-3">
                                                <div class="widget-card card__blue am-cf">
                                                    <div class="card-header">商品总量(Sku)</div>
                                                    <div class="card-body">
                                                        <div class="card-value"><?=  $recentStatisticsInfo['all']['product_count'] ?></div>
                                                        <div class="card-description">当前商品Sku总数量</div>
                                                        <span class="card-icon iconfont icon-goods fa fa-product-hunt"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="widget-4 am-u-sm-12 am-u-md-6 am-u-lg-3">
                                                <div class="widget-card card__red am-cf">
                                                    <div class="card-header">用户总量</div>
                                                    <div class="card-body">
                                                        <div class="card-value"><?=  $recentStatisticsInfo['all']['customer_count'] ?></div>
                                                        <div class="card-description">当前用户总数量</div>
                                                        <span class="card-icon iconfont icon-goods fa fa-user"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="widget-4 am-u-sm-12 am-u-md-6 am-u-lg-3">
                                                <div class="widget-card card__violet am-cf">
                                                    <div class="card-header">订单总量</div>
                                                    <div class="card-body">
                                                        <div class="card-value"><?=  $recentStatisticsInfo['all']['order_count'] ?></div>
                                                        <div class="card-description">已付款订单总数量</div>
                                                        <span class="card-icon iconfont icon-goods fa fa-ship"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="widget-4 am-u-sm-12 am-u-md-6 am-u-lg-3">
                                                <div class="widget-card card__primary am-cf">
                                                    <div class="card-header">评价总量</div>
                                                    <div class="card-body">
                                                        <div class="card-value"><?=  $recentStatisticsInfo['all']['review_count'] ?></div>
                                                        <div class="card-description">订单产品评价总数量</div>
                                                        <span class="card-icon iconfont icon-goods fa fa-eye"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="clear:both"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>                   
                        
                            <div class="row page-home">
                                <div class="am-u-sm-12 am-u-md-12 am-u-lg-12 am-margin-bottom">
                                    <div class="widget am-cf">
                                        <div class="widget-head">
                                            <div class="widget-title">实时概况</div>
                                        </div>
                                        <div class="widget-body am-cf">
                                            <div class="widget-4 am-u-sm-6 am-u-md-6 am-u-lg-3">
                                                <div class="widget-outline dis-flex flex-y-center">
                                                    <div class="outline-left">
                                                        <img src="data:image/png;base64,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" alt="">
                                                    </div>
                                                    <div class="outline-right dis-flex flex-dir-column flex-x-between">
                                                        <div style="color: rgb(102, 102, 102); font-size: 1rem;">支付订单销售额(基础货币)</div>
                                                        <div style="color: rgb(51, 51, 51); font-size: 2rem;"><?=  $recentStatisticsInfo['today']['order_base_sale'] ?></div>
                                                        <div style="color: rgb(153, 153, 153); font-size: 1rem;">
                                                            昨日：<?=  $recentStatisticsInfo['yestday']['order_base_sale'] ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="widget-4 am-u-sm-6 am-u-md-6 am-u-lg-3">
                                                <div class="widget-outline dis-flex flex-dir-column flex-x-between">
                                                    <div style="color: rgb(102, 102, 102); font-size: 1rem;">支付订单数</div>
                                                    <div style="color: rgb(51, 51, 51); font-size: 2rem;"><?=  $recentStatisticsInfo['today']['order_count'] ?></div>
                                                    <div style="color: rgb(153, 153, 153); font-size: 1rem;">
                                                        昨日：<?=  $recentStatisticsInfo['yestday']['order_count'] ?></div>
                                                </div>
                                            </div>
                                            <div class=" widget-4 am-u-sm-6 am-u-md-6 am-u-lg-3">
                                                <div class="widget-outline dis-flex flex-y-center">
                                                    <div class="outline-left">
                                                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAI4AAACMCAMAAACd62ExAAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAAAzUExURUdwTPn///D4/+/2//T6//D3//H3//D3//D3//H5/2aq/7DT/5HC/3az/8Ld/9zs/53I/+kzVBAAAAAKdFJOUwAOY/8hooXE4kIJnP2wAAADmUlEQVR42u2ca3fCIAyGLeFqW+j//7XTOWedbUkgYHaO784+6p6FJNxCTqdyDVpZY5zz3gN475wzVoXh1F9DsO7CsCnvjNJ9USAn3wdpUHmUu5xtTBQM0ORUM1carIcCmdACRlsolWMH0gZqxAtUCcMMpIBDhifMggcmKYZwMsCn6hHjM81NtorGArdcuQdpB/zyQchA1Q2YglZygwS3qeEx0FJevy/bVPMMDlqLEmDtaSj2MQCCeCz0ES6+etHgeBT0k8nPU9BT6v0h/qQgIahW4TVIcZy8+2joL/XebPwiLWeovrPPXlT5t+DsRRciqmIcqYqxLLpCFmY8F2lOJYvnrB/P50LNBd6cNc50LtZITz4548Tv/3NakHqCX8jmQRkn6wV3peUxUpffiZybUZ6DpIm/ppnHFDE4f82TD6vrt6MMM/66/BR/BjmPY6nTAw5nZZgl/fpcHgcG4tyJwfljGAqOIq6Pszgr913Sc0ROxJnLV+PE+eG+LwkCgbN25gCVOFujRMSxtL3MEc70iOvN9InB8aSxOsIZdwxDwnmsMzBjdYQzreK6HMeQ9p0HOIcJG43jSUvk5jj32BpABo4iuE4HHEM5smiP4ym7q/Y4P84DUnAUYSc8t8exeE++pt6xMY6hHL6lBI1xHM+RDhcO8BxbsOFo5HTeCSeg47wLjsLOWN1w9AfnIA+GD85BWv7gfHBKXVl/cP7PJCFsCpW2wBC2/EIuTuM8xx6LU4s2wdxj6Y7Mgx22fYaw7Tt32vYJ2xRLOzIQdqAi7LhJ2GGcsKNKaQe5QQSO4boE4MEJXFckLDiedoF08DdZcCztem3a/1oWHE27fLxd6y9bVR9nBhxHLWs6LnpIu6u2gqtZTCZMMxknLbfPUC+ucalnoeE87tQjyZHRE0WK47Lxs4WTDm5rM45MWKIiI+thmPOSSBmZoSruL864d6eONk6VeZ5wVsUGmFHaMU6NeVY4T7Uy6C/QRaVoOZx7XFMMsxVWleWvN5zXWhmkdspgi8sYrziFhnleWfAUea6nD5phXmYrDm9O1LjO+nFdgXC8GSaWfFY1KJ9Ol6kiFX3S/KPicmml98IeJkh7tiHtUYu0Jz/SHkRJey4m7TGdsKeG0h5iinumKu0Rr7QnztIegEt7Hi+ueQBLC4yVaRiaYYhqPCGuLYe4piXSWrqIa3gjrh2QuGZJ5FZS3nZpbiWo0Za8NmQbTdqAq0nbF7i46IS8tSAEAAAAAElFTkSuQmCC" alt="">
                                                    </div>
                                                    <div class="outline-right dis-flex flex-dir-column flex-x-between">
                                                        <div style="color: rgb(102, 102, 102); font-size: 1rem;">新增用户数</div>
                                                        <div style="color: rgb(51, 51, 51); font-size: 2rem;"><?=  $recentStatisticsInfo['today']['register_customer_count'] ?></div>
                                                        <div style="color: rgb(153, 153, 153); font-size: 1rem;">
                                                            昨日：<?=  $recentStatisticsInfo['yestday']['register_customer_count'] ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="widget-4 am-u-sm-6 am-u-md-6 am-u-lg-3">
                                                <div class="widget-outline dis-flex flex-dir-column flex-x-between">
                                                    <div style="color: rgb(102, 102, 102); font-size: 1rem;">下单用户数</div>
                                                    <div style="color: rgb(51, 51, 51); font-size: 2rem;"><?=  $recentStatisticsInfo['today']['order_customer_count'] ?></div>
                                                    <div style="color: rgb(153, 153, 153); font-size: 1rem;">
                                                        昨日：<?=  $recentStatisticsInfo['yestday']['order_customer_count'] ?></div>
                                                </div>
                                            </div>
                                             <div style="clear:both"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>      
                        
                            <?php $day = 31;  // 获取三个月的数据?>
                            
                            <?php list($orderAmount, $orderCount) = Yii::$service->order->getPreMonthOrder($day); ?>
                            <div class="widget" style="padding:50px 5px; 100px">
                                <div style="padding-left:100px;font-size:16px;">
                                    <?= Yii::$service->page->translate->__('Order amount trend for the last month (base currency)'); ?>
                                </div>
                                <?= Yii::$service->helper->echart->getLine($orderAmount, true); ?>
                            </div>
                            <br/>
                            
                            <div class="widget"  style="padding:50px 5px; 100px">
                                <div style="padding-left:100px;font-size:16px;">
                                    <?= Yii::$service->page->translate->__('Trends in the number of orders in the last month'); ?>
                                </div>
                                <?= Yii::$service->helper->echart->getLine($orderCount, true); ?>
                            </div>
                            <br/>
                            <div class="widget"  style="padding:50px 5px; 100px">
                                <div style="padding-left:100px;font-size:16px;">
                                    <?= Yii::$service->page->translate->__('Number of registered users in the last month'); ?>
                                </div>
                                <?php $customerRegisterCount = Yii::$service->customer->getPreMonthCustomer($day); ?>
                                <?= Yii::$service->helper->echart->getLine($customerRegisterCount, false); ?>
                            </div> 
                        <br/><br/><br/><br/><br/><br/><br/>
					</div>
				</div>
			</div>
		</div>
	</div>
<footer class="footer">
    <div class="container">
        <div style="position:absolute;z-index: 99999;" id="footer">
            © 2015-2019
            <a style="text-decoration:none" href="http://www.fecmall.com" target="_blank">
                <?= Yii::$service->page->translate->__('Fecmall Team'); ?> - www.fecmall.com
            </a>
            <span style="padding-left:14px">
                当前版本：<a  target="_blank" href="https://github.com/fecshop/yii2_fecshop/releases"><?= Yii::$service->helper->getVersion()  ?></a>
            </span>
        </div>
    </div>
</footer>
<?php $this->endBody() ?>
<script> 
　$(document).ready(function(){
        $(".store_langs").change(function(){
            $langCode = $(this).val();
            $.ajax({
                url:'<?= Yii::$service->url->getUrl('fecadmin/login/changelang')  ?>',
                async:true,
                timeout: 80000,
                dataType: 'json', 
                type:'get',
                data:{
                    'lang':$langCode,
                },
                success:function(data, textStatus){
                    if (data.status == "success"){
                        window.location.reload();
                    } else {
                        
                    }
                },
                error:function(){
                    alert('<?= Yii::$service->page->translate->__('change language error'); ?>');
                }
            });
        });    
    });  
</script> 
                    
 <style>
.row {
    margin-right: -10px;
    margin-left: -10px;
}

.am-margin-bottom {
    margin-bottom: 1.6rem;
}

.widget-4 {
    width: 23.5%;
    float: left;
    padding: 0.5%;
}

.page-home .widget {
    padding: 10px 20px 25px;
}
.widget {
    width: 100%;
    min-height: 148px;
    border-radius: 0;
    position: relative;
    padding: 10px 20px 13px;
    background-color: #fff;
    color: #333;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}
.am-cf:after, .am-cf:before {
    content: " ";
    display: table;
}

.page-home .widget .widget-head {
    margin-top: 0;
    margin-bottom: 10px;
    border-bottom: none;
}
.widget-head {
    width: 100%;
    padding: 12px 20px;
    border-bottom: 1px solid #eef1f5;
    margin-top: 10px;
    margin-bottom: 20px;
}

.widget-head .widget-title {
    position: relative;
    font-size: 15px;
    color:#555;
}

.widget-head .widget-title::before {
    content: '';
    position: absolute;
    width: 4px;
    height: 14px;
    background: #00aeff;
    top: 0px;
    left: -12px;
}

.page-home .widget .widget-body {
    padding: 0;
   
}

.card-body{position: relative;}
.widget-body {
    padding: 0 15px;
    width: 100%;
}
.page-home .widget-card.card__blue {
    background: linear-gradient(-125deg, #57bdbf, #2f9de2);
}
.page-home .widget-card {
    min-height: 174px;
    color: #ffffff;
    padding: 12px 17px 12px 22px;
    margin-bottom: 20px;
}

.page-home .widget-card .card-header {
    position: relative;
    display: block;
    padding-top: 18px;
    color: #fff;
    font-size: 1.4rem;
    text-transform: uppercase;
    margin-bottom: 8px;
}
.page-home .widget-card .card-value {
    position: relative;
    font-weight: 300;
    display: block;
    color: #fff;
    font-size: 46px;
    line-height: 46px;
    margin-bottom: 8px;
}
.page-home .widget-card .card-description {
    position: relative;
    display: block;
    font-size: 1.2rem;
    line-height: 1.2rem;
    padding-top: 8px;
    color: rgba(255, 255, 255, 0.88);
}
.page-home .widget-card .card-icon {
    position: absolute;
    right: 30px;
    top: 24px;
    font-size: 70px;
    color: rgba(255, 255, 255, 0.12);
}
.page-home .widget-card.card__red {
    background: linear-gradient(-125deg, #ff7d7d, #fb2c95);
}
.page-home .widget-card.card__violet {
    background: linear-gradient(-113deg, #c543d8, #925cc3);
}
.page-home .widget-card.card__primary {
    background: linear-gradient(-141deg, #ecca1b, #f39526);
}
.tabsPage .tabsPageContent {
    border-color: #b8d0d6;
    background: #f1f1f1;
    padding: 10px 10px;
}




.page-home .widget-outline {
    flex-basis: 50%;
    height: 117px;
    padding: 20px;
    margin-bottom: 20px;
    box-sizing: border-box;
}
.page-home .widget-outline .outline-left {
    margin-right: 30px;
}

.page-home .widget-outline .outline-left img {
    width: 58px;
    height: 58px;
}

.page-home .widget-outline .outline-right {
    flex-basis: 50%;
}
.flex-x-between {
    -webkit-box-pack: justify;
    -webkit-justify-content: space-between;
    -ms-flex-pack: justify;
    justify-content: space-between;
}
.flex-dir-column {
    -webkit-box-orient: vertical;
    -webkit-flex-direction: column;
    -ms-flex-direction: column;
    flex-direction: column;
}

.dis-flex {
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
}
</style> 

</body>
</html>
<?php $this->endPage() ?>
