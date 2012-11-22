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
									<img src="http://<?php echo $_SERVER['HTTP_HOST'];?>/images/email/logo_booking_ticket.jpg" alt="Showcode. Бронирование билета." title="Showcode. Бронирование билета." style="margin: 0pt; padding: 0pt; border: 0pt none; display: block;">
								</td>
							</tr>
							<tr>
							<?php if(!$eventUniq):?>
								<td colspan="2">
									<div style="height: 40px;">
										<p style="font-family:Arial, Helvetica, sans-serif; font-size:24px; font-weight:normal; font-style:normal; color:#333; padding-bottom: 10px;">Здравствуйте, <?php echo $model->family;?>.</p>
									</div>
								</td>
							<?php else:?>
								<td colspan="2">
									<div style="height: 30px;">
										<p style="font-family:Arial, Helvetica, sans-serif; font-size:24px; font-weight:normal; font-style:normal; color:#333; padding-bottom: 10px;">Здравствуйте, <?php echo $model->family;?>.</p>
									</div>
								</td>
							<?php endif;?>
							</tr>
							<tr>
							<?php if(!$eventUniq):?>
								<td style="background-color:#e5e5e5;">
									<p style=" font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; color:#333;line-height: 18px;padding-left: 10px;padding-right: 10px;margin-top: 10px;margin-bottom: 10px;">Вы забронировали билет на мероприятие под названием «<a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/events/view/<?php echo $model->event_id;?>" target="_blank" title="<?php echo  $tit;?>"><?php echo $tit;?></a>», которое состоится 
									<b>
									<?php if($model->type == 'travel'):?>
										<?php echo Events::getEventDate($ticket->date_begin);?> - 
										<?php echo Events::getEventDate($ticket->date_end):?> года
									<?php else:?>
										<?php echo Events::getEventDate($model->event_id);?> года
										<?php if($ticket->time_begin):?>
											(начало в <?php echo $ticket->time_begin;?>
										<?php else:?>
											(начало в <?php echo Events::getEventTime($model->event_id);?>
										<?php endif;?>
										<?php if($ticket->time_end):?>
											, окончание в <?php echo $ticket->time_end;?>
										<?php endif;?>
										)
									<?php endif;?>
									<?php if (isset($model->column) && isset($model->place)):?>
										Ваш ряд № <?php echo $model->column;?>, место <?php echo $model->place;?>.
									</b>
									<br/><br/>
									Вы должны оплатить билет, выбранным вами способом, <b>в течение 4 часов</b>. Если за это время оплата не произойдёт, то ваша бронь аннулируется.
									</p>
								</td>
								<td style="padding-right: 10px;">&nbsp;</td>
							<?php else:?>
								<td>
									<table cellspacing="0" border="0" cellpadding="0" width="" style="margin: 0pt; padding: 20px 0; background-color: rgb(255, 255, 255); border-collapse: collapse;">
										<tr>
											<td width="120px">
												<img src="http://<?php echo $_SERVER['HTTP_HOST'];?>/images/email/zoo/zoo-logo.png" alt="<?php echo $event->title;?>" title="<?php echo $event->title;?>" style="margin: 0pt; padding: 0pt; border: 0pt none; display: block; border:1px">
											</td>
											<td style="background-color:#30aabc; width:419px">
												<p style="padding:0 10px 0 20px; font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:normal; font-style:normal; color:#fff; line-height:18px;"><b>Вы купили билет</b> в «<?php echo $event->title;?>»<br />
													Часы работы: <?php echo $eventUniq->time_work;?><br />
													Адрес: <?php echo $event->address;?><br />
													Тел.: <?php echo $eventUniq->phone;?>
												</p>
											</td>
											<td style="background-color:#30aabc; vertical-align:bottom;">
												<img style="vertical-align:bottom;" src="http://<?php echo $_SERVER['HTTP_HOST'];?>/images/email/zoo/bird_top_part.png" alt="" />
											</td>
										</tr>
										<tr>
											<td>
												<img style="vertical-align:top;" src="http://<?php echo $_SERVER['HTTP_HOST'];?>/images/email/zoo/bird_bottom_part_empty.png" alt=""/>
											</td>
											<td>
												<img style="vertical-align:top;" src="http://<?php echo $_SERVER['HTTP_HOST'];?>/images/email/zoo/bird_bottom_part_empty.png" alt=""/>
											</td>
											<td>
												<img style="vertical-align:top;" src="http://' .$_SERVER['HTTP_HOST']. '/images/email/zoo/bird_bottom_part.png" alt=""/>
											</td>
										</tr>
									</table>
								</td>
							<?php endif;?>
							</tr>
							<tr>
							<?php if(!$eventUniq):?>
								<td colspan="2" style="padding-top: 7px;   padding-bottom: 17px;padding-right: 10px;">
									<table cellspacing="0" border="0" cellpadding="5" width="" style="margin: 0pt; padding: 20px 0; background-color: rgb(255, 255, 255); border-collapse: collapse;">
							<?php else:?>
								<td colspan="2" style="padding-top: 7px;   padding-bottom: 0;padding-right: 10px;">';
									<table cellspacing="0" border="0" cellpadding="5" width="" style="margin: 0pt; padding: 0; background-color: rgb(255, 255, 255); border-collapse: collapse;">
							<?php endif;?>
										<tr>
											<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; color:#000;">
												Номер билета:
											</td>
											<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">
												<?php echo $model->uniq;?>
											</td>
										</tr>
										<tr>
											<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; color:#000;">
												Ссылка на билет:
											</td>
											<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; text-decoration:underline; color:#0000ff;">
												<a target="_blank" href="http://<?php echo $_SERVER['HTTP_HOST'];?>/ticket/view/<?php echo $model->uniq;?>" title="Здесь вы можете просмотреть статус покупки"><?php echo $_SERVER['HTTP_HOST'];?>/ticket/view/<?php echo .$model->uniq;?>
												</a>
											</td>
										</tr>
										<tr>
											<td colspan="2" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:normal; font-style:normal; color:#999;">
												Чтобы попасть на мероприятие вы должны:
												<ul style="list-style:none; margin:0pt; padding-top: 3px;padding-right: 0;padding-bottom: 0;padding-left: 0;">
													<li>&mdash; внести плату за данный билет, указанным вами способом оплаты;</li>
												</ul>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td style="padding-top: 10px; border-top-color: #999; border-top-style: solid; border-top-width: 1px;">
									<p style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; color:#333;line-height: 0;">С уважением, администрация сайта <a target="_blank" href="'<?php echo $_SERVER['HTTP_HOST'];?>" title="">ShowCode.ru</a>.
									</p>
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