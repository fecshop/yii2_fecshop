<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<div id="top_nav">
	<input type="hidden" class="currentBaseUrl" value="<?= $currentBaseUrl ?>" />
	<input type="hidden" class="logoutUrl" value="<?= $logoutUrl ?>" />
	<input type="hidden" class="logoutStr" value="<?= Yii::$service->page->translate->__('Logout'); ?>" />
	<input type="hidden" class="welcome_str" value="<?= Yii::$service->page->translate->__('Welcome!'); ?>" />
	<div class="top_nav_inner">	    
		<div class="top_nav_left">
			<dl class="top_lang">
				<dt><span class="current_lang" rel="<?= $currentStore ?>"><?= $currentStoreLang ?></span></dt>
				<dd class="lang_list">
					<ul>
						<?php foreach($stores as $store=> $langName):   ?>
							<li  class="store_lang"  rel="<?= $store ?>"><a href="javascript:void(0)"><?= $langName ?></a></li>
						<?php endforeach; ?>
						</ul>
				</dd>
			</dl>
			<!-- 币种选择 -->
			<dl class="top_currency">
				<dt><span class="current_currency"><label><?= $currency['symbol'] ?></label><?= $currency['code'] ?></span></dt>
				<dd class="currency_list">
					<ul>
					
					<?php foreach($currencys as $c):    ?>
						<li rel="<?= $c['code'] ?>"><label><?= $c['symbol'] ?></label><?= $c['code'] ?></li>
					<?php endforeach; ?>							
					</ul>
				</dd>
			</dl>
		</div>
		<div class="top_nav_right">
			<div class="login-text t_r">
				<span id="js_isNotLogin">
					<a href="<?= Yii::$service->url->getUrl('customer/account/login') ?>" rel="nofollow"><?= Yii::$service->page->translate->__('Sign In / Join Free'); ?></a>
				</span>
			</div>
			<dl class="top_account t_r">
				<dt>
					<a href="<?= Yii::$service->url->getUrl('customer/account') ?>" rel="nofollow" class="mycoount"></a>
				</dt>
				<dd style="">
					<ul>
						<li><a href="<?= Yii::$service->url->getUrl('customer/account') ?>" rel="nofollow"><?= Yii::$service->page->translate->__('My Account'); ?></a></li>
						<li><a href="<?= Yii::$service->url->getUrl('customer/order') ?>" rel="nofollow"><?= Yii::$service->page->translate->__('My Orders'); ?></a></li>
						<li><a href="<?= Yii::$service->url->getUrl('customer/productfavorite') ?>" rel="nofollow"><?= Yii::$service->page->translate->__('My Favorites'); ?></a></li>
						<li><a href="<?= Yii::$service->url->getUrl('customer/productreview') ?>" rel="nofollow"><?= Yii::$service->page->translate->__('My Review'); ?></a></li>
					</ul>
				</dd>
			</dl>
			<div class="mywish t_r">
				<a href="<?= Yii::$service->url->getUrl('customer/productfavorite') ?>">
					<span class="mywishbg"></span>
				</a>
				<span class="mywish-text" id="js_favour_num">0</span>
			</div>
			<div class="mycart t_r">
				<a href="<?= Yii::$service->url->getUrl('checkout/cart') ?>">
					<span class="mycartbg" id="js_topBagWarp"></span>
				</a>
				<span class="mycart-text" id="js_cart_items">0</span>
			</div>
		</div>
	</div><!--end .top_nav_inner-->
</div><!--end #top_nav-->

<div id="top_main">
	<div class="top_main_inner pr">
		<div class="top_header clearfix">
			<div class="topSeachForm">
				<?= Yii::$service->page->widget->render('base/topsearch',$this); ?>
			</div>
			<div class="logo"><a titel="fecshop logo" href="<?= $homeUrl ?>" style="">
				<img src="<?= Yii::$service->image->getImgUrl('appfront/custom/logo.png'); ?>"  />
			</a></div>
		</div><!--end .top_header-->
    </div><!--end .top_main_inner-->
</div>
		