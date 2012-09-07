<?php $this->pageTitle=Yii::app()->name.' - Просмотр настройки' ?>

<?php
$this->breadcrumbs=array(
	'Настройки'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	array('label'=>'Создать', 'url'=>array('create')),
	array('label'=>'Изменить', 'url'=>array('update', 'id'=>$model->control_id)),
	//array('label'=>'Удалить', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->control_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'управление', 'url'=>array('admin')),
);
?>
<div class="main_form_wrapper list_buy_events">
    <div id="list_tickets">
        
<h1>Просмотр настройки #<?php echo $model->control_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		//'control_id',
		'name',
		'value',
		'description',
	),
    'tagName'=>'table id="user_info_table"',
    'itemTemplate'=>"<tr><td class=\"first_column\">{label}</td><td class=\"second_column\">{value}</td></tr>\n",
    'htmlOptions'=>array('id'=>'user_info_table'),
    'itemCssClass'=>array('')
)); ?>

</div>
    </div>
