<?php $this->pageTitle=Yii::app()->name.' - Создать настройку' ?>
<?php
$this->breadcrumbs=array(
	'Настройки'=>array('index'),
	'Создать',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	array('label'=>'Управление', 'url'=>array('admin')),
);
?>

<h1>Создание насторойки</h1>

<?php echo $this->renderPartial(Yii::app()->mf->siteType(). '/_form', array('model'=>$model)); ?>