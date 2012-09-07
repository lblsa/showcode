<?php $this->pageTitle=Yii::app()->name.' - Редактировать настройку "'.$model->тфьу.'"' ?>
<?php
$this->breadcrumbs=array(
	'Настройки'=>array('index'),
	$model->name=>array('view','id'=>$model->control_id),
	'Изменить',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	array('label'=>'Создать', 'url'=>array('create')),
	array('label'=>'Просмотр', 'url'=>array('view', 'id'=>$model->control_id)),
	array('label'=>'Управление', 'url'=>array('admin')),
);
?>

<h1>Изменить <?php echo $model->name; ?></h1>

<?php echo $this->renderPartial(Yii::app()->mf->siteType(). '/_form', array('model'=>$model)); ?>