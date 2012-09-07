<?php $this->pageTitle=Yii::app()->name.' - Список ваших билетов' ?>
<?php $this->headering = 'Список билетов'; ?>
<ul data-divider-theme="d" data-theme="d" data-role="listview" data-inset="true">
	<?php foreach($data as $i=>$item): ?>
        <?php echo $this->renderPartial(Yii::app()->mf->siteType(). '/_view',array('data'=>$item)); ?>
    <?php endforeach; ?>
</ul>