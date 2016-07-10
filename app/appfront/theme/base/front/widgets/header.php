<div id="top_nav">
	<input type="hidden" class="baseurl" value="<?= $baseurl ?>" />
	<div class="top_nav_inner">	    
		<div class="top_nav_left">
		
			<dl class="top_lang">
				<dt><span class="current_lang" rel="<?= $currentStore ?>"><?= $currentStoreLang ?></span></dt>
				<dd class="lang_list">
					<ul>
						<?php foreach($stores as $store=> $langName){   ?>
							<li  class="store_lang"  rel="<?= $store ?>"><a href="javascript:void(0)"><?= $langName ?></a></li>
						<?php } ?>
						</ul>
				</dd>
			</dl>
			<!-- ±ÒÖÖÑ¡Ôñ -->
			<dl class="top_currency">
				<dt><span class="current_currency"><label><?= $currency['symbol'] ?></label><?= $currency['code'] ?></span></dt>
				<dd class="currency_list">
					<ul>
					
					<?php foreach($currencys as $c){    ?>
						<li rel="<?= $c['code'] ?>"><label><?= $c['symbol'] ?></label><?= $c['code'] ?></li>
					<?php } ?>							
					</ul>
				</dd>
			</dl>
		</div>
		
		<div class="top_nav_right">
			<div class="login-text t_r">
				<span id="js_isNotLogin">
					<a href="" rel="nofollow">Sign in</a>
				</span>
				<span class="join">
					<a href="">/ Join</a>
				</span>
			</div>
			<dl class="top_account t_r">
				<dt>
					<a href="" rel="nofollow" class="mycoount"></a>
				</dt>
				<dd style="">
					<ul>
						<li><a href="" rel="nofollow">My S Points</a></li>
						<li><a href="" rel="nofollow">My Orders</a></li>
						<li><a href="" rel="nofollow">My Favorites</a></li>
						<li><a href="" rel="nofollow">Personal Data</a></li>
						<li><a href="" rel="nofollow">Order Review</a></li>
					</ul>
				</dd>
			</dl>
			<div class="mywish t_r">
				<a href="">
					<span class="mywishbg"></span>
				</a>
				<span class="mywish-text" id="js_favour_num">0</span>
			</div>
			<div class="mycart t_r">
				<a href="">
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
				<form method="post" name="searchFrom" class="js_topSeachForm">
					<div class="top_seachBox">
						<div class="searchInput fl">
							<input type="text" autocomplete="off" value="" maxlength="150" placeholder="Products keyword" class="searchArea js_k2 ac_input" name="k1">
						</div>
						<button class="fl js_topSearch seachBtn" type="submit"><span class="t_hidden">search</span></button>
						<input type="hidden" class="category" value="0" name="category">
											</div><!--end .top_seachBox-->
				</form>
			</div>

			<div class="logo"><a titel="SammyDress logo" href="http://www.sammydress.com/"><img width="254" height="40" src="http://css.sammydress.com/imagecache/S1/images/styleimg/slogo2.gif"></a></div>
		</div><!--end .top_header-->

		</div><!--end .top_main_inner-->
</div>
		