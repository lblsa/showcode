<style>
	#my_tick
	{
		background-color: #FB6006; 
		cursor: pointer; 
		padding: 10px; 
		text-align: center; 
		vertical-align: middle;
		text-decoration: none; 
		text-shadow: 0 1px 1px #565656;
		border: none;
		margin-bottom: 10px;
		border-radius: 5px;
		color: white;
		font-size: 150%;
		background: -moz-linear-gradient(top, #FB6006, #822100); 
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#FB6006), color-stop(100%,#822100));
		background: -webkit-linear-gradient(top, #FB6006, #822100);
		background: -o-linear-gradient(top, #FB6006, #822100); 
		background: -ms-linear-gradient(top, #FB6006, #822100);
		background: linear-gradient(top, #FB6006, #822100); 
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
    <h1>Список купленных билетов</h1>
	<?php echo CHtml::button('Показать мои билеты', array('id'=>'my_tick'));?>

    <div id="list_tickets" style="display: none;">
		<?php if(count($data)>0): ?>
			<?php if($pages->itemCount > 0): ?>
			<div class="number_of_tickets">
				<?php echo CHtml::encode('Записи с '); ?>
				<span><?php echo ($pages->offset + 1); ?></span>
				<?php echo CHtml::encode(' по '); ?>
				<!--Вычисляет по какой элемент выводится список-->
				<span><?php echo ($pages->offset + ($pages->limit-floor(($pages->currentPage+1) * (1/$pages->pageCount))*($pages->limit*$pages->pageCount - $pages->itemCount))); ?></span>
				<?php echo CHtml::encode('. Всего записей '); ?>
				<span><?php echo $pages->itemCount; ?></span>
			</div>
			<?php endif; ?>
			<table>
				<tr class="title_table">
					<td><?php echo CHtml::encode($data[0]->getAttributeLabel('event_id')); ?></td>
					<td><?php echo CHtml::encode($data[0]->getAttributeLabel('type')); ?></td>
					<td><?php echo CHtml::encode($data[0]->getAttributeLabel('quantity')); ?></td>
					<td><?php echo CHtml::encode($data[0]->getAttributeLabel('price')); ?></td>
					<td><?php echo CHtml::encode($data[0]->getAttributeLabel('total')); ?></td>
					<td><?php echo CHtml::encode($data[0]->getAttributeLabel('datetime')); ?></td>
					<td><?php echo CHtml::encode($data[0]->getAttributeLabel('user_id')); ?></td>
					<td><?php echo CHtml::encode($data[0]->getAttributeLabel('payment')); ?></td>
					<td><?php echo CHtml::encode($data[0]->getAttributeLabel('status')); ?></td>
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
		<?php else:?>
			<div style="font-size: 200%; margin-left: 20px">Нет билетов</div>
	   <?php endif; ?>
    </div>

</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('#my_tick').click(function(){
			$('#list_tickets').fadeIn(900);		
		});
	});
</script>