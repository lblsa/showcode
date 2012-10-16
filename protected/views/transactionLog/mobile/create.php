<?php $this->headering = 'Создать'; ?>

<?php
$this->menu=array(
	array('label'=>'Список билетов', 'url'=>array('index')),
	//array('label'=>'Управление', 'url'=>array('admin')),
);
?>

<h3>Create TransactionLog</h3>

<?php echo $this->renderPartial(Yii::app()->mf->siteType(). '/_form', array('model'=>$model)); ?>