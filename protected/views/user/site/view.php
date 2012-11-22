<?php $this->pageTitle=Yii::app()->name.' - Пользователь "'.$model->name.'"' ?>
<?php
if (yii::app()->user->isAdmin())
	$this->breadcrumbs['Пользователи']=array('index');
$this->breadcrumbs[]=$model->name;

if (Yii::app()->user->isAdmin())
{
	$this->menu=array(
		array('label'=>'Список пользователей', 'url'=>array('index')),
		array('label'=>'Создать', 'url'=>array('create')),
		array('label'=>'Редактировать', 'url'=>array('update', 'id'=>$model->user_id)),
		array('label'=>'Удалить', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->user_id),'confirm'=>'Are you sure you want to delete this item?')),
		array('label'=>'Управление пользователями', 'url'=>array('admin')),
	);
}
else
	$this->menu=array(
		array('label'=>'Изменить информацию', 'url'=>array('update', 'id'=>$model->user_id)),
	);
?>

<div class="main_form_wrapper list_buy_events">
    <div id="list_tickets">
	<h1>Пользователь <?php echo $model->name; ?></h1>

	<?php
	if (yii::app()->user->isAdmin() || Yii::app()->user->isOrganizer())
		$attributes[] = 'uniq';
	$attributes[] = 'email';
	if($model->phone)
		$attributes[] = 'phone';
	$attributes[] = 'name';
	$attributes[] = array(
			'name'=>'role',
			'type'=>'raw',
			'value'=>User::$ROLE[$model->role]
	);
	$attributes[] = 'organization';

	$this->widget('zii.widgets.CDetailView', array(
		'data'=>$model,
		'attributes'=>$attributes,
			'tagName'=>'table id="user_info_table"',
			'itemTemplate'=>"<tr><td class=\"first_column\">{label}</td><td class=\"second_column\">{value}</td></tr>\n",
			'htmlOptions'=>array('id'=>'user_info_table'),
			'itemCssClass'=>array('')
	)); ?>

	</div>
</div>