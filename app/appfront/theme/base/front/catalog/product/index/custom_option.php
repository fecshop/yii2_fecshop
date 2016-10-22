<?php  $custom_option = $parentThis['custom_option']; ?>
<?php	if(is_array($custom_option) && !empty($custom_option)){  ?>
<?php		$custom_option = \fec\helpers\CFunc::array_sort($custom_option,'sort_order','asc');  ?>
<?php		foreach($custom_option as $one){  ?>
<?php			$option_title 		= $one['title'];  ?>
<?php			$is_require = $one['is_require'];  ?>
<?php			$data 		= $one['data'];  ?>
<?php			if(is_array($data) && !empty($data)){ ?>
				<div class="one_option">
					<div class="one_option_title"><?= $option_title ?>:</div>
					<select class="custom_option <?= $title  ?> <?= $is_require ? 'required' : '' ?>" name="custom_option[<?= $title  ?>]">
<?php					$data = \fec\helpers\CFunc::array_sort($data,'sort_order','asc'); ?>
<?php					foreach($data as $one){ ?>
<?php						$title = $one['title']; ?>
<?php						$price = $one['price']; ?>
<?php						$val = Yii::$service->store->getStoreAttrVal($title,'title'); ?>
						<option value="<?= $val ?>"><?= $val ?></option>
<?php					} ?>
					</select>
					<div class="clear"></div>
				</div>
<?php			} ?>
<?php		}  ?>
<?php	}  ?>