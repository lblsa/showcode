<br/>
<div class="form_main">

<?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'events-form',
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div>
		<?php echo $form->label($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div>
		<?php echo $form->label($model,'value'); ?>
		<?php echo $form->textField($model,'value',array('size'=>50,'maxlength'=>50)); ?>
	</div>	

	<div>
		<?php echo CHtml::submitButton('Поиск',array('id'=>'submit_save_button')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->