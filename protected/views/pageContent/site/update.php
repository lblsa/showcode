<?php $this->pageTitle=Yii::app()->name.' - Редактировать элемент контента' ?>
<?php
$this->breadcrumbs=array(
	'Page Contents'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'Вернуться', 'url'=>array('index')),		
);
?>

<h1>Редактирование<?php //echo $model->еф; ?></h1>

<?php echo $this->renderPartial(Yii::app()->mf->siteType(). '/_form', array('model'=>$model)); ?>