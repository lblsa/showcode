<?php $this->headering = 'Редактирование'; ?>

<?php $this->menu=array(
	array('label'=>'Список отзывов', 'url'=>array('index')),
	array('label'=>'Создать отзыв', 'url'=>array('create')),
	array('label'=>'Просмотреть текущий', 'url'=>array('view', 'id'=>$model->contact_id)),
	//array('label'=>'Управление', 'url'=>array('admin')),
);
?>

<h4>Редактировать отзыв <?php echo $model->contact_id; ?></h4>

<?php echo $this->renderPartial(Yii::app()->mf->siteType(). '/_form', array('model'=>$model)); ?>