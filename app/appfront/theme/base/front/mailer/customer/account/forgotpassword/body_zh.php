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
                                <h1 style="font-size: 22px; font-weight: normal; line-height: 22px; margin: 0 0 11px 0;">您好 <?= $name ?>,</h1>
                                <p style="font-size: 12px; line-height: 16px; margin: 0 0 8px 0;">有一个请求要求更改您帐户的密码。</p>
                                <p style="font-size: 12px; line-height: 16px; margin: 0 0 8px 0;">如果更改密码请求是由您发起，请点击以下链接重置密码： <a href="<?= $resetUrl ?>" style="color:#1E7EC8;"><?= $resetUrl ?></a></p>
                                <p style="font-size: 12px; line-height: 16px; margin: 0 0 4px 0;">如果点击链接无效，请将网址复制并粘贴到您的浏览器中。</p>
                                <br />
                                <p style="font-size:12px; line-height:16px; margin:0 0 8px 0;">如果您没有发出此请求，则可以忽略此消息，并且您的密码将保持不变。</p>
								<p style="font-size:12px; line-height:16px; margin:0 0 8px 0;">如果您对帐户或任何其他问题有任何疑问，请随时与我们联系 <a href="mailto:<?= $contactsEmailAddress ?>" style="color:#1E7EC8;"><?= $contactsEmailAddress ?></a> 或通过电话 <?= $contactsPhone ?>.</p>
                       
							</td>
                        </tr>
                        <tr>
                            <td style="background-color: #EAEAEA; text-align: center;"><p style="font-size:12px; margin:0; text-align: center;">谢谢, <strong><?= $storeName; ?></strong></p></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>