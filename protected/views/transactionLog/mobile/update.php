<?php $this->headering = 'Редактирование'; ?>

<?php
$this->menu=array(
	array('label'=>'Список билетов', 'url'=>array('index')),
	array('label'=>'Создать', 'url'=>array('create')),
	array('label'=>'Просмотр', 'url'=>array('view', 'id'=>$model->log_id)),
	//array('label'=>'Управление', 'url'=>array('admin')),
);
?>

<?php echo $this->renderPartial(Yii::app()->mf->siteType(). '/_form', array('model'=>$model)); ?>