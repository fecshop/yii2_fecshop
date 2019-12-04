<?php
    use fec\helpers\CRequest;
?>
<h1>Mysql数据库配置</h1>
<br/>

<?=  $errorInfo  ?>


<form action="" method="post">
    <?php echo CRequest::getCsrfInputHtml();  ?>	
  <div class="form-group">
    <label for="name">Mysql数据库Host</label>
    <input type="text" class="form-control" value="<?= $editForm['host'] ?>" name="editForm[host]" placeholder="Mysql数据库Host Ip，本地请填写127.0.0.1">
  </div>
  
  <div class="form-group">
    <label for="name">Mysql数据库端口</label>
    <input type="text" class="form-control" value="<?= $editForm['port'] ? $editForm['port'] : '3306' ?>" name="editForm[port]" placeholder="Mysql数据库port，请先去mysql中创建数据库，然后再填写">
  </div>
  
  <div class="form-group">
    <label for="name">Mysql数据库名称</label>
    <input type="text" class="form-control" value="<?= $editForm['database'] ?>" name="editForm[database]" placeholder="Mysql数据库名称，请先去mysql中创建数据库，然后再填写">
  </div>
  
  <div class="form-group">
    <label for="name">Mysql数据库账户</label>
    <input type="text" class="form-control" value="<?= $editForm['user'] ?>" name="editForm[user]" placeholder="Mysql数据库账户">
  </div>
  
  <div class="form-group">
    <label for="name">Mysql数据库密码</label>
    <input type="text" class="form-control" value="<?= $editForm['password'] ?>" name="editForm[password]" placeholder="Mysql数据库密码">
  </div>
  
  
  <button type="submit" class="btn btn-default">提交</button>
</form>