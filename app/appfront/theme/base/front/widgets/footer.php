<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<footer id="footer" class="footer-container ">
	<div class="footer-top sidebar">
		<div class="container">
			<div class="row">
			
				<div class="col-xs-12 col-sm-6 col-md-3 newsletter widget social">
				<header>
					<h3 class="title">
					
					<?= Yii::$service->page->translate->__('Follow Us'); ?>
					</h3>
				</header>
				<p>
					<?= Yii::$service->page->translate->__('Follow us in social media'); ?>
				</p>
				<a class="sbtnf sbtnf-rounded color color-hover icon-facebook" href="https://www.facebook.com/" rel="nofollow" target="_blank"></a> 
				<a class="sbtnf sbtnf-rounded color color-hover icon-twitter" href="https://twitter.com/" rel="nofollow" target="_blank"></a> 
				<a class="sbtnf sbtnf-rounded color color-hover icon-dribbble" href="http://pinterest.com/" rel="nofollow" target="_blank"></a> 
				<a class="sbtnf sbtnf-rounded color color-hover icon-flickr" href="http://www.google.com/" rel="nofollow" target="_blank"></a>			

				</div>
				<div class="col-xs-12 col-sm-6 col-md-3 newsletter widget links">
					<header>
						<h3 class="title">
							<?= Yii::$service->page->translate->__('Newsletter'); ?>
						</h3>
					</header>
					<p>
						<?= Yii::$service->page->translate->__('Sign up for newsletter'); ?>
					</p>
					<form action="<?=  Yii::$service->url->getUrl('customer/newsletter') ?>" method="get" id="newsletter-validate-detail">
						<div class="newsletter-container">
							<input type="text" name="email" id="newsletter" placeholder="<?= Yii::$service->page->translate->__('Enter your email adress'); ?>..." title="Sign up for our newsletter" class="input-text form-control  validate-email input-block-level">
							<button type="submit" title="Subscribe" class="newsletter-button">
							JOIN</button>
						</div>
					</form>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-3 widget links">
					<header>
						<h3 class="title">
							<?= Yii::$service->page->translate->__('General Links'); ?>
						</h3>
					</header>
					<nav>
						<ul>
							<li class="first"><a href="<?= \Yii::$service->url->getUrl('about-us');  ?>" title="About us" rel="nofollow"><?= Yii::$service->page->translate->__('About Us'); ?></a></li>
							<li><a href="<?= \Yii::$service->url->getUrl('privacy-policy');  ?>" title="Privacy Policy" rel="nofollow"><?= Yii::$service->page->translate->__('Privacy Policy'); ?></a></li>
							<li><a href="<?= \Yii::$service->url->getUrl('return-policy');  ?>" title="Return Policy" rel="nofollow"><?= Yii::$service->page->translate->__('Return Policy'); ?></a></li>
							<li><a href="<?= \Yii::$service->url->getUrl('faq');  ?>" title="FAQ" rel="nofollow"><?= Yii::$service->page->translate->__('FAQ'); ?></a></li>
							<li class=" last"><a href="<?= \Yii::$service->url->getUrl('customer/contacts');  ?>" title="Contact Us" rel="nofollow"><?= Yii::$service->page->translate->__('Contact Us'); ?></a></li>
						</ul>
					</nav>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-3 newsletter widget links">
					<header>
						<h3 class="title"><?= Yii::$service->page->translate->__('My Account'); ?></h3>
					</header>
					<ul>
						<li><a href="<?= \Yii::$service->url->getUrl('customer/account/index');  ?>" rel="nofollow"><?= Yii::$service->page->translate->__('My Account'); ?></a></li>
						<li><a href="<?= \Yii::$service->url->getUrl('customer/order');  ?>" rel="nofollow"><?= Yii::$service->page->translate->__('My Order'); ?></a></li>
						<li><a href="<?= \Yii::$service->url->getUrl('customer/account/productreview');  ?>" rel="nofollow"><?= Yii::$service->page->translate->__('My Reviews'); ?></a></li>
						<li><a href="<?= \Yii::$service->url->getUrl('favorite/product');  ?>" rel="nofollow"><?= Yii::$service->page->translate->__('My Favorite'); ?></a></li>
						<li><a href="<?= \Yii::$service->url->getUrl('sitemap.xml');  ?>"><?= Yii::$service->page->translate->__('Site Map'); ?></a></li>
					</ul>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	</div>
	
	<div class="footer-bottom">
		<div class="container">
			<img src="<?= Yii::$service->image->getImgUrl('images/pp.png','appfront'); ?>" />
		</div>
		<div class="container">
			<div id="copy">Copyright Notice &copy;2016 FecShop.com All rights reserved .</div>
		</div>
	</div>
</footer>
	