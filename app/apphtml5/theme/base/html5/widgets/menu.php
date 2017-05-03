<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<?php  //var_dump($categoryArr);
	
?>



		
		


<div class="panel-overlay">
</div>
<!-- Left Panel with Reveal effect -->
<div class="panel panel-left panel-reveal theme-dark" id='panel-left-menu'>
	<div class="content-block">
		<div class="searchbar row">
			
			<div class="search-input">
			  <label class="icon icon-search" for="search"></label>
			  <input type="search" id='search' placeholder='输入关键字...'/>
			</div>
		</div>
		<div class="category_menu">
			<?php if(is_array($categoryArr) && !empty($categoryArr)){ ?>
				<ul>
				<?php foreach($categoryArr as $category1){ ?>
					<li class="item-content">
						<div class="item-title">
							<a href="<?= $category1['url'] ?>" class="nav_t" external><?= $category1['name'] ?></a>	
						</div>
						<?php $childMenu1 = $category1['childMenu'];   ?>
						<?php if(is_array($childMenu1) && !empty($childMenu1)){ ?>
							<ul>
								<?php foreach($childMenu1 as $category2){ ?>
									<span class="icon icon-right"></span>
									<li class="item-content">
										<div class="item-title">
											<a href="<?= $category2['url'] ?>" external>
												<?= $category2['name'] ?>
											</a>
										</div>
										<?php $childMenu2 = $category2['childMenu'];   ?>
										<?php if(is_array($childMenu2) && !empty($childMenu2)){ ?>
											<ul>
											<?php foreach($childMenu2 as $category3){ ?>
												<span class="icon icon-right"></span>
												<li class="item-content">
													<div class="item-title"><a href="<?= $category3['url'] ?>" external><?= $category3['name'] ?></a></div>
												</li>
												
											<?php } ?>
											</ul>
										<?php } ?>
									</li>
								<?php } ?>
							</ul>
							<?php //echo $category1['menu_custom'];  ?>
									
						<?php } ?>
					</li>
				<?php } ?>
				</ul>
			<?php } ?>
		</div>
		
	</div>
  <div class="list-block">
	<!-- .... -->
  </div>
</div>

<div class="panel-overlay"></div>
<!-- Left Panel with Reveal effect -->
<div class="panel panel-left panel-reveal theme-dark" id='panel-left-account'>
	<div class="content-block">
		<div class="searchbar row">
			
			<div class="search-input">
			  <label class="icon icon-search" for="search"></label>
			  <input type="search" id='search' placeholder='输入关键字...'/>
			</div>
		</div>
		<div class="category_menu list-block">
			<ul>
				<li class="item-content">
					<div class="item-inner">
						<div class="item-title"><a href="<?= Yii::$service->url->homeUrl();  ?>" external>Home</a></div>
					</div>
				</li>
				<li class="item-content">
					<div class="item-inner">
						<div class="item-title"><a href="<?= Yii::$service->url->getUrl('customer/account/index'); ?>" external>Account</a></div>
					</div>
				</li>
				<li class="item-content">
					<div class="item-inner">
						<div class="item-title"><a href="<?= Yii::$service->url->getUrl('checkout/cart'); ?>" external>Shopping Cart</a></div>
					</div>
				</li>
				<li class="item-content">
					<div class="item-inner">
						<div class="item-title"><a href="<?= Yii::$service->url->getUrl('customer/order'); ?>" external>My Order</a></div>
					</div>
				</li>
				<li class="item-content">
					
					<div class="item-inner">
						<div class="item-title"><a href="<?= Yii::$service->url->getUrl('customer/productfavorite'); ?>" external>My Favorite</a></div>
					</div>
				</li>
				<li class="item-content">
					<div class="item-inner">
						<div class="item-title"><a href="<?= Yii::$service->url->getUrl('about-us'); ?>" external>About Us</a></div>
					</div>
				</li>
				<li class="item-content">
					<div class="item-inner">
						<div class="item-title"><a href="<?= Yii::$service->url->getUrl('customer/contacts'); ?>" external>Contact us</a></div>
					</div>
				</li>
				<li class="item-content">
					<div class="item-inner">
						<div class="item-title"><a href="<?= Yii::$service->url->getUrl('privacy-policy'); ?>" external>privacy policy</a></div>
					</div>
				</li>
				<li class="item-content">
					<div class="item-inner">
						<div class="item-title"><a href="<?= Yii::$service->url->getUrl('return-policy'); ?>" external>Return Policy</a></div>
					</div>
				</li>
			</ul>
		</div>
		
	</div>
  <div class="list-block">
	<!-- .... -->
  </div>
</div>