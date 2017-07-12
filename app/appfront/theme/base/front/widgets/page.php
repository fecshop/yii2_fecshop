<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<div class="tb_rg">
	<?php  if($prevPage):  ?>
		<a href="<?= $prevPage['url']['url'] ?>"><</a>
	<?php else:  ?>
		<span><</span>
	<?php endif;  ?>	
	<?php if($firstSpaceShow):  ?>
		<a href="<?= $firstSpaceShow['url']['url'] ?>"><?= $firstSpaceShow['p'] ?></a>
	<?php endif;  ?>	
	<?= $hiddenFrontStr ?>		
	<?php  if(!empty($frontPage )): ?>
		<?php foreach($frontPage as $page): ?>
			<a href="<?= $page['url']['url'] ?>"><?= $page['p'] ?></a>
		<?php endforeach;  ?>	
	<?php endif;  ?>	
	
	<?php if($currentPage): ?>
		<span class="current" ><?= $currentPage['p'] ?></span>
	<?php endif;  ?>	
	
	<?php if(!empty($behindPage )): ?>
		<?php foreach($behindPage as $page): ?>
			<a href="<?= $page['url']['url'] ?>"><?= $page['p'] ?></a>
		<?php endforeach;  ?>	
	<?php endif;  ?>		
		
	<?= $hiddenBehindStr ?>			
	<?php if($lastSpaceShow): ?>
		<a href="<?= $lastSpaceShow['url']['url'] ?>"><?= $lastSpaceShow['p'] ?></a>
	<?php endif;  ?>	
	<?php if($nextPage):  ?>
		<a href="<?= $nextPage['url']['url'] ?>">></a>
	<?php else:  ?>
		<span>></span>
	<?php endif;  ?>	
</div>
	