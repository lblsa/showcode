<?php $this->pageTitle=Yii::app()->name.' - Список настроек' ?>

<?php
$this->breadcrumbs=array(
	'Настройки',
);

$this->menu=array(
        array('label'=>'Управление контентом', 'url'=>array('/pageContent')),
	array('label'=>'Создать', 'url'=>array('create')),
	array('label'=>'Управление', 'url'=>array('admin')),
);
?>

<div class="main_form_wrapper list_buy_events">

    <h1>Настройки</h1>
    <?php if(count($data)>0): ?>
    <div id="list_tickets">
        <table>            
            <tr class="title_table">
                <td><?php echo CHtml::encode($data[0]->getAttributeLabel('name')); ?></td>
                <td><?php echo CHtml::encode($data[0]->getAttributeLabel('value')); ?></td>
                <td><?php echo CHtml::encode($data[0]->getAttributeLabel('description')); ?></td>
            </tr>
            <?php foreach($data as $i=>$item): ?>
                <tr>
                    <?php echo $this->renderPartial(Yii::app()->mf->siteType(). '/_view',array('data'=>$item)); ?>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
<?php endif; ?>
</div>