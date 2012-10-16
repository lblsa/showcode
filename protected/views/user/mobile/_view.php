<?php
	$html = '<h3>'.CHtml::encode($data->name).'</h3>';
	$html = $html.'<p><strong>'.CHtml::encode($data->getAttributeLabel('phone')).':</strong>&nbsp;' .CHtml::encode($data->phone). '</p>';
	if($data->email)
		$html = $html.'<p><strong>'.CHtml::encode($data->getAttributeLabel('email')).':</strong>&nbsp;' .CHtml::encode($data->email). '</p>';
	$html = $html.'<p><strong>'.CHtml::encode($data->getAttributeLabel('role')).':</strong>&nbsp;' .CHtml::encode($data->role). '</p>';
	if($data->organization)
		$html = $html.'<p><strong>'.CHtml::encode($data->getAttributeLabel('organization')).':</strong>&nbsp;' .CHtml::encode($data->organization). '</p>';
	echo CHtml::link($html, array('/user/view/'.$data->user_id));
?>