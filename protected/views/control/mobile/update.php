<?php $this->headering = 'Редактирование'; ?>

<?php
$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	array('label'=>'Создать', 'url'=>array('create')),
	array('label'=>'Просмотр', 'url'=>array('view', 'id'=>$model->control_id)),
	//array('label'=>'Управление', 'url'=>array('admin')),
);
?>

<h3>Изменить <?php echo $model->name; ?></h3>

<?php echo $this->renderPartial(Yii::app()->mf->siteType(). '/_form', array('model'=>$model)); ?>