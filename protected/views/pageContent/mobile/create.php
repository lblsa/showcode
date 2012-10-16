<?php
$this->breadcrumbs=array(
	'Page Contents'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List PageContent', 'url'=>array('index')),
	array('label'=>'Manage PageContent', 'url'=>array('admin')),
);
?>

<h1>Create PageContent</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>