<?php
$this->breadcrumbs=array(
	$title=>array('/events/view/' .$id),
	'Управление',
);

$this->menu=array(
	array('label'=>$title, 'url'=>array('/events/view/' .$id)),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('transaction-log-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h4>Просмотр билетов на мероприятие:<br />«<?php echo $title; ?>»</h4>

<p>Для поиска вы можете использовать операторы сравнения: (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>, <b>=</b>).</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial(Yii::app()->mf->siteType(). '/_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'transaction-log-grid',
	'dataProvider'=>$model->search($id),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name'=>'type',
			'value'=>'Tickets::$type_ticket[$data->type]',
			'filter'=>Tickets::$type_ticket,
        ),
		'quantity',
		'price',
		'total',
		array(
		'name'=>'datetime',
		'type'=>'raw',
		'value'=>'Events::normalViewDate($data->datetime)',
		),
		array(
		'name'=>'payment',
		'value'=>'TransactionLog::$payment_type[$data->payment]',
		'filter'=>TransactionLog::$payment_type,
		),
		/*array(
		'name'=>'user_id',
		'value'=>'Yii::app()->user->getAuthorName($data->user_id)'
		),*/
		array(
		'name'=>'status',
		'value'=>'TransactionLog::$status[$data->status]',
		'filter'=>TransactionLog::$status,
		),
		/*array(
			'class'=>'CButtonColumn',
		),*/
	),
)); ?>