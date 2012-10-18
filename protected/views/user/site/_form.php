<div class="form_main">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p>Поля, отмеченные <span class="required">*</span> обязательны к заполнению</p>

	<?php //echo $form->errorSummary($model); ?>	

	<div>
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>40,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	
	<?php if (!$model->isNewRecord): ?>
		<div>
			<?php echo $form->labelEx($model,'password'); ?>
			<?php echo $form->passwordField($model,'oldPassword',array('size'=>40,'maxlength'=>128, 'value'=>'')); ?> 
                        <p>Для изменения пароля введите текущий пароль</p>
			<?php echo $form->labelEx($model,'newPassword'); ?>
			<?php echo $form->passwordField($model,'newPassword',array('size'=>40,'maxlength'=>128, 'value'=>'')); ?>
                        <p>Введите ваш новый пароль</p>
                        <?php echo $form->labelEx($model,'repeatPassword'); ?>
			<?php echo $form->passwordField($model,'repeatPassword',array('size'=>40,'maxlength'=>128, 'value'=>'')); ?>
                        <p>Введите ваш новый пароль повторно</p>
			<?php echo $form->error($model,'password'); ?>
		</div>
	<?php endif; ?>
	
	
	<div>		
		<?php echo $form->labelEx($model,'phone'); ?>
		+7 <?php echo $form->textField($model,'phone',array('size'=>37, 'maxlength'=>10)); ?>
		<?php echo $form->error($model,'phone'); ?>
	</div>
		
	<div>
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>40,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'email'); ?> 
	</div>
	
	<?php if ($model->isNewRecord || (Yii::app()->user->isAdmin() && $model->role != 'admin')): ?>
		<div>		
			<?php echo $form->labelEx($model,'role'); ?>
			<?php echo $form->dropDownList($model,'role',$roles); ?>
			<?php echo $form->error($model,'role'); ?>
		</div>
	<?php endif; ?>

	<div>
		<?php echo $form->labelEx($model,'organization'); ?>
		<?php echo $form->textField($model,'organization',array('size'=>40,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'organization'); ?>
	</div>
	
	<?php if (!$model->isNewRecord && (Yii::app()->user->isAdmin() && $model->user_id == Yii::app()->user->id)): ?>
		<div class="checkbox i_can_only_see">
			<?php echo $form->checkBox($model,'send_mail'); ?>
			<?php echo $form->labelEx($model,'send_mail',array('style'=>'display: inline;')); ?>
			<?php echo $form->error($model,'send_mail'); ?>
		</div>
	<?php endif; ?>
	

	<div>
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить',array('id'=>"submit_save_button")); ?>
	</div>
        <div>
		<?php if (!$model->isNewRecord): ?>
			<!--<a title="Сменить пароль" href="?newpass=true"></a>-->
                        <?php echo CHtml::link('Сменить пароль','?newpass=true') ?>
		<?php endif; ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->