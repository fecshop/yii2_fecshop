<body style="background:#F6F6F6; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; margin:0; padding:0;">
<div style="background:#F6F6F6; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; margin:0; padding:0;">
<table cellspacing="0" cellpadding="0" border="0" height="100%" width="100%">
        <tr>
            <td align="center" valign="top" style="padding:20px 0 20px 0">
                <!-- [ header starts here] -->
                <table bgcolor="FFFFFF" cellspacing="0" cellpadding="10" border="0" width="650" style="border:1px solid #E0E0E0;">
                    <tr>
                        <td valign="top">
                            <a href="<?= $homeUrl; ?>">
								<img src="<?=  $logoImg; ?>" alt="" style="margin:10px 0 ;" border="0"/>
							</a>
						</td>
                    </tr>
                <!-- [ middle starts here] -->
                    <tr>
                        <td valign="top">
                            <h1 style="font-size:22px; font-weight:normal; line-height:22px; margin:0 0 11px 0;"">亲爱的 <?= $name ?>,</h1>
                            <p style="font-size:12px; line-height:16px; margin:0 0 16px 0;">欢迎您来到 <?= $storeName ?>. 
                            您可以通过点击页面顶部的
                            <a href="<?= $loginUrl ?>" style="color:#1E7EC8;">登录</a> 
                            或者 
                            <a href="<?= $accountUrl ?>" style="color:#1E7EC8;">我的账户</a>
                            来访问我们的网站， 
                            然后，输入您的邮箱账户和密码即可。
                            </p>
                            <?php if ($registerEnableUrl): ?>
                            <p style="font-size: 12px; line-height: 16px; margin: 0 0 8px 0;">请点击该链接激活您的账户: <a href="<?= $registerEnableUrl ?>" style="color:#1E7EC8;"><?= $registerEnableUrl ?></a></p>
                            <?php endif; ?>
                            
                            <p style="border:1px solid #E0E0E0; font-size:12px; line-height:16px; margin:0; padding:13px 18px; background:#f9f9f9;">
                                提示登录时使用以下值：<br/>
                                <strong>邮箱账户</strong>: <?= $email; ?><br/>
                                <strong>账户密码</strong>: <?= $password; ?><p>
                            <p style="font-size:12px; line-height:16px; margin:0 0 8px 0;">
                                当您登录到您的帐户时，您将能够执行以下操作:</p>
                            <ul style="font-size:12px; line-height:16px; margin:0 0 16px 0; padding:0;">
                                <li style="list-style:none inside; padding:0 0 0 10px;">&ndash; 进行购物时，尽快结帐</li>
                                <li style="list-style:none inside; padding:0 0 0 10px;">&ndash; 检查订单的状态</li>
                                <li style="list-style:none inside; padding:0 0 0 10px;">&ndash; 查看过去的订单</li>
                                <li style="list-style:none inside; padding:0 0 0 10px;">&ndash; 更改您的帐户信息</li>
                                <li style="list-style:none inside; padding:0 0 0 10px;">&ndash; 更改您的密码</li>
                                <li style="list-style:none inside; padding:0 0 0 10px;">&ndash; 存储更多地址（用于发送给多个家庭成员和朋友！）</li>
                            </ul>
                            <p style="font-size:12px; line-height:16px; margin:0;">
                            如果您对您的帐户或任何其他事项有任何疑问, 
                            请通过邮箱地址： <a href="mailto:<?= $contactsEmailAddress ?>" style="color:#1E7EC8;"><?= $contactsEmailAddress ?></a>
                            随时与我们联系       
                            ，或者打电话给我们： <?= $contactsPhone ?>.</p>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#EAEAEA" align="center" style="background:#EAEAEA; text-align:center;"><center><p style="font-size:12px; margin:0;">再次感谢您, <strong><?= $storeName; ?></strong></p></center></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
</body>