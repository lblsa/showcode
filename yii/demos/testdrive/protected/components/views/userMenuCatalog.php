<ul>
    <li><?php echo CHtml::link('Создать новую группу товаров',array('productsgroup/create')); ?></li>
	<li><?php echo CHtml::link('Создать новую запись о товаре',array('productsElement/create')); ?></li>
    <li><?php echo CHtml::link('Управление группами',array('productsgroup/admin')); ?></li>
	<li><?php echo CHtml::link('Управление товарами',array('productsElement/admin')); ?></li>
    <li><?php echo CHtml::link('Выход',array('site/logout')); ?></li>
</ul>