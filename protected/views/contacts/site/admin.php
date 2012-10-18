<?php $this->pageTitle=Yii::app()->name.' - Управление отзывами' ?>
<?php
$this->breadcrumbs=array(
	'Отзывы'=>array('index'),
	'Управление',
);

$this->menu=array(
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
<div class="main_form_wrapper list_buy_events">

    <h1>Управление</h1>
    <div id="list_tickets">
        <p>Для поиска вы можете использовать операторы сравнения: (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>, <b>=</b>).</p>

        <?php echo CHtml::link(CHtml::encode('Расширенный поиск'),'#',array('class'=>'search-button')); ?>
        <div class="search-form" style="display:none">
        <?php $this->renderPartial(Yii::app()->mf->siteType(). '/_search',array(
                'model'=>$model,
        )); ?>
        </div><!-- search-form -->

        <?php $this->widget('zii.widgets.grid.CGridView', array(
                'id'=>'contacts-grid',
                'itemsCssClass'=>'',
                'rowCssClass'=>array('',''),
                'hideHeader'=>true,
                'dataProvider'=>$model->search(),
                //'filter'=>$model,
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
                    array(
                        'name'=>'datetime',
                        'type'=>'raw',
                        'value'=>'Events::normalViewDate($data->datetime)',
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

    </div>
</div>
