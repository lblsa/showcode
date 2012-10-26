<style>
	#sendAlert
	{
		height: 40px;
		width: 180px;
		border: none;
		color: #bf5000;
		font- wight: bold;
		font-size: 142%;
		padding-left: 1px;
		/*background: #FF2600;
		background: -moz-linear-gradient(top, #FF2600, #2B0F00);
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#FF2600), color-stop(100%,#2B0F00));
		background: -webkit-linear-gradient(top, #FF2600, #2B0F00);
		background: -o-linear-gradient(top, #FF2600, #2B0F00);
		background: -ms-linear-gradient(top, #FF2600, #2B0F00);
		background: linear-gradient(top, #FF2600, #2B0F00);
		border-radius: 10px;*/
		background: url('/images/button_bg.png');
		cursor: pointer;
	}
</style>

<?php $this->pageTitle=Yii::app()->name.' - Мероприятие "'.$model->title.'"' ?>

<?php
if(count($log->errors)>0)
	echo CHtml::hiddenField('click','click');
if($model->place)
	$places = $model->place;
else
	$places = 0;
Yii::app()->clientScript->registerScriptFile('https://userapi.com/js/api/openapi.js?34', CClientScript::POS_HEAD);
$id_api_vk = intval(Yii::app()->params["vk_id"]);
/*Yii::app()->clientScript->registerScript('vkontakte_like','
	VK.init({apiId: '.$id_api_vk.', onlyWidgets: true});
	VK.Widgets.Like("vk_like", {type: "button"});
');*/
/*
Yii::app()->clientScript->registerScript('facebook_like','
	(function(d){
	  var js, id = "facebook-jssdk"; if (d.getElementById(id)) {return;}
	  js = d.createElement("script"); js.id = id; js.async = true;
	  js.src = "//connect.facebook.net/ru_RU/all.js#appId=281547825204430&xfbml=1";
	  d.getElementsByTagName("head")[0].appendChild(js);
	}(document));
');*/
?>

<?php
$this->breadcrumbs=array(
	'Мероприятия'=>array('index'),
	$model->title,
);
/*if (yii::app()->user->isAdmin() || yii::app()->user->isOrganizer())
	$this->menu[]=array('label'=>'Создать мероприятие', 'url'=>array('create'));*/
/*if (yii::app()->user->isAdmin() || yii::app()->user->id == $model->author)
    $this->menu=array(
        array('label'=>'Создать мероприятие', 'url'=>array('create')),
    );*/
if((yii::app()->user->isAdmin() || yii::app()->user->isOrganizer()) && $model->status != 'published'){
    $this->menu=array(
        array('label'=>'Опубликовать', 'url'=>array('public', 'id'=>$model->id)),
        array('label'=>'Редактировать', 'url'=>array('update', 'id'=>$model->id)),
        array('label'=>'Управление мероприятиями', 'url'=>array('admin')),
    );
}
else if(yii::app()->user->isAdmin() || yii::app()->user->isOrganizer())
{
    $this->menu[] = array('label'=>'Редактировать', 'url'=>array('update', 'id'=>$model->id));
    $this->menu[] = array('label'=>'Управление мероприятиями', 'url'=>array('admin'));
}
if (yii::app()->user->isAdmin() || yii::app()->user->isCreator($model->id))
        if($model->status != 'published')
            $this->menu[]=array('label'=>'Удалить мероприятие', 'url'=>'', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Вы действительно хотите удалить мероприятие?'));
        else
            $this->menu[]=array('label'=>'Статистика', 'url'=>'#', 'linkOptions'=>array('submit'=>array('/statistics'),'params'=>array('TransactionLog[user_id]' => $model->author,'TransactionLog[event_id]'=>$model->id,'TransactionLog[period]'=>'weeks','TransactionLog[date_begin]'=> date('d.m.Y', mktime(0, 0, 0, date("m")-2, date("d"), date("Y"))),'TransactionLog[date_end]'=>date('d.m.Y'))));
			
            //$this->menu[]=array('label'=>'Статистика', 'url'=>array('/statistics', 'TransactionLog[user_id]' => $model->author,'TransactionLog[event_id]'=>$model->id,'TransactionLog[period]'=>'weeks','TransactionLog[date_begin]'=> date('d.m.Y', mktime(0, 0, 0, date("m")-2, date("d"), date("Y"))),'TransactionLog[date_end]'=>date('d.m.Y')));

if (yii::app()->user->isCreator($model->id) && $model->status == 'published')
{
	$this->menu[]=array('label'=>'Билеты', 'url'=>array('/ticket/admin', 'id'=>$model->id));
	$this->menu[]=array('label'=>'Проверка билетов', 'url'=>array('checkTicket', 'id'=>$model->id));
    $this->menu[]=array('label'=>'Письмо с билетами', 'url'=>array('protectionEmail', 'id'=>$model->id));
}
?>

<!--<h2>Просмотр события: <?php //echo $model->title; ?></h2>-->

<?php if(!$uniqEvent): ?>
    <?php
        $attributes = array(
            //'id',
            //'title',
            array(
                'name'=>'title',
                'type'=>'raw',
                'value'=>'<h2>'.$model->title.'</h2>',
                ),
            array(
                'name'=>'description',
                'type'=>'raw',
                'value'=>'<p>'.nl2br($model->description).'</p>',
                ),
            array(
                'name'=>'address',
                'type'=>'raw',
                'value'=>'<p>'.nl2br($model->address).'</p>',
                ),
            array(
                'name'=>'author',
                'type'=>'raw',
                'value'=>'<p>'.Yii::app()->user->getAuthorName($model->author).'</p>',
                )
          );


        $attributes[] = array(
            'name'=>'datetime',
            'type'=>'raw',
            'value'=>'<p>'.$model->normalViewDate($model->datetime).'</p>',
            );

        if ($model->facebook_eid)
            $attributes[] = array(
                'name'=>'facebook_eid',
                'type'=>'url',
                );
        /*$attributes[] = array(
            'name'=>'status',
            'type'=>'raw',
            'value'=>'<p>'.Events::$STATUS[$model->status].'</p>',
            );
        /*$attributes[] = array(
         *		'name'=>'logo',
         * 		'type'=>'image',
         * 		'value'=>$model->logo,
                    );*/
        $attributes[]=array(
            'label'=>'Тип',
            'type'=>'raw',
            'value'=>'<p>'.Tickets::$type_ticket[$ticket[0]['type']].'</p>',
            );
        if ( isset($ticket[0]['date_begin']) && isset($ticket[0]['date_end']) ){
            $attributes[]=array(
                'label'=>'Дата начала действия',
                'type'=>'raw',
                'value'=>'<p>'.Events::normalViewDate($ticket[0]['date_begin']).'</p>',
                );
            $attributes[]=array(
                'label'=>'Дата окончания действия',
                'type'=>'raw',
                'value'=>'<p>'.Events::normalViewDate($ticket[0]['date_end']).'</p>',
                );

            }
    ?>

<div class="description_event_wrapper" id="qr-events">
	<div class="description_event">
		<div class="full_image"><?php echo CHtml::image($model->logo, $model->title);?></div>
			<div class="full_text_info_about_event<?php echo $uniqEvent->prefix_class; ?>">
			   <?php        
					//Выводится информация о мероприятии
					$this->widget('zii.widgets.CDetailView', array(
						'data' => $model,
						'attributes' => $attributes,
						'tagName' => 'div',
						'itemTemplate' => "<div><span>{label}:</span>{value}</div>\n",
						'itemCssClass' => array('event-title-zoo',''),
					)); ?>
<?php else: ?>
    <?php
        $attributes = array(
            //'id',
            //'title',
            array(
                'type'=>'raw',
                'value'=>'<div id="event-title'.$uniqEvent->prefix_class.'">'.'<h2>'.$model->title.$uniqEvent->prefix_title.'</h2>'.'</div>',
                ),
            array(
                'label'=>'<div><span>'.Events::model()->getAttributeLabel('address').':</span>',
                'type'=>'raw',
                'value'=>'<p>'.nl2br($model->address).'</p>'.'</div>',
                ),
          );

        if($uniqEvent->location)
            $attributes[] = array(
                'label'=>'<div><span>'.EventUniq::model()->getAttributeLabel('location').':</span>',
                'type'=>'raw',
                'value'=>'<p id="mapLocation">Расположение</p>'.'</div><div id="map'.$uniqEvent->prefix_class.'" style="visibility:hidden;"><input id="buy_close'.$uniqEvent->prefix_class.'" class="buy_close" type="button" value="" name="yt0">'.$uniqEvent->location.'</div>',
                );

        if($uniqEvent->phone)
            $attributes[] = array(
                'label'=>'<div><span>'.EventUniq::model()->getAttributeLabel('phone').':</span>',
                'type'=>'raw',
                'value'=>'<p>'.nl2br($uniqEvent->phone).'</p>'.'</div>',
                );

        if($uniqEvent->fax)
            $attributes[] = array(
                'label'=>'<div><span>'.EventUniq::model()->getAttributeLabel('fax').':</span>',
                'type'=>'raw',
                'value'=>'<p>'.nl2br($uniqEvent->fax).'</p>'.'</div>',
                );

        if($uniqEvent->email)
            $attributes[] = array(
                'label'=>'<div><span>'.EventUniq::model()->getAttributeLabel('email').':</span>',
                'type'=>'raw',
                'value'=>'<p><a href="mailto:'.nl2br($uniqEvent->email).'">'.nl2br($uniqEvent->email).'</a></p>'.'</div>',
                );

        if($uniqEvent->sait)
            $attributes[] = array(
                'label'=>'<div><span>'.EventUniq::model()->getAttributeLabel('sait').':</span>',
                'type'=>'raw',
                'value'=>'<p><a target="_blank" href="http://'.nl2br($uniqEvent->sait).'">'.nl2br($uniqEvent->sait).'</a></p>'.'</div>',
                );

        if($uniqEvent->time_work)
            $attributes[] = array(
                'label'=>'<div><span>'.EventUniq::model()->getAttributeLabel('time_work').':</span>',
                'type'=>'raw',
                'value'=>'<p class="info_about_event_full'.$uniqEvent->prefix_class.'">'.nl2br($uniqEvent->time_work).'</p>'.'</div>',
                );

        $attributes[] = array(
            'label'=>'<div><span>'.Events::model()->getAttributeLabel('description').':</span>',
            'type'=>'raw',
            'value'=>'<p class="info_about_event_full'.$uniqEvent->prefix_class.'">'.nl2br($model->description).'</p>'.'</div>',
            );

        if(Yii::app()->user->isAdmin())
            $attributes[] = array(
                'label'=>'<div><span>'.Events::model()->getAttributeLabel('author').':</span>',
                'type'=>'raw',
                'value'=>'<p>'.Yii::app()->user->getAuthorName($model->author).'</p>'.'</div>',
                );

        if(!$uniqEvent->infinity_time){
            $attributes[] = array(
                'label'=>'<div><span>'.Events::model()->getAttributeLabel('datetime').':</span>',
                'type'=>'raw',
                'value'=>'<p>'.$model->normalViewDate($model->datetime).'</p>'.'</div>',
                );
        }

        if ($model->facebook_eid)
            $attributes[] = array(
                'name'=>'facebook_eid',
                'type'=>'url',
                );

/*
        if(Yii::app()->user->isAdmin())
            $attributes[] = array(
                'label'=>'<div><span>'.Events::model()->getAttributeLabel('status').':</span>',
                'type'=>'raw',
                'value'=>'<p>'.Events::$STATUS[$model->status].'</p>'.'</div>',
                );
                */
        if(Yii::app()->user->isAdmin())
            $attributes[]=array(
                'label'=>'<div><span>'.Tickets::model()->getAttributeLabel('type').':</span>',
                'type'=>'raw',
                'value'=>'<p>'.Tickets::$type_ticket[$ticket[0]['type']].'</p>'.'</div>',
                );
        if(Yii::app()->user->isAdmin())
            if ( isset($ticket[0]['date_begin']) && isset($ticket[0]['date_end']) ){
                $attributes[]=array(
                    'label'=>'<div><span>'.Tickets::model()->getAttributeLabel('date_begin').':</span>',
                    'type'=>'raw',
                    'value'=>'<p>'.Events::normalViewDate($ticket[0]['date_begin']).'</p>'.'</div>',
                    );
                $attributes[]=array(
                    'label'=>'<div><span>'.Tickets::model()->getAttributeLabel('date_end').':</span>',
                    'type'=>'raw',
                    'value'=>'<p>'.Events::normalViewDate($ticket[0]['date_end']).'</p>'.'</div>',
                    );

                }
            echo '<div class="description_event_wrapper'.$uniqEvent->prefix_class.'" id="qr-events">';
            echo '<div class="description_event'.$uniqEvent->prefix_class.'">';
            echo '<!-- image -->';
            echo '<div class="full_image'.$uniqEvent->prefix_class.'">'.CHtml::image($model->logo, $model->title).'</div>';
            echo '<!-- full text information about event -->';
echo '<div class="full_text_info_about_event'.$uniqEvent->prefix_class.'">';
        //Выводится информация о мероприятии
        $this->widget('zii.widgets.CDetailView', array(
            'data' => $model,
            'attributes' => $attributes,
            'tagName' => 'div',
            'itemTemplate' => "{label}{value}\n",
            'itemCssClass' => array('event-title-zoo',''),
        ));
    ?>
<?php endif; ?>

<div class="clear"></div>
<!-- links -->
<div class="buy_ticket_and_back_to_events<?php echo $uniqEvent->prefix_class ?>">
    <?php if($model->status=='published'):?>
        <?php if($ticket[0]['type'] == 'free'): ?>
            <?php echo CHtml::link(CHtml::image('/images/button_visit.png'), '#',array('id'=>'button_bye')); ?>
        <?php else: ?>
            <?php if($uniqEvent->is_not_sale): ?>
                <p class="title_t_and_c">Продажа билетов еще не началась</p>
            <?php else: ?>
                <?php echo CHtml::link(CHtml::image('/images/button_buy_ticket'.$uniqEvent->prefix_class.'.png'), '#',array('id'=>'button_bye')); ?><br/>
            <?php endif; ?>
        <?php endif; ?>
        <br/>
    <?php endif; ?>

    <?php echo CHtml::link('Вернуться к мероприятиям', '/events',array('class' => 'back_to_events'.$uniqEvent->prefix_class.''.$uniqEvent->prefix_class.'')); ?>

    <?php if (Yii::app()->user->isAdmin() || Yii::app()->user->isCreator($model->id)): ?>
        <br/>
        <?php $this->widget('application.extensions.print.printWidget', array(
                        'htmlOptions' => array('class' => 'print_qr_code'),
	   		'cssFile' => 'print.css',
			'printedElement' => '.eprint',
                        'coverElement' => '#wrapper',
			'title' => 'Showcode.ru',
			'tooltip' => 'Распечатать QR-code мероприятия',    //tooltip message of the print icon. Defaults to 'print'
	    	'text' => 'Распечатать QR-code мероприятия', //text which will appear beside the print icon. Defaults to NULL
		)
	); ?>
    <?php endif; ?>
	 <?php if (Yii::app()->user->isAdmin() || Yii::app()->user->isCreator($model->id)): ?>
		<div style="margin-top: 10px;"><?php echo Chtml::Button('Рассылка оповещений', array('id'=>'sendAlert'));?></div>
	<?php endif;?>
</div>
</div>


<!--Выводится информация о билетах-->
<div style="clear: left">
	
    <?php $this->renderPartial(Yii::app()->mf->siteType(). '/_ticket', array('ticket'=>$ticket,'uniqEvent'=>$uniqEvent)); ?>
</div>
<?php
	//для админа или создателя мероприятия выводим открытые ключи rsa и код для вставки на сторонние ресурсы.
if (Yii::app()->user->isAdmin() || Yii::app()->user->isCreator($model->id))
{
    echo '<br/>';
    echo '<div class="api_key_qr_code_rsa_key">';
    echo '<fieldset>';
        echo '<div class="one_group_elements_form eprint">';
            echo CHtml::label('API ключ мероприятия: ','apikey');
            echo CHtml::textField('apikey', $model->uniq, array('size'=>34, 'readonly'=>true, 'id' => 'apikey'));
        echo '</div>';
        echo '<div class="one_group_elements_form eprint">';
            echo CHtml::label('QR-код мероприятия: ','qr');
            echo CHtml::image($model->qr, 'QR-код мероприятия');
        echo '</div>';
        echo '<div class="one_group_elements_form">';
            echo CHtml::label('Открытый ключ RSA: ','open_key');
            $str_keys = $model->open_key.':'.$model->general_key;
            echo CHtml::textField('open_key', $str_keys, array('size'=>110, 'readonly'=>true, 'id' => 'apikey'));
        echo '</div>';
        echo '<div class="one_group_elements_form">';
            echo CHtml::label('Код мероприятия для вставки на сторонние ресурсы: ','htmlcode');
            echo CHtml::textArea('htmlcode',$model->getHtmlCode(),array('rows'=>1, 'cols'=>80, 'readonly'=>true, 'id' => 'apikey'));
        echo '</div>';
     echo '</fieldset>';
     echo '</div>';
}
?>

<br />

<table id="social_networks_likes">
	<tr>
    <!--div id="fb-root"></div><div class="fb-like" data-href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/events/view/<?php echo $model->id; ?>" data-send="false" data-width="300" data-show-faces="false"></div-->
		<td> <div id="vk_like"></div> </td>
		<td>
			<iframe src="http://www.facebook.com/plugins/like.php?href=https://<?php echo $_SERVER['HTTP_HOST']; ?>/events/view/<?php echo $model->id; ?>&amp;send=false&amp;layout=standard&amp;width=350&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=35" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:350px; height:35px;" allowTransparency="true"></iframe>
		</td>

		<?php if ($model->facebook_eid): ?>
			<td>
			<?php if($facebook_event): ?>
				<a href="?facebook=declined"><img src="/images/declined.png" alt="Отказаться от посещения" title="Отказаться от посещения" /></a>
			<?php else: ?>
				<a href="?facebook=attending"><img src="/images/attending.png" alt="Пойти на это мероприятие" title="Пойти на это мероприятие" /></a>
			<?php endif;?>
			</td>
		<?php endif; ?>
	</tr>
</table>


<a name="formticketsbuy"></a>
<?php if($model->status=='published'):?>
<div class="payment" style="display:none;">
    <div class="form_main" id="form_tickets_buy">
    <?php echo CHtml::button('',array('id'=>'buy_close','class'=>'buy_close')); ?>
    <?php if ($log->errors['doAuth'][0]==1): ?>
        <h3>Вы забронировали билет на данное мероприятие.</h3>
        <p>Для просмотра билета, вы должны авторизоваться.</p>
        <p>Если вы были уже зарегистрированны в ShowCode и указали данные, соответствующее вашему аккаунта в системе, то авторизуйтесь используя ваш номер телефона и пароль.</p>
        <p>Если вы забыли пароль, вы можете его <?php echo CHtml::link('востановить', '/site/recovery') ?>.</p>
        <br/>
        <p>Если вы не были зарегистрированны в системе, то на указанный вами номер телефона и адрес электронной почты (если соответствующее поле было заполнено) будет выслан ваш пароль для входа в систему.</p>
        <p>Используя указанный номер телефона и данный пароль, вы сможете автрозоваться в системе ShowCode</p>
    <?php else: ?>
            <?php			//Начинается форма покупки
	$form=$this->beginWidget('CActiveForm', array(
			'id'=>'events-form',
			'enableAjaxValidation'=>false,
			'htmlOptions'=>array('enctype'=>'multipart/form-data'),
		));
	?>

<!--<a style="font-size: 16px; margin:0px 0px 0px 230px;" id="button_bye" href="#">Купить билеты</a>-->


            <?php echo $form->hiddenField($log,'event_id',array('value'=>$model->id)); ?>
            <?php if (!isset(Yii::app()->user->id)): ?>
                    <?php echo $form->hiddenField($log,'family',array('value'=>Yii::app()->user->name)); ?>
                    <?php echo $form->hiddenField($log,'phone',array('value'=>Yii::app()->user->phone)); ?>
            <?php endif; ?>
<!--<?php //echo $form->hiddenField($log,'price',array('value'=>$ticket->price)); ?>-->
<!--<?php //echo $form->hiddenField($log,'quantity',array('name'=>'quantity_max','id'=>'quantity_max','value'=>$ticket->quantity)); ?>-->
<!--<?php //echo $form->hiddenField($log,'column[]'); ?>-->
<!--<?php //echo $form->hiddenField($log,'place[]'); ?>-->

            <?php if (!isset(Yii::app()->user->id)): ?>
                <div>
                        <?php echo $form->labelEx($log,'family'); ?>
                        <?php echo $form->textField($log,'family',array('value'=>$log->family,'size'=>40,'maxlength'=>50)); ?>
                        <?php if ($log->errors['family'][0]==1): ?>
                                <div class="errorMessage">Некорректно заполнено</div>
                        <?php endif; ?>
                </div>

			<!--<div>
				<?php #echo $form->labelEx($log,'address'); ?>
				<?php #echo $form->textField($log,'address',array('size'=>40,'maxlength'=>50)); ?>
				<?php #if ($log->errors['address'][0]==1): ?>
					<div class="errorMessage">Некорректно заполнено</div>
				<?php #endif; ?>
			</div>-->
		<?php endif; ?>

		<?php if (!isset(Yii::app()->user->phone)): ?>
			<div>
				<?php echo $form->labelEx($log,'phone'); ?>
				+7 <?php echo $form->textField($log,'phone',array('value'=>$log->phone,'size'=>37,'maxlength'=>10)); ?>
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
				<div>
					<?php echo $form->labelEx($log,'mail'); ?>
					<?php echo $form->textField($log,'mail',array('value'=>$log->mail,'size'=>40,'maxlength'=>50)); ?>
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

		<div>
			<?php //echo $form->labelEx($log,'payment'); ?>
			<?php //echo $form->radioButton($log,'payment', array('value'=>'credit_card','id'=>'TransactionLog_payment_0')); ?>
			<?php //echo CHtml::image('/images/credit_card.jpg','credit_card'); ?>
			<?php //echo $form->labelEx($log,TransactionLog::$payment_type['credit_card'],array('for'=>'TransactionLog_payment_0')); ?>
			<?php //echo $form->radioButton($log,'payment', array('value'=>'qiwi','id'=>'TransactionLog_payment_1')); ?>
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
							<td class="place<?php echo $uniqEvent->prefix_class ?>" column="<?php echo $i;?>" place="<?php echo $j;?>"><?php //echo ($i-1)*$model->place+$j;?></td>
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


		<div>
			<?php $this->renderPartial(Yii::app()->mf->siteType(). '/_ticket', array('ticket'=>$ticket, 'buy'=>true, 'log'=>$log, 'uniqEvent'=>$uniqEvent)); ?>

			<?php if ($log->errors['ticket'][0]==1): ?>
				<p style="color:red">Не выбран билет</p>
			<?php endif; ?>
		</div>

                                      <?php if ($ticket[0]->type=='reusable'):?>
                                            <div>
                                                    <?php echo $form->labelEx($log,'quantity'); ?>
                                                    <?php echo $form->textField($log,'quantity',array('value'=>$log->quantity,'size'=>5)); ?>

                                                    <?php if ($log->errors['quantity'][0]=='big_size'): ?>
                                                            <div class="errorMessage">Вы не можете купить столько билетов</div>
                                                    <?php endif; ?>

                                                    <?php if ($log->errors['quantity'][0]=='null'): ?>
                                                            <div class="errorMessage">Поле не может быть пустым</div>
                                                    <?php endif; ?>
                                            </div>

                                            <div>
                                                    <?php echo $form->labelEx($log,'total'); ?>
                                                    <?php echo $form->textField($log,'total',array('value'=>$log->total,'name'=>'summ', 'size'=>5, 'readonly'=>1)); ?>
                                                    <?php if ($log->errors['total'][0]==1): ?>
                                                            <div class="errorMessage">Сумма не верна</div>
                                                    <?php endif; ?>
                                            </div>
                                    <?php endif; ?>


	<?php if ($ticket[0]['type']!='free'):?>
                        <div>
                            <h2>Способ оплаты</h2>
                            <table width="400px" >
                                <tr>
                                    <td><input value="credit_card" id="TransactionLog_payment_0" name="TransactionLog[payment]" type="radio" <?php if ($log->payment == 'credit_card') echo 'checked'; ?> ></td>
                                    <td><label for="TransactionLog_payment_0"><img src="/images/card.png" alt="credit_card"></label></td>
                                    <td><label for="TransactionLog_payment_0">Кредитная карта</label></td>
                                </tr>
                                <tr>
                                    <td><input value="qiwi" id="TransactionLog_payment_1" name="TransactionLog[payment]" type="radio" <?php if ($log->payment == 'qiwi') echo 'checked'; ?> ></td>
                                    <td><label for="TransactionLog_payment_1"><img src="/images/qiwi.gif" alt="qiwi"></label></td>
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
        <?php endif; ?>
        </div>
</div>

<?php endif; ?>


</div>
</div>


<script type="text/javascript">
	$('#sendAlert').click(function(){

		document.location.href = "<?php echo CHtml::normalizeUrl(array('events/sendAlert', 'id'=>$model->id))?>"
	
	});
	
	var count_place = <?php echo $places;?>;
	var places = Array();
	var placesCount = 0;
	$("#button_bye").click(function()
	{
		active = <?php echo $model->active;?>;
		if(active!=1)
		{
			alert('Вы не можете купить билет на данное мероприятие, так как оно не является активным!');
			return;
		}
		else
		{
			$("body").css("overflow","hidden")
			$("div.payment").show();
			$("#button_bye").hide();
			return false;
		}
	});
	
    $("#buy_close").click(function()
	{
		$("body").css("overflow","");
		$("div.payment").hide();
		$("#button_bye").show();
		return false;
	});
	
	$("#mapLocation").click(function()
	{
		$("body").css("overflow","hidden")
		$("div#map-zoo").css("visibility","visible");
		return false;
	});
    
	$("#buy_close-zoo").click(function()
	{
		$("body").css("overflow","");
		$("div#map-zoo").css("visibility","hidden");
		return false;
	});

	if ($("#click").val()=="click")
		$("#button_bye").click();

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
			var price = parseInt($(elem).attr("price"));
			var quantity_max = parseInt($(elem).attr("quantity"));

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
                    alert("Выберите билет");
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
</script>

