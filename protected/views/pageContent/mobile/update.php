<?php
$this->breadcrumbs=array(
	'Page Contents'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List PageContent', 'url'=>array('index')),
	array('label'=>'Create PageContent', 'url'=>array('create')),
	array('label'=>'View PageContent', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage PageContent', 'url'=>array('admin')),
);
?>

<h1>Update PageContent <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>