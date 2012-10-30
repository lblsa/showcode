
<?php $this->pageTitle=Yii::app()->name.' - Редактирование мероприятия: "'.$model->title.'"' ?>
<?php
$this->breadcrumbs=array(
	'Мероприятия'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Редактирование',
);

$this->menu=array(
	array('label'=>'Список мероприятий', 'url'=>array('index')),
	array('label'=>'Создать', 'url'=>array('create')),
	array('label'=>'Просмотр', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Управление мероприятиями', 'url'=>array('admin')),
);
?>

<h1>Редактирование мероприятия: <?php echo $model->title; ?></h1>

<?php echo $this->renderPartial(Yii::app()->mf->siteType(). '/_form', array(
									'model'=>$model,
									'tickets1'=>$tickets1,
									'ticket'=>$ticket,
									'modelOrg'=>$modelOrg,
									'ids' =>$ids,
									'values' =>$values,
									//'tickets2'=>$tickets2,
									//'tickets3'=>$tickets3,
									)); ?>