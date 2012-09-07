<?php $this->pageTitle=Yii::app()->name.' - Manage Controls' ?>
<?php
$this->breadcrumbs=array(
	'Настройки'=>array('index'),
	'Управление',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	array('label'=>'Создать', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('control-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div class="main_form_wrapper list_buy_events">
    <h1>Manage Controls</h1>
    <div id="list_tickets">
        <p>Для поиска вы можете использовать операторы сравнения: (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>, <b>=</b>).</p>

        <?php echo CHtml::link(CHtml::encode('Расширенный поиск'),'#',array('class'=>'search-button')); ?>
        <div class="search-form" style="display:none">
        <?php $this->renderPartial(Yii::app()->mf->siteType(). '/_search',array(
                'model'=>$model,
        )); ?>
        </div><!-- search-form -->

        <?php $this->widget('zii.widgets.grid.CGridView', array(
                'id'=>'control-grid',
                'itemsCssClass'=>'',
                'rowCssClass'=>array('',''),
                'hideHeader'=>true,
                'dataProvider'=>$model->search(),
                //'filter'=>$model,
                'columns'=>array(
                        //'control_id',
                        'name',
                        'value',
                        
                        array(
                            'name' => 'description',
                            'type' => 'raw',                            
                            'value'=>$model->description,
                        ),
                        array(
                                'class'=>'CButtonColumn',
                        ),
                ),
            
        )); ?>

    </div>
</div>
