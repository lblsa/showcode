<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
        <link rel="stylesheet" href="/css/reset.css" />
        <link rel="stylesheet" href="/css/base.css" />
        <script src="/js/jquery-1.6.4.js"></script>
</head>
<body style="background: #FFF;">
        
      <div class="form_main" id="form_tickets_buy" style="height: 92%; width: 95%; top: 0px; border: none; margin: 0; ">
<?php if(!$saveDB): ?>
         <?php
            if($model->place)
                $places = $model->place;
            else
                $places = 0;
        ?>
        <script type="text/javascript" language="javascript">
               //<![CDATA[
            $(document).ready(function() {
            var count_place = <?php echo $places ?>;
            var places = Array();
            var placesCount = 0;
            $("#TransactionLog_quantity").keyup(function()
            {
                    var q = parseInt($("#TransactionLog_quantity").val());
                    var radio_inputs = $(".grid-view input");                    
                        
                    for(var i = 0; i < radio_inputs.length; i++){
                            if(radio_inputs[i].checked){
                                    var elem = radio_inputs[i];
                            }
                    }
                    if(elem){
                            var price = parseInt($(elem).parents("tr").find("td.price").text());
                            var quantity_max = parseInt($(elem).parents("tr").find("td.quantity").text());

                            if (q >= 0)
                                    $("input[name=summ]").val(q*price);
                            if (q > quantity_max || !q)
                            {
                                    $("#TransactionLog_quantity").val(0);
                                    $("input[name=summ]").val(0);
                            }
                    }else{

                        $("#TransactionLog_quantity").val(0);
                        $("input[name=summ]").val(0);
                        alert("Выбрать билет");
                    }
            });

            $("#htmlcode").click(function()
            {
                    $("#htmlcode").attr("rows","9");
            });

            $("td.place").click(function(){
                    var place = this;
                    var placeClass = place.className;
                    var iplace = parseInt($(place).attr("place"));
                    var icolumn = parseInt($(place).attr("column"));
                    var id = (icolumn - 1) * count_place + iplace;
                    if (!places["order_" + id]) {
                            if (placesCount < 5) {
                                    if (placeClass == "place") {
                                            place.className = "place select_place";
                                            $(place).css("background", "#429FCC");
                                    }
                                    places["order_" + id] = {"place": iplace, "column": icolumn};
                                    placesCount++;
                            } else {
                                    alert("\u041C\u043E\u0436\u043D\u043E \u0437\u0430\u0431\u0440\u043E\u043D\u0438\u0440\u043E\u0432\u0430\u0442\u044C \u043D\u0435 \u0431\u043E\u043B\u0435\u0435 5 \u043C\u0435\u0441\u0442");
                            }
                    } else {
                            if (placeClass == "place select_place") {
                                    place.className = "place";
                                    $(place).css("background", "#FFF");
                            }
                            places["order_" + id] = null;
                            placesCount--;
                    }
            });

            $("td.place").mouseover(function(){
                    if(placesCount > 0){
                            var darw = true;
                            for(var id in places){
                                    if (!places[id] || (places[id]["place"] != $(this).attr("place") && places[id]["column"] != $(this).attr("column")))
                                            darw = true;
                                    else{
                                            darw = false;
                                            break;
                                    }
                            }
                            if(darw){
                                    $(this).css("background", "#AAE6FF");
                            }
                    }else{
                            $(this).css("background", "#AAE6FF");
                    }
            });

            $("td.place").mouseout(function(){
                    if(placesCount > 0){
                            var darw = true;
                            for(var id in places){
                                    if (!places[id] || (places[id]["place"] != $(this).attr("place") && places[id]["column"] != $(this).attr("column")))
                                            darw = true;
                                    else{
                                            darw = false;
                                            break;
                                    }
                            }
                            if(darw){
                                    $(this).css("background", "#FFF");
                            }
                    }else{
                            $(this).css("background", "#FFF");
                    }
            });

            $("input[name=\"TransactionLog[payment]\"]").click(function(){
            if ($("#isPlace").val()==1 && placesCount < 1)
            {
                    alert("Сначала выберете место!");
                    $(this).removeAttr("checked");
            }});

            $("input[name=\"TransactionLog[ticket_id]\"]").click(function(){
            if ($("#isPlace").val()==1 && placesCount < 1)
            {
                    alert("Сначала выберете место!");
                    $(this).removeAttr("checked");
            }});

            $("#submit_save_button").click(function(){
                    if(placesCount > 0){
                            var html = "";
                            for(var id in places){
                                    html = "";
                                    html += "<input id=\"TransactionLog_place\" type=\"hidden\" name=\"TransactionLog[place][]\" value=\""+places[id]["place"]+"\">";
                                    html += "<input id=\"TransactionLog_column\" type=\"hidden\" name=\"TransactionLog[column][]\" value=\""+places[id]["column"]+"\">";
                                    $("#TransactionLog_event_id").after(html);
                            }
                    }
            });
            })
            //]]>
        </script>
          <div>
              <?php if($ticket[0]['type'] == 'free'): ?>
                <h1 style="width: 700px; font-size: 20px; line-height: 24px; color: #fb6006;">Заказ билетов на мероприятие «<?php echo $model->title ?>»</h1>
              <?php else: ?>
                <h1 style="width: 700px; font-size: 20px; line-height: 24px; color: #fb6006;">Покупка билетов на мероприятие «<?php echo $model->title ?>»</h1>
              <?php endif; ?>
          </div>
	<?php			//Начинается форма покупки
	$form=$this->beginWidget('CActiveForm', array(
			'id'=>'events-form',
			'enableAjaxValidation'=>false,
			'htmlOptions'=>array('enctype'=>'multipart/form-data'),
		));
	?>
	
<!--<a style="font-size: 16px; margin:0px 0px 0px 230px;" id="button_bye" href="#">Купить билеты</a>-->
	
            
            <?php echo $form->hiddenField($log,'event_id',array('value'=>$model->id)); ?>
           
            <div>
                    <?php echo $form->labelEx($log,'phone'); ?>
                    +7 <?php echo $form->textField($log,'phone',array('value'=>$log->phone,'size'=>37,'maxlength'=>10)); ?>
                    <?php if ($log->errors['phone'][0]): ?>
                            <?php if ($log->errors['phone'][0]==1): ?>
                                <div class="errorMessage"><p style="color:red">Некорректно заполнено</p></div>
                            <?php else: ?>
                                    <?php if ($log->errors['phone'][0]==2): ?>
                                            <div class="errorMessage"><p style="color:red">Пользователь с таким номером телефона уже зарегистрирован в системе</p></div>
                                    <?php else: ?>
                                            <div class="errorMessage"><p style="color:red"><?php print_r($log->errors['phone'][0]); ?></p></div>
                                    <?php endif; ?>
                            <?php endif; ?>
                    <?php endif; ?>
            </div>

            <div>
                    <?php echo $form->labelEx($log,'family'); ?>
                    <?php echo $form->textField($log,'family',array('value'=>$log->family,'size'=>40,'maxlength'=>50)); ?>
                    <?php if ($log->errors['family'][0]==1): ?>
                            <div class="errorMessage"><p style="color:red">Некорректно заполнено</p></div>
                    <?php else: ?>
                            <div class="errorMessage"><p style="color:red"><?php print_r($log->errors['family'][0]); ?></p></div>
                    <?php endif; ?>
            </div>
            
            <div>
                    <?php echo $form->labelEx($log,'mail'); ?>
                    <?php echo $form->textField($log,'mail',array('value'=>$log->mail,'size'=>40,'maxlength'=>50)); ?>
                    <?php if ($log->errors['mail'][0]): ?>
                            <?php if ($log->errors['mail'][0]==1): ?>
                                    <div class="errorMessage"><p style="color:red">Некорректный е-mail</p></div>
                            <?php else: ?>
                                    <?php if ($log->errors['mail'][0]==2): ?>
                                            <div class="errorMessage"><p style="color:red">Такой e-mail уже зарегистрирован в системе</p></div>
                                    <?php else: ?>
                                            <div class="errorMessage"><p style="color:red"><?php print_r($log->errors['mail'][0]); ?></p></div>
                                    <?php endif; ?>
                            <?php endif; ?>
                    <?php endif; ?>
            </div>			

		<?php if ($model->column && $model->place): ?>
			<div>
				<?php echo CHtml::hiddenField('isPlace',1); ?>
				<h2><?php echo $log->getAttributeLabel('place'); ?></h2>
                                
				<table border="1px">
                                    <tr>
                                        <td></td>
                                        <?php for ($j=1;$j<=$model->place;$j++): ?>                                            
                                            <td><?php echo $j;?></td>
                                        <?php endfor; ?>
                                    </tr>
				<?php for ($i=1;$i<=$model->column;$i++): ?>                                        
					<tr>
                                            <td><?php echo $i;?></td>
					<?php for ($j=1;$j<=$model->place;$j++): ?>
						<?php if (in_array(($i-1)*$model->place+$j, $buy_place)): ?>
							<td title="Билет куплен" style="background:#898989"><?php //echo ($i-1)*$model->place+$j;?></td>
						<?php else: ?>
							<td class="place" column="<?php echo $i;?>" place="<?php echo $j;?>"><?php //echo ($i-1)*$model->place+$j;?></td>
						<?php endif; ?>
					<?php endfor; ?>
					</tr>
				<?php endfor; ?>
				</table>
				<?php if ($log->errors['place'][0]==1): ?>
						<div class="errorMessage"><p style="color:red">Вы не выбрали место!</p></div>
				<?php endif; ?>
				<?php if ($log->errors['place'][0]==2): ?>
						<div class="errorMessage"><p style="color:red">Этот билет уже куплен!</p></div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
			
		
		<div>
			<?php $this->renderPartial(Yii::app()->mf->siteType(). '/_ticket', array('ticket'=>$ticket, 'buy'=>true, 'log'=>$log)); ?>
			
			<?php if ($log->errors['ticket'][0]==1): ?>
				<p style="color:red">Не выбран билет</p>
			<?php endif; ?>
		</div>
                        
                                      <?php if ($ticket[0]->type=='reusable'):?>
                                            <div>
                                                    <?php echo $form->labelEx($log,'quantity'); ?>
                                                    <?php echo $form->textField($log,'quantity',array('value'=>$log->quantity,'size'=>5)); ?>

                                                    <?php if ($log->errors['quantity'][0]=='big_size'): ?>
                                                            <div class="errorMessage"><p style="color:red">Вы не можете купить столько билетов</p></div>
                                                    <?php endif; ?>

                                                    <?php if ($log->errors['quantity'][0]=='null'): ?>
                                                            <div class="errorMessage"><p style="color:red">Поле не может быть пустым</p></div>
                                                    <?php endif; ?>
                                            </div>

                                            <div>
                                                    <?php echo $form->labelEx($log,'total'); ?>
                                                    <?php echo $form->textField($log,'total',array('value'=>$log->total,'name'=>'summ', 'size'=>5, 'readonly'=>1)); ?>
                                                    <?php if ($log->errors['total'][0]==1): ?>
                                                            <div class="errorMessage"><p style="color:red">Сумма не верна</p></div>
                                                    <?php endif; ?>
                                            </div>
                                    <?php endif; ?>                        
		
			
	<?php if ($ticket[0]['type']!='free'):?>
		<div>
			<h2>Способ оплаты</h2>
			<table width="250px" >
			<!--<tr>
				<td><input value="credit_card" id="TransactionLog_payment_0" name="TransactionLog[payment]" type="radio" <?php if ($log->payment == 'credit_card') echo 'checked'; ?> /></td>
				<td><label for="TransactionLog_payment_0"><img src="/images/credit_card.jpg" alt="credit_card" /></label></td>
				<td><label for="TransactionLog_payment_0">Кредитная карта</label></td>
			</tr>-->
			<tr>
				<td><input value="qiwi" id="TransactionLog_payment_1" name="TransactionLog[payment]" type="radio" <?php if ($log->payment == 'qiwi') echo 'checked'; else  echo 'checked'; ?> /></td>
				<td><label for="TransactionLog_payment_1"><img src="/images/qiwi.gif" alt="qiwi" /></label></td>
				<td><label for="TransactionLog_payment_1">Qiwi кошелёк</label></td>
			</tr>
			</table>
			<?php if ($log->errors['payment'][0]==1): ?>
				<p style="color:red">Некорректный тип</p>
			<?php endif; ?>
		</div>
			<?php endif; ?>
                <div>
                    <?php if($ticket[0]['type'] == 'free'): ?>
                        <?php echo CHtml::submitButton('Забрать билет', array('id'=>'submit_save_button',)); ?>
                    <?php else: ?>
                        <?php echo CHtml::submitButton('Перейти к оплате', array('id'=>'submit_save_button',)); ?>
                    <?php endif; ?>
                </div>

	
	<?php $this->endWidget(); ?>


<?php else: ?>
                        <h2>Ваш билет забронирован в сервисе Showcode.</h2>
                        <p>На указанный вами телефонный номер и E-mail выслана ссылка на билет данного мероприятия.</p>
                        <p>Просмотр билетов в сервисе Showcode доступен только для зарегистрированных пользователей. Если вы не зарегистрированы, то вам на указанный номер телефона будет выслано SMS-сообщение с паролем к данному сервису.</p>
                        <a target="_blank" href="http://<?php echo $_SERVER['HTTP_HOST'] ?>/ticket/view/<?php echo $log->uniq ?>">Для просмотра билета перейдите по ссылке.</a>
<?php endif; ?>
</div>
</body>
</html>
