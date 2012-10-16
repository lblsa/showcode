<?php $this->headering = 'Создание мероприятия'; ?>

<?php
$this->menu=array(
	array('label'=>'Список мероприятий', 'url'=>array('index')),
	//array('label'=>'Управление мероприятиями', 'url'=>array('admin')),
);
?>

<?php echo $this->renderPartial(Yii::app()->mf->siteType(). '/_form', array(
									'model'=>$model,
									'tickets1'=>$tickets1,
									//'tickets2'=>$tickets2,
									//'tickets3'=>$tickets3,
									)); ?>