<br/>
<div class="form_main">

<?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'events-form',
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div>
		<?php echo $form->label($model,'quantity'); ?>
		<?php echo $form->textField($model,'quantity'); ?>
	</div>

	<div>
		<?php echo $form->label($model,'price'); ?>
		<?php echo $form->textField($model,'price'); ?>
	</div>

	<div>
		<?php echo $form->label($model,'total'); ?>
		<?php echo $form->textField($model,'total'); ?>
	</div>

	<div>
		<?php echo CHtml::submitButton('Поиск',array('id'=>'submit_save_button')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->