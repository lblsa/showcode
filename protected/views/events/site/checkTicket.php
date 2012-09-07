<?php $this->pageTitle=Yii::app()->name.' - Проверка билетов на мероприятие: "'.$title.'"' ?>
<?php
$this->breadcrumbs=array(
	'Мероприятия'=>array('index'),
	//$model->title,
);

$this->menu=array(
        array('label'=>'Вернуться', 'url'=>array('view','id'=>$event_id)),
	array('label'=>'Список мероприятий', 'url'=>array('index')),
        array('label'=>'Список билетов', 'url'=>array('/ticket')),
	array('label'=>'Создать мероприятие', 'url'=>array('create')),
);
?>

<div class="main_form_wrapper list_buy_events">
    <div id="list_tickets">
        <h1 align="center">Проверка билетов на мероприятие:<br /> <?php echo $title; ?></h1>
        <?php if ($model==null): ?>
                <p style="color:red;">Вы не можете проверять билеты. Данное мероприятие сегодня не проходит.</p>
        <?php else: ?>

                <div class="form_main">

                    <?php
                        $form=$this->beginWidget('CActiveForm', array(
                        'enableAjaxValidation'=>false,
                        'htmlOptions'=>array('enctype'=>'multipart/form-data'),
                        'action'=>'?markticket'
                    )); ?>

                        <div class="row">
                                <?php echo $form->labelEx($model,'uniq'); ?>
                                <?php echo $form->textField($model,'uniq',array('size'=>15,'maxlength'=>10)); ?>
                                <?php echo $form->error($model,'uniq'); ?>
                        </div>

                        <div class="row buttons">
                                <?php echo CHtml::submitButton('Проход',array('id'=>'submit_save_button')); ?>
                        </div>


                <?php if($model->status==1): ?>
                                <p>Вы успешно активировали билет.
                                <?php if ($model->type=='reusable') echo 'проходов осталось: ' .$model->quantity; ?>
                                <?php if ($model->type=='travel'): ?> Период действия: с <?php echo Events::model()->normalViewDate($date_begin); ?> по <?php echo Events::model()->normalViewDate($date_end); ?> <?php endif; ?>
                                </p>
                <?php elseif(isset($model->status)): ?>
                                <div class="errorMessage">Билет не действителен. статус: <?php if ($model->status==4) echo CHtml::encode('Билет сегодня не действует'); else echo TransactionLog::$status[$model->status];?>.</div>
                <?php endif; ?>

                <?php $this->endWidget(); ?>

                </div>

        <?php endif; ?>
    </div>
</div>
