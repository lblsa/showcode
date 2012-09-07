<?php
$this->breadcrumbs=array(
	'Create',
);

$this->menu=array(
	array('label'=>'List ProductsElement', 'url'=>array('index')),
	array('label'=>'Manage ProductsElement', 'url'=>array('admin')),
);
?>

<h1>Create ProductsElement</h1>

<?php
foreach($this->group_data() as $str) {
	$group_data[$str['group_id']] = $str['title'];
}

echo $this->renderPartial('_form', array('model'=>$model, 'group_data'=>$group_data));
?>