<?php
$this->menu=array(
	array('label'=>'Список мероприятий', 'url'=>array('index')),
	array('label'=>'Создать', 'url'=>array('create')),
	array('label'=>'Просмотр', 'url'=>array('view', 'id'=>$model->id)),
	//array('label'=>'Управление мероприятиями', 'url'=>array('admin')),
);
?>

<h4>Редактирование мероприятия:</h4>

<?php echo $this->renderPartial(Yii::app()->mf->siteType(). '/_form', array(
									'model'=>$model,
									'tickets1'=>$tickets1,
									'ticket'=>$ticket,
									//'tickets2'=>$tickets2,
									//'tickets3'=>$tickets3,
									)); ?>