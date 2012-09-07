<?php $this->pageTitle = 'Список отзывов'; ?>
<?php $this->headering = 'Отзывы'; ?>

<?php $this->menu=array(
	array('label'=>'Создать отзыв', 'url'=>array('create')),
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
