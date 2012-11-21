<style>
	#list_tickets
	{
		margin-top: 20px;
		margin-bottom: 35px;
	}
	div.summary
	{
		text-align: right;
		width: 94%!important;
	}
</style>
<div id="list_tickets" >

<?php 
	$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'tickets-grid',
		'dataProvider'=>$model->searchTickets($user_id, $flag, $page),
		'columns'=>array(
			array(
				'header'=>'Мероприятие',
				'htmlOptions'=>array('style'=>'width: 40px;'),
				'value'=>'(Yii::app()->user->id == $data->user_id) ? CHtml::link(CHtml::encode(Events::getEventTitle($data->event_id)), array("ticket/view/".$data->uniq)) : CHtml::encode(Events::getEventTitle($data->event_id))',
				'type'=>'raw',
			),
			array(
				'header'=>'Тип билета',
				'value'=>'CHtml::encode(Tickets::$type_ticket[$data->type])',
			),
			array(
				'header'=>'Кол-во',
				'value'=>'$data->quantity',
			),
			array(
				'header'=>'Цена',
				'value'=>'$data->price."&nbsp;руб."',
				'type'=>'raw',
			),
			array(
				'header'=>'Итог',
				'value'=>'$data->total."&nbsp;руб."',
				'type'=>'raw',
			),
			array(
				'header'=>'Дата покупки',
				'value'=>'CHtml::encode(Events::normalViewDate($data->datetime))',
			),
			array(
				'header'=>'Покупатель',
				'value'=>'($data->user_id) ? CHtml::encode(Yii::app()->user->getAuthorName($data->user_id)) : $data->family',
			),
			array(
				'header'=>'E-mail',
				'value'=>'$data->mail',
			),
			array(
				'header'=>'Мобильный телефон',
				'value'=>'$data->phone',
			),
			array(
				'header'=>'Способ оплаты',
				'value'=>'CHtml::encode(TransactionLog::$payment_type[$data->payment])',
			),				
			array(
				'header'=>'Статус',
				'value'=>'CHtml::encode(TransactionLog::$status[$data->status])',
			),
		),
		'cssFile' => Yii::app()->baseUrl.'/css/gridview/styles.css',
	));
?>
</div>
<script type="text/javascript">

	function str_replace(search, replace, subject)
	{
		return subject.split(search).join(replace);
	}
	
	$('div.pagination_coming_events ul li a').each( function() {
		val = $(this).attr('href');
		
		val = str_replace('ajaxTicketList', 'index', val);
		$(this).attr('href', val)
	});
</script>