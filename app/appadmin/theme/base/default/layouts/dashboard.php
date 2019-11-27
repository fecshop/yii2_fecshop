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
	<link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" >
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
				<div class="toggleCollapse" style="background:#20222A !important;overflow: visible;">
					<h2 style="font-size:20px;font-weight:100;    padding-top: 10px;padding-bottom: 10px;"><?= Yii::$service->page->translate->__('Fecmall'); ?></h2>
					<div><i class="fa fa-list"></i></div>
                    
				</div>

				<div class="accordion" fillSpace="sidebar">
                    
					<?= Yii::$service->admin->menu->getLeftMenuHtml();  ?>
				</div>
			</div>
		</div>
		<div id="container">
			<div id="navTab" class="tabsPage" style="position: relative;">
				<div class="tabsPageHeader">
					<div class="tabsPageHeaderContent"><!-- 显示左右控制时添加 class="tabsPageHeaderMargin" -->
						<ul class="navTab-tab">
							<li tabid="main" class="main"><a href="javascript:;"><span><span class="home_icon"><?= Yii::$service->page->translate->__('My Main Page'); ?></span></span></a></li>
						</ul>
					</div>
					
				</div>
                
                <div class="headerNav" style="position: absolute;right: 25px; top: 0; width: 200px;  height: 30px; z-index: 999999;">
				
                      <?php $currentLangCode = Yii::$service->admin->getCurrentLangCode() ?>
                      <?php $langArr = Yii::$service->admin->getLangArr() ?>
                      <select class="store_langs" style="font-size:10px;">
                           <?php foreach ($langArr as $code => $name): ?>
                               <option  value="<?= $code ?>" <?= ($code == $currentLangCode) ? 'selected="selected"' : ''  ?>>
                                   <?= $name ?>
                               </option>
                          <?php endforeach; ?>
                       </select>
                    <a style="color:#777; display: block; height: 21px;position: absolute; right: 5px;top: 10px;z-index: 31;"
                       doPost
                       href='javascript:doPost("<?= Yii::$service->url->getUrl("fecadmin/logout") ?>", {"<?= CRequest::getCsrfName() ?>": "<?= CRequest::getCsrfValue() ?>", "islogout": "1"}) '>
                        <?= Yii::$service->page->translate->__('Logout'); ?>
                    </a>
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
            © 2015-2019
            <a style="text-decoration:none" href="http://www.fecmall.com" target="_blank">
                <?= Yii::$service->page->translate->__('Fecmall Team'); ?> - www.fecmall.com
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
                    alert('<?= Yii::$service->page->translate->__('change language error'); ?>');
                }
            });
        });    
    
    });  
</script> 
</body>
</html>
<?php $this->endPage() ?>
