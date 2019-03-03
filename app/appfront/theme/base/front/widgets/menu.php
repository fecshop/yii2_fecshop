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
		<?php if(is_array($categoryArr) && !empty($categoryArr)): ?>
			<ul class="clearfix">
			<?php foreach($categoryArr as $category1): ?>
				<li>		
				<a href="<?= $category1['url'] ?>" class="nav_t"><?= $category1['name'] ?></a>	
				<?php $childMenu1 = isset($category1['childMenu']) ? $category1['childMenu'] : null;   ?>
				<?php if(is_array($childMenu1) && !empty($childMenu1)): ?>
					<div class="sub_menu big_sub_menu clearfix">
						<div class="trends_item clearfix">
							<?php foreach($childMenu1 as $category2): ?>
								<dl>
									<dt><a href="<?= $category2['url'] ?>"><?= $category2['name'] ?></dt>
									<?php $childMenu2 = isset($category2['childMenu']) ? $category2['childMenu'] : null;   ?>
									<?php if(is_array($childMenu2) && !empty($childMenu2)): ?>
										<?php foreach($childMenu2 as $category3): ?>
											<dd><a href="<?= $category3['url'] ?>"><?= $category3['name'] ?></a></dd>
										<?php endforeach; ?>
									<?php endif; ?>
								</dl>
							<?php endforeach; ?>
						</div>
						<div class="trends_img">
							<?= isset($category1['menu_custom']) ? $category1['menu_custom'] : '';  ?>
						</div>
					</div>
				<?php endif; ?>
				</li>
			<?php endforeach; ?>
			</ul>
		<?php endif; ?>
		<div class="nav_fullbg" style="display: none;"></div>
		<div class="navmask" style="display: none;"></div>
	</nav>
</div>
	