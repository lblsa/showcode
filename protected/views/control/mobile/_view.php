<?php
	$html = '<h3>'.CHtml::encode($data->name).'</h3>';
	$html = $html.'<p><strong>'.CHtml::encode($data->getAttributeLabel('value')).':</strong>&nbsp;' .CHtml::encode($data->value). '</p>';
	if($data->description)
		$html = $html.'<p><strong>'.CHtml::encode($data->getAttributeLabel('email')).':</strong>&nbsp;' .CHtml::encode($data->description). '</p>';
	echo CHtml::link($html, array('/control/view/'.$data->control_id));
?>