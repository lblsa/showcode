<style>
	tr.title_table th a
	{
		text-decoration: none;
		font-weight: normal;
	}
</style>
<div id="list_tickets<?php echo $uniqEvent->prefix_class;?>">

	<h2>Билеты:</h2>
	
	<?php 
		$columns = array();
		$columns1 = array();
		
		if(!$uniqEvent->infinity_qantitty)
		{
			if ($log)
			{
				if ($buy)
				{
					if($log->ticket_id == $value->ticket_id)
						$check = true;
					else
						$check = false;			
				
					$columns = array(
						array(
							'header'=>'',
							'value'=>'CHtml::radioButton("TransactionLog[ticket_id]", $check, array("id"=>"TransactionLog_ticket_id_".$data->ticket_id,"value"=>$data->ticket_id,"price"=>$data->price, "quantity"=>$data->quantity))',
							'type'=>'raw',
							'htmlOptions'=>array('style'=>'width: 20px;'),
						),
						array(
							'header'=>'Осталось',
							'htmlOptions'=>array('style'=>'width: 40px;'),
							'value'=>'$data->quantity',
						),
					);
				}
			}
			else
				$columns[] = array(
					'header'=>'Количество',				
					'value'=>'$data->quantity',
					'type' =>'raw',
					'htmlOptions'=>array('style'=>'width: 40px;'),
				);
		}	
		$columns1 = array(
			array(
				'name'=>'price',
				'htmlOptions'=>array('style'=>'width: 40px;'),
				'value'=>'$data->price." руб."',
			),
			array(
				'name'=>'time_begin',
				'htmlOptions'=>array('style'=>'width: 100px;'),
			),
			array(
				'name'=>'time_end',
				'htmlOptions'=>array('style'=>'width: 100px;'),
			),
			'description',
		);
		
		$columns = array_merge($columns, $columns1);
		
		$this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'tickets-grid',
			'dataProvider'=>$tickets->searchTisk($ticket[0]->event_id),
			'columns'=>$columns,
			'cssFile' => Yii::app()->baseUrl.'/css/gridview/styles.css',
		));
	?>
</div>