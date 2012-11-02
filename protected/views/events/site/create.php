
<?php $this->pageTitle=Yii::app()->name.' - Создание мероприятия' ?>
<?php
$this->breadcrumbs=array(
	'Мероприятия'=>array('index'),
	'Создать',
);

$this->menu=array(
	array('label'=>'Список мероприятий', 'url'=>array('index')),
	array('label'=>'Управление мероприятиями', 'url'=>array('admin')),
);
?>

<h1>Создание мероприятия</h1>

<?php echo $this->renderPartial(Yii::app()->mf->siteType(). '/_form', array(
									'model'=>$model,
									'tickets1'=>$tickets1,
									'modelOrg'=>$modelOrg,
									'ids' =>$ids,
									'values' =>$values,
									//'tickets2'=>$tickets2,
									//'tickets3'=>$tickets3,
									)); ?>