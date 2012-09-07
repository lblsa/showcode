<?php $this->headering = $model->type; ?>

<?php $this->menu=array(
	array('label'=>'Список отзывов', 'url'=>array('index')),
	array('label'=>'Создать', 'url'=>array('create')),
	array('label'=>'Редактировать', 'url'=>array('update', 'id'=>$model->contact_id)),
	array('label'=>'Удалить', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->contact_id),'confirm'=>'Вы действительно хотите удалить отзыв?')),
	//array('label'=>'Управление', 'url'=>array('admin')),
);
?>

<!-- <h1>View Contacts #<?php echo $model->contact_id; ?></h1> -->

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		//'contact_id',
		'user_id',
		'email',
		'type',
		'message',
		//'isread',
	),
	'tagName' => 'ul',
    'itemTemplate' => '<li data-role="list-divider" role="heading">{label}</li><li>{value}</li></li>',
    'itemCssClass'=> array(),
    'htmlOptions'=>array('data-role'=>'listview', 'data-theme'=>"c", 'data-inset'=>"true"),
)); ?>
