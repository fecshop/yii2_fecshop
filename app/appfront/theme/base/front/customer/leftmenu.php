<div class="col-left sidebar">
	<div class="block block-account">
		<div class="block-title">
			<strong><span>My Account</span></strong>
		</div>
		<div class="block-content">
			<ul>
				<?php  if(!empty($leftMenuArr) && is_array($leftMenuArr)){  ?>
					<?php foreach($leftMenuArr as $one){ ?>
					<li <?= $one['current'] ?>>
						<a href="<?= $one['url'] ?>"  ><?= $one['name'] ?></a>
					</li>
					<?php } ?>
				<?php } ?>	
			</ul>
		</div>
	</div>
</div>