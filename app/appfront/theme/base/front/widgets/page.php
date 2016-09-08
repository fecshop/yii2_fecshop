<div class="tb_rg">
	<?php  if($prevPage){  ?>
		<a href="<?= $prevPage['url']['url'] ?>"><</a>
	<?php }else{  ?>
		<span><</span>
	<?php }  ?>	
	<?php if($firstSpaceShow){  ?>
		<a href="<?= $firstSpaceShow['url']['url'] ?>"><?= $firstSpaceShow['p'] ?></a>
	<?php }  ?>	
	<?= $hiddenFrontStr ?>		
	<?php  if(!empty($frontPage )){ ?>
		<?php foreach($frontPage as $page){ ?>
			<a href="<?= $page['url']['url'] ?>"><?= $page['p'] ?></a>
		<?php }  ?>	
	<?php }  ?>	
	
	<?php if($currentPage){ ?>
		<span class="current" ><?= $currentPage['p'] ?></span>
	<?php }  ?>	
	
	<?php  if(!empty($behindPage )){ ?>
		<?php foreach($behindPage as $page){ ?>
			<a href="<?= $page['url']['url'] ?>"><?= $page['p'] ?></a>
		<?php }  ?>	
	<?php }  ?>		
		
	<?= $hiddenBehindStr ?>			
	<?php if($lastSpaceShow){ ?>
		<a href="<?= $lastSpaceShow['url']['url'] ?>"><?= $lastSpaceShow['p'] ?></a>
	<?php }  ?>	
	<?php  if($nextPage){  ?>
		<a href="<?= $nextPage['url']['url'] ?>">></a>
	<?php }else{  ?>
		<span>></span>
	<?php }  ?>	
	</div>
	