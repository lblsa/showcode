<?php
    Yii::app()->clientScript->registerScript('name_js1','
        $("#list_tickets table tr").click(function()
	{
            if(this.children[0].getElementsByTagName("a").item("a")){
                var to_link = this.children[0].getElementsByTagName("a").item("a").href;
                document.location = to_link;
            }
            return true;
	});
    ');
?>

<?php //if (Yii::app()->user->id == $data->user_id):?>
    <!--<td><?php //echo CHtml::link(CHtml::encode('Просмотреть'), array('view', 'id'=>$data->uniq)); ?></td>-->
<?php //else: ?>
    <!--<td></td>-->
<?php //endif; ?>

<?php if (Yii::app()->user->id == $data->user_id):?>
    <td><?php echo CHtml::link(CHtml::encode(Events::getEventTitle($data->event_id)), array('ticket/view/'.$data->uniq)); ?></td>
<?php else: ?>
    <td><?php echo CHtml::encode(Events::getEventTitle($data->event_id)); ?></td>
<?php endif; ?>

<td><?php echo CHtml::encode(Tickets::$type_ticket[$data->type]); ?></td>

<td><?php echo CHtml::encode($data->quantity); ?></td>

<?php if($data->type != 'free'):?>
    <td><?php echo CHtml::encode($data->price); ?></td>
    <td><?php echo CHtml::encode($data->total); ?></td>
<?php else: ?>
    <td></td>
    <td></td>
<?php endif; ?>

<td><?php echo CHtml::encode(Events::normalViewDate($data->datetime)); ?></td>

<?php if($data->user_id):?>
    <td><?php echo CHtml::encode(Yii::app()->user->getAuthorName($data->user_id)); ?></td>
<?php else:?>
    <td><?php echo CHtml::encode($data->family); ?></td>
<?php endif; ?>
 <td><?php echo CHtml::encode($data->mail); ?></td>
 <td><?php echo CHtml::encode($data->phone); ?></td>

<?php if($data->type != 'free'):?>
    <td><?php echo CHtml::encode(TransactionLog::$payment_type[$data->payment]); ?></td>
<?php else: ?>
    <td></td>
<?php endif; ?>

    <td><?php echo CHtml::encode(TransactionLog::$status[$data->status]); ?></td>