<?php
$this->breadcrumbs=array(
	'Группы товаров'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ProductsGroup', 'url'=>array('index')),
	array('label'=>'Manage ProductsGroup', 'url'=>array('admin')),
);
?>

<h1>Create ProductsGroup</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>