<?php $this->menu=array(
	array('label'=>'Список отзывов', 'url'=>array('index')),
	array('label'=>'Создать отзыв', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('contacts-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h4>Управление</h4>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial(Yii::app()->mf->siteType(). '/_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'contacts-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
            'name'=>'user_id',
            'type'=>'raw',
            'value'=>'Yii::app()->user->getAuthorName($data->user_id)',
        ),
		'email',
		array(
			'name'=>'type',
			'value'=>'Contacts::$type[$data->type]',
			'filter'=>Contacts::$type,
        ),
		//'message',
		array(
			'name'=>'isread',
			'value'=>'Contacts::$isread[$data->isread]',
			'filter'=>Contacts::$isread,
        ),
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
