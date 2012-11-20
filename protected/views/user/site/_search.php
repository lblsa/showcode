<br/>
<div class="form_main">

<?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'events-form',
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div>
		<?php echo $form->label($model,'phone'); ?>
		<?php echo $form->textField($model,'phone',array('size'=>60,'maxlength'=>128)); ?>
	</div>

	<div>
		<?php echo $form->label($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>128)); ?>
	</div>

	<div>
		<?php echo $form->label($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>128)); ?>
	</div>

	<div>
		<?php echo $form->label($model,'role'); ?>
		<?php echo $form->dropDownList($model,'role',  User::$ROLE); ?>
	</div>

	<div>
		<?php echo $form->label($model,'organization'); ?>
		<?php echo $form->textField($model,'organization',array('size'=>60,'maxlength'=>128)); ?>
	</div>
	
	<div>
		<?php echo CHtml::submitButton('Поиск',array('id'=>'submit_save_button')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->