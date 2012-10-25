<?php
Yii::app()->clientScript->registerScript('switcher','
	$("input.datepicker").datepicker({minDate:"0"});

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

');
?>
<h2>Событие</h2>

<div class="form_main">

<?php
 $form=$this->beginWidget('CActiveForm', array(
	'id'=>'events-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
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
		<?php
		if(!$model->isNewRecord){
			echo $form->hiddenField($ticket,'count_tickets',array('id'=>'count_tickets','value'=>count($tickets1)));
		}else{
			echo $form->hiddenField($tickets1,'count_tickets',array('id'=>'count_tickets','value'=>'1'));
		}
		if(!$model->isNewRecord){
                        echo '<div>';
                            echo $form->labelEx($tickets1[0],'type');
                            echo $form->dropDownList($tickets1[0],'type',Tickets::$type_ticket);
                            echo $form->error($tickets1[0],'type');
                        echo '</div>';

			foreach($tickets1 as $i=>$value){
				if($i == 0)
                                        echo '<div id="first_field">';
				else
					echo '<div>';
                                            echo $form->hiddenField($value,'ticket_id[]',array('value'=>$value->ticket_id));

                                            echo '<div class="type_ticket">';
                                                echo $form->labelEx($value,'description');
                                                echo $form->textField($value,'description[]',array('size'=>80, 'value'=>$value->description,'id'=>"type_ticket"));
                                                echo $form->error($value,'description');
                                            echo '</div>';

                                            echo '<div class="number">';
                                                echo $form->labelEx($value,'quantity');
                                                echo $form->textField($value,'quantity[]',array('size'=>14,'maxlength'=>10, 'value'=>$value->quantity,'id'=>"number"));
                                                echo $form->error($value,'quantity');
                                            echo '</div>';

                                            echo '<div class="type_ticket">';
                                                echo $form->labelEx($value,'time_begin');
                                                echo $form->textField($value,'time_begin[]',array('maxlength'=>'5', 'size'=>'14','value'=>$value->time_begin,'id'=>"type_ticket"));
                                                echo $form->error($value,'time_begin');
                                            echo '</div>';

                                            echo '<div class="type_ticket">';
                                                echo $form->labelEx($value,'time_end');
                                                echo $form->textField($value,'time_end[]',array('maxlength'=>'5', 'size'=>'14','value'=>$value->time_end,'id'=>"type_ticket"));
                                                echo $form->error($value,'time_end');
                                            echo '</div>';

                                            echo '<div class="price">';
                                                echo $form->labelEx($value,'price');
                                                echo $form->textField($value,'price[]',array('size'=>14,'maxlength'=>10, 'value'=>$value->price,'id'=>"price"));
                                                echo $form->error($value,'price');
                                            echo '</div>';

                                            if($i > 0){
                                                    echo '<p>';
                                                    echo CHtml::linkButton('Удалить билет',array(
                                                       'submit'=>array('events/deleteTicket','id'=>$value->ticket_id),
                                                       'params'=>array('returnUrl'=>array('/events/update/'.$model->id)),
                                                       'confirm'=>"Вы уверены, что хотите удалить билет?",
                                                    ));
                                                    echo '</p>';
                                            }
				echo '</div>';
			}

			echo '<div class="switcher" style="display:none;">';
				echo $form->labelEx($tickets1[0],'date_begin');
				echo $form->textField($tickets1[0],'date_begin',array('class'=>'datepicker','value'=>$model->NormalViewDate($tickets1->date_begin)));
				echo $form->error($tickets1[0],'date_begin');

				echo '<label for="Tickets_date_end">'.CHtml::encode($tickets1[0]->getAttributeLabel("date_end")).'<span class="required">*</span></label>';
				echo $form->textField($tickets1[0],'date_end',array('class'=>'datepicker','value'=>$model->NormalViewDate($tickets1->date_end)));
				echo $form->error($tickets1[0],'date_end');
			echo '</div>';
		}else{
                        echo '<div>';
                            echo $form->labelEx($tickets1,'type');
                            echo $form->dropDownList($tickets1,'type',Tickets::$type_ticket);
                            echo $form->error($tickets1,'type');
                        echo '</div>';
			echo '<div id="first_field">';
                            echo '<div class="type_ticket">';
				echo $form->labelEx($tickets1,'description');
				echo $form->textField($tickets1,'description[]',array('size'=>80,'value'=>$tickets1->description,'id'=>"type_ticket"));
				echo $form->error($tickets1,'description');
                            echo '</div>';

                            echo '<div class="number">';
				echo $form->labelEx($tickets1,'quantity');
				echo $form->textField($tickets1,'quantity[]',array('size'=>10,'maxlength'=>10,'value'=>$tickets1->quantity,'id'=>"number"));
				echo $form->error($tickets1,'quantity');
                            echo '</div>';

                            echo '<div class="price" style="margin-left: 40px;">';
				echo $form->labelEx($tickets1,'price');
				echo $form->textField($tickets1,'price[]',array('size'=>14,'maxlength'=>10,'value'=>$tickets1->price,'id'=>'price'));
				echo $form->error($tickets1,'price');
                            echo '</div>';

                            echo '<div class="type_ticket" style="margin-top: 25px;">';
				echo $form->labelEx($tickets1,'time_begin');
				echo $form->textField($tickets1,'time_begin[]',array('maxlength'=>'5', 'size'=>'14','value'=>$tickets1->time_begin));
				echo $form->error($tickets1,'time_begin');
                            echo '</div>';

                            echo '<div class="type_ticket" style="margin-top: 25px; margin-left: 40px;">';
				echo $form->labelEx($tickets1,'time_end',array('style'=>'width: 120px;'));
				echo $form->textField($tickets1,'time_end[]',array('maxlength'=>'5', 'size'=>'14','value'=>$tickets1->time_end));
				echo $form->error($tickets1,'time_end');
                            echo '</div>';
			echo '</div>';
		}
		?>
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