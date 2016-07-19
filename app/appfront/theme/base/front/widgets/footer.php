<footer id="footer" class="footer-container ">
				<div class="footer-top sidebar">
					<div class="container">
						<div class="row">
						
							<div class="col-xs-12 col-sm-6 col-md-3 newsletter widget social">
							<header>
								<h3 class="title">
								
								<?= Yii::$app->page->translate->__('Follow Us'); ?>
								</h3>
							</header>
							<p>
								<?= Yii::$app->page->translate->__('Follow us in social media'); ?>
							</p>
							<a class="sbtnf sbtnf-rounded color color-hover icon-facebook" href="https://www.facebook.com/" rel="nofollow" target="_blank"></a> 
							<a class="sbtnf sbtnf-rounded color color-hover icon-twitter" href="https://twitter.com/" rel="nofollow" target="_blank"></a> 
							<a class="sbtnf sbtnf-rounded color color-hover icon-dribbble" href="http://pinterest.com/" rel="nofollow" target="_blank"></a> 
							<a class="sbtnf sbtnf-rounded color color-hover icon-flickr" href="http://www.google.com/" rel="nofollow" target="_blank"></a>			

							</div>
							<div class="col-xs-12 col-sm-6 col-md-3 newsletter widget links">
								<header>
									<h3 class="title">
										<?= Yii::$app->page->translate->__('Newsletter'); ?>
									</h3>
								</header>
								<p>
									<?= Yii::$app->page->translate->__('Sign up for newsletter'); ?>
								</p>
								<form action="http://www.intosmile.com/customer/contacts/save?uenc=aHR0cDovL3d3dy5pbnRvc21pbGUuY29tLw==" method="post" id="newsletter-validate-detail">
									<input type="hidden" name="_csrf" value="YmZEWnpqbXYTA3ZoFSgCByYEAA8rLRUbJh4uIBczWxQxAQ0NSiU8Gw==" class="thiscsrf">
									<div class="newsletter-container">
										<input type="text" name="email" id="newsletter" placeholder="<?= Yii::$app->page->translate->__('Enter your email adress'); ?>..." title="Sign up for our newsletter" class="input-text form-control required-entry validate-email input-block-level">
										<button type="submit" title="Subscribe" class="newsletter-button">
										JOIN</button>
									</div>
								</form>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-3 widget links">
								<header>
									<h3 class="title">
										<?= Yii::$app->page->translate->__('General Links'); ?>
									</h3>
								</header>
								<nav>
									<ul>
										<li class="first"><a href="http://www.intosmile.com/about-us" title="About us" rel="nofollow"><?= Yii::$app->page->translate->__('About Us'); ?></a></li>
										<li><a href="http://www.intosmile.com/privacy-policy" title="Privacy Policy" rel="nofollow"><?= Yii::$app->page->translate->__('Privacy Policy'); ?></a></li>
										<li><a href="http://www.intosmile.com/return-policy" title="Return Policy" rel="nofollow"><?= Yii::$app->page->translate->__('Return Policy'); ?></a></li>
										<li><a href="http://www.intosmile.com/faq" title="FAQ" rel="nofollow"><?= Yii::$app->page->translate->__('FAQ'); ?></a></li>
										<li class=" last"><a href="http://www.intosmile.com/contacts" title="Contact Us" rel="nofollow"><?= Yii::$app->page->translate->__('Contact Us'); ?></a></li>
									</ul>
								</nav>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-3 newsletter widget links">
								<header>
									<h3 class="title"><?= Yii::$app->page->translate->__('My Account'); ?></h3>
								</header>
								<ul>
									<li><a href="http://www.intosmile.com/customer/account/index" rel="nofollow"><?= Yii::$app->page->translate->__('My Account'); ?></a></li>
									<li><a href="http://www.intosmile.com/customer/order" rel="nofollow"><?= Yii::$app->page->translate->__('My Order'); ?></a></li>
									<li><a href="http://www.intosmile.com/customer/account/productreview" rel="nofollow"><?= Yii::$app->page->translate->__('My Reviews'); ?></a></li>
									<li><a href="http://www.intosmile.com/favorite/product" rel="nofollow"><?= Yii::$app->page->translate->__('My Favorite'); ?></a></li>
									<li><a href="http://www.intosmile.com/sitemap.xml"><?= Yii::$app->page->translate->__('Site Map'); ?></a></li>
								</ul>
							</div>
							<div class="clear"></div>
						</div>
					</div>
				</div>
				
				<div class="footer-bottom">
					<div class="container">
						<img src="./images/pp.png"  />
					</div>
					<div class="container">
						<div id="copy">Copyright Notice &copy;2016 FecShop.com All rights reserved .</div>
					</div>
				</div>
				
			
		</footer>
	