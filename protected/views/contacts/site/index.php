<?php $this->pageTitle=Yii::app()->name.' - Список отзывов' ?>
<?php
$this->breadcrumbs=array(
	'Отзывы',
);

$this->menu=array(
	array('label'=>'Создать отзыв', 'url'=>array('create')),
	array('label'=>'Управление', 'url'=>array('admin')),
);
?>

<div class="main_form_wrapper list_buy_events">
    <h1>Отзывы</h1>
    <?php if(count($data)>0): ?>
    <div id="list_tickets">
        <table>            
            <tr class="title_table">            
                <td><?php echo CHtml::encode($data[0]->getAttributeLabel('user_id')); ?></td>
                <td><?php echo CHtml::encode($data[0]->getAttributeLabel('email')); ?></td>
                <td><?php echo CHtml::encode($data[0]->getAttributeLabel('type')); ?></td>
                <td><?php echo CHtml::encode($data[0]->getAttributeLabel('datetime')); ?></td>
                <td><?php echo CHtml::encode($data[0]->getAttributeLabel('isread')); ?></td>
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