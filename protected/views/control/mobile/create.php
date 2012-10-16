<?php $this->headering = 'Создание'; ?>

<?php
$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	//array('label'=>'Управление', 'url'=>array('admin')),
);
?>

<h3>Создать настройку</h3>

<?php echo $this->renderPartial(Yii::app()->mf->siteType(). '/_form', array('model'=>$model)); ?>