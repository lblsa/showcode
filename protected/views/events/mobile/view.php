<?php
if(count($log->errors)>0)
	echo CHtml::hiddenField('click','click');
if($model->place)
	$places = $model->place;
else
	$places = 0;

Yii::app()->clientScript->registerScript('name_js1','
    $("body > div.ui-page").live("pageaftershow",function(event){
	var count_place = '.$places.';
	var places = Array();
	var placesCount = 0;

                    $("#button_bye").live("tap",function(event) {
                          console.log("test");
                          $("#button_bye").empty();
                          $("div.payment").show();
                          return false;
                      });

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

	$("#idbuy").click(function(){
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
        });
');
/*
Yii::app()->clientScript->registerScriptFile('https://userapi.com/js/api/openapi.js?34', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScript('vkontakte_like','
	VK.init({apiId:'. Yii::app()->params["vk_id"].', onlyWidgets: true});
	VK.Widgets.Like("vk_like", {type: "button"});
');

Yii::app()->clientScript->registerScript('facebook_like','
	(function(d){
	  var js, id = "facebook-jssdk"; if (d.getElementById(id)) {return;}
	  js = d.createElement("script"); js.id = id; js.async = true;
	  js.src = "//connect.facebook.net/ru_RU/all.js#appId=281547825204430&xfbml=1";
	  d.getElementsByTagName("head")[0].appendChild(js);
	}(document));
');*/
?>

<?php $this->headering = $model->title; ?>


<?php
if (yii::app()->user->isAdmin() || yii::app()->user->isOrganizer())
	$this->menu=array(
		array('label'=>'Список мероприятий', 'url'=>array('index')),
	);
if (yii::app()->user->isAdmin() || yii::app()->user->id == $model->author)
	$this->menu=array(
		//array('label'=>'Создать мероприятие', 'url'=>array('create')),
		array('label'=>'Создать мероприятие', 'url'=>array('create'), 'itemOptions'=>array('data-icon'=>'plus'),),
		array('label'=>'Редактировать', 'url'=>array('update', 'id'=>$model->id)),
		//array('label'=>'Управление мероприятиями', 'url'=>array('admin')),
	);
if (yii::app()->user->isAdmin())
	$this->menu[]=array('label'=>'Удалить мероприятие', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Вы действительно хотите удалить мероприятие?'), 'itemOptions'=>array('data-icon'=>'delete'),);

if (yii::app()->user->isCreator($model->id))
{
	$this->menu[]=array('label'=>'Билеты', 'url'=>array('/ticket/admin', 'id'=>$model->id));
	$this->menu[]=array('label'=>'Проверка билетов', 'url'=>array('checkTicket', 'id'=>$model->id));
}
?>


<?php
    $attributes[] = array(
        'name'=>'description',
        'type'=>'raw',
        'value'=>nl2br($model->description),
        );

    if(!$uniqEvent)
        $attributes[] = array(
            'name'=>'datetime',
            'value'=>$model->normalViewDate($model->datetime),
            );

    if(!$uniqEvent || Yii::app()->user->isAdmin())
        $attributes[] = array(
            'name'=>'author',
            'type'=>'raw',
            'value'=>Yii::app()->user->getAuthorName($model->author),
            );

    if($uniqEvent->location)
            $attributes[] = array(
                'label'=>EventUniq::model()->getAttributeLabel('location'),
                'type'=>'raw',
                'value'=>$uniqEvent->location,
                );
    if($uniqEvent->phone)
            $attributes[] = array(
                'label'=>EventUniq::model()->getAttributeLabel('phone'),
                'type'=>'raw',
                'value'=>nl2br($uniqEvent->phone),
                );

    if($uniqEvent->fax)
        $attributes[] = array(
            'label'=>EventUniq::model()->getAttributeLabel('fax'),
            'type'=>'raw',
            'value'=>nl2br($uniqEvent->fax),
            );

    if($uniqEvent->email)
        $attributes[] = array(
            'label'=>EventUniq::model()->getAttributeLabel('email'),
            'type'=>'raw',
            'value'=>'<a href="mailto:'.nl2br($uniqEvent->email).'">'.nl2br($uniqEvent->email).'</a>',
            );

    if($uniqEvent->sait)
        $attributes[] = array(
            'label'=>EventUniq::model()->getAttributeLabel('sait'),
            'type'=>'raw',
            'value'=>'<a target="_blank" href="http://'.nl2br($uniqEvent->sait).'">'.nl2br($uniqEvent->sait).'</a>',
            );

    if($uniqEvent->time_work)
        $attributes[] = array(
            'label'=>EventUniq::model()->getAttributeLabel('time_work'),
            'type'=>'raw',
            'value'=>nl2br($uniqEvent->time_work),
            );

    if(!$uniqEvent->infinity_time){
            $attributes[] = array(
                'label'=>Events::model()->getAttributeLabel('datetime'),
                'type'=>'raw',
                'value'=>$model->normalViewDate($model->datetime),
                );
        }

        if ($model->facebook_eid)
            $attributes[] = array(
                'name'=>'facebook_eid',
                'type'=>'url',
                );

        if(!$uniqEvent || Yii::app()->user->isAdmin())
            $attributes[] = array(
                'name'=>'status',
                'type'=>'raw',
                'value'=>Events::$STATUS[$model->status],
                );

        $attributes[] = array(
            'name'=>'logo',
            'type'=>'image',
            'value'=>$model->logo,
            );

        if(!$uniqEvent || Yii::app()->user->isAdmin())
            $attributes[]=array(
                'label'=>'Тип билета',
                'type'=>'raw',
                'value'=>Tickets::$type_ticket[$ticket[0]['type']],
                );

        if(!$uniqEvent || Yii::app()->user->isAdmin()){
            if ( isset($ticket[0]['date_begin']) && isset($ticket[0]['date_end']) ){
                $attributes[]=array(
                    'label'=>'Дата начала действия',
                    'type'=>'raw',
                    'value'=>Events::normalViewDate($ticket[0]['date_begin']),
                    );

                $attributes[]=array(
                    'label'=>'Дата окончания действия',
                    'type'=>'raw',
                    'value'=>Events::normalViewDate($ticket[0]['date_end']),
                    );
            }
        }

        $this->widget('zii.widgets.CDetailView', array(
            'data'=>$model,
            'attributes'=>$attributes,
            'tagName' => 'ul',
            'itemTemplate' => '<li data-role="list-divider" role="heading">{label}</li><li>{value}</li></li>',
            'itemCssClass'=> array(),
            'htmlOptions'=>array('data-role'=>'listview', 'data-theme'=>"c", 'data-inset'=>"true"),
        ));
?>

<?php echo '<br/>';

				//Выводится информация о билетах
$this->renderPartial(Yii::app()->mf->siteType(). '/_ticket', array('ticket'=>$ticket,));?>

<?php
	//для админа или создателя мероприятия выводим открытые ключи rsa и код для вставки на сторонние ресурсы.
if (Yii::app()->user->isAdmin() || Yii::app()->user->isCreator($model->id))
{
	echo '<ul data-role="listview" data-theme="c" data-inset="true">';
	echo '<li>';
    echo CHtml::encode('Открытый ключ RSA: ').'<br/>';
    $str_keys = $model->open_key.':'.$model->general_key;
    echo CHtml::textField('open_key', $str_keys, array('size'=>110, 'readonly'=>true));
    echo '</li>';

    echo '<li>';
    echo CHtml::encode('Код мероприятия для вставки на сторонние ресурсы:').'<br/>';
    echo CHtml::textArea('htmlcode',$model->getHtmlCode(),array('rows'=>1, 'cols'=>80, 'readonly'=>true));
    echo '</li>';

	echo '</ul>';
}
?>

<br />
<!--
<table>
	<tr>
		<td> <div id="vk_like"></div> </td>
		<td> <div id="fb-root"></div> <div class="fb-like" data-href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/events/view/<?php echo $model->id; ?>" data-send="false" data-width="300" data-show-faces="false"></div> </td>
		<td>
		<?php if($facebook_event): ?>
			<a href="?facebook=declined"><img src="/images/declined.png" alt="Отказаться от посещения" title="Отказаться от посещения" /></a>
		<?php else: ?>
			<a href="?facebook=attending"><img src="/images/attending.png" alt="Пойти на это мероприятие" title="Пойти на это мероприятие" /></a>
		<?php endif;?>
		</td>
	</tr>
</table>
-->


<?php if($model->status=='published'):?>
                <a id="button_bye" data-role="button" href="#bar">Купить билеты</a>
<div data-role="page" id="bar">
	<?php			//Начинается форма покупки
	$form=$this->beginWidget('CActiveForm', array(
			'id'=>'log-form',
			'enableAjaxValidation'=>false,
			'htmlOptions'=>array('enctype'=>'multipart/form-data'),
		));
	?>

	<div class = "form payment">

		<?php echo $form->hiddenField($log,'event_id',array('value'=>$model->id)); ?>
		<!--<?php echo $form->hiddenField($log,'price',array('value'=>$ticket->price)); ?>-->
		<!--<?php echo $form->hiddenField($log,'quantity',array('name'=>'quantity_max','id'=>'quantity_max','value'=>$ticket->quantity)); ?>-->
		<!--<?php echo $form->hiddenField($log,'column[]'); ?>-->
		<!--<?php echo $form->hiddenField($log,'place[]'); ?>-->

		<?php if ($ticket->type=='reusable'):?>
			<div class="row">
				<?php echo $form->labelEx($log,'quantity'); ?>
				<?php echo $form->textField($log,'quantity',array('size'=>5)); ?>

				<?php if ($log->errors['quantity'][0]=='big_size'): ?>
					<div class="errorMessage">Вы не можете купить столько билетов</div>
				<?php endif; ?>

				<?php if ($log->errors['quantity'][0]=='null'): ?>
					<div class="errorMessage">Поле не может быть пустым</div>
				<?php endif; ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($log,'total'); ?>
				<?php echo $form->textField($log,'total',array('name'=>'summ', 'size'=>5, 'readonly'=>1)); ?>
				<?php if ($log->errors['total'][0]==1): ?>
					<div class="errorMessage">Сумма не верна</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if (!isset(Yii::app()->user->id)): ?>
			<div class="row">
				<?php echo $form->labelEx($log,'family'); ?>
				<?php echo $form->textField($log,'family',array('size'=>40,'maxlength'=>50)); ?>
				<?php if ($log->errors['family'][0]==1): ?>
					<div class="errorMessage">Некорректно заполнено</div>
				<?php endif; ?>
			</div>

			<!--<div class="row">
				<?php echo $form->labelEx($log,'address'); ?>
				<?php echo $form->textField($log,'address',array('size'=>40,'maxlength'=>50)); ?>
				<?php if ($log->errors['address'][0]==1): ?>
					<div class="errorMessage">Некорректно заполнено</div>
				<?php endif; ?>
			</div>-->
		<?php endif; ?>

		<?php if (!isset(Yii::app()->user->phone)): ?>
			<div class="row">
				<?php echo $form->labelEx($log,'phone'); ?>
				+7 <?php echo $form->textField($log,'phone',array('size'=>37,'maxlength'=>10)); ?>
				<?php if ($log->errors['phone'][0]): ?>
					<?php if ($log->errors['phone'][0]==1): ?>
						<div class="errorMessage">Некорректно заполнено</div>
					<?php else: ?>
						<?php if ($log->errors['phone'][0]==2): ?>
							<div class="errorMessage">Пользователь с таким номером телефона уже зарегистрирован в системе</div>
						<?php else: ?>
							<div class="errorMessage"><?php print_r($log->errors['phone'][0]); ?></div>
						<?php endif; ?>
					<?php endif; ?>
				<?php endif; ?>
			</div>
			<?php if (!isset(Yii::app()->user->email)): ?>
				<div class="row">
					<?php echo $form->labelEx($log,'mail'); ?>
					<?php echo $form->textField($log,'mail',array('size'=>40,'maxlength'=>50)); ?>
					<?php if ($log->errors['mail'][0]): ?>
						<?php if ($log->errors['mail'][0]==1): ?>
							<div class="errorMessage">Некорректный е-mail</div>
						<?php else: ?>
							<?php if ($log->errors['mail'][0]==2): ?>
								<div class="errorMessage">Такой e-mail уже зарегистрирован в системе</div>
							<?php else: ?>
								<div class="errorMessage"><?php print_r($log->errors['mail'][0]); ?></div>
							<?php endif; ?>
						<?php endif; ?>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<?php if (isset(Yii::app()->user->id)): ?>
			<div class="row rememberMe">
				<?php echo $form->checkBox($log,'rememberMail'); ?>
				<?php echo $form->label($log,'rememberMail'); ?>
				<?php echo $form->error($log,'rememberMail'); ?>
			</div>
		<?php endif; ?>

		<div class="row">
			<?php //echo $form->labelEx($log,'payment'); ?>
			<?php //echo $form->radioButton($log,'payment', array('value'=>'credit_card','id'=>'TransactionLog_payment_0')); ?>
			<?php //echo CHtml::image('/images/credit_card.jpg','credit_card'); ?>
			<?php //echo $form->labelEx($log,TransactionLog::$payment_type['credit_card'],array('for'=>'TransactionLog_payment_0')); ?>
			<?php //echo $form->radioButton($log,'payment', array('value'=>'qiwi','id'=>'TransactionLog_payment_1')); ?>
		</div>

		<?php if ($model->column && $model->place): ?>
			<div class="row">
				<?php echo CHtml::hiddenField('isPlace',1); ?>
				<?php echo $form->labelEx($log,'place'); ?>
				<table border="1px">
				<?php for ($i=1;$i<=$model->column;$i++): ?>
					<tr>
					<?php for ($j=1;$j<=$model->place;$j++): ?>
						<?php if (in_array(($i-1)*$model->place+$j, $buy_place)): ?>
							<td title="Билет куплен" style="background:#898989"><?php echo ($i-1)*$model->place+$j;?></td>
						<?php else: ?>
							<td class="place" column="<?php echo $i;?>" place="<?php echo $j;?>"><?php echo ($i-1)*$model->place+$j;?></td>
						<?php endif; ?>
					<?php endfor; ?>
					</tr>
				<?php endfor; ?>
				</table>
				<?php if ($log->errors['place'][0]==1): ?>
						<div class="errorMessage">Вы не выбрали место!</div>
				<?php endif; ?>
				<?php if ($log->errors['place'][0]==2): ?>
						<div class="errorMessage">Этот билет уже куплен!</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="row">
			<?php $this->renderPartial(Yii::app()->mf->siteType(). '/_ticket', array('ticket'=>$ticket, 'buy'=>true)); ?>

			<?php if ($log->errors['ticket'][0]==1): ?>
				<p style="color:red">Не выбран билет</p>
			<?php endif; ?>
		</div>
		<br />

<?php if ($ticket[0]['type']!='free'):?>
		<div class="row">
			<label for="TransactionLog_payment">Способ оплаты</label>
			<table width="250px" >
			<tr>
				<td><input value="credit_card" id="TransactionLog_payment_0" name="TransactionLog[payment]" type="radio"></td>
				<td><label for="TransactionLog_payment_0"><img src="/images/credit_card.jpg" alt="credit_card"></label></td>
				<td><label for="TransactionLog_payment_0">Кредитная карта</label></td>
			</tr>
			<tr>
				<td><input value="qiwi" id="TransactionLog_payment_1" name="TransactionLog[payment]" type="radio"></td>
				<td><label for="TransactionLog_payment_1"><img src="/images/qiwi.gif" alt="qiwi"></label></td>
				<td><label for="TransactionLog_payment_1">Qiwi кошелёк</label></td>
			</tr>
			</table>
			<?php if ($log->errors['payment'][0]==1): ?>
				<p style="color:red">Некорректный тип</p>
			<?php endif; ?>
		</div>
		<?php endif; ?>
		<br />
		<?php echo CHtml::submitButton('Перейти к оплате', array('id'=>'idbuy')); ?>

	</div>

	<?php $this->endWidget(); ?>
    </div>
<?php endif; ?>