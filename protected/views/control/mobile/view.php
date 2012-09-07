<?php $this->headering = 'Настройка '.$model->control_id; ?>

<?php
$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	array('label'=>'Создать', 'url'=>array('create')),
	array('label'=>'Изменить', 'url'=>array('update', 'id'=>$model->control_id)),
	//array('label'=>'Удалить', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->control_id),'confirm'=>'Are you sure you want to delete this item?')),
	//array('label'=>'Управление', 'url'=>array('admin')),
);
?>

<h3>Просмотр настройки #<?php echo $model->control_id; ?></h3>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'name',
		'value',
		'description',
	),
	'tagName' => 'ul',
    'itemTemplate' => '<li data-role="list-divider" role="heading">{label}</li><li>{value}</li></li>',
    'itemCssClass'=> array(),
    'htmlOptions'=>array('data-role'=>'listview', 'data-theme'=>"c", 'data-inset'=>"true"),
)); ?>
