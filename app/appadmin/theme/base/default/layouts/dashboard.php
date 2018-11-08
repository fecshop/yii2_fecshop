<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use fecadmin\myassets\AppAsset;
use common\widgets\Alert;
use fec\helpers\CUrl;
use fecadmin\views\layouts\Head;
AppAsset::register($this);
$publishedPath = $this->assetManager->publish('@fecadmin/myassets/dwz_jui-master/dwz.frag.xml');
?>
<?php
// fecshop 多模板机制的js和css部分
$jsOptions = [
	# js config 1
	[
		'js'	=>[
			'js/appadmin.js',
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
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>"  xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?= Html::csrfMetaTags() ?>
    <title><?= $this->title ? Html::encode($this->title) : 'Fecshop 后台管理系统' ?></title>
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
		<div id="header">
			<div class="headerNav">
				<a target="_blank" class="logo" href="http://www.fecshop.com">FECSHOP</a>
				
                  <?php $currentLangCode = Yii::$service->admin->getCurrentLangCode() ?>
                  <?php $langArr = Yii::$service->admin->getLangArr() ?>
                  <select class="store_langs" style="font-size:10px;">
                       <?php foreach ($langArr as $code => $name): ?>
                           <option  value="<?= $code ?>" <?= ($code == $currentLangCode) ? 'selected="selected"' : ''  ?>>
                               <?= $name ?>
                           </option>
                      <?php endforeach; ?>
                   </select>
				<a style="color:#fff; display: block; height: 21px;position: absolute; right: 10px;top: 18px;z-index: 31;" 
				href="<?= Yii::$service->url->getUrl("fecadmin/logout") ?>">
					<?= Yii::$service->page->translate->__('Logout'); ?>
				</a>
			</div>
		</div>
		<div id="leftside">
			<div id="sidebar_s">
				<div class="collapse">
					<div class="toggleCollapse"><div></div></div>
				</div>
			</div>
			<div id="sidebar">
				<div class="toggleCollapse"><h2><?= Yii::$service->page->translate->__('Main Menu'); ?></h2><div><?= Yii::$service->page->translate->__('Pull Back'); ?></div></div>

				<div class="accordion" fillSpace="sidebar">
					<?= Yii::$service->admin->menu->getLeftMenuHtml();  ?>
				</div>
			</div>
		</div>
		<div id="container">
			<div id="navTab" class="tabsPage">
				<div class="tabsPageHeader">
					<div class="tabsPageHeaderContent"><!-- 显示左右控制时添加 class="tabsPageHeaderMargin" -->
						<ul class="navTab-tab">
							<li tabid="main" class="main"><a href="javascript:;"><span><span class="home_icon"><?= Yii::$service->page->translate->__('My Main Page'); ?></span></span></a></li>
						</ul>
					</div>
					<div class="tabsLeft">left</div><!-- 禁用只需要添加一个样式 class="tabsLeft tabsLeftDisabled" -->
					<div class="tabsRight">right</div><!-- 禁用只需要添加一个样式 class="tabsRight tabsRightDisabled" -->
					<div class="tabsMore">more</div>
				</div>
				<ul class="tabsMoreList">
					<li><a href="javascript:;"><?= Yii::$service->page->translate->__('My Main Page'); ?></a></li>
				</ul>
				<div class="navTab-panel tabsPageContent layoutBox">
					<div class="page unitBox">
						<div class="accountInfo">
							<p><span><?= Yii::$service->page->translate->__('Hello'); ?>: <?= \fec\helpers\CUser::getCurrentUsername();   ?></span></p>
                        </div>
                        
                        <?php $day = 31;  // 获取三个月的数据?>
                        
                        <br/><br/><br/><br/>
                        
                        <?php list($orderAmount, $orderCount) = Yii::$service->order->getPreMonthOrder($day); ?>
                        <div style="padding-left:100px;font-size:16px;">
                            <?= Yii::$service->page->translate->__('Order amount trend for the last month (base currency)'); ?>
                        </div>
                        <?= Yii::$service->helper->echart->getLine($orderAmount, true); ?>
                        
                        <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
                        <div style="padding-left:100px;font-size:16px;">
                            <?= Yii::$service->page->translate->__('Trends in the number of orders in the last month'); ?>
                        </div>
                        <?= Yii::$service->helper->echart->getLine($orderCount, true); ?>
                        
						<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
                        
                        <div style="padding-left:100px;font-size:16px;">
                            <?= Yii::$service->page->translate->__('Number of registered users in the last month'); ?>
                        </div>
                        <?php $customerRegisterCount = Yii::$service->customer->getPreMonthCustomer($day); ?>
                        <?= Yii::$service->helper->echart->getLine($customerRegisterCount, false); ?>
                        
                        <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
					</div>
				</div>
			</div>
		</div>
	</div>
<footer class="footer">
    <div class="container">
        <div style="position:absolute;z-index: 99999;" id="footer">
            © 2015-2018 
            <a style="text-decoration:none" href="http://www.fecshop.com" target="_blank">
                <?= Yii::$service->page->translate->__('Fecshop Team'); ?> - www.fecshop.com
            </a>
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
                    alert('<?= Yii::$service->page->translate->__('loading category information error'); ?>');
                }
            });
        });    
    
    });  
</script> 
</body>
</html>
<?php $this->endPage() ?>
