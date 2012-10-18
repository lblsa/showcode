<td><?php echo CHtml::link(CHtml::encode($data->name), array('/user/view/'.$data->user_id)); ?></td>

<td><?php echo CHtml::encode($data->phone); ?></td>

<td><?php echo CHtml::encode($data->email); ?></td>

<?php //echo CHtml::encode($data->password); ?>

<td><?php echo CHtml::encode($data->role); ?></td>

<td><?php echo CHtml::encode($data->organization); ?></td>

<!--
<td><?php //echo CHtml::encode($data->profile); ?></td>
-->