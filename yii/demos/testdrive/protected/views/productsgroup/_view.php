<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('group_id')); ?>:</b>
	<?php echo '<a href="../index.php/productsElement?group=' .$data->group_id. '">' .$data->group_id. '</a>';?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

</div>