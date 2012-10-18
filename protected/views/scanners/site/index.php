<?php
    Yii::app()->clientScript->registerScript('name_js1','
        setInterval(function(){return document.location.reload();}, 60000);
    ');
?>

<?php $this->pageTitle='Список сканеров - '.Yii::app()->name ?>

<?php
    $this->menu=array(
            array('label'=>'Новое устройство', 'url'=>array('create')),
    );
?>
<div class="main_form_wrapper list_buy_events">
    <h1>Зарегистрированные устройсва</h1>
    <div id="list_tickets">
        <?php if(count($data)>0): ?>
            <table>
                <tr class="title_table">
                    <td><?php echo CHtml::encode($data[0]->getAttributeLabel('DESCRIPTION')); ?></td>
                    <td><?php echo CHtml::encode($data[0]->getAttributeLabel('UNIQ')); ?></td>
                    <td colspan="2"><?php echo CHtml::encode($data[0]->getAttributeLabel('ACCESS')); ?></td>
                </tr>
                <?php foreach($data as $i=>$item): ?>
                    <tr>
                        <?php echo $this->renderPartial(Yii::app()->mf->siteType(). '/_view',array('data'=>$item)); ?>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <div><h3>Список пуст</h3></div>
        <?php endif; ?>
    </div>
</div>
