<script src="/js/redactor/redactor.js"></script>
<link rel="stylesheet" href="/js/redactor/css/redactor.css" />
<div class="form_main">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'control-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Поля, отмеченные <span class="required">*</span> обязательны для заполнения.</p>

	<?php //echo $form->errorSummary($model); ?>
        
        <div>
		<?php echo $form->labelEx($model,'tag_uniq'); ?>
		<?php echo $form->textField($model,'tag_uniq',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'tag_uniq'); ?>
	</div>

	<div>
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>555)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>	

	<div>
		<?php echo $form->labelEx($model,'body'); ?>
		<?php echo $form->textArea($model,'body',array('rows'=>6, 'cols'=>50, 'name'=>"PageContent[body]", 'id'=>"PageContent_body", 'style'=>'width: 100%; height: 500px;')); ?>
		<?php echo $form->error($model,'body'); ?>
	</div>

	<div>
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить',array('id'=>'submit_save_button')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<script type="text/javascript">
	$(function(){$("#PageContent_body").redactor();	});
</script>