<!--<td><?php //echo CHtml::link(CHtml::encode($data->contact_id), array('view', 'id'=>$data->contact_id)); ?></td>-->

<td><?php echo CHtml::link(CHtml::encode(Yii::app()->user->getAuthorName($data->user_id)), array('/contacts/view/'.$data->contact_id)); ?></td>

<td><?php echo CHtml::encode($data->email); ?></td>

<td><?php echo CHtml::encode(Contacts::$type[$data->type]); ?></td>

<td><?php echo CHtml::encode(Events::normalViewDate($data->datetime)); ?></td>

<!--
<td><?php //echo CHtml::encode($data->message); ?></td>
-->

<td><?php echo CHtml::encode(Contacts::$isread[$data->isread]); ?></td>