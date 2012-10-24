<style>
#my_tick, #my_event, #all_tick
{
    margin-right: 10px;
    margin-bottom: 10px;
}
</style>
<?php $this->pageTitle=Yii::app()->name.' - Список ваших билетов' ?>
<?php
$this->breadcrumbs=array(
	'Билеты',
);

$this->menu=array(
);
?>
<div class="main_form_wrapper list_buy_events">
<div>
<?php //echo CHtml::button('Показать мои купленные билеты', array('id'=>'my_tick'));?>
<?php //echo CHtml::checkBox('my_tick');?>
</div>
<h1 style="margin-bottom: 5px;">Список моих купленных билетов</h1>
<div id="my_tick_list">
<?php $t = TransactionLog::model()->getData(0, yii::app()->user->id);?>
<?php $data = $t['data'];?>
<?php $pages = $t['pages'];?>
<?php $this->renderPartial(Yii::app()->mf->siteType().'/_ticket_list', array('data' => $data, 'pages' => $pages, 'flag' => 0, 'id_user' => yii::app()->user->id));?>
</div>  

<h1 style="margin-bottom: 5px;">Список билетов на мои мероприятия</h1>  
<div id="my_ev_tick_list">
<?php $t2 = TransactionLog::model()->getData(1, yii::app()->user->id);?>
<?php $data2 = $t2['data'];?>
<?php $pages2 = $t2['pages'];?>
<?php $this->renderPartial(Yii::app()->mf->siteType().'/_ticket_list', array('data' => $data2, 'pages' => $pages2, 'flag' => 1, 'id_user' => yii::app()->user->id));?>
</div>

<?php if(yii::app()->user->isAdmin()):?>
<h1 style="margin-bottom: 5px;">Список всех билетов</h1>    
<div id="all_tick_list">
<?php $t3 = TransactionLog::model()->getData(1, '');?>
<?php $data3 = $t3['data'];?>
<?php $pages3 = $t3['pages'];?>
<?php $this->renderPartial(Yii::app()->mf->siteType().'/_ticket_list', array('data' => $data3, 'pages' => $pages3, 'flag' => 1, 'id_user' => ''));?>
</div>
<?php endif;?>
</div>
