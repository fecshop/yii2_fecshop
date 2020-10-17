<?php  if($prevPage):  ?>
    <a href="<?= $prevPage['url']['url'] ?>">
        <span class="page-small-link font-small pre-page-js">
            <i class="nc-icon nc-icon-angle-left d-inline-block"></i>
        </span>
    </a>
<?php else:  ?>
    <a href="javasript:void()">
        <span class="page-small-link font-small pre-page-js disabled">
            <i class="nc-icon nc-icon-angle-left d-inline-block"></i>
        </span>
    </a>
<?php endif;  ?>	

<span class="text-primary mr-lg-5 current-page-js"><?= $pageNum ?></span>/ 
<span class="total-page-js"><?= $pageCount ?></span> 
<?php if($nextPage):  ?>
    <a href="<?= $nextPage['url']['url'] ?>">
        <span class="page-small-link font-small next-page-js">
            <i class="nc-icon nc-icon-angle-right d-inline-block"></i>
        </span>
    </a>
<?php else:  ?>
    <a href="javascript:void()">
        <span class="page-small-link font-small next-page-js disabled">
            <i class="nc-icon nc-icon-angle-right d-inline-block"></i>
        </span>
    </a>
<?php endif;  ?>
        