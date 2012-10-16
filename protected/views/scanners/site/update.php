<?php

$this->menu=array(
        array('label'=>'Назад', 'url'=>array('view', 'id'=>$model->SCANNERS_ID)),
	array('label'=>'Список', 'url'=>array('index')),
	array('label'=>'Новое устройство', 'url'=>array('create')),
);
?>

<h1>Редактировать устройство</h1>

<?php echo $this->renderPartial(Yii::app()->mf->siteType(). '/_form', array('model'=>$model)); ?>