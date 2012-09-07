<?php $this->pageTitle=Yii::app()->name.' - Создать элемент контента' ?>
<?php
$this->breadcrumbs=array(
	'Page Contents'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Вернуться', 'url'=>array('index')),	
);
?>

<h1>Создание</h1>

<?php echo $this->renderPartial(Yii::app()->mf->siteType(). '/_form', array('model'=>$model)); ?>