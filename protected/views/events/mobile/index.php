<?php

switch ($_GET['view'])
{
	case organizer:
                $this->pageTitle=Yii::app()->name.' - Все мероприятия автора: "' .Yii::app()->user->getName().'"';
    	$this->headering = Yii::app()->user->getName();
		echo '<h1>Все мероприятия автора</h1>';
		if (yii::app()->user->isAdmin() || yii::app()->user->isOrganizer())
			$this->menu=array(
				array('label'=>'Создать мероприятие', 'url'=>array('create')),
				//array('label'=>'Управление мероприятиями', 'url'=>array('admin')),
			);
		break;
	case events:
	default:
            $this->pageTitle=Yii::app()->name.' - Доступные мероприятия';
            $this->headering = 'Ближайшие мероприятия';
            if (yii::app()->user->isAdmin())
                $this->menu=array(
                        //array('label'=>'Управление мероприятиями', 'url'=>array('admin')),
                );
            
            //echo '<h1>Ближайшие мероприятия</h1>';
            break;
}
?>
<ul data-role="listview" data-inset="true">
	<?php foreach($data as $i=>$item): ?>
        <?php echo $this->renderPartial(Yii::app()->mf->siteType(). '/_view',array('data'=>$item)); ?>
	<?php endforeach; ?>
</ul>

<!-- pagination:begin -->

    <?php $this->widget('CLinkPager', array(
        'pages' => $pages,                
        'header'=>'',
        'nextPageLabel'=>'>',
        'prevPageLabel'=>'<',
        'firstPageLabel'=>'<<',
        'lastPageLabel'=>'>>',
        'htmlOptions'=>array('class'=>'','id'=>''),
    )) ?>

<!-- pagination:end -->

