<?php $this->pageTitle=Yii::app()->name.' - Редактировать отзыв' ?>
<?php
$this->breadcrumbs=array(
	'Список отзывов'=>array('index'),
	$model->contact_id=>array('view','id'=>$model->contact_id),
	'Редактировать отзыв',
);

$this->menu=array(
	array('label'=>'Список отзывов', 'url'=>array('index')),
	array('label'=>'Создать отзыв', 'url'=>array('create')),
	array('label'=>'Просмотреть текущий', 'url'=>array('view', 'id'=>$model->contact_id)),
	array('label'=>'Управление', 'url'=>array('admin')),
);
?>

<h1>Редактировать отзыв <?php echo $model->contact_id; ?></h1>

<?php echo $this->renderPartial(Yii::app()->mf->siteType(). '/_form', array('model'=>$model)); ?>