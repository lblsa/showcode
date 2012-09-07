<?php
$this->breadcrumbs=array(
	'Products Elements'=>array('index'),
	$model->title=>array('view','id'=>$model->product_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ProductsElement', 'url'=>array('index')),
	array('label'=>'Create ProductsElement', 'url'=>array('create')),
	array('label'=>'View ProductsElement', 'url'=>array('view', 'id'=>$model->product_id)),
	array('label'=>'Manage ProductsElement', 'url'=>array('admin')),
);
?>

<h1>Update ProductsElement <?php echo $model->product_id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>