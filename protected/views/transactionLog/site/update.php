<?php $this->pageTitle=Yii::app()->name.' - Update TransactionLog' ?>

<?php
$this->breadcrumbs=array(
	'Билеты'=>array('index'),
	$model->log_id=>array('view','id'=>$model->uniq),
	'Редактировать',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	array('label'=>'Создать', 'url'=>array('create')),
	array('label'=>'Просмотр', 'url'=>array('view', 'id'=>$model->log_id)),
	array('label'=>'Управление', 'url'=>array('admin')),
);
?>

<h1>Update TransactionLog <?php echo $model->log_id; ?></h1>

<?php echo $this->renderPartial(Yii::app()->mf->siteType(). '/_form', array('model'=>$model)); ?>