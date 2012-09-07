<?php
$this->breadcrumbs=array(
	'Мероприятия',
);

echo '<div class="list_events_wrapper">';
echo '<div id="list_events">';

switch ($_GET['view'])
{
	case organizer:
                $this->pageTitle=Yii::app()->name.' - Все мероприятия автора: "' .Yii::app()->user->getName().'"';
		echo '<h1>Ваши мероприятия</h1>';
		if (yii::app()->user->isAdmin())
			$this->menu=array(
				array('label'=>'Создать мероприятие', 'url'=>array('create')),
			);
                /*if (yii::app()->user->isAdmin())
			$this->menu=array(
				array('label'=>'Управление мероприятиями', 'url'=>array('admin')),
			);*/
		break;
	case events:
	default:
            $this->pageTitle=Yii::app()->name.' - Доступные мероприятия';
            /*
            if (yii::app()->user->isAdmin())
                    $this->menu=array(
                            array('label'=>'Управление мероприятиями', 'url'=>array('admin')),
                    );
            */
            echo '<h1>Ближайшие мероприятия</h1>';
            break;
}
?>

<?php foreach($data as $i=>$item): ?>
    <div class="one_event_preview">
        <?php echo $this->renderPartial(Yii::app()->mf->siteType(). '/_view',array('data'=>$item)); ?>
    </div>
<?php endforeach; ?>


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