<!--<td><?php //echo CHtml::encode($data->getAttributeLabel('id')); ?>:</td>
<?php //echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
<br />-->

<td><?php echo CHtml::encode($data->tag_uniq); ?></td>
<td class="last_cell"><?php echo CHtml::encode($data->description); ?></td>
<td><?php echo CHtml::link(CHtml::encode('Редактировать'), array('update', 'id'=>$data->id)); ?></td>
