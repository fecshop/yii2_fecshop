<?php

use fec\helpers\CRequest;

?>
<h1>Mysql数据库配置</h1>
<br/>

<?= $errorInfo ?>


<form action="<?=  Yii::$app->homeUrl . '/database/config/index?database=1' ?>" method="post">
    <?php echo CRequest::getCsrfInputHtml(); ?>
    
    <div class="form-group">
        <label for="name">Mysql数据库Host</label>
        <input type="text" class="form-control" value="<?= $editForm['host'] ? $editForm['host'] : '127.0.0.1' ?>"
               name="editForm[host]" placeholder="Mysql数据库Host，同服务器可填写 127.0.0.1 或 localhost">
    </div>

    <div class="form-group">
        <label for="name">Mysql数据库端口</label>
        <input type="text" class="form-control" value="<?= $editForm['port'] ? $editForm['port'] : '3306' ?>"
               name="editForm[port]" placeholder="Mysql 数据库 port，默认：3306">
    </div>

    <div class="form-group">
        <label for="name">Mysql数据库名称</label>
        <input type="text" class="form-control" value="<?= $editForm['database'] ? $editForm['database'] : 'fecmall' ?>"
               name="editForm[database]" placeholder="Mysql数据库名称，请确认数据库已创建。">
    </div>

    <div class="form-group">
        <label for="name">Mysql数据库账户</label>
        <input type="text" class="form-control" value="<?= $editForm['user'] ? $editForm['user'] : 'root' ?>"
               name="editForm[user]" placeholder="Mysql数据库账户">
    </div>

    <div class="form-group">
        <label for="name">Mysql数据库密码</label>
        <input type="password" class="form-control" value="<?= $editForm['password'] ?>" name="editForm[password]"
               placeholder="Mysql数据库密码">
    </div>

    <button type="submit" class="btn btn-default">下一步</button>
</form>
