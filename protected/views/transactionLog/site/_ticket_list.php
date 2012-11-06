<style>
	#list_tickets
	{
		margin-top: 20px;
		margin-bottom: 35px;
	}
</style>
<div id="list_tickets" >
    <?php if(count($data)>0): ?>        
    <table>
		<?php if($pages->itemCount > 0): ?>
		<tr>
			<td colspan="11">
				<div class="number_of_tickets">
					<?php echo CHtml::encode('Записи с '); ?>
					<span><?php echo ($pages->offset + 1); ?></span>
					<?php echo CHtml::encode(' по '); ?>
					<!--Вычисляет по какой элемент выводится список-->
					<span><?php echo ($pages->offset + ($pages->limit-floor(($pages->currentPage+1) * (1/$pages->pageCount))*($pages->limit*$pages->pageCount - $pages->itemCount))); ?></span>
					<?php echo CHtml::encode('. Всего записей '); ?>
					<span><?php echo $pages->itemCount; ?></span>
				</div>
			</td>
		</tr>
		<?php endif; ?>
		<tr class="title_table">
			<td><?php echo CHtml::encode($data[0]->getAttributeLabel('event_id')); ?></td>
			<td><?php echo CHtml::encode($data[0]->getAttributeLabel('type')); ?></td>
			<td><?php echo CHtml::encode($data[0]->getAttributeLabel('quantity')); ?></td>
			<td><?php echo CHtml::encode($data[0]->getAttributeLabel('price')); ?></td>
			<td><?php echo CHtml::encode($data[0]->getAttributeLabel('total')); ?></td>
			<td><?php echo CHtml::encode($data[0]->getAttributeLabel('datetime')); ?></td>
			<td><?php echo CHtml::encode($data[0]->getAttributeLabel('user_id')); ?></td>
			<td><?php echo CHtml::encode($data[0]->getAttributeLabel('mail')); ?></td>
			<td><?php echo CHtml::encode($data[0]->getAttributeLabel('phone')); ?></td>
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
        <div style="font-size: 200%; margin-left: 20px; margin-bottom: 20px;">Нет билетов</div>
    <?php endif; ?>
</div>
<script type="text/javascript">

	function str_replace(search, replace, subject)
	{
		return subject.split(search).join(replace);
	}
	
	$('div.pagination_coming_events ul li a').each( function() {
		val = $(this).attr('href');
		
		val = str_replace('ajaxTicketList', 'index', val);
		$(this).attr('href', val)
	});
</script>