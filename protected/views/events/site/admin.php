<?php $this->pageTitle=Yii::app()->name.' - Управление мероприятиями' ?>

<?php
$this->breadcrumbs=array(
	'Мероприятия'=>array('index'),
	'Управление мероприятиями',
);
?>

<div class="main_form_wrapper list_buy_events">

    <h1>Управление мероприятиями</h1>
    <div id="list_tickets">
        <p>Для поиска вы можете использовать операторы сравнения: (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>, <b>=</b>).</p>

        <?php echo CHtml::link(CHtml::encode('Расширенный поиск'),'#',array('class'=>'search-button')); ?>
        <div class="search-form" style="display:none">
            <?php $this->renderPartial(Yii::app()->mf->siteType(). '/_search',array(
                    'model'=>$model,
            )); ?>
        </div><!-- search-form -->
        <?php $this->widget('zii.widgets.grid.CGridView', array(
                'id'=>'events-grid',
                'itemsCssClass'=>'',
                'rowCssClass'=>array('',''),
                'dataProvider'=>$model->search(),
                //'filter'=>$model,
                'columns'=>array(
                        array(
							'name'=>'logo',
							'type'=>'image',
							'value'=>'$data->changeNameImageOnMini($data->logo)',
                        ),
                        array(
							'name'=>'title',
							'type'=>'raw',
							'value'=>'CHtml::link(CHtml::encode($data->title), "view/".$data->id)',
						),
                        //'description',
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
                        /*array(
							'name'=>'status',
							'value'=>'Events::$STATUS[$data->status]',
							'filter'=>Events::$STATUS,
						),*/                        
                        array(
							'class'=>'CButtonColumn',
                        ),
					),
            'hideHeader'=>true,
			'cssFile' => Yii::app()->baseUrl.'/css/gridview/styles.css',
        )); ?>
    </div>
</div>
<script type="text/javascript">	
	$(document).ready(function(){
		$('.search-button').click(function(){
			$('.search-form').toggle();
			return false;
		});
		$('.search-form form').submit(function(){
			$.fn.yiiGridView.update('events-grid', {
				data: $(this).serialize()
			});
			return false;
		});
	});
	
</script>