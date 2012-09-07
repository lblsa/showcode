<?php
	//$html = '<h3>'.CHtml::encode($data->name).'</h3>';
	$html = '<p><strong>'.CHtml::encode($data->getAttributeLabel('user_id')).':</strong>&nbsp;' .CHtml::encode(Yii::app()->user->getAuthorName($data->user_id)). '</p>';
	$html = $html.'<p><strong>'.CHtml::encode($data->getAttributeLabel('email')).':</strong>&nbsp;' .CHtml::encode($data->email). '</p>';
	$html = $html.'<p><strong>'.CHtml::encode($data->getAttributeLabel('type')).':</strong>&nbsp;' .CHtml::encode(Contacts::$type[$data->type]). '</p>';
	$html = $html.'<p><strong>'.CHtml::encode($data->getAttributeLabel('datetime')).':</strong>&nbsp;' .CHtml::encode(Events::normalViewDate($data->datetime)). '</p>';
	$html = $html.'<p><strong>'.CHtml::encode($data->getAttributeLabel('isread')).':</strong>&nbsp;' .CHtml::encode(Contacts::$isread[$data->isread]). '</p>';
	echo CHtml::link($html, array('/contacts/view/'.$data->contact_id));
?>