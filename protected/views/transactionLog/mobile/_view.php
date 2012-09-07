<li>
	<?php
		//$html = CHtml::image($data->logo,'logo');
		$html = '<h3>'.CHtml::encode($data->getAttributeLabel('event_id')).':&nbsp;'.CHtml::encode(Events::getEventTitle($data->event_id)).'</h3>';
		$html = $html.'<p><strong>'.CHtml::encode($data->getAttributeLabel('type')).':</strong>&nbsp;' .CHtml::encode(Tickets::$type_ticket[$data->type]). '</p>';
		$html = $html.'<p><strong>'.CHtml::encode($data->getAttributeLabel('quantity')).':</strong>&nbsp;' .CHtml::encode($data->quantity). '</p>';
		if($data->type != 'free'){
			$html = $html.'<p><strong>'.CHtml::encode($data->getAttributeLabel('price')).':</strong>&nbsp;' .CHtml::encode($data->price). '</p>';
		    $html = $html.'<p><strong>'.CHtml::encode($data->getAttributeLabel('total')).':</strong>&nbsp;' .CHtml::encode($data->total). '</p>';
		}
		$html = $html.'<p><strong>'.CHtml::encode($data->getAttributeLabel('datetime')).':</strong>&nbsp;' .CHtml::encode(Events::normalViewDate($data->datetime)). '</p>';
		if($data->user_id)
			$html = $html.'<p><strong>'.CHtml::encode($data->getAttributeLabel('user_id')).':</strong>&nbsp;' .CHtml::encode(Yii::app()->user->getAuthorName($data->user_id)). '</p>';
		else{
			$html = $html.'<p><strong>'.CHtml::encode($data->getAttributeLabel('family')).':</strong>&nbsp;' .CHtml::encode($data->family). '</p>';
			$html = $html.'<p><strong>'.CHtml::encode($data->getAttributeLabel('phone')).':</strong>&nbsp;' .CHtml::encode($data->phone). '</p>';
		}
		if($data->type != 'free')
			$html = $html.'<p><strong>'.CHtml::encode($data->getAttributeLabel('payment')).':</strong>&nbsp;' .CHtml::encode(TransactionLog::$payment_type[$data->payment]). '</p>';
		$html = $html.'<p>'.'<strong>'.CHtml::encode($data->getAttributeLabel('status')).':</strong>&nbsp;'.CHtml::encode(TransactionLog::$status[$data->status]).'</p>';
		echo CHtml::link($html, array('view', 'id'=>$data->uniq));
	?>
</li>