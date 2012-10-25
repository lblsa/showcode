<h1>Рассылка оповещений</h1>

<div class="form_main">
	<?php echo CHtml::beginForm(); ?>
		<div class="row">
			<span style="color: #CCC; font-size: 150%; margin-right: 20px;">Заголовок</span>
			
		<?php echo CHtml::textField('title', '', array('style'=>'width: 200px;')); ?>
		</div>
		 
		<div class="row">
			<span style="color: #CCC; font-size: 150%; margin-right: 20px;">Текст оповещения</span>
		</div>	 
		<div class="row">
		<?php echo CHtml::textArea('text', '', array('style'=>'width: 400px; height: 100px; border: none; background-image:url(../../images/bg/form_textarea_bg.png)')); ?>
		</div>
		
		<div class="row">
			<?php echo CHtml::checkBox('mobile', '', array('style'=>'height: auto;')); ?>
			<span style="color: #CCC; font-size: 150%; margin-left: 20px;">Выслать оповещение на мобильный</span>		
		</div>
		
		<div class="row">
			<?php echo CHtml::checkBox('mail', '', array('style'=>'height: auto;')); ?>
			<span style="color: #CCC; font-size: 150%; margin-left: 20px;">Выслать оповещение на почту</span>
		</div>
		 
		<div class="row submit">
		<?php echo CHtml::submitButton('Отправить', array('id'=>'submit_save_button')); ?>
		</div>
	<?php echo CHtml::endForm(); ?>
</div>