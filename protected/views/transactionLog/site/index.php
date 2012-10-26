<style>
#my_tick, #my_event, #all_tick_0, #all_tick_1, #all_tick_2
{
	height: 15px;
	width: 20px;
	padding-top: 15px;
}

.main_form_wrapper, .list_buy_events
{
	padding-left: 25px;
	padding-right: 0px;
}
</style>
<?php $this->pageTitle=Yii::app()->name.' - Список ваших билетов' ?>
<?php
$this->breadcrumbs=array(
	'Билеты',
);

$this->menu=array(
);

$tickList = array('1'=>'Список моих купленных билетов', '2'=>'Список билетов на мои мероприятия');
if(Yii::app()->user->isAdmin())
	$tickList[] = 'Список всех билетов';
?>
<div class="main_form_wrapper list_buy_events">
	<h1 style="margin-bottom: 5px;"> <?php echo CHtml::radioButtonList('all_tick', $ch, $tickList);?></h1>
	<div id="all_tick_list"  style="display: none;">

	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		
		<?php if($ch==1):?>
			$.get("<?php echo CHtml::normalizeUrl(array('transactionLog/ajaxTicketList'))?>", {'flag' : 0, 'user_id' : <?php echo Yii::app()->user->id;?>, 'num' : 1, 'page' : <?php echo $pages;?>},  function(data){
				
				$('#all_tick_list').empty();
				$(data).appendTo('#all_tick_list');
				$('#all_tick_list').fadeIn(900);
			});	
		<?php endif;?>
		<?php if($ch==3):?>
			$.get("<?php echo CHtml::normalizeUrl(array('transactionLog/ajaxTicketList'))?>", {'flag' : 1, 'user_id' : <?php echo Yii::app()->user->id;?>, 'num' : 2, 'page' : <?php echo $pages;?>},  function(data){
				
				$('#all_tick_list').empty();
				$(data).appendTo('#all_tick_list');
				$('#all_tick_list').fadeIn(900);
			});	
		<?php endif;?>
		<?php if($ch==3):?>
			$.get("<?php echo CHtml::normalizeUrl(array('transactionLog/ajaxTicketList'))?>", {'flag' : 1, 'user_id' : '', 'num' : 3, 'page' : <?php echo $pages;?>},  function(data){
				
				$('#all_tick_list').empty();
				$(data).appendTo('#all_tick_list');
				$('#all_tick_list').fadeIn(900);
			});	
		<?php endif;?>
		
		$('#all_tick_0').click(function(){
			$.get("<?php echo CHtml::normalizeUrl(array('transactionLog/ajaxTicketList'))?>", {'flag' : 0, 'user_id' : <?php echo Yii::app()->user->id;?>, 'num' : 1, 'page' : <?php echo $pages;?>},  function(data){
				$('#all_tick_list').empty();
				$(data).appendTo('#all_tick_list');
				$('#all_tick_list').fadeIn(900);
			});		
		});
		
		$('#all_tick_1').click(function(){
			$.get("<?php echo CHtml::normalizeUrl(array('transactionLog/ajaxTicketList'))?>", {'flag' : 1, 'user_id' : <?php echo Yii::app()->user->id;?>, 'num' : 2, 'page' : <?php echo $pages;?>},  function(data){
				
				$('#all_tick_list').empty();
				$(data).appendTo('#all_tick_list');
				$('#all_tick_list').fadeIn(900);
			});		
		});
		
		$('#all_tick_2').click(function(){
			$.get("<?php echo CHtml::normalizeUrl(array('transactionLog/ajaxTicketList'))?>", {'flag' : 1, 'user_id' : '', 'num' : 3, 'page' : <?php echo $pages;?>},  function(data){
				$('#all_tick_list').empty();
				$(data).appendTo('#all_tick_list');
				$('#all_tick_list').fadeIn(900);
			});		
		});
	});
</script>
