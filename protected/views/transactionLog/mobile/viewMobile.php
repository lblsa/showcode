<?php
    header("Content-Type: text/html; charset=utf-8");
    echo "<meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1'>";
?>
<table cellspasing="0" border="0" cellpadding="0" style="margin: 0pt; padding: 0pt; background-color: rgb(255, 255, 255); border-collapse: collapse;">
	<!-- QR-code -->
    <tr>
    	<td><a href="https://<?php echo $_SERVER['HTTP_HOST'] ?>/ticket/view/<?php echo $model->uniq ?>" target="_blank" title=""><img src="https://<?php echo $_SERVER['HTTP_HOST'].$model->qr ?>" alt="<?php echo Events::getEventTitle($model->event_id) ?>" style="margin: 0pt; padding: 0pt; border: 0pt none; display: block; border:1px #cccccc solid;"></a></td>
    </tr>
    <!-- hello, usename -->
    <tr>
    	<td><p style="padding:10px 0 5px 10px;font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:normal; font-style:normal; color:#333;">Здравствуйте, <?php echo $model->family ?>.</p></td>
    </tr>
    <!-- date, name of event, location -->
    <tr>
        <?php if(!$eventUniq): ?>
            <td style="background-color:#e5e5e5;"><p style="padding:5px 10px; font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:normal; font-style:normal; color:#333;">
                Вы купили билет на мероприятие под названием «<a target="_blank" title="<?php echo Events::getEventTitle($model->event_id) ?>" href="https://<?php echo $_SERVER['HTTP_HOST'] ?>/events/view/<?php echo $model->event_id ?>"><?php echo Events::getEventTitle($model->event_id) ?></a>», которое состоится <b>
	   	<?php if($model->type == 'travel'): ?>
	   		<?php echo Events::getEventDate($ticket->date_begin) .' - '; ?>
	   		<?php echo Events::getEventDate($ticket->date_end) .' года '; ?>
		<?php else: ?>
			<?php echo Events::getEventDate($model->event_id) .' года '; ?>
		<?php endif; ?>

		<?php if($ticket->time_begin): ?>
			<?php echo '(начало в '. $ticket->time_begin; ?>
		<?php else: ?>
			<?php echo '(начало в '. Events::getEventTime($model->event_id); ?>
		<?php endif; ?>
		<?php if($ticket->time_end): ?>
			<?php echo ', окончание в '. $ticket->time_end; ?>
		<?php endif; ?>
		<?php echo '). '; ?>

		<?php if (isset($model->column) && isset($model->place)): ?>
			<?php echo 'Ваш рад №' .$model->column. ', место ' .$model->place; ?>
		<?php endif; ?>
		</b></p>
            </td>
        <?php else: ?>
            <td style="background-color: #30aabc;">
                <p style="padding:0px 10px; font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:normal; font-style:normal; color:#FFF;">
                    <b>Вы купили билет</b> в «<?php echo Events::getEventTitle($model->event_id) ?>»</p>
                <?php if($eventUniq->time_work): ?>
                    <p style="padding:0px 10px; font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:normal; font-style:normal; color:#FFF;">
                        Часы работы: <?php echo $eventUniq->time_work ?></p>
                <?php endif; ?>

                <?php if($event->address): ?>
                    <p style="padding:0px 10px; font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:normal; font-style:normal; color:#FFF;">
                        Адрес: <?php echo $event->address ?></p>
                <?php endif; ?>

                <?php if($eventUniq->phone): ?>
                    <p style="padding:0px 10px; font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:normal; font-style:normal; color:#FFF;">
                        Тел.: <?php echo $eventUniq->phone ?></p>
                <?php endif; ?>
            </td>
        <?php endif; ?>
    </tr>
    <!-- ticket number, link to the event -->
    <tr>
    	<td style="font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:normal; font-style:normal; color:#000; padding-top:5px;">Номер билета:&nbsp;&nbsp;<a target="_blank" href="#" title="2155956"><?php echo $model->uniq; ?></a></td>
    </tr>
    <tr>
    	<td style="font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:normal; font-style:normal; color:#000;"><?php echo CHtml::link("Ссылка на билет", "https://".$_SERVER['HTTP_HOST']."/ticket/view/".$model->uniq) ?></td>
    </tr>
    <tr>
        <td colspan="2" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:normal; font-style:normal; color:#666; padding-top:5px; padding-bottom:10px; border-bottom-width:1px; border-bottom-color:#999999; border-bottom-style:solid;">
            <b>Попасть на мероприятие вы сможете:</b>
            <ul style="list-style:none; margin:0pt; padding:0pt;">
                <li>&mdash; распечатав изображение данного QR-кода на бумаге;</li>
                <li>&mdash; показав изображение на экране любого цифрового носителя.</li>
            </ul>
        </td>
    </tr>
    <!-- footer -->
    <tr>
    	<td style="padding-top:5px;"><p style="font-family:Arial, Helvetica, sans-serif; font-size:11px; font-weight:normal; font-style:normal; color:#333;">С уважением, администрация сайта <a target="_blank" href="/" title="">ShowCode.ru</a>. Ждём вас снова!</p></td>
    </tr>
</table>