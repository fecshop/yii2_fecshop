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
				<?=  Yii::$service->cms->staticblock->getStoreContentByIdentify('social','appfront') ?>
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
				<?=  Yii::$service->cms->staticblock->getStoreContentByIdentify('links_and_account','appfront') ?>
				<div class="clear"></div>
			</div>
		</div>
	</div>
	<div class="footer-bottom">
		<?=  Yii::$service->cms->staticblock->getStoreContentByIdentify('copy_right','appfront') ?>
	</div>
</footer>
	