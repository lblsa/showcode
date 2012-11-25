<div id="list_tickets">
<?php 
	$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'tickets-grid',
		'dataProvider'=>$model->search($event_id, true, $period, $date_begin, $date_end),
		'columns'=>array(
			array(
				'header'=>'Дата',
				'htmlOptions'=>array('style'=>'width: 25%;'),
				'value'=>'(isset($data->e) && isset($data->b)) ? CHtml::encode(Events::normalViewDate($data->b))." - ".CHtml::encode(Events::normalViewDate($data->e)) : CHtml::encode(Events::normalViewDate($data->datetime))',
				'footer'=>'Итого',
				'footerHtmlOptions'=>array('class'=>'summ', 'style'=>'text-align: left'),
			),
			array(
				'header'=>'Билет',
				'value'=>'"<strong>".$data->sPrice."&nbsp;руб.</strong>"."<br/>".CHtml::encode(Tickets::$type_ticket[$data->type])',
				'type'=>'raw',
				'htmlOptions'=>array('style'=>'text-align: center; width: 25%;'),
				'footer'=>'',
			),
			array(
				'header'=>'Куплено',
				'value'=>'$data->sQuant."/".$data->sQuant*$data->sPrice."&nbsp;руб."',
				'type'=>'raw',
				'htmlOptions'=>array('style'=>'text-align: center; width: 25%;'),
				'footer'=>$quantityAll.'/'.$qXp.'&nbsp;руб.',
				'footerHtmlOptions'=>array('class'=>'summ'),
			),
			array(
				'header'=>'Использовано',
				'value'=>'($data->status==3) ? $data->sQuant."/".$data->sQuant*$data->sPrice."&nbsp;руб." : "0/0&nbsp;руб."',
				'type'=>'raw',
				'htmlOptions'=>array('style'=>'text-align: center; width: 25%'),
				'footer'=>$quantityAllu.'/'.$qXpu.'&nbsp;руб.',
				'footerHtmlOptions'=>array('class'=>'summ'),
			),
		),
		'cssFile' => Yii::app()->baseUrl.'/css/gridview/styles.css',
		'summaryText'=>'',
	));
?>
</div>