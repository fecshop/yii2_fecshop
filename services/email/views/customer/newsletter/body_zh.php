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
                            <h1 style="font-size:22px; font-weight:normal; line-height:22px; margin:0 0 11px 0;"">您好 <?= $email ?>,</h1>
                            <p style="font-size:12px; line-height:16px; margin:0 0 16px 0;">
								您的订阅电子邮件已成功，您可以点击此处访问
								<a href="<?= $homeUrl ?>"><?= $storeName; ?></a>
								， 谢谢。
							</p>
                            
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#EAEAEA" align="center" style="background:#EAEAEA; text-align:center;"><center><p style="font-size:12px; margin:0;">再次感谢, <strong><?= $storeName; ?></strong></p></center></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
</body>