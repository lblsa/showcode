<meta charset="utf-8">
<table cellspacing="0" border="0" cellpadding="0" width="100%" style="background-color:#dadada; border-collapse: collapse; border-spacing:0;">
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td align="center">
			<table cellspacing="0" border="0" cellpadding="0" height="460px" width="728px" style="margin: 0pt; padding:0; background-color: rgb(255, 255, 255); border-collapse: collapse; border-spacing:0;">
				<tr>
					<td style="background-image:url(http://<?php echo $_SERVER['HTTP_HOST'];?>/images/email/content_bg.png); background-repeat:no-repeat; background-position:left top; padding-top: 0px; padding-right:0; padding-bottom:0; padding-left: 21px;">
						<table cellspacing="0" border="0" cellpadding="0" width="698px" style="margin: 0pt; padding: 0pt; background-color: rgb(255, 255, 255); border-collapse: collapse; border-spacing:0;">
							<tr>
								<td colspan="2">
									<img src="http://<?php echo $_SERVER['HTTP_HOST'];?>/images/email/logo_welcome.jpg" alt="Showcode. Добро пожаловать." title="Showcode. Добро пожаловать." style="margin: 0pt; padding: 0pt; border: 0pt none; display: block;">
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<div style="height: 40px;">
										<p style="font-family:Arial, Helvetica, sans-serif; font-size:24px; font-weight:normal; font-style:normal; color:#333; padding-bottom: 10px;">Здравствуйте, <?php echo $model->name;?>.
										</p>
									</div>
								</td>
							</tr>
							<tr>
								<td style="background-color:#e5e5e5;">
									<p style=" font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; color:#333;line-height: 18px;padding-left: 10px;padding-right: 10px;margin-top: 10px;margin-bottom: 10px;">Вы успешно зарегистрировались в <b><?php echo Yii::app()->name;?></b>.
									</p>
								</td>
								<td style="padding-right: 10px;">&nbsp;</td>
							</tr>
							<tr>
								<td colspan="2" style="padding-top: 7px;   padding-bottom: 17px;padding-right: 10px;">
									<table cellspacing="0" border="0" cellpadding="5" width="" style="margin: 0pt; padding: 20px 0; background-color: rgb(255, 255, 255); border-collapse: collapse;">
										<tr>
											<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; color:#000;">
												Ваш логин:
											</td>
											<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">
												<b><?php echo $model->phone;?></b>
											</td>
										</tr>
										<tr>
											<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; color:#000;">
												Ваш пароль:
											</td>
											<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">
												<b><?php echo $password;?></b>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td style="padding-top: 10px; border-top-color: #999; border-top-style: solid; border-top-width: 1px;">
									<p style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; color:#333;line-height: 0;">С уважением, администрация сайта <a target="_blank" href="<?php echo $_SERVER['HTTP_HOST'];?>" title="">ShowCode.ru</a>.</p>
								</td>
								<td style="padding-right: 10px;">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
</table>