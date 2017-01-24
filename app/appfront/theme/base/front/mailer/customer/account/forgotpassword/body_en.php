<body style="background: #F6F6F6; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; margin: 0; padding: 0;">
    <div style="background: #F6F6F6; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; margin: 0; padding: 0;">
        <table cellspacing="0" cellpadding="0" border="0" height="100%" width="100%">
            <tr>
                <td align="center" valign="top" style="padding: 20px 0 20px 0">
                    <table bgcolor="FFFFFF" cellspacing="0" cellpadding="10" border="0" width="650" style="border:1px solid #E0E0E0;">
                        <tr>
							<td valign="top">
								<a href="<?= $homeUrl; ?>">
									<img src="<?=  $logoImg; ?>" alt="" style="margin:10px 0 ;" border="0"/>
								</a>
							</td>
						</tr>
                        <tr>
                            <td valign="top">
                                <h1 style="font-size: 22px; font-weight: normal; line-height: 22px; margin: 0 0 11px 0;">Dear <?= $name ?>,</h1>
                                <p style="font-size: 12px; line-height: 16px; margin: 0 0 8px 0;">There was recently a request to change the password for your account.</p>
                                <p style="font-size: 12px; line-height: 16px; margin: 0 0 8px 0;">If you requested this password change, please click on the following link to reset your password: <a href="<?= $resetUrl ?>" style="color:#1E7EC8;"><?= $resetUrl ?></a></p>
                                <p style="font-size: 12px; line-height: 16px; margin: 0 0 4px 0;">If clicking the link does not work, please copy and paste the URL into your browser instead.</p>
                                <br />
                                <p style="font-size:12px; line-height:16px; margin:0 0 8px 0;">If you did not make this request, you can ignore this message and your password will remain the same.</p>
								<p style="font-size:12px; line-height:16px; margin:0 0 8px 0;">If you have any questions about your account or any other matter, please feel free to contact us at <a href="mailto:<?= $contactsEmailAddress ?>" style="color:#1E7EC8;"><?= $contactsEmailAddress ?></a> or by phone at <?= $contactsPhone ?>.</p>
                       
							</td>
                        </tr>
                        <tr>
                            <td style="background-color: #EAEAEA; text-align: center;"><p style="font-size:12px; margin:0; text-align: center;">Thank you, <strong><?= $storeName; ?></strong></p></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>