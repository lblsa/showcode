<?php $this->pageTitle=Yii::app()->name.' - Управление мероприятиями' ?>

<div class="main_form_wrapper list_buy_events">

    <h1>
        Статистика
        <?php if($tickets->event_id): ?>
             по мероприятию «<?php echo Events::model()->getEventTitle($tickets->event_id); ?>»
        <?php endif; ?>
    </h1>

	<div>
		<span style="font-size: 150%; margin-right: 20px;">Высылать статистику на email</span> 
		<?php echo CHtml::dropDownList('mailer', $selectStat, array('0'=>'Не высылать', '1'=>'Ежедневно', '2'=>'Еженедельно', '3'=>'Ежемесячно')); ?>
		<?php echo CHtml::Button('Применить',array('id'=>'submit_stat', 'style'=>'margin-left: 20px;')); ?>
	</div>
	
	
    <div id="list_tickets">
         <div class="search-form">
           <?php $this->renderPartial(Yii::app()->mf->siteType(). '/_search',array(
                    'model'=>$tickets,
                    'sortDate'=>$sortDate,
            )); ?>
        </div>

        <br/>

		<div>
            <?php if($tickets->user_id): ?>
                <?php $this->renderPartial(Yii::app()->mf->siteType(). '/_table',array(
                        'model' => $tickets,
						'event_id' => $tickets->event_id,
						'date_begin' => $date_begin,
						'date_end' => $date_end,
						'period' => $period,
						'quantityAll' => $quantityAll,
						'quantityAllu' => $quantityAllu,
						'qXp' => $qXp,
						'qXpu' => $qXpu,
                )); ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<div id="test"></div>
<script type="text/javascript">
	$('#submit_stat').click(function(){
		val = $('#mailer option:selected').val();
		event_id = '<?php echo $tickets->event_id; ?>';
		
		$.get("<?php echo CHtml::normalizeUrl(array('statistics/ajaxSendStat'))?>", {'select' : val, 'event_id'  : event_id}, function(data){ alert('Сделано!'); return true;	});
	});
</script>