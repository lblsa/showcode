<?php
    Yii::app()->clientScript->registerScript('name_js1','
        setInterval(function(){return document.location.reload();}, 60000);
    ');
?>

<?php

$this->menu=array(
	array('label'=>'Назад', 'url'=>array('index')),
	array('label'=>'Новое устройсво', 'url'=>array('create')),
	array('label'=>'Редактировать', 'url'=>array('update', 'id'=>$model->SCANNERS_ID)),
	array('label'=>'Удалить устройсво', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->SCANNERS_ID),'confirm'=>'Вы точно хотите удалить устройсво?')),
);
?>

<?php
    $attributes = array();

    $attributes[]=array(
        'name'=>'DESCRIPTION',
        'type'=>'raw',
        'value'=>$model->DESCRIPTION
    );

    $attributes[]=array(
        'name'=>'UNIQ',
        'type'=>'raw',
        'value'=>$model->UNIQ
    );

    $attributes[]=array(
        'name'=>'DATE_CREATED',
        'type'=>'raw',
        'value'=>Events::normalViewDate($model->DATE_CREATED)
    );

    $attributes[]=array(
        'name'=>'USERS_ID',
        'type'=>'raw',
        'value'=>Yii::app()->user->getAuthorName($model->USERS_ID)
    );

    if($model->ACCESS)
        $attributes[]=array(
            'name'=>'ACCESS',
            'type'=>'raw',
            'value'=>'Сканер выслал сигнал. Со времени последнего сигнала от сканера прошло меньше 5 минут.'
        );
    else
        $attributes[]=array(
            'name'=>'ACCESS',
            'type'=>'raw',
            'value'=>'Сканер не отвечает. Со времени последнего сигнала прошло больше 5 минут.'
        );

    $attributes[]=array(
            'name'=>'DATE_LAST_ACCESS',
            'type'=>'raw',
            'value'=>Events::normalViewDate($model->DATE_LAST_ACCESS)
        );

?>

<div class="main_form_wrapper list_buy_events">
    <div id="list_tickets">
        <?php
            $this->widget('zii.widgets.CDetailView', array(
                'data'=>$model,
                'attributes'=>$attributes,
                'tagName'=>'table id="user_info_table"',
                'itemTemplate'=>"<tr><td class=\"first_column\">{label}</td><td class=\"second_column\">{value}</td></tr>\n",
                'htmlOptions'=>array('id'=>'user_info_table'),
                'itemCssClass'=>array('')
            ));
        ?>
    </div>
</div>

