<?php $this->pageTitle='Список настроек' ?>
<?php $this->headering='Настройки' ?>

<?php
$this->menu=array(
	array('label'=>'Создать', 'url'=>array('create')),
	//array('label'=>'Управление', 'url'=>array('admin')),
);
?>
<ul data-role="listview" data-inset="true">
	<?php foreach($data as $i=>$item): ?>
	    <li>
	        <?php echo $this->renderPartial(Yii::app()->mf->siteType(). '/_view',array('data'=>$item)); ?>
	    </li>
	<?php endforeach; ?>
</ul>