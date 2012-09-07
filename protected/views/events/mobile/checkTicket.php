<?php
$this->menu=array(
	array('label'=>'Список мероприятий', 'url'=>array('index')),
	array('label'=>'Создать мероприятие', 'url'=>array('create')),
	array('label'=>'Управление мероприятиями', 'url'=>array('admin')),
);
?>

<h4>Проверка билетов на мероприятие:<br /> <?php echo $title; ?></h4>
<?php if ($model==null): ?>
	<p style="color:red;">Вы не можете проверять билеты. Данное мероприятие сегодня не проходит.</p>
<?php else: ?>

	<div class="form">

	<?php
		$form=$this->beginWidget('CActiveForm', array(
		'id'=>'events-form',
		'enableAjaxValidation'=>false,
		'htmlOptions'=>array('enctype'=>'multipart/form-data'),
	)); ?>

		<div class="row">
			<?php echo $form->labelEx($model,'uniq'); ?>
			<?php echo $form->textField($model,'uniq',array('size'=>15,'maxlength'=>10)); ?>
			<?php echo $form->error($model,'uniq'); ?>
		</div>
		
		<div class="row buttons">
			<?php echo CHtml::submitButton('Проход'); ?>
		</div>

		
	<?php if($model->status==1): ?>
			<p>Вы успешно активировали билет.
			<?php if ($model->type=='reusable') echo 'проходов осталось: ' .$model->quantity; ?>
			<?php if ($model->type=='travel'): ?> Период действия: с <?php echo $date_begin; ?> по <?php echo $date_end; ?> <?php endif; ?>
			</p>
	<?php elseif(isset($model->status)): ?>
			<div class="errorMessage">Билет не действителен. статус: <?php if ($model->status==4) echo CHtml::encode('Билет сегодня не действует'); else echo TransactionLog::$status[$model->status];?>.</div>
	<?php endif; ?>
		
	<?php $this->endWidget(); ?>

	</div>

<?php endif; ?>