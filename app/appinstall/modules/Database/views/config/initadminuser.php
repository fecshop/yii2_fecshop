<?php

use fec\helpers\CRequest;

?>
<h1>后台用户面密码初始化</h1>
<br/>

<?=  $errorInfo  ?>
<?=  $successInfo  ?>


<form action="" method="post">
    <?php echo CRequest::getCsrfInputHtml(); ?>
    <div class="form-group">
        <label for="name">超级管理员账户</label>
        <input type="text" class="form-control" value="<?= $editForm['username'] ? $editForm['username'] : 'admin' ?>"
               name="editForm[username]" placeholder="超级管理员账户">
    </div>

    <div class="form-group">
        <label for="name">超级账户密码</label>
        <input type="password" class="form-control" required value="<?= $editForm['password'] ?>"
               name="editForm[password]" placeholder="超级账户密码">
    </div>
    <button type="submit" class="btn btn-default">下一步</button>
</form>
