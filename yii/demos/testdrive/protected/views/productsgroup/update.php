<?php
$this->breadcrumbs=array(
	'Products Groups'=>array('index'),
	$model->title=>array('view','id'=>$model->group_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ProductsGroup', 'url'=>array('index')),
	array('label'=>'Create ProductsGroup', 'url'=>array('create')),
	array('label'=>'View ProductsGroup', 'url'=>array('view', 'id'=>$model->group_id)),
	array('label'=>'Manage ProductsGroup', 'url'=>array('admin')),
);
?>

<h1>Update ProductsGroup <?php echo $model->group_id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>