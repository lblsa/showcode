<?php
Yii::app()->clientScript->registerScript('switcher','
$(document).ready(function(){

	$("input.datepicker").datepicker({minDate:"0"});

	$("#Events_time").keyup(function(){
		time = $("#Events_time").val();
		
		if (time.length==1 && time>2 || time.length==2 && time>23 || time.length==4 && time.substr(3,1)>5)
		{
			$("#Events_time").val(time.substr(0,time.length-1));
		}
		if (time.length==2 && time<=23)
		{
			$("#Events_time").val(time+":");
		}
	});
	
	$("#Tickets_type").change(function(){
		if ($(this).val() == "free"){
			$("#Tickets_price")[0].value = 0;
			$("#Tickets_price")[0].readOnly = true;
		}else{
			$("#Tickets_price")[0].readOnly = false;
			if ($(this).val() == "travel")
				$("div.switcher").show();
			else{
				$("div.switcher").hide();
			}
		}
	}).change();
	
	$("#Events_column,#Events_place").change(function(){
		column = $("#Events_column").val();
		place = $("#Events_place").val();
		if(column == "" && place == ""){
			$("#Tickets_quantity")[0].readOnly = false;
		}else{
			if(column != "" && place != ""){
				$("#Tickets_quantity")[0].value = parseInt(column) * parseInt(place);
			}
			$("#Tickets_quantity")[0].readOnly = true;
		}
	}).change();
	
	$("#add_tickets").click(function(){
		new_fieldset = $("fieldset#first_field").clone();
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
	});	
	
	
	function DELETE_TICKETS(){
		bilet = $(this).parents("fieldset");
		bilet.remove();
		new_count = parseInt($("#count_tickets").val()) - 1;
		$("#count_tickets").val(new_count);
	};
});
');
?>

<div class="form">

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

	<?php echo $form->errorSummary($model); ?>

	<div class="row" data-role="fieldcontain">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row" data-role="fieldcontain">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row" data-role="fieldcontain">
		<?php echo $form->labelEx($model,'date'); ?>
		<?php //echo $form->textField($model,'date',array('size'=>'12','type'=>"date", 'data-role'=>"datebox", 'data-options'=>'{"mode": "calbox"}','value'=>$model->date)); ?>
		<input name="Events[date]" id="Events_date" type="date" data-role="datebox" data-options='{"mode": "calbox", "calStartDay": 1}' size="12" value=<?php echo $model->date ?> />
		<?php echo $form->error($model,'date'); ?>
	</div>
	
	<div class="row" data-role="fieldcontain">
		<?php echo $form->labelEx($model,'time'); ?>
		<?php echo $form->textField($model,'time',array('maxlength'=>'5', 'size'=>'12','value'=>$model->time)); ?>
		<?php echo $form->error($model,'time'); ?>
	</div>
	
	<div class="row" data-role="fieldcontain">
		<?php echo $form->labelEx($model,'facebook_eid'); ?>
		<?php echo $form->textField($model,'facebook_eid',array('maxlength'=>'20', 'size'=>'14','value'=>$model->facebook_eid)); ?>
		<?php echo $form->error($model,'facebook_eid'); ?>
	</div>
		
	<?php if($model->isNewRecord || !$model->facebook_eid): ?>
		<div class="row" data-role="fieldcontain">
			<?php echo $form->checkBox($model,'addEventFacebook',array('class'=>'custom')); ?>
			<?php echo $form->labelEx($model,'addEventFacebook',array('data-corners'=>"true", 'data-shadow'=>"false", 'data-iconshadow'=>"true", 'data-inline'=>"false", 'data-wrapperels'=>"span", 'data-icon'=>"checkbox-off", 'data-theme'=>"c")); ?>
			<?php echo $form->error($model,'addEventFacebook'); ?>
		</div>
	<?php endif; ?>
	
	<div class="row" data-role="fieldcontain">
		<b><?php echo CHtml::encode('Количество рядов вашего зала'); ?></b><br />
		<?php echo $form->textField($model,'column',array('maxlength'=>'5', 'size'=>'14','value'=>$model->column)); ?>
		<?php echo $form->error($model,'column'); ?>
	</div>
	
	<div class="row" data-role="fieldcontain">
		<b><?php echo CHtml::encode('Количество мест в каждом ряду'); ?></b><br />
		<?php echo $form->textField($model,'place',array('maxlength'=>'5', 'size'=>'14','value'=>$model->place)); ?>
		<?php echo $form->error($model,'place'); ?>
	</div>
	
	<div class="row" data-role="fieldcontain">
		<?php echo $form->labelEx($model,'status'); ?>
		<div data-role="controlgroup" data-type="horizontal">
			<?php echo $form->radioButtonList($model,'status',Events::$STATUS,array('separator'=>'','labelOptions'=>array('style'=>'font-size: 15px;'))); ?>
		</div>
		<?php echo $form->error($model,'status'); ?>
	</div>
	
	<div class="row" data-role="fieldcontain">
		<?php echo $form->labelEx($model,'logo'); ?>
		<?php echo $form->fileField($model,'logo'); ?>
		<?php echo $form->error($model,'logo'); ?>

		<?php if(!$model->isNewRecord): ?>
			<br /><?php echo CHtml::image($model->changeNameImageOnMini($model->logo),'logo'); ?>
			<br /><?php echo $form->checkBox($model,'delete_logo',array('id'=>'delete_logo')); ?>
			<br /><?php //echo Chtml::activeCheckBox($model,'delete_logo'); ?>
			<?php echo $form->labelEx($model,'delete_logo',array('for'=>'delete_logo')); ?> <br />
		<?php endif; ?>
	</div>

	<div class="row" data-role="fieldcontain">
		<?php
		if(!$model->isNewRecord){
			echo $form->hiddenField($ticket,'count_tickets',array('id'=>'count_tickets','value'=>count($tickets1)));
		}else{
			echo $form->hiddenField($tickets1,'count_tickets',array('id'=>'count_tickets','value'=>'1'));
		}
		if(!$model->isNewRecord){
			echo $form->labelEx($tickets1[0],'type');
			echo '<div data-role="controlgroup">';
				echo $form->radioButtonList($tickets1[0],'type',Tickets::$type_ticket, array('separator'=>''));
			echo '</div>';
			foreach($tickets1 as $i=>$value){
				if($i == 0)
					echo '<fieldset id="first_field" class="ui-body ui-body-e" style="padding: 10px;">';
				else
					echo '<fieldset class="ui-body ui-body-e" style="padding: 10px;">';
					echo $form->hiddenField($value,'ticket_id[]',array('value'=>$value->ticket_id));
					
					echo $form->labelEx($value,'description');
					echo $form->textField($value,'description[]',array('size'=>80, 'value'=>$value->description));
					echo $form->error($value,'description');
					
					echo $form->labelEx($value,'quantity');
					echo $form->textField($value,'quantity[]',array('size'=>14,'maxlength'=>10, 'value'=>$value->quantity));
					echo $form->error($value,'quantity');
					
					echo $form->labelEx($value,'time_begin');
					echo $form->textField($value,'time_begin[]',array('maxlength'=>'5', 'size'=>'14','value'=>$value->time_begin));
					echo $form->error($value,'time_begin');
				
					echo $form->labelEx($value,'time_end');
					echo $form->textField($value,'time_end[]',array('maxlength'=>'5', 'size'=>'14','value'=>$value->time_end));
					echo $form->error($value,'time_end');
					
					echo $form->labelEx($value,'price');
					echo $form->textField($value,'price[]',array('size'=>14,'maxlength'=>10, 'value'=>$value->price));
					echo $form->error($value,'price');
					
					if($i > 0){
						echo '<p>';
						echo CHtml::linkButton('Удалить билет',array(
						   'submit'=>array('events/deleteTicket','id'=>$value->ticket_id),
						   'params'=>array('returnUrl'=>array('/events/update/'.$model->id)),
						   'confirm'=>"Вы уверены, что хотите удалить билет?",
						));
						echo '</p>';
					}
				echo '</fieldset>';
			}
			echo '<div><a id="add_tickets">Добавить билет</a></div>';
			echo '<div class="switcher" style="display:none;">';
				echo $form->labelEx($tickets1[0],'date_begin');
				echo $form->textField($tickets1[0],'date_begin',array('class'=>'datepicker','value'=>$model->NormalViewDate($tickets1->date_begin)));
				echo $form->error($tickets1[0],'date_begin');
			
				echo $form->labelEx($tickets1[0],'date_end');
				echo $form->textField($tickets1[0],'date_end',array('class'=>'datepicker','value'=>$model->NormalViewDate($tickets1->date_end)));
				echo $form->error($tickets1[0],'date_end');
			echo '</div>';
			echo $form->error($tickets1[0],'type');
		}else{
			echo $form->labelEx($tickets1,'type');
			echo '<div data-role="controlgroup">';
			echo $form->radioButtonList($tickets1,'type',Tickets::$type_ticket, array('separator'=>''));
			echo '</div>';
			echo '<fieldset id="first_field" class="ui-body ui-body-e" style="padding: 10px;">';
				echo $form->labelEx($tickets1,'quantity');
				echo $form->textField($tickets1,'quantity[]',array('size'=>14,'maxlength'=>10));
				echo $form->error($tickets1,'quantity');
				
				echo $form->labelEx($tickets1,'time_begin');
				echo $form->textField($tickets1,'time_begin[]',array('maxlength'=>'5', 'size'=>'14','value'=>$tickets1->time_begin));
				echo $form->error($tickets1,'time_begin');
			
				echo $form->labelEx($tickets1,'time_end');
				echo $form->textField($tickets1,'time_end[]',array('maxlength'=>'5', 'size'=>'14','value'=>$tickets1->time_end));
				echo $form->error($tickets1,'time_end');
				
				echo $form->labelEx($tickets1,'price');
				echo $form->textField($tickets1,'price[]',array('size'=>14,'maxlength'=>10));
				echo $form->error($tickets1,'price');
				
				echo $form->labelEx($tickets1,'description');
				echo $form->textField($tickets1,'description[]',array('size'=>80));
				echo $form->error($tickets1,'description');
			echo '</fieldset>';
			echo '<div><a id="add_tickets">Добавить билет</a></div>';
			echo '<div class="switcher" style="display:none;">';
				echo $form->labelEx($tickets1,'date_begin');
				echo $form->textField($tickets1,'date_begin',array('class'=>'datepicker','value'=>$model->NormalViewDate($tickets1->date_begin)));
				echo $form->error($tickets1,'date_begin');
			
				echo $form->labelEx($tickets1,'date_end');
				echo $form->textField($tickets1,'date_end',array('class'=>'datepicker','value'=>$model->NormalViewDate($tickets1->date_end)));
				echo $form->error($tickets1,'date_end');
			echo '</div>';
			echo $form->error($tickets1,'type');
		}
			
			
			/*
			echo CHtml::encode(Tickets::$type_ticket['disposable'].': ');
			echo $form->CheckBox($tickets1,'type',array('name'=>'Tickets1[type]','class'=>'switcher', 'value'=>'disposable'));
			//echo ' Одноразовый<br>';
			echo '<div class="switcher" style="display:none; ">';
				echo $form->labelEx($tickets1,'quantity');
				echo $form->textField($tickets1,'quantity',array('name'=>'Tickets1[quantity]','size'=>10,'maxlength'=>10));
				echo $form->error($tickets1,'quantity');
				echo $form->labelEx($tickets1,'price');
				echo $form->textField($tickets1,'price',array('name'=>'Tickets1[price]','size'=>10,'maxlength'=>10));
				echo $form->error($tickets1,'price');
			echo '</div><br>';
			
			echo CHtml::encode(Tickets::$type_ticket['reusable'].': ');
			echo $form->CheckBox($tickets2,'type',array('name'=>'Tickets2[type]','class'=>'switcher', 'value'=>'reusable'));
			//echo ' Многоразовый<br>';
			echo '<div class="switcher">';
				echo $form->labelEx($tickets2,'quantity');
				echo $form->textField($tickets2,'quantity',array('name'=>'Tickets2[quantity]','size'=>10,'maxlength'=>10));
				echo $form->error($tickets2,'quantity');
				
				echo $form->labelEx($tickets2,'price');
				echo $form->textField($tickets2,'price',array('name'=>'Tickets2[price]','size'=>10,'maxlength'=>10));
				echo $form->error($tickets2,'price');
			echo '</div><br>';
			
			echo CHtml::encode(Tickets::$type_ticket['travel'].': ');
			echo $form->CheckBox($tickets3,'type',array('name'=>'Tickets3[type]','class'=>'switcher', 'value'=>'travel'));
			//echo ' Проездной<br>';
			echo '<div class="switcher">';
				echo $form->labelEx($tickets3,'quantity');
				echo $form->textField($tickets3,'quantity',array('name'=>'Tickets3[quantity]','size'=>10,'maxlength'=>10));
				echo $form->error($tickets3,'quantity');
				
				echo $form->labelEx($tickets3,'price');
				echo $form->textField($tickets3,'price',array('name'=>'Tickets3[price]','size'=>10,'maxlength'=>10));
				echo $form->error($tickets3,'price');
				
				echo $form->labelEx($tickets3,'date_begin');
				echo $form->textField($tickets3,'date_begin',array('name'=>'Tickets3[date_begin]','class'=>'datepicker','value'=>$model->NormalViewDate($tickets3->date_begin)));
				echo $form->error($tickets3,'date_begin');
			
				echo $form->labelEx($tickets3,'date_end');
				echo $form->textField($tickets3,'date_end',array('name'=>'Tickets3[date_end]','class'=>'datepicker','value'=>$model->NormalViewDate($tickets3->date_end)));
				echo $form->error($tickets3,'date_end');
			echo '</div>';
			*/
		?>
	</div>
			
	<div class="row" data-role="fieldcontain">
		<?php echo $form->checkBox($model,'online',array('class'=>'custom')); ?>
		<?php echo $form->labelEx($model,'online',array('data-corners'=>"true", 'data-shadow'=>"false", 'data-iconshadow'=>"true", 'data-inline'=>"false", 'data-wrapperels'=>"span", 'data-icon'=>"checkbox-off", 'data-theme'=>"c")); ?>
		<?php echo $form->error($model,'online'); ?>
	</div>
	
	<div class="row buttons" data-role="fieldcontain">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', array('data-theme'=>"b")); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->