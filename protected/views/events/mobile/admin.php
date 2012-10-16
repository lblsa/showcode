<?php
$this->breadcrumbs=array(
	'Мероприятия'=>array('index'),
	'Управление мероприятиями',
);
/*
$this->menu=array(
	array('label'=>'Список мероприятий', 'url'=>array('index')),
	array('label'=>'Создать', 'url'=>array('create')),
);
*/
$this->headering = 'Управление мероприятиями';
?>



<?php //echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form"  data-role="collapsible-set">
<?php $this->renderPartial(Yii::app()->mf->siteType(). '/_search',array(
	'model'=>$model,
)); ?>
<!-- search-form -->

<div data-role="collapsible">
	<h3>Список мероприятий</h3>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'events-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'summaryText'=>'',
	'columns'=>array(
		'id',
		array(
            'name'=>'title',
            'type'=>'raw',
            'value'=>'CHtml::link(CHtml::encode($data->title), "view/".$data->id)',
        ),
		'description',
		array(
		'name'=>'datetime',
		'type'=>'raw',
		'value'=>'$data->NormalViewDate($data->datetime)',
		),
		array(
            'name'=>'author',
            'type'=>'raw',
            'value'=>'Yii::app()->user->getAuthorName($data->author)',
        ),
		array(
			'name'=>'status',
			'value'=>'Events::$STATUS[$data->status]',
			'filter'=>Events::$STATUS,
        ),
		array(
			'name'=>'logo',
			'type'=>'image',
			'value'=>'$data->changeNameImageOnMini($data->logo)',
		),
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
</div>
</div>