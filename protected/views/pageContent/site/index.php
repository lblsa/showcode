<?php $this->pageTitle=Yii::app()->name.' - Элементы контента' ?>
<?php
$this->breadcrumbs=array(
	'Page Contents',
);

$this->menu=array(
	array('label'=>'Создать', 'url'=>array('create')),
);
?>
<div class="main_form_wrapper list_buy_events">
    <h1>Контент</h1>
    <?php if(count($data)>0): ?>
        <div id="list_tickets">
            <table>            
                <tr class="title_table">
                    <td><?php echo CHtml::encode($data[0]->getAttributeLabel('tag_uniq')); ?></td>                    
                    <td><?php echo CHtml::encode($data[0]->getAttributeLabel('description')); ?></td>
                    <td></td>
                </tr>
                <?php foreach($data as $i=>$item): ?>
                    <tr>
                        <?php echo $this->renderPartial(Yii::app()->mf->siteType(). '/_view',array('data'=>$item)); ?>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
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
    <?php endif; ?>
</div>
    
