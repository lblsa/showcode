<br/>
<div class="form_main">

<?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'events-form',
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div>
		<?php echo $form->label($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>50,'maxlength'=>50)); ?>
	</div>	

	<div>
		<?php echo $form->label($model,'datetime'); ?>
		<?php echo $form->textField($model,'datetime'); ?>
	</div>
	
	<div>
		<?php echo $form->label($model,'author'); ?>
		<?php echo $form->textField($model,'author'); ?>
	</div>
	
	<div>
		<?php echo $form->label($model,'status'); ?>
		<?php echo $form->dropDownList($model,'status',Events::$STATUS); ?>
	</div>	

	<div>
		<?php echo CHtml::submitButton('Поиск',array('id'=>'submit_save_button')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->