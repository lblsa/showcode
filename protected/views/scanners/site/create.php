<?php

$this->menu=array(
	array('label'=>'Список устройств', 'url'=>array('index')),
);
?>

<h1>Создать устройство</h1>

<?php echo $this->renderPartial(Yii::app()->mf->siteType(). '/_form', array('model'=>$model)); ?>