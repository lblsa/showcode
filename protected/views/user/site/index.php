<?php $this->pageTitle=Yii::app()->name.' - Список пользователей' ?>

<?php
$this->breadcrumbs=array(
	'Пользователи',
);

$this->menu=array(
	array('label'=>'Создать', 'url'=>array('create')),
	array('label'=>'Управление пользователями', 'url'=>array('admin')),
);
?>

<div class="main_form_wrapper list_buy_events">
    <h1>Пользователи</h1>

    <div id="list_tickets">
        <table>            
            <tr class="title_table">            
                <td><?php echo CHtml::encode($data[0]->getAttributeLabel('name')); ?></td>
                <td><?php echo CHtml::encode($data[0]->getAttributeLabel('phone')); ?></td>
                <td><?php echo CHtml::encode($data[0]->getAttributeLabel('email')); ?></td>
                <td><?php echo CHtml::encode($data[0]->getAttributeLabel('role')); ?></td>
                <td><?php echo CHtml::encode($data[0]->getAttributeLabel('organization')); ?></td>
            </tr>
            <?php foreach($data as $i=>$item): ?>
                <tr>
                    <?php echo $this->renderPartial(Yii::app()->mf->siteType(). '/_view',array('data'=>$item)); ?>
                </tr>
            <?php endforeach; ?>
        </table>
        
        <!-- pagination:begin -->
        <div class="pagination_coming_events">
            <?php $this->widget('CLinkPager', array(
                'pages' => $pages,                
                'header'=>'',
                'nextPageLabel'=>'',
                'prevPageLabel'=>'',
                'firstPageLabel'=>'',
                'lastPageLabel'=>'',
                'htmlOptions'=>array('class'=>'','id'=>''),
            )) ?>
        </div>
        <!-- pagination:end -->
    </div>
</div>