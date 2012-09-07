<?php
$this->breadcrumbs=array(
	'Группы товаров'=>array('../index.php/productsgroup'),
	$this->get_group_name($model->group_id)=>array('?group='. $model->group_id),
	$model->title,
);
$this->menu=array(
	array('label'=>'List ProductsElement', 'url'=>array('index')),
	array('label'=>'Create ProductsElement', 'url'=>array('create')),
	array('label'=>'Update ProductsElement', 'url'=>array('update', 'id'=>$model->product_id)),
	array('label'=>'Delete ProductsElement', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->product_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ProductsElement', 'url'=>array('admin')),
);
?>

<h1><?php echo $model->title; ?></h1>

<?php
$group_title = $this->get_group_name($model->group_id);
$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'product_id',
		'title',
		'description',
		'price',
		array(
            'name'=>'group_id',
            'type'=>'raw',
            'value'=>$group_title,
        ),
	),
)); ?>
