<?php
	Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery-ui-1.9.1.custom.js');
	Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/jquery-ui-1.9.1.custom.css');
?>

<h2>Событие</h2>

<div class="form_main">

<?php
 $form=$this->beginWidget('CActiveForm', array(
	'id'=>'events-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('enctype'=>'multipart/form-data', 'onsubmit'=>'sendOrg()'),
)); ?>
	<?php if(!$model->isNewRecord && $model->facebook_eid): ?>
		<p>При изменении данных данного мероприятия, эти данные не будут обновлены в соотвествующем мероприятии в Facebook'е.</p>
		<p>Чтобы информация о мероприятии совпадала с информацией на сайте, нужно зайти в <a href="http://www.facebook.com/events/create/?eid=<?php echo $model->facebook_eid; ?>" target="_black" title="Facebook">Facebook</a> и вручную изменить данные.</p>
	<?php endif; ?>
	<p class="note">Поля, отмеченные <span class="required">*</span> обязательны к заполнению</p>

	<?php //echo $form->errorSummary($model); ?>

        <div>
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>50,'maxlength'=>50,'id'=>'title_input')); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div>
		<?php echo $form->labelEx($model,'date'); ?>
		<?php echo $form->textField($model,'date',array('size'=>'14','class'=>'datepicker','value'=>$model->date)); ?>
		<?php echo $form->error($model,'date'); ?>
	</div>

	<div>
		<?php echo $form->labelEx($model,'time'); ?>
		<?php echo $form->textField($model,'time',array('maxlength'=>'5', 'size'=>'14','value'=>$model->time)); ?>
		<?php echo $form->error($model,'time'); ?>
	</div>

        <div class="adrress_input">
		<?php echo $form->labelEx($model,'address'); ?>
		<?php echo $form->textField($model,'address',array('id'=>'adrress_input')); ?>
		<?php echo $form->error($model,'address'); ?>
	</div>
	
	   <div>
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50,'id'=>'description_textarea')); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>
	
	<div>
		<span style="font-size: 13px; color: #CCC; margin-right: 10px;">
			Огранизаторы мероприятия
		</span>
		<?php echo CHtml::activeTextField($modelOrg, 'id_org', array()); ?>
		<input type='hidden' name='Orgs' value='' id = "orgs_list"/>
	</div>
	<div id="orgs">
	</div>

	<div>
		<?php echo $form->labelEx($model,'facebook_eid'); ?>
		<?php echo $form->textField($model,'facebook_eid',array('maxlength'=>'20', 'size'=>'14','value'=>$model->facebook_eid)); ?>
		<?php echo $form->error($model,'facebook_eid'); ?>
            <p>В данное поле вы можете вставить ссылку на ваше уже существующее мероприятие в Facebook'е. Если такого мероприятия нет, то вы можете не заполнять данное поле, а поставить галочку напротив "Добавить событие в Facebook". В этом случае мероприятие в социальной сети Facebook будет создано автоматически.</p>
	</div>	
		
    <div>
		<?php echo $form->labelEx($model,'active'); ?>
		<?php echo $form->checkBox($model,'active'); ?>
		<?php echo $form->error($model,'active'); ?>
	</div>

	<!--div-->
		<?php //echo $form->labelEx($model,'status'); ?>
		<?php //echo $form->dropDownList($model,'status',Events::$STATUS); ?>
		<?php //echo $form->error($model,'status'); ?>
	<!--/div-->

	<div>
		<?php echo $form->labelEx($model,'logo'); ?>
                <div id="mask">
                </div>
		<?php echo $form->fileField($model,'logo',array('id'=>'file_upload')); ?>
		<?php echo $form->error($model,'logo'); ?>

		<?php if(!$model->isNewRecord): ?>
			<br /><?php echo CHtml::image($model->changeNameImageOnMini($model->logo),'logo'); ?>
			<br />
                        <br /><?php echo $form->checkBox($model,'delete_logo',array('id'=>'delete_logo')); ?>
			<?php echo $form->labelEx($model,'delete_logo',array('for'=>'delete_logo')); ?> <br />
                        <p>Выбирете этот пункт, если вы хотите поставить стандартную картинку для мероприятия.</p>
		<?php endif; ?>
	</div>


	<h2>Билеты</h2>
	<div class="bottom_form_style">
		<div class="elements_form_line_1">
		<?php if(!$model->isNewRecord):?>
			<?php echo $form->hiddenField($ticket,'count_tickets',array('id'=>'count_tickets','value'=>count($tickets1)));?>
			<div>
				<?php echo $form->labelEx($tickets1[0],'type');?>
				<?php echo $form->dropDownList($tickets1[0],'type',Tickets::$type_ticket);?>
				<?php echo $form->error($tickets1[0],'type');?>
			</div>

			<?php foreach($tickets1 as $i=>$value):?>
				<?php if($i == 0):?>
					<div id="first_field">
				<?php else:?>
					<div>
				<?php endif;?>
				
				<?php echo $form->hiddenField($value,'ticket_id[]',array('value'=>$value->ticket_id));?>

				<div class="type_ticket">
					<?php echo $form->labelEx($value,'description');?>
					<?php echo $form->textField($value,'description[]',array('size'=>80, 'value'=>$value->description,'id'=>"type_ticket"));?>
					<?php echo $form->error($value,'description');?>
				</div>

				<div class="number">
					<?php echo $form->labelEx($value,'quantity');?>
					<?php echo $form->textField($value,'quantity[]',array('size'=>14,'maxlength'=>10, 'value'=>$value->quantity,'id'=>"number"));?>
					<?php echo $form->error($value,'quantity');?>
				</div>

				<div class="type_ticket">
					<?php echo $form->labelEx($value,'time_begin');?>
					<?php echo $form->textField($value,'time_begin[]',array('maxlength'=>'5', 'size'=>'14','value'=>$value->time_begin,'id'=>"type_ticket"));?>
					<?php echo $form->error($value,'time_begin');?>
				</div>

				<div class="type_ticket">
					<?php echo $form->labelEx($value,'time_end');?>
					<?php echo $form->textField($value,'time_end[]',array('maxlength'=>'5', 'size'=>'14','value'=>$value->time_end,'id'=>"type_ticket"));?>
					<?php echo $form->error($value,'time_end');?>
				</div>

				<div class="price">
					<?php echo $form->labelEx($value,'price');?>
					<?php echo $form->textField($value,'price[]',array('size'=>14,'maxlength'=>10, 'value'=>$value->price,'id'=>"price"));?>
					<?php echo $form->error($value,'price');?>
				</div>

				<?php if($i > 0):?>
					<p>
					<?php echo CHtml::linkButton('Удалить билет',array(
					   'submit'=>array('events/deleteTicket','id'=>$value->ticket_id),
					   'params'=>array('returnUrl'=>array('/events/update/'.$model->id)),
					   'confirm'=>"Вы уверены, что хотите удалить билет?",
					));?>
					</p>
				<?php endif;?>
				</div>
			<?php endforeach;?>

			<div class="switcher" style="display:none;">
			<?php echo $form->labelEx($tickets1[0],'date_begin');?>
			<?php echo $form->textField($tickets1[0],'date_begin',array('class'=>'datepicker','value'=>$model->NormalViewDate($tickets1->date_begin)));?>
			<?php echo $form->error($tickets1[0],'date_begin');?>

			<label for="Tickets_date_end"><?php echo CHtml::encode($tickets1[0]->getAttributeLabel("date_end"));?>
				<span class="required">*</span></label>
				<?php echo $form->textField($tickets1[0],'date_end',array('class'=>'datepicker','value'=>$model->NormalViewDate($tickets1->date_end)));?>
				<?php echo $form->error($tickets1[0],'date_end');?>
			</div>
		<?php else:?>
			<?php echo $form->hiddenField($tickets1,'count_tickets',array('id'=>'count_tickets','value'=>'1'));?>
			<div>
				<?php echo $form->labelEx($tickets1,'type');?>
				<?php echo $form->dropDownList($tickets1,'type',Tickets::$type_ticket);?>
				<?php echo $form->error($tickets1,'type');?>
			</div>
			<div id="first_field">
				<div class="type_ticket">
					<?php echo $form->labelEx($tickets1,'description');?>
					<?php echo $form->textField($tickets1,'description[]',array('size'=>80,'value'=>$tickets1->description,'id'=>"type_ticket"));?>
					<?php echo $form->error($tickets1,'description');?>
				</div>

				<div class="number">
					<?php echo $form->labelEx($tickets1,'quantity');?>
					<?php echo $form->textField($tickets1,'quantity[]',array('size'=>10,'maxlength'=>10,'value'=>$tickets1->quantity,'id'=>"number"));?>
					<?php echo $form->error($tickets1,'quantity');?>
				</div>

				<div class="price" style="margin-left: 40px;">
					<?php echo $form->labelEx($tickets1,'price');?>
					<?php echo $form->textField($tickets1,'price[]',array('size'=>14,'maxlength'=>10,'value'=>$tickets1->price,'id'=>'price'));?>
					<?php echo $form->error($tickets1,'price');?>
				</div>

				<div class="type_ticket" style="margin-top: 25px;">
					<?php echo $form->labelEx($tickets1,'time_begin');?>
					<?php echo $form->textField($tickets1,'time_begin[]',array('maxlength'=>'5', 'size'=>'14','value'=>$tickets1->time_begin));?>
					<?php echo $form->error($tickets1,'time_begin');?>
				</div>

				<div class="type_ticket" style="margin-top: 25px; margin-left: 40px;">
					<?php echo $form->labelEx($tickets1,'time_end',array('style'=>'width: 120px;'));?>
					<?php echo $form->textField($tickets1,'time_end[]',array('maxlength'=>'5', 'size'=>'14','value'=>$tickets1->time_end));?>
					<?php echo $form->error($tickets1,'time_end');?>
				</div>
			</div>
		<?php endif; ?>
	</div>

            <div class="clear"></div>

            <div class="links_block_in_form not_float_block"><?php echo CHtml::link('Добавить билет', '',array('id'=>'add_tickets')) ?></div>

            <div class="clear"></div>

            <div class="elements_form_line_2">
                <div class="number_of_rows">
                    <?php echo CHtml::encode('Количество рядов '); ?>&nbsp;
                    <?php echo $form->textField($model,'column',array('maxlength'=>'5', 'size'=>'14','value'=>$model->column)); ?>
                    <?php echo $form->error($model,'column'); ?>
                </div>

                <div class="seats">
                    <?php echo CHtml::encode('Количество мест в ряду'); ?>&nbsp;
                    <?php echo $form->textField($model,'place',array('maxlength'=>'5', 'size'=>'14','value'=>$model->place)); ?>
                    <?php echo $form->error($model,'place'); ?>
                </div>
                <div class="switcher" style="display:none;">
                    <div>
                        <?php if($model->isNewRecord): ?>
                            <label for="Tickets_date_begin"><?php echo CHtml::encode($tickets1->getAttributeLabel("date_begin")).'<span class="required">*</span>' ?></label>
                            <?php echo $form->textField($tickets1,'date_begin',array('class'=>'datepicker','value'=>$model->NormalViewDate($tickets1->date_begin))); ?>
                            <?php echo $form->error($tickets1,'date_begin'); ?>
                        <?php else: ?>
                            <label for="Tickets_date_begin"><?php echo CHtml::encode($tickets1[0]->getAttributeLabel("date_begin")).'<span class="required">*</span>' ?></label>
                            <?php echo $form->textField($tickets1[0],'date_begin',array('class'=>'datepicker','value'=>$model->NormalViewDate($tickets1->date_begin))); ?>
                            <?php echo $form->error($tickets1[0],'date_begin'); ?>
                        <?php endif; ?>
                    </div>

                    <div style="margin-left: 20px;">
                        <?php if($model->isNewRecord): ?>
                            <label for="Tickets_date_end"><?php echo CHtml::encode($tickets1->getAttributeLabel("date_end")).'<span class="required">*</span>' ?></label>
                            <?php echo $form->textField($tickets1,'date_end',array('class'=>'datepicker','value'=>$model->NormalViewDate($tickets1->date_end))); ?>
                            <?php echo $form->error($tickets1,'date_end'); ?>
                        <?php else: ?>
                            <label for="Tickets_date_end"><?php echo CHtml::encode($tickets1[0]->getAttributeLabel("date_end")).'<span class="required">*</span>' ?></label>
                            <?php echo $form->textField($tickets1[0],'date_end',array('class'=>'datepicker','value'=>$model->NormalViewDate($tickets1->date_end))); ?>
                            <?php echo $form->error($tickets1[0],'date_end'); ?>
                        <?php endif ?>
                    </div>
                </div>
            </div>

            <div class="clear"></div>

            <div class="elements_form_line_3">
                <div>
                        <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить',array('id'=>'submit_save_button')); ?>
                </div>
                <?php if($model->isNewRecord || !$model->facebook_eid): ?>
                    <div class="checkbox event_in_fb">
                            <?php echo $form->checkBox($model,'addEventFacebook'); ?>
                            <?php echo $form->labelEx($model,'addEventFacebook'); ?>
                            <?php echo $form->error($model,'addEventFacebook'); ?>
                    </div>
                <?php endif; ?>
                <div class="checkbox i_can_only_see">
                        <?php echo $form->checkBox($model,'online'); ?>
                        <?php echo $form->labelEx($model,'online'); ?>
                        <?php echo $form->error($model,'online'); ?>
                </div>
            </div>
        </div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<script type="text/javascript">
	
	var ids = [];
	var values = [];
	
	<?php if(!empty($ids) && !empty($values)):?>
		<?php for($i = 0; $i<count($ids); $i++):?>
			ids[<?php echo $i?>] = <?php echo $ids[$i]; ?>;
			values[<?php echo $i?>] = '<?php echo $values[$i]; ?>';
		<?php endfor;?>
		newData();
	<?php endif; ?>	
	
	$(document).ready(function(){
	
		$("input.datepicker").datepicker({
			minDate:"0",
			dateFormat:"dd.mm.yy",
		});

		$("#Events_time,#Tickets_time_begin,#Tickets_time_end").keyup(function(){
			putsTime(this);
		});

		function putsTime(item){
			if(item.view == window)
				item = this;
			time = $(item).val();

			if (time.length==1 && time>2 || time.length==2 && time>23 || time.length==4 && time.substr(3,1)>5)
			{
					$(item).val(time.substr(0,time.length-1));
			}
			if (time.length==2 && time<=23)
			{
					$(item).val(time+":");
			}
		}

		$("#Tickets_type").change(function(){
			if ($(this).val() == "free"){
						$("#price")[0].value = 0;
						$("#price")[0].readOnly = true;
			}else{
						$("#price")[0].readOnly = false;
			}
					if ($(this).val() == "travel")
							$("div.switcher").show();
					else{
							$("div.switcher").hide();
					}
		}).change();

		$("#Events_column,#Events_place").change(function(){
			column = $("#Events_column").val();
			place = $("#Events_place").val();
			if(column == "" && place == ""){
				$("#number")[0].readOnly = false;
			}else{
				if(column != "" && place != ""){
					$("#number")[0].value = parseInt(column) * parseInt(place);
				}
				$("#number")[0].readOnly = true;
			}
		}).change();

		$("#add_tickets").click(function(){
			new_fieldset = $("div#first_field").clone();
			new_fieldset.removeAttr("id");
			new_fieldset.insertBefore($("#add_tickets").parent());
			input_id = new_fieldset.find("input#Tickets_ticket_id");
			if(input_id){
				input_id.val("");
			}
			new_count = parseInt($("#count_tickets").val()) + 1;
			$("#count_tickets").val(new_count);
			new_link = document.createElement("p");
			new_link.innerHTML = "<a class=\"delete_tickets\">Удалить билет</a>";
			new_link.children[0].addEventListener ("click",DELETE_TICKETS,false);
			$(new_link).insertAfter(new_fieldset.children().last());
					input_time = new_fieldset.find("input#Tickets_time_begin");
					input_time[0].addEventListener ("keyup",putsTime,false);
					input_time = new_fieldset.find("input#Tickets_time_end");
					input_time[0].addEventListener ("keyup",putsTime,false);
		});
		
		function DELETE_TICKETS(){
			bilet = $(this).parents("div")[0];
			$(bilet).remove();
			new_count = parseInt($("#count_tickets").val()) - 1;
			$("#count_tickets").val(new_count);
		};
		

		
		$(function() {
			function split( val ) {
				return val.split( /,\s*/ );
			}
			function extractLast( term ) {
				return split( term ).pop();
			}
			
			$( "#EventOrg_id_org" )
				// don't navigate away from the field on tab when selecting an item
				.bind( "keydown", function( event ) {
					if ( event.keyCode === $.ui.keyCode.TAB &&
							$( this ).data( "autocomplete" ).menu.active ) {
						event.preventDefault();
					}
				})
				.autocomplete({
					source: function( request, response ) {
						
						strId = '';
						for(i = 0; i<ids.length; i++)
						{
							strId += ids[i] + ', ';
						}
						
						$.getJSON( "<?php echo Yii::app()->createUrl('events/searchOrg'); ?>", {
							term: extractLast( request.term ), 'ids': strId,
						}, response );
					},
					search: function() {
						// custom minLength
						var term = extractLast( this.value );
						if ( term.length < 0 ) {
							return false;
						}
					},
					focus: function() {
						// prevent value inserted on focus
						return false;
					},
					select: function( event, ui ) {
						ids.push(ui.item.id);
						values.push(ui.item.value);
						newData();
						this.value = '';
						return false;
					}
				});
			});	
	   });
	   
	function newData()
	{
		html = '';		
		
		for(i = 0; i<ids.length; i++)
		{
			html += '<div onclick = "removeData(' + ids[i] + ')">' + values[i]  + '</div>';
		}
		
		$('#orgs').fadeOut(300, function(){
			$('#orgs').html(html).fadeIn(900);
		});
	}

	function removeData(id)
	{
		index = ids.indexOf(id);
		ids.splice(index, 1);
		values.splice(index, 1);
		newData();
	}
	
	function sendOrg()
	{
		newId = ids.join(", ");
		$('#orgs_list').attr('value', newId);
		return true;
	}
</script>