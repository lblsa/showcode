<?php $this->headering = 'Редактирование'; ?>

<?php
if (Yii::app()->user->isAdmin())
	$this->menu=array(
		array('label'=>'Список пользователей', 'url'=>array('index')),
		array('label'=>'Создать', 'url'=>array('create')),
		array('label'=>'Просмотр', 'url'=>array('view', 'id'=>$model->user_id)),
		//array('label'=>'Управление пользователями', 'url'=>array('admin')),
	);
?>

<h3>Редактирование данных <?php echo $model->name; ?></h3>

<?php echo $this->renderPartial(Yii::app()->mf->siteType(). '/_form', array(
			'model'=>$model,
			'roles'=>$roles,
			)); ?>