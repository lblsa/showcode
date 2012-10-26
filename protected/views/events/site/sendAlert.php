<h1>Рассылка оповещений</h1>

<div class="form_main">
	<?php echo CHtml::beginForm(); ?>
		<div class="row">
			<span style="color: #CCC; font-size: 13px; margin-right: 20px;">Заголовок</span>
			
		<?php echo CHtml::textField('title', '', array('style'=>'width: 200px;')); ?>
		</div>
		 
		<div class="row">
			<span style="color: #CCC; font-size: 13px; margin-right: 20px;">Текст оповещения</span>
		</div>	 
		<div class="row">
		<?php echo CHtml::textArea('text', '', array('style'=>'width: 400px; height: 100px; border: none; background-image:url(../../images/bg/form_textarea_bg.png)')); ?>
		</div>
		
		<div class="row">
			<?php echo CHtml::checkBox('mobile', '1', array('style'=>'height: auto;')); ?>
			<span style="color: #CCC; font-size: 13px; margin-left: 20px;">Выслать оповещение на мобильный</span>		
		</div>
		
		<div class="row">
			<?php echo CHtml::checkBox('mail', '1', array('style'=>'height: auto;')); ?>
			<span style="color: #CCC; font-size: 13px; margin-left: 20px;">Выслать оповещение на почту</span>
		</div>
		
		<div id = "list_tickets">
			<h1>Список участников</h1>
			<?php if(count($data)!=0):?>
			<table>
				<tr class="title_table">
					<td></td>
					<td>
						Фамилия Имя Отчество
					</td>
					<td>
						Номер телефона
					</td>
					<td>
						e-mail
					</td>
				</tr>
				<?php foreach ($data as $user):?>
				<tr>
					<td style="width: 20px;">
						<?php echo CHtml::checkBoxList('user', $user['user_id'], array($user['user_id']=>' ')); ?>
					</td>				
					<td>				
							<?php echo $user['family']; ?>					
					</td>			
					<td>				
							<?php echo $user['phone']; ?>					
					</td>			
					<td>				
							<?php echo $user['mail']; ?>					
					</td>
					
				</tr>
				<?php endforeach; ?>
			</table>
			<?php else:?>
				<div style="font-size: 200%; margin-left: 20px; margin-bottom: 20px;">Нет участников</div>				
			<?php endif;?>
		</div>
		 
		<div class="row submit">
		<?php echo CHtml::submitButton('Отправить', array('id'=>'submit_save_button')); ?>
		</div>
	<?php echo CHtml::endForm(); ?>
</div>

<script type="text/javascript">
	$('#submit_save_button').click(function(){
		c = <?php echo count($data); ?>;
		if(c==0)
			alert('На ваше мероприятие пока никто не купил билеты!');
		else
			alert('Сообщения отправлены!');
	});
</script>