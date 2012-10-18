<?php
$this->breadcrumbs=array(
	'Группы товаров',
);

$this->menu=array(
	array('label'=>'Create ProductsGroup', 'url'=>array('create')),
	array('label'=>'Manage ProductsGroup', 'url'=>array('admin')),
);
?>

<h1>Группы товаров</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
