<?php
$this->breadcrumbs=array(
	'Группы товаров'=>array('../index.php/productsgroup'),
	$this->get_group_name($group_id),
);

$this->menu=array(
	array('label'=>'Create ProductsElement', 'url'=>array('create')),
	array('label'=>'Manage ProductsElement', 'url'=>array('admin')),
);
?>

<h1><?php echo $this->get_group_name($group_id)?></h1>

<?php
$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
