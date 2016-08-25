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
<div class="top_menu">
	<nav id="nav">
		<?php if(is_array($categoryArr) && !empty($categoryArr)){ ?>
			<ul class="clearfix">
			<?php foreach($categoryArr as $category1){ ?>
				<li>		
				<a href="<?= $category1['url'] ?>" class="nav_t"><?= $category1['name'] ?></a>	
				<?php $childMenu1 = $category1['childMenu'];   ?>
				<?php if(is_array($childMenu1) && !empty($childMenu1)){ ?>
					<div class="sub_menu big_sub_menu clearfix">
						<div class="trends_item clearfix">
							<?php foreach($childMenu1 as $category2){ ?>
								<dl>
									<dt><a href="<?= $category2['url'] ?>"><?= $category2['name'] ?></dt>
									<?php $childMenu2 = $category2['childMenu'];   ?>
									<?php if(is_array($childMenu2) && !empty($childMenu2)){ ?>
										<?php foreach($childMenu2 as $category3){ ?>
											<dd><a href="<?= $category3['url'] ?>"><?= $category3['name'] ?></a></dd>
										<?php } ?>
									<?php } ?>
								</dl>
							<?php } ?>
						</div>
						<div class="trends_img">
							<?= $category1['menu_custom'];  ?>
							
						</div>
					</div>
				<?php } ?>
				</li>
			<?php } ?>
			</ul>
		<?php } ?>
		
		<div class="nav_fullbg" style="display: none;"></div>
		<div class="navmask" style="display: none;"></div>
	</nav>
</div>
	