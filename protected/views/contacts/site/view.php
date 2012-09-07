<?php $this->pageTitle=Yii::app()->name.' - Просмотр отзыва' ?>

<?php
$this->breadcrumbs=array(
	'Отзывы'=>array('index'),
	$model->contact_id,
);

$this->menu=array(
	array('label'=>'Список отзывов', 'url'=>array('index')),
	array('label'=>'Создать', 'url'=>array('create')),
	array('label'=>'Редактировать', 'url'=>array('update', 'id'=>$model->contact_id)),
	array('label'=>'Удалить', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->contact_id),'confirm'=>'Вы действительно хотите удалить отзыв?')),
	array('label'=>'Управление', 'url'=>array('admin')),
);
?>

<div class="main_form_wrapper list_buy_events">
    <div id="list_tickets">
<!-- <h1>View Contacts #<?php echo $model->contact_id; ?></h1> -->

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(		
		'user_id',
		'email',
		'type',
		'message',
		'datetime',
	),
    'tagName'=>'table id="user_info_table"',
    'itemTemplate'=>"<tr><td class=\"first_column\">{label}</td><td class=\"second_column\">{value}</td></tr>\n",
    'htmlOptions'=>array('id'=>'user_info_table'),
    'itemCssClass'=>array('')
)); ?>
</div>
    </div>