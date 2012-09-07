<?php $this->pageTitle=Yii::app()->name.' - Оставить отзыв' ?>
<?php

if (Yii::app()->user->isAdmin())
{
	$this->menu=array(
		array('label'=>'Список отзывов', 'url'=>array('index')),
		array('label'=>'Управление', 'url'=>array('admin')),
	);
}
?>
<?php if(!$message_send): ?>
	<h1>Оставить отзыв</h1>
<?php endif; ?>

<?php echo $this->renderPartial(Yii::app()->mf->siteType(). '/_form', array('model'=>$model,'message_send'=>$message_send)); ?>