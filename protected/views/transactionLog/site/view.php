<link href="/js/theme-redmond/jquery-ui-1.8.13.custom.css" type="text/css" rel="stylesheet">
<script src="/js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
<script src="/js/jquery-ui-timepicker-addon.js" type="text/javascript"></script>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.PrintArea.js');?>
<div class="main_form_wrapper list_buy_events">
    <div id="list_tickets">
	<div id="print_area">
        <?php if ($model->errors['responsePaymentClientError']): ?>
            <h1>Ошибка</h1>
            <p>В данный момент вы не можете произвести оплату.</p>
            <p>Повторите попытку через некоторое время.</p>
            <p><?php echo $model->errors['responsePaymentClientError'][0]; ?></p>
            <br/><br/>
            <?php echo CHtml::link("Нажмите для повторной попытки", "/events/view/".$model->event_id) ?>
        <?php else: ?>
			<?php if ($model->status==0): ?>
				<?php $Booking_Time = Control::model()->find("name = 'Booking_Time'")->value; ?>
				<?php if ($model->payment=='qiwi'): ?>
					<p>Ваш билет ожидает оплаты. В течении <?php echo $Booking_Time; ?> часов вы должны оплатить данный счёт в терминале <b>qiwi</b>.</p>
					<div style="margin:0 auto; padding:5px; width:500px; border:1px solid #ddd; background:#fff; border-radius: 7px; -webkit-border-radius: 7px; -moz-border-radius: 7px; font:normal 14px/14px Geneva,Verdana,Arial,Helvetica,Tahoma,sans-serif;">
						<form action="https://w.qiwi.ru/setInetBill_utf.do" method="get" accept-charset="UTF-8" onSubmit="return checkSubmit();" target="qiwiIframeName">

							<input type="hidden" name="from" value="16029"/>
							<input type="hidden" name="summ" value="<?php echo $model->total; ?>"/>
							<input type="hidden" name="com" value="<?php echo Events::getEventTitle($model->event_id); ?>">
							<input type="hidden" name="lifetime" value="<?php echo $Booking_Time; ?>"/>
							<input type="hidden" name="check_agt" value="false"/>
							<input type="hidden" name="iframe" value="true"/>
							<input type="hidden" name="txn_id" value="<?php echo $model->uniq;?>"/>

							<p style="text-align:center; color:#006699; padding:20px 0px; background:url(http://ishop.qiwi.ru/img/button/logo_31x50.jpg) no-repeat 10px 50%;">Выставить счёт за покупку</p>
							<table style="border-collapse:collapse;">
								<tr style="background:#f1f5fa;">
									<td style="color:#a3b52d; width:45%; text-align:center; padding:10px 0px;">Мобильный телефон (пример: 9057772233)</td>
									<td style="padding:10px">
										<input type="text" name="to" id="idto" style="width:130px; border: 1px inset #555;"></input>
										<span id="div_idto"></span>
										<script type="text/javascript">
											inputMasks["idto"] = new Mask(document.getElementById("idto"));
											function checkSubmit() {
												if (inputMasks["idto"].getValue().match(/^\d{10}$/)) {
													document.getElementById("idto").setAttribute("disabled", "disabled");
													inputMasks["idto"].makeHInput();
													document.getElementById("qiwiIframeName").style.display = "block";
													setTimeout(function(){inputMasks["idto"] = new Mask(document.getElementById("idto"));}, 1);
													return true;
												} else {
													alert("Введите номер телефона в федеральном формате без \"8\" и без \"+7\"");
													return false;
												}
											}
										</script>
									</td>
								</tr>
							</table>
							<p style="text-align:center;"><input type="submit" value="Выставить счёт за покупку" style=" padding:10px 0;border:none; background:url(http://ishop.qiwi.ru/img/button/superBtBlue.jpg) no-repeat 0 50%; color:#fff; width:300px;"/></p>
						</form>
						<iframe id="qiwiIframeName" name="qiwiIframeName" frameborder="0" width="500" height="600" style="display: none;"></iframe>
					</div>

<!--
			<strong>Инструкция:</strong>
			<ul>
				<li>нажимаете на центральную кнопку основного меню - "QIWI Кошелёк",</li>
				<li>указываете свой номер мобильного телефона <b><?php echo $_GET['phone']; ?></b>,</li>
				<li>заходите в раздел "Счета",</li>
				<li>видите свой счёт, оплачиваете его наличными.</li>
			</ul>
			<a href="http://qiwi.ru/private/how/terminals/area/" target="_blank">Найти ближайший терминал оплаты</a> <br />
			<a href="http://qiwi-in-use.livejournal.com/40012.html" target="_blank">Подробная инструкция</a>
-->
			<?php endif; ?>
			<?php if ($model->payment=='credit_card'): ?>
				<p>Ваш билет ожидает оплаты. В течении <?php echo $Booking_Time; ?> часов вы должны перевести деньги.
				<br />Если за это время оплата не произойдёт, то ваша бронь аннулируется.</p>
				<br />
				<div id="card-form">
					<h3>Пожайлуста, укажите данные карты</h3>
					<?php			//Начинается форма покупки
						$form=$this->beginWidget('CActiveForm', array(
									'enableAjaxValidation'=>false,
									'htmlOptions'=>array('enctype'=>'multipart/form-data'),
							));
					?>
					<div class="row">
						<?php echo CHtml::label('<b>Номер карты:</b>', 'Card_number'); ?>
						<?php echo CHtml::textField('Card[number]','0000000000000000',array('maxlength'=>16)); ?>

					</div>
					<div class="row">
						<?php echo CHtml::label('<b>Срок действия:</b>', 'Card_date'); ?>
						<?php echo CHtml::textField('Card[date]','',array('class'=>'datepicker')); ?>
					</div>
					<div class="row">
						<?php echo CHtml::label('<b>Держатель карты:</b>', 'Card_person'); ?>
						<?php echo CHtml::textField('Card[person]','CARD HOLDER'); ?><br/>
						<span>(латинскими буквами)</span>
					</div>
					<div class="row">
						<?php echo CHtml::label('<b>Код CVV2/CVC2:</b>', 'Card_code'); ?>
						<?php echo CHtml::textField('Card[code]', 'CVC'); ?>
					</div>
					<div class="row">
						<?php echo CHtml::submitButton('Оплатить', array('id'=>'card_pay')); ?>
					</div>
					<?php $this->endWidget(); ?>
				</div>
			<?php endif; ?>
			<?php if ($model->payment=='paypal'): ?>
					<p>Ваш билет ожидает оплаты. В течении <?php echo $Booking_Time; ?> часов вы должны перевести деньги.
					<br />Если за это время оплата не произойдёт, то ваша бронь аннулируется.</p>

					<form action="/expresscheckout.php" METHOD='POST'>
						<input type='image' name='submit' src='https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif' border='0' align='top' alt='Check out with PayPal'/>
					</form>
			<?php endif; ?>
		<?php else: ?>

			<?php
			$this->breadcrumbs=array(
				'Билеты'=>array('index'),
				Events::getEventTitle($model->event_id),
			);
			if(Yii::app()->user->isCreator($model->event_id) && $model->status == 1){
				$this->menu[]=array('label'=>'Провести билет', 'url'=>'#', 'linkOptions'=>array('submit'=>array('/events/checkTicket/'.$model->event_id.'?markticket=1'),'params'=>array('TransactionLog[uniq]'=>$model->uniq),'confirm'=>'Вы действительно хотите провести билет?'));
			}
			if (yii::app()->user->isAdmin()){
				$this->menu[]=array('label'=>'Список', 'url'=>array('index'));
					if ($model->status == 0)
						$this->menu[]=array('label'=>'Удалить заказ', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->uniq),'confirm'=>'Точно удалить заказ?'));
			}else
				if ($model->status == 0)
					$this->menu[]=array('label'=>'Удалить бронь', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->uniq),'confirm'=>'Вы действительно хотите удалить заказ?'));
			?>
			<?php if ($model->status==1): ?>
					<?php $this->pageTitle=Yii::app()->name.' - Билет' ?>
				<h1 align="center">Ваш билет</h1>
			<?php //else: ?>
					<?php //$this->pageTitle=Yii::app()->name.' - Бронь' ?>
				<!--<h1 align="center">Ваша бронь</h1>-->
			<?php endif; ?>


			<?php
			$attributes = array();
			$attributes[]=array(
					'name'=>'event_id',
					'type'=>'raw',
					'value'=>CHtml::link(Events::getEventTitle($model->event_id),'/../events/view/' .$model->event_id)
					);
			if(yii::app()->user->isAdmin() || !$eventUniq->infinity_time){
				$attributes[]=array(
					'label'=>CHtml::encode('Дата мероприятия'),
					'type'=>'raw',
					'value'=>$datetime
					);
			}

			if(yii::app()->user->isAdmin() || !$eventUniq){
				$attributes[]=array(
					'name'=>'type',
					'type'=>'raw',
					'value'=>Tickets::$type_ticket[$model->type]
					);
			}

			if ($model->type=='travel')
			{
				$attributes[]=array(
						'name'=>'date_begin',
						'type'=>'raw',
						'value'=>$date_begin
						);
				$attributes[]=array(
						'name'=>'date_end',
						'type'=>'raw',
						'value'=>$date_end
						);
			}
			if ($model->type=='reusable')
			{
				$attributes['quantity']='quantity';
			}
			if (isset($model->column) && isset($model->place))
			{
				$attributes['column']='column';
				$attributes['place']='place';
			}

					//Если билет куплен
			if ($model->status==1)
			{
				$attributes['uniq']='uniq';
				$attributes[]=array(
					'name'=>'qr',
					'type'=>'image',
					'value'=>$model->qr,
					);
			}
			else
			{
				/*if ($model->type!='free')
				{
					$attributes['price']='price';
				}*/
				if ($model->type=='reusable')
				{
					$attributes['total']='total';
				}
				if ($model->user_id)
				{
					$attributes[]=array(
						'name'=>'user_id',
						'type'=>'raw',
						'value'=>Yii::app()->user->getAuthorName($model->user_id)
						);
				}
				else
				{
					if($model->mail)
						$attributes['mail']='mail';
					$attributes['family']='family';
					//$attributes['address']='address';
					$attributes['phone']='phone';
				}
				if ($model->type!='free'){
					$attributes[]=array(
						'name'=>'payment',
						'type'=>'raw',
						'value'=>TransactionLog::$payment_type[$model->payment],
						);
				}
				$attributes[]=array(
					'name'=>'status',
					'type'=>'raw',
					'value'=>TransactionLog::$status[$model->status],
					);
			}


			$this->widget('zii.widgets.CDetailView', array(
				'data'=>$model,
				'attributes'=>$attributes,
					'tagName'=>'table id="user_info_table"',
					'itemTemplate'=>"<tr><td class=\"first_column\">{label}</td><td class=\"second_column\">{value}</td></tr>\n",
					'htmlOptions'=>array('id'=>'user_info_table'),
					'itemCssClass'=>array('')
				));
			?>
			<br />
			<h2>Билеты:</h2>

			<table>
				<tr class="title_table" id="t">
					<td>Количество</td>
					<td>Цена</td>
					<td>Описание</td>
					<td>Время начала</td>
					<td>Время окончания</td>
				</tr>
				<tr>
					<td><?php echo CHtml::encode($model->quantity); ?></td>
					<?php if ($model->type!='free'):?>
					<td><?php echo CHtml::encode($model->total); ?> руб.</td>
					<?php else:?>
					<td>0 руб.</td>
					<?php endif; ?>
					<?php if ($ticket->description):?>
					<td><?php echo CHtml::encode($ticket->description); ?></td>
					<?php else: ?>
					<td></td>
					<?php endif; ?>
					<?php if ($ticket->time_begin):?>
					<td><?php echo CHtml::encode($ticket->time_begin);?></td>
					<?php else: ?>
					<td></td>
					<?php endif; ?>
					<?php if ($ticket->time_end):?>
					<td><?php echo CHtml::encode($ticket->time_end);?></td>
					<?php else:?>
					<td></td>
					<?php endif; ?>
				</tr>
			</table>


		<br /><br />

		<?php if ($model->status==2): ?>
			<p>Бронь по данному билету просрочена и удалена. Вы не можете его купить</p>
		<?php endif; ?>
		</div>
				<?php if ($model->status > 0): ?>
					<?php
							$this->widget('application.extensions.print.printWidget', array(
								'htmlOptions' => array('class' => 'print_qr_code'),
								'cssFile' => 'print.css',
								'printedElement' => '#print_area',
								'coverElement' => '#wrapper',
								'title' => 'Showcode.ru',
								'tooltip' => 'Распечатать билет',    //tooltip message of the print icon. Defaults to 'print'
								'text' => 'Распечатать билет', //text which will appear beside the print icon. Defaults to NULL
							)
					   );
					?>
				<?php endif; ?>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</div>
	
<script type="text/javascript">
	var ie=document.all;var moz=(navigator.userAgent.indexOf("Mozilla")!=-1);var opera=window.opera;var brodilka="";if(ie&&!opera){brodilka="ie"}else{if(moz){brodilka="moz"}else{if(opera){brodilka="opera"}}}var inputMasks=new Array();function kdown(a,c){var e=a.getAttribute("id");var b=e.substring(0,e.length-1);var d=Number(e.substring(e.length-1));inputMasks[b].BlKPress(d,a,c)}function kup(a,b){if(Number(a.getAttribute("size"))==a.value.length){var f=a.getAttribute("id");var d=f.substring(0,f.length-1);var e=Number((f.substring(f.length-1)))+1;var c=document.getElementById(d+e);if(b!=8&&b!=9){if(c){c.focus()}}else{if(b==8){a.value=a.value.substring(0,a.value.length-1)}}}}function Mask(d){var c="(\\d{3})\\d{3}-\\d{2}-\\d{2}";var f=[];var g=[];var a=[];var e="";var b=function(k){var j=Number(k.substring(3,k.indexOf("}")));var i=d.getAttribute("id");var m=g.length;var l="";var h=function(n){return((n>=48)&&(n<=57))||((n>=96)&&(n<=105))||(n==27)||(n==8)||(n==9)||(n==13)||(n==45)||(n==46)||(n==144)||((n>=33)&&(n<=40))||((n>=16)&&(n<=18))||((n>=112)&&(n<=123))};this.makeInput=function(){return"<input type='text' size='"+j+"' maxlength='"+j+"' id='"+i+m+"' onKeyDown='kdown(this, event)' onKeyUp='kup(this, event.keyCode)' value='"+l+"'>"};this.key=function(n,q){if(opera){return}if(!h(q.keyCode)){switch(brodilka){case"ie":q.cancelBubble=true;q.returnValue=false;break;case"moz":q.preventDefault();q.stopPropagation();break;case"opera":break;default:}return}if(q.keyCode==8&&n.value==""){var s=n.getAttribute("id");var r=s.substring(0,s.length-1);var o=Number(s.substring(s.length-1))-1;var p=document.getElementById(r+o);if(p!=null){p.focus()}}};this.getText=function(){l=document.getElementById(i+m).value;return l};this.setText=function(n){l=n};this.getSize=function(){return j}};this.drawInputs=function(){var k="<span class='Field'>";var l=0;var h=0;for(var j=0;j<a.length;j++){if(a[j]=="p"){k+=f[l];l++}else{k+=g[h].makeInput();h++}}k+="</span>";document.getElementById("div_"+d.getAttribute("id")).innerHTML=k;d.style.display="none"};this.buildFromFields=function(){var i=c;while(i.indexOf("\\")!=-1){var h=i.indexOf("\\");var k="";if(i.substring(0,h)!=""){f[f.length]=i.substring(0,h);a[a.length]="p";i=i.substring(h)}var j=i.indexOf("}");g[g.length]=new b(i.substring(0,j+1),k);i=i.substring(j+1);a[a.length]="b"}if(i!=""){f[f.length]=i;a[a.length]="p"}this.drawInputs()};this.buildFromFields();this.BlKPress=function(j,h,i){g[j].key(h,i)};this.makeHInput=function(){var h=d.getAttribute("name");document.getElementById("div_"+d.getAttribute("id")).innerHTML="<input type='text' readonly='readonly' name='"+h+"' value='"+this.getValue()+"'>"};this.getFName=function(){return d.getAttribute("name")};this.getValue=function(){e="";var k=0;var h=0;for(var j=0;j<a.length;j++){if(a[j]!="p"){e+=g[h].getText();h++}}return e};this.check=function(){for(var h in g){if(g[h].getText().length==0){return false}}return true}};

	$('a.print_qr_code').click(function(){
		//$('#list_tickets').printArea({popTitle: 'Showcode.ru'});
		//CallPrint('print_area');
	});

	
	function CallPrint(strid)
	{
		var prtContent = document.getElementById(strid);
		var prtCSS = '<link rel="stylesheet" href="<?php echo Yii::app()->baseUrl?>/css/print_tisket.css" type="text/css" />';
		var WinPrint = window.open('','','left=50,top=50,width=800,height=640,toolbar=0,scrollbars=1,status=0');
		WinPrint.document.write('<div id="print" class="contentpane">');
		WinPrint.document.write(prtCSS);
		WinPrint.document.write(prtContent.innerHTML);
		WinPrint.document.write('</div>');
		WinPrint.document.close();
		WinPrint.focus();
		WinPrint.print();
		WinPrint.close();
		prtContent.innerHTML=strOldOne;
	}
	
	$("input.datepicker").datepicker({minDate:"0"});
</script>