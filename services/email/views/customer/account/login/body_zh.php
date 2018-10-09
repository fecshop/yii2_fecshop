<?php
use yii\helpers\Html;

// @var $this yii\web\View
// @var $user common\models\User

?>
收到 <?= \Yii::$service->helper->htmlEncode($name) ?>, 消息<br/>
Store:"en"<br/>
邮箱地址:<?= \Yii::$service->helper->htmlEncode($email) ?><br/>
手机号码:<?= \Yii::$service->helper->htmlEncode($contactsPhone) ?><br/>
内容:<?= \Yii::$service->helper->htmlEncode($comment) ?>
