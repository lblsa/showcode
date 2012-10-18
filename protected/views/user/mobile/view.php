<?php $this->headering = 'Пользователь '.$model->name; ?>

<?php
if (Yii::app()->user->isAdmin())
{
	$this->menu=array(
		array('label'=>'Список пользователей', 'url'=>array('index')),
		array('label'=>'Создать', 'url'=>array('create')),
		array('label'=>'Редактировать', 'url'=>array('update', 'id'=>$model->user_id)),
		array('label'=>'Удалить', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->user_id),'confirm'=>'Are you sure you want to delete this item?')),
		//array('label'=>'Управление пользователями', 'url'=>array('admin')),
	);
}
else
	$this->menu=array(
		array('label'=>'Изменить информацию', 'url'=>array('update', 'id'=>$model->user_id)),
	);
?>

<?php
	$attributes[] = 'name';
	$attributes[] = 'phone';
	$attributes[] = array(
		'name'=>'role',
		'type'=>'raw',
		'value'=>User::$ROLE[$model->role]
	);
	$attributes[] = 'email';
	if (yii::app()->user->isAdmin() || Yii::app()->user->isOrganizer())
		$attributes[] = 'uniq';

	$attributes[] = 'organization';

	$this->widget('zii.widgets.CDetailView', array(
		'data'=>$model,
		'attributes'=>$attributes,
		'tagName' => 'ul',
	    'itemTemplate' => '<li data-role="list-divider" role="heading">{label}</li><li>{value}</li></li>',
	    'itemCssClass'=> array(),
	    'htmlOptions'=>array('data-role'=>'listview', 'data-theme'=>"c", 'data-inset'=>"true"),
	));
?>