<?php
$this->breadcrumbs=array(
	'Группы товаров'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List ProductsGroup', 'url'=>array('index')),
	array('label'=>'Create ProductsGroup', 'url'=>array('create')),
	array('label'=>'Update ProductsGroup', 'url'=>array('update', 'id'=>$model->group_id)),
	array('label'=>'Delete ProductsGroup', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->group_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ProductsGroup', 'url'=>array('admin')),
);
?>

<h1>View ProductsGroup #<?php echo $model->group_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'group_id',
		'title',
		'description',
	),
)); ?>
