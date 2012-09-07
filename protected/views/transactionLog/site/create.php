<?php $this->pageTitle=Yii::app()->name.' - Create TransactionLog' ?>

<?php
$this->breadcrumbs=array(
	'Билеты'=>array('index'),
	'Создать',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	array('label'=>'Управление', 'url'=>array('admin')),
);
?>

<h1>Create TransactionLog</h1>

<?php echo $this->renderPartial(Yii::app()->mf->siteType(). '/_form', array('model'=>$model)); ?>