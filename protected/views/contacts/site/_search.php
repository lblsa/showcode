<br/>
<div class="form_main">

<?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'events-form',
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div>
		<?php echo $form->label($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>30,'maxlength'=>30)); ?>
	</div>
    
        <div>
		<?php echo $form->label($model,'datetime'); ?>
		<?php echo $form->textField($model,'datetime',array('size'=>30,'maxlength'=>30)); ?>
	</div>

	<div>
		<?php echo $form->label($model,'type'); ?>
		<?php echo $form->dropDownList($model,'type',  Contacts::$type); ?>
	</div>	

	<div>
		<?php echo $form->label($model,'isread'); ?>
		<?php echo $form->dropDownList($model,'isread',  Contacts::$isread); ?>
	</div>

	<div>
		<?php echo CHtml::submitButton('Поиск',array('id'=>'submit_save_button')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->