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
                            <h1 style="font-size:22px; font-weight:normal; line-height:22px; margin:0 0 11px 0;"">Dear <?= $name ?>,</h1>
                            <p style="font-size:12px; line-height:16px; margin:0 0 16px 0;">Welcome to <?= $storeName ?>. To log in when visiting our site just click <a href="<?= $loginUrl ?>" style="color:#1E7EC8;">Login</a> or <a href="<?= $accountUrl ?>" style="color:#1E7EC8;">My Account</a> at the top of every page, and then enter your e-mail address and password.</p>
                            <?php if ($registerEnableUrl): ?>
                            <p style="font-size: 12px; line-height: 16px; margin: 0 0 8px 0;">please click on the following link to enable your register account: <a href="<?= $registerEnableUrl ?>" style="color:#1E7EC8;"><?= $registerEnableUrl ?></a></p>
                            <?php endif; ?>
                            <p style="border:1px solid #E0E0E0; font-size:12px; line-height:16px; margin:0; padding:13px 18px; background:#f9f9f9;">
                                Use the following values when prompted to log in:<br/>
                                <strong>E-mail</strong>: <?= $email; ?><br/>
                                <strong>Password</strong>: <?= $password; ?><p>
                            <p style="font-size:12px; line-height:16px; margin:0 0 8px 0;">When you log in to your account, you will be able to do the following:</p>
                            <ul style="font-size:12px; line-height:16px; margin:0 0 16px 0; padding:0;">
                                <li style="list-style:none inside; padding:0 0 0 10px;">&ndash; Proceed through checkout faster when making a purchase</li>
                                <li style="list-style:none inside; padding:0 0 0 10px;">&ndash; Check the status of orders</li>
                                <li style="list-style:none inside; padding:0 0 0 10px;">&ndash; View past orders</li>
                                <li style="list-style:none inside; padding:0 0 0 10px;">&ndash; Make changes to your account information</li>
                                <li style="list-style:none inside; padding:0 0 0 10px;">&ndash; Change your password</li>
                                <li style="list-style:none inside; padding:0 0 0 10px;">&ndash; Store alternative addresses (for shipping to multiple family members and friends!)</li>
                            </ul>
                            <p style="font-size:12px; line-height:16px; margin:0;">If you have any questions about your account or any other matter, please feel free to contact us at <a href="mailto:<?= $contactsEmailAddress ?>" style="color:#1E7EC8;"><?= $contactsEmailAddress ?></a> or by phone at <?= $contactsPhone ?>.</p>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#EAEAEA" align="center" style="background:#EAEAEA; text-align:center;"><center><p style="font-size:12px; margin:0;">Thank you again, <strong><?= $storeName; ?></strong></p></center></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
</body>