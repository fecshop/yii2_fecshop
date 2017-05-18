<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

?>
Get <?= \Yii::$service->helper->htmlEncode($name) ?>, message<br/>
Store:"en"<br/>
Email:<?= \Yii::$service->helper->htmlEncode($email) ?><br/>
Mobile:<?= \Yii::$service->helper->htmlEncode($contactsPhone) ?><br/>
Content:<?= \Yii::$service->helper->htmlEncode($comment) ?>
