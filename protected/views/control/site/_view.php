<!--<td><?php //echo CHtml::link(CHtml::encode($data->control_id), array('view', 'id'=>$data->control_id)); ?></td>-->

<td><?php echo CHtml::link(CHtml::encode($data->name), array('/control/view/'.$data->control_id)); ?></td>

<td><?php echo CHtml::encode($data->value); ?></td>

<?php if($data->description): ?>
    <td class="last_cell"><?php echo CHtml::encode($data->description); ?></td>
<?php else: ?>
    <td class="last_cell"></td>
<?php endif; ?>