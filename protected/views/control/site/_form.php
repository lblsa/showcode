<div class="form_main">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'control-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Поля, отмеченные <span class="required">*</span> обязательны для заполнения.</p>

	<?php //echo $form->errorSummary($model); ?>

	<div>
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('maxlength'=>50)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div>
		<?php echo $form->labelEx($model,'value'); ?>
		<?php echo $form->textField($model,'value',array('maxlength'=>50)); ?>
		<?php echo $form->error($model,'value'); ?>
	</div>

	<div>
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('maxlength'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div>
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить',array('id'=>'submit_save_button')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->