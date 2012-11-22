<meta charset="utf-8">
<table cellspacing="0" border="0" cellpadding="0" width="697px" style="margin: 0pt; padding: 0pt; background-color: rgb(255, 255, 255); border-collapse: collapse;">
	<tr height="138px">
		<td>
			<img src="http://<?php echo $_SERVER['HTTP_HOST']?>/images/email/logo_empty.jpg" alt="Showcode." title="Showcode." style="margin: 0pt; padding: 0pt; border: 0pt none; display: block;">
		</td>
	</tr>
	<tr height="41px">
		<td>
			<p style="font-family:Arial, Helvetica, sans-serif; font-size:24px; font-weight:normal; font-style:normal; color:#333;">Здравствуйте, <?php echo $organizator; ?>.</p>
		</td>
	</tr>
	<tr>
		<td style="background-color:#e5e5e5;">
			<p style="padding:16px 10px; font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; color:#333;">Вы запросили список всех билетов для мероприятия под названием «<a target="_blank" title="'<?php echo $event->title; ?> '" href="http://<?php echo $_SERVER['HTTP_HOST']?>/events/view/<?php $event->id;?>."><?php echo $event->title;?>.</a>»</p>
		</td>
	</tr>
	<tr>
		<td style="padding-top:15px; padding-bottom:15px; border-bottom-width:1px; border-bottom-color:#999999; border-bottom-style:solid;">
			<table cellspacing="0" border="0" cellpadding="5" width="" style="text-align: center; margin: 0pt; padding: 20px 0; background-color: rgb(255, 255, 255); border-collapse: collapse;">            
			<?php if(!empty($tickets)):?>
				<tr style="border: 1px solid #000;">
				<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;"><?php echo $tickets[0]->getAttributeLabel('user_id');?></td>
				<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;"><?php echo $tickets[0]->getAttributeLabel('mail');?></td>
				<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;"><?php echo $tickets[0]->getAttributeLabel('phone');?></td>
				<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;"><?php echo $tickets[0]->getAttributeLabel('status');?></td>
				<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;"><?php echo $tickets[0]->getAttributeLabel('quantity');?></td>
				<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;"><?php echo $tickets[0]->getAttributeLabel('total');?></td>
				<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;"><?php echo $tickets[0]->getAttributeLabel('column');?></td>
				<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;"><?php echo $tickets[0]->getAttributeLabel('place')?></td>
				<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;"><?php echo $tickets[0]->getAttributeLabel('uniq');?></td>
				<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;"><?php echo $tickets[0]->getAttributeLabel('payment');?></td>
				</tr>
			<?php foreach($tickets AS $i => $ticket):?>
				<tr style="border-bottom: 1px solid #000;">
				<?php if($ticket->user_id):?>
					<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">
						<?php echo Yii::app()->user->getAuthorName($ticket->user_id);?>
					</td>
				<?php else: ?>
					<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">
						<?php echo $ticket->family;?>
					</td>
				<?php endif;?>
					<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">
						<?php echo $ticket->mail;?>
					</td>
					<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">
						<?php echo $ticket->phone;?>
					</td>
					<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">
						<?php echo TransactionLog::$status[$ticket->status];?>
					</td>
					<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">
						<?php echo $ticket->quantity;?>
					</td>
					<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">
						<?php echo $ticket->total;?>
					</td>
					<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">
						<?php echo $ticket->column;?>
					</td>
					<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">
						<?php echo $ticket->place;?>
					</td>
					<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">
						<?php echo $ticket->uniq;?>
					</td>
					<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">
						<?php echo TransactionLog::$payment_type[$ticket->payment];?>
					</td>
				</tr>
				<?php endforeach;?>
			<?php else: ?>
				<tr style="border: 1px solid #000;">
					<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">
						Нет купленных билетов на данное мероприятие.
					</td>
				</tr>
			<?php endif;?>
			</table>
		</td>
	</tr>
	<tr>
		<td style="padding-top:5px;">
			<p style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; color:#333;">С уважением, администрация сайта <a target="_blank" href="<?php echo $_SERVER['HTTP_HOST'];?>" title="">ShowCode.ru</a>.
			</p>
		</td>
	</tr>
</table>