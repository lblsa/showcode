<li>
	<?php
		$html = CHtml::image($data->logo,'logo');
		$html = $html.'<h3>'.CHtml::encode($data->title).'</h3>';
		$html = $html.'<p><strong>Дата:</strong>&nbsp;' .CHtml::encode($data->getEventDate($data->id)). '</p>';
		$html = $html.'<p><strong>Время:</strong>&nbsp;' .CHtml::encode($data->getEventTime($data->id)). '</p>';
		if($data->address)
			$html = $html.'<p><strong>Место:</strong>&nbsp;' .CHtml::encode($data->address). '</p>';
		$html = $html.'<p><strong>Автор:</strong>&nbsp;' .CHtml::encode(Yii::app()->user->getAuthorName($data->author)). '</p>';
		$html = $html.'<p>'.'<strong>'.CHtml::encode($data->getAttributeLabel('status')).':</strong>&nbsp;'.CHtml::encode(Events::$STATUS[$data->status]).'</p>';
		echo CHtml::link($html, array('view', 'id'=>$data->id));
	?>
</li>