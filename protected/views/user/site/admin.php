<?php $this->pageTitle=Yii::app()->name.' - Управление пользователями' ?>

<?php
$this->breadcrumbs=array(
	'пользователи'=>array('index'),
	'Управление',
);

$this->menu=array(
	array('label'=>'Список пользователей', 'url'=>array('index')),
	array('label'=>'Создать', 'url'=>array('create')),
);
?>
<div class="main_form_wrapper list_buy_events">
    <h1>Управление пользователями</h1>
    <div id="list_tickets">
        <p>Для поиска вы можете использовать операторы сравнения: (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>, <b>=</b>).</p>

        <?php echo CHtml::link(CHtml::encode('Расширенный поиск'),'#',array('class'=>'search-button')); ?>
        <div class="search-form" style="display:none">
        <?php $this->renderPartial(Yii::app()->mf->siteType(). '/_search',array(
                'model'=>$model,
        )); ?>
        </div><!-- search-form -->

        <?php $this->widget('zii.widgets.grid.CGridView', array(
                'id'=>'user-grid',
                'itemsCssClass'=>'',
                'rowCssClass'=>array('',''),
                'dataProvider'=>$model->search(),
                //'filter'=>$model,
                'columns'=>array(
					'name',
					'phone',
					'email',              
					array(
						'name'=>'role',
						'value'=>'$data->role',
						'filter'=>User::$ROLE,
					),
					'organization',
					array(
						'class'=>'CButtonColumn',
					),
                ),
				'hideHeader'=>true,
			)); ?>
    </div>
</div>
<script type="text/javascript">
	$('.search-button').click(function(){
		$('.search-form').toggle();
		return false;
	});
	$('.search-form form').submit(function(){
		$.fn.yiiGridView.update('user-grid', {
			data: $(this).serialize()
		});
		return false;
	});
</script>