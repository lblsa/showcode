<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Поля, отмеченные <span class="required">*</span> обязательны к заполнению</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row" data-role="fieldcontain">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>40,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>
	
	<?php if (!$model->isNewRecord): ?>
		<div class="row" data-role="fieldcontain">
			<?php echo $form->labelEx($model,'password'); ?>
			Для изменения пароля введите текущий пароль: <br />
			<?php echo $form->passwordField($model,'oldPassword',array('size'=>40,'maxlength'=>128)); ?> <br />
			Введите ваш новый пароль: <br />
			<?php echo $form->passwordField($model,'password',array('size'=>40,'maxlength'=>128)); ?>
			<?php echo $form->error($model,'password'); ?>
		</div>
	<?php endif; ?>
		
	<div class="row" data-role="fieldcontain">
		<?php echo $form->labelEx($model,'phone'); ?>
		+7 <?php echo $form->textField($model,'phone',array('style'=>'width: 85%;', 'maxlength'=>10)); ?>
		<?php echo $form->error($model,'phone'); ?>
	</div>
	
	<div class="row" data-role="fieldcontain">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>40,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'email'); ?> 
	</div>
	
	<?php if ($model->isNewRecord || (Yii::app()->user->isAdmin() && $model->role != 'admin')): ?>
		<div class="row" data-role="fieldcontain">
			<?php echo $form->labelEx($model,'role'); ?>
			<div data-role="controlgroup">
				<?php echo $form->radioButtonList($model,'role',$roles,array('separator'=>'')); ?>
			</div>
			<?php echo $form->error($model,'role'); ?>
		</div>
	<?php endif; ?>

	<div class="row" data-role="fieldcontain">
		<?php echo $form->labelEx($model,'organization'); ?>
		<?php echo $form->textField($model,'organization',array('size'=>40,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'organization'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', array('data-role'=>"button",'data-theme'=>"a", 'data-iconpos'=>"right") ); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->