<?php

/**
 * This is the model class for table "{{transaction_log}}".
 *
 * The followings are the available columns in table '{{transaction_log}}':
 * @property integer $log_id
 * @property string $event_id
 * @property string $type
 * @property integer $quantity
 * @property integer $price
 * @property integer $total
 * @property integer $user_id
 * @property string $mail
 * @property string $family
 * @property string $address
 * @property string $phone
 * @property string $payment
 * @property string $date_begin Дата начала действия билета.
 * @property string $date_end Дата окончания действия билета.
 * @property boolean $rememberMail CheckBox. Если "true", то сохраняем E-mail в БД для будующих покупок.
 * @property mixed $payment_type Массив всевозможных типов оплаты билетов.
 * @property mixed $status Массив всевозможных видов статусов билетов.
 */
class TransactionLog extends CActiveRecord
{
	public static $payment_type = array(
		'credit_card' => 'Кредитная карта',
		'qiwi' => 'Qiwi кошелёк',
		);

	public static $status = array(
		'0' => 'Забронирован',
		'1' => 'Оплачен',
		'2' => 'Бронь просрочена',
		'3' => 'Билет использован',
		//'4' => 'Билет сегодня не действует',
		);

	public $date_begin;
	public $date_end;
	public $period;
	public $rememberMail;

	/**
	 * This method is invoked before saving a record (after validation, if any).
	 * The default implementation raises the {@link onBeforeSave} event.
	 * You may override this method to do any preparation work for record saving.
	 * Use {@link isNewRecord} to determine whether the saving is
	 * for inserting or updating record.
	 * Make sure you call the parent implementation so that the event is raised properly.
	 * @return boolean whether the saving should be executed. Defaults to true.
	 */
	protected function beforeSave()
	{
		if(parent::beforeSave())
		{
			$ticket = Tickets::model()->findByPk($this->ticket_id);
			$this->price = $ticket->price;

				//проверяем на валидность введённые данные
			if ($ticket->type=='reusable')
			{
				$this->quantity=(int)$this->quantity;
				if ($this->quantity==0)
					$this->addError('quantity','null');
				else
					$this->total = ($this->quantity)*($this->price);
			}
			else
			{
				$this->total = $this->price;
				$this->quantity = 1;
			}

			if (strlen($this->event_id)>30 or strlen($this->event_id)<1)
				$this->addError('size',1);
			if (!isset($this->ticket_id) or empty($this->ticket_id))
				$this->addError('ticket',1);
			if ($ticket->type!='free' && $this->payment != 'qiwi' && $this->payment != 'credit_card')
				$this->addError('payment',1);


			if (!Yii::app()->user->phone)
			{
				if (strlen($this->phone) < 5)
					$this->addError('phone',1);
				if ($this->rememberMail && User::model()->exists('phone = "7' .$this->phone. '"'))
					$this->addError('phone',2);
			}
			if (!Yii::app()->user->email && $this->mail){
                            if (strlen($this->mail) < 5)
                                    $this->addError('mail',1);
                            /*if (User::model()->exists('email = "' .$this->mail. '" AND phone = "7' .$this->phone. '"'))
                                    $this->addError('mail',2);*/
			}
			if (!Yii::app()->user->id && $this->family)
			{
				if (strlen($this->family) < 5)
					$this->addError('family',1);
				/*if (strlen($this->address) < 5)
					$this->addError('address',1);*/
			}

				//проверяем не купил ли пользователь билетов больше, чем возможно
			if ($ticket->quantity - $this->quantity < 0)
				$this->addError('quantity','big_size');
				//и не подменил ли он сумму
			if ($this->price != $ticket->price)
				$this->addError('total',1);

			$event = Events::model()->find('id=:event_id', array(':event_id'=>$this->event_id));
                        $eventUniq = $event->uniqium;
			if ($event->column && $event->place)
			{
				if (!$this->column || !$this->place)
					$this->addError('place',1);
				else
				{
						//Проверяем свободно ли купленное место
					$tr = TransactionLog::model()->findAll(array(
						'select'=>'`column`, `place`',
						'condition'=>'event_id=:event_id and status != 2 AND ticket_id=:ticket_id',
                                                'params'=>array(':event_id'=>$this->event_id, ':ticket_id'=>$ticket->ticket_id)
					));
					for ($i=0;$i<count($tr);$i++)
					{
						if ($tr[$i]->column==$this->column && $tr[$i]->place==$this->place)
							$this->addError('place',2);
					}
				}
			}

			//Если всё хорошо, то добавляем в БД.
			if (count($this->errors)==0)
			{
                            $this->phone = '7'.$this->phone;
                            $this->uniq = substr(sprintf('%x',crc32(rand(10000000,99999999).time())),0,8);
                            if(strlen($this->uniq) < 8){
                                    $this->uniq .= substr(sprintf('%x',crc32(rand(10000000,99999999).time())),0,8-intval(strlen($this->uniq)));
                            }

                            $this->type = $ticket->type;
                            $this->datetime = date('Y-m-d H:i:s');

                            if(!$this->status)
                                $this->status = 0;
                            if (Yii::app()->user->id){
                                    if (Yii::app()->user->email)
                                    {
                                            $this->mail = Yii::app()->user->email;
                                    }

                                    if (Yii::app()->user->phone)
                                    {
                                            $this->phone = Yii::app()->user->phone;
                                    }
                                    if(!$this->user_id)
                                        $this->user_id = Yii::app()->user->id;
                                    $this->family = Yii::app()->user->name;
                            }

                            //Создаём RSA подпись
                            $RSA = new RSA();
                            $event = Events::model()->findByPk($this->event_id, array('select' => 'id,title,datetime,close_key,general_key,online'));
                            if(!$event->online){
                                    $message='event_id=' .$event->id. '&datetime=' .$event->datetime. '&quantity=' .$this->quantity. '&uniq=' .$this->uniq;
                                    $this->rsa= $RSA->encrypt ($message, $event->close_key, $event->general_key, 90);		// и коэф. сложности кодирования(настраивается в зависимости от величины входящих простых чисел)
                            }
                            /*$strr = '';
                            foreach(str_split($this->rsa,6) as $i)
                            {
                                    $int = base_convert($i, 10, 36);
                                    if(strlen($int) < 4 && $i != substr($this->rsa, strlen($this->rsa) - strlen($i))){
                                            while(strlen($int) < 4){
                                                    $int = '0'.$int;
                                            }
                                    }
                                    $strr .= "{$int}";
                            }
                            $a = '';
                            foreach(str_split($strr,4) as $i)
                            {
                                    $int = base_convert($i, 36, 10);
                                    if(strlen($int) < 6 && $i != substr($strr, strlen($strr) - strlen($i))){
                                            while(strlen($int) < 6){
                                                    $int = '0'.$int;
                                            }
                                    }
                                    print_r($int);
                                    echo '<br/>';
                                    $a .= "{$int}";
                            }*/

                            //и qr код
                            include_once("./phpqrcode/qrlib.php");
                            $errorCorrectionLevel = 'L';
                            $matrixPointSize = 6;
                            if($event->online){
                                    $data='/ticket/view/' .$this->uniq;
                            }else{
                                    $data='/ticket/view/' .$this->uniq. '#rsa=' .$this->rsa;
                            }
                            $filename = $event->id. '_' .sprintf('%x',crc32($event->id.time())). '.png';
                            $filepath = '.' .DIRECTORY_SEPARATOR. 'images' .DIRECTORY_SEPARATOR. 'qrcode' .DIRECTORY_SEPARATOR.$filename;
                            $this->qr = '/images/qrcode/' .$filename;
                            QRcode::png($data, $filepath, $errorCorrectionLevel, $matrixPointSize, 2);
                            return true;
			}
			else
				return false;
		}
		else
			return false;
	}


	/**
	 * This method is invoked after saving a record successfully.
	 * The default implementation raises the {@link onAfterSave} event.
	 * You may override this method to do postprocessing after record saving.
	 * Make sure you call the parent implementation so that the event is raised properly.
	 * На данном этапе пользователь уже забронировал билет.
	 */
	protected function afterSave()
	{
		parent::afterSave();
		//E-mail от кого отправлено письмо
		//$fromMail = $Booking_Time = Control::model()->find("name = 'fromMail'")->value;
		$fromMail = 'noreply@'.$_SERVER[HTTP_HOST];

		//Сохраняем телефон пользователя в БД.
		if (Yii::app()->user->id && !Yii::app()->user->phone && $this->rememberMail)
		{
			if($this->mail){
				User::model()->updateByPk(Yii::app()->user->id, array('email'=>$this->mail,'phone'=>$this->phone));
			}else{
				User::model()->updateByPk(Yii::app()->user->id, array('phone'=>$this->phone));
			}
		}
                $event = Events::model()->findByPk($this->event_id);
                $eventUniq = $event->uniqium;

		//Уменьшаем ко-во оставшихся билетов
		$ticket = Tickets::model()->findByPk($this->ticket_id);
                if(!$eventUniq->infinity_qantitty){
                    $count = $ticket->quantity - $this->quantity;
                    Tickets::model()->updateAll(array("quantity" => "$count"),"ticket_id = '$this->ticket_id' AND type = '$this->type'");
                }

		if($this->type == 'free'){
			$this->buyIsDone();
		}else{

			if($this->mail && $this->payment != 'credit_card'){
                            //И отправляем письмо на почту...
                            $tit = Events::getEventTitle($this->event_id);
                            $ev_date = Events::getEventDate($this->event_id);
                            $title = 'Бронь: ' .$tit;
                            $text = '';
                            $text = $text.'<table cellspasing="0" border="0" cellpadding="0" width="100%" style="background-color:#dadada; border-collapse: collapse; border-spacing:0;">';
                            $text = $text.'<tr>';
                            $text = $text.'<td height="20"></td>';
                            $text = $text.'</tr>';
                            $text = $text.'<tr>';
                            $text = $text.'<td align="center">';
                            $text = $text.'<table cellspasing="0" border="0" cellpadding="0" height="460px" width="728px" style="margin: 0pt; padding:0; background-color: rgb(255, 255, 255); border-collapse: collapse; border-spacing:0;">';
                            $text = $text.'<tr>';
                            $text = $text.'<td style="background-image:url(/images/email/content_bg.png); background-repeat:no-repeat; background-position:left top; padding-top: 0px; padding-right:0; padding-bottom:0; padding-left: 21px;">';
                            $text = $text.'<table cellspasing="0" border="0" cellpadding="0" width="698px" style="margin: 0pt; padding: 0pt; background-color: rgb(255, 255, 255); border-collapse: collapse; border-spacing:0;">';
                            $text = $text.'<tr>';
                            $text = $text.'<td colspan="2"><img src="/images/email/logo_booking_ticket.jpg" alt="Showcode. Бронирование билета." title="Showcode. Бронирование билета." style="margin: 0pt; padding: 0pt; border: 0pt none; display: block;"></td>';
			    $text = $text.'</tr>';
			    $text = $text.'<tr>';
                            if(!$eventUniq)
                                $text = $text.'<td colspan="2"><div style="height: 40px;"><p style="font-family:Arial, Helvetica, sans-serif; font-size:24px; font-weight:normal; font-style:normal; color:#333; padding-bottom: 10px;">Здравствуйте, '.$this->family.'.</p></td>';
                            else
                                $text = $text.'<td colspan="2"><div style="height: 30px;"><p style="font-family:Arial, Helvetica, sans-serif; font-size:24px; font-weight:normal; font-style:normal; color:#333; padding-bottom: 10px;">Здравствуйте, '.$this->family.'.</p></td>';
			    $text = $text.'</tr>';
			    $text = $text.'<tr>';
                            if(!$eventUniq){
			   	$text = $text.'<td style="background-color:#e5e5e5;"><p style=" font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; color:#333;line-height: 18px;padding-left: 10px;padding-right: 10px;margin-top: 10px;margin-bottom: 10px;">Вы забронировали билет на мероприятие под названием «<a href="/events/view/' .$this->event_id. '" target="_blank" title="'.$tit.'">'.$tit.'</a>», которое состоится <b>';
			   	if($this->type == 'travel'){
					$text = $text.''. Events::getEventDate($ticket->date_begin) .' - ';
	   				$text = $text.''. Events::getEventDate($ticket->date_end) .' года ';
				}else{
					$text = $text.''. Events::getEventDate($this->event_id) .' года ';
					if($ticket->time_begin){
						$text = $text.'(начало в '. $ticket->time_begin;
					}else{
						$text = $text.'(начало в '. Events::getEventTime($this->event_id);
					}
					if($ticket->time_end){
						$text = $text.', окончание в '. $ticket->time_end;
					}
					$text = $text.'). ';
				}

				if (isset($this->column) && isset($this->place))
				{
					$text = $text.'Ваш ряд №' .$this->column. ', место ' .$this->place. '.';
				}
				$text = $text.'</b><br/><br/>Вы должны оплатить билет, выбранным вами способом, <b>в течение 4 часов. Если за это время оплата не произойдёт, то ваша бронь аннулируется.</p></td>';
                                $text = $text.'<td style="padding-right: 10px;">&nbsp;</td>';
                            }else{
                                $text = $text.'<td>';
                                $text = $text.'<table cellspasing="0" border="0" cellpadding="0" width="" style="margin: 0pt; padding: 20px 0; background-color: rgb(255, 255, 255); border-collapse: collapse;">';
                                $text = $text.'<tr>';
                                $text = $text.'<td width="120px">';
                                $text = $text.'<img src="/images/email/zoo/zoo-logo.png" alt="' .$event->title. '" title="' .$event->title. '" style="margin: 0pt; padding: 0pt; border: 0pt none; display: block; border:1px #cccccc solid;">';
                                $text = $text.'</td>';
                                $text = $text.'<td style="background-color:#30aabc; width:419px">';
                                $text = $text.'<p style="padding:0 10px 0 20px; font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:normal; font-style:normal; color:#fff; line-height:18px;"><b>Вы купили билет</b> в «' .$event->title. '»<br />';
                                $text = $text.'Часы работы: '.$eventUniq->time_work.'<br />';
                                $text = $text.'Адрес: '.$event->address.'<br />';
                                $text = $text.'Тел.: '.$eventUniq->phone.'</p>';
                                $text = $text.'</td>';
                                $text = $text.'<td style="background-color:#30aabc; vertical-align:bottom;">';
                                $text = $text.'<img style="vertical-align:bottom;" src="/images/email/zoo/bird_top_part.png" alt="" />';
                                $text = $text.'</td>';
                                $text = $text.'</tr>';
                                $text = $text.'<tr>';
                                $text = $text.'<td><img style="vertical-align:top;" src="/images/email/zoo/bird_bottom_part_empty.png" alt=""/></td>';
                                $text = $text.'<td><img style="vertical-align:top;" src="/images/email/zoo/bird_bottom_part_empty.png" alt=""/></td>';
                                $text = $text.'<td>';
                                $text = $text.'<img style="vertical-align:top;" src="/images/email/zoo/bird_bottom_part.png" alt=""/>';
                                $text = $text.'</td>';
                                $text = $text.'</tr>';
                                $text = $text.'</table>';
                                $text = $text.'</td>';
                            }
			    $text = $text.'</tr>';
			    $text = $text.'<tr>';
                            if(!$eventUniq){
                                $text = $text.'<td colspan="2" style="padding-top: 7px;   padding-bottom: 17px;padding-right: 10px;">';
                                $text = $text.'<table cellspasing="0" border="0" cellpadding="5" width="" style="margin: 0pt; padding: 20px 0; background-color: rgb(255, 255, 255); border-collapse: collapse;">';
                            }else{
                                $text = $text.'<td colspan="2" style="padding-top: 7px;   padding-bottom: 0;padding-right: 10px;">';
                                $text = $text.'<table cellspasing="0" border="0" cellpadding="5" width="" style="margin: 0pt; padding: 0; background-color: rgb(255, 255, 255); border-collapse: collapse;">';
                            }
			    $text = $text.'<tr>';
			    $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; color:#000;">Номер билета:</td>';
			    $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">' .$this->uniq. '</td>';
			    $text = $text.'</tr>';
			    $text = $text.'<tr>';
			    $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; color:#000;">Ссылка на билет:</td>';
			    $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; text-decoration:underline; color:#0000ff;"><a target="_blank" href="/ticket/view/' .$this->uniq. '" title="Здесь вы можете просмотреть статус покупки">' .$_SERVER['HTTP_HOST']. '/ticket/view/' .$this->uniq. '</a></td>';
			    $text = $text.'</tr>';
			    $text = $text.'<tr>';
			    $text = $text.'<td colspan="2" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:normal; font-style:normal; color:#999;">';
			    $text = $text.'Чтобы попасть на мероприятие вы должны:';
			    $text = $text.'<ul style="list-style:none; margin:0pt; padding-top: 3px;padding-right: 0;padding-bottom: 0;padding-left: 0;">';
				$text = $text.'<li>&mdash; внести плату за данный билет, указанным вами способом оплаты;</li>';
			    $text = $text.'</ul>';
			    $text = $text.'</td>';
			    $text = $text.'</tr>';
			    $text = $text.'</table>';
			    $text = $text.'</td>';
			    $text = $text.'</tr>';
			    $text = $text.'<tr>';
			    $text .= '<td style="padding-top: 10px; border-top-color: #999; border-top-style: solid; border-top-width: 1px;"><p style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; color:#333;line-height: 0;">С уважением, администрация сайта <a target="_blank" href="' .$_SERVER['HTTP_HOST']. '" title="">ShowCode.ru</a>.</p></td>';
                            $text = $text.'<td style="padding-right: 10px;">&nbsp;</td>';
                            $text = $text.'</tr>';
                            $text = $text.'</table>';
                            $text = $text.'</td>';
                            $text = $text.'</tr>';
                            $text = $text.'</table>';
                            $text = $text.'</td>';
                            $text = $text.'</tr>';
                            $text = $text.'<tr>';
                            $text = $text.'<td height="20"></td>';
			    $text = $text.'</tr>';
				$text = $text.'</table>';

				Yii::app()->mf->mail_html($this->mail,$fromMail,Yii::app()->name,$text,$title);
			}
		}
	}

	/**
	 * скрипт, посылающий запрос к qiwi на создание счёта.
	 * Эта функция больше не используется. запрос отправляется через i-frame.
	 */
	public function CreateBill()
	{
		include_once("soap/IShopServerWSService.php");
		$phone = $_POST['TransactionLog1']['phone'];
		$hour = Control::model()->find("name = 'Booking_Time'")->value;
		$date =  date('d.m.Y ');
		$date.=(date('H')) + $hour;
		$date.=date(':i:s');

		define('LOGIN', 16029);
		define('PASSWORD', 'Dfm3rnn7snnbbd?as');
		define('TRACE', 1);				// просмотр SOAP-запросов/ответов (для отладки)
		$service = new IShopServerWSService('soap/IShopServerWS.wsdl', array('location'      => 'http://ishop.qiwi.ru/services/ishop', 'trace' => TRACE));
		$params = new createBill();
		$params->login = LOGIN; 		// логин
		$params->password = PASSWORD; 		// пароль
		$params->user = $phone; 		// пользователь, которому выставляется счет
		$params->amount = ''.$this->total; 		// сумма
		$params->comment = Events::getEventTitle($this->event_id);		 // комментарий
		$params->txn = $this->uniq; 		// номер заказа
		$params->lifetime = $date;		 // время жизни (если пусто, используется по умолчанию 30 дней)
		// уведомлять пользователя о выставленном счете (0 - нет, 1 - послать СМС, 2 - сделать звонок)
		// уведомления платные для магазина, доступны только магазинам, зарегистрированным по схеме "Именной кошелёк"
		$params->alarm = 0;
		// выставлять счет незарегистрированному пользователю
		// false - возвращать ошибку в случае, если пользователь не зарегистрирован
		// true - выставлять счет всегда
		$params->create = true;
		$res = $service->createBill($params);
		return $res->createBillResult;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * @return Contacts the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{transaction_log}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('event_id, type, quantity, price, total, family, address, phone', 'required'),
                        //array('family, phone', 'required', 'message'=>'Не может быть пустым'),
			array('quantity, price, total, user_id, column, place, rememberMail, phone, ticket_id, status', 'numerical', 'integerOnly'=>true, 'message'=>'Вводите только цифры'),
			array('event_id', 'length', 'max'=>30),
			array('column, place', 'length', 'max'=>3),
			array('type', 'length', 'max'=>10),
			array('mail', 'length', 'max'=>50),
			array('phone', 'length', 'max'=>10),
			array('phone', 'match', 'pattern'=>'/^[\d]{10}$/', 'message'=>'Телефонный номер должен состоять из 10 цифр'),
			array('address, payment, phone, family', 'safe'),	//'безопасные' поля, которые может менять пользователь
			//array('address', 'safe'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('log_id, uniq, event_id, type, quantity, price, total, user_id, datetime, payment, status, mail, family, address, phone, column, place,ticket_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'log_id' => 'log_id',
			'uniq' => 'Цифровой код',
			'event_id' => 'Мероприятие',
			'type' => 'Тип билета',
			'quantity' => 'Кол-во',
			'price' => 'Цена',
			'total' => 'Итог',
			'user_id' => 'Покупатель',
			'datetime' => 'Дата покупки',
			'payment' => 'Способ оплаты',
			'status' => 'Статус',
			'mail' => 'E-mail',
			'family' => 'Фамилия Имя Отчество',
			'address' => 'Адрес',
			'phone' => 'Мобильный телефон (10 цифр)',
			'column' => 'Ряд',
			'place' => 'Место',
			'rsa' => 'RSA код',
			'qr' => 'QR код',
			'date_begin' => 'Дата начала действия',
			'date_end' => 'Дата окончания действия',
			'rememberMail' => 'Запомнить данные для будующих покупок',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search($id)
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		$criteria->condition = 'event_id="' .$id. '"';

		$criteria->compare('log_id',$this->log_id);
		$criteria->compare('uniq',$this->uniq);
		$criteria->compare('event_id',$this->event_id,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('quantity',$this->quantity);
		$criteria->compare('price',$this->price);
		$criteria->compare('total',$this->total);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('datetime',$this->datetime);
		$criteria->compare('payment',$this->payment);
		$criteria->compare('status',$this->status);
		$criteria->compare('mail',$this->mail,true);
		$criteria->compare('family',$this->family,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('phone',$this->phone,true);
                $criteria->order = 'datetime DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

        /**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function searchStatistics($event, $date_b, $user, $period, $ticket)
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
		$criteria=new CDbCriteria;

                $cond = '';
                if($date_b){
                    switch ($period) {
       		        case 'days':
        	           	$cond .= 'DATE(datetime) = "' .Yii::app()->mf->dateForMysql($date_b['day'].'.'.$date_b['mounth'].'.'.$date_b['year']). '" AND ';
    	                    break;
	                case 'weeks':

	                   	$cond .= 'WEEK(datetime) = "' .intval($date_b['week']). '" AND YEAR(datetime) = "' .intval($date_b['year']). '" AND ';
                        	break;
                    case 'mounths':
                    	$cond .= 'MONTH(datetime) = "' .intval($date_b['mounth']). '" AND YEAR(datetime) = "' .intval($date_b['year']). '" AND ';
                    	    break;
                	}

				}
                if($user)
                    $cond .= 'event_id IN (SELECT id FROM tbl_events WHERE author = "' .$user['user_id']. '") AND ';

                if($event)
                    $cond .= 'event_id="' .$event['id']. '" AND ';

                if($ticket)
                    $cond .= 'ticket_id="' .$ticket['ticket_id']. '" AND ';

                $cond .= '1';

		$criteria->condition = $cond;

		$criteria->group = 'status';

		$criteria->select = 'COUNT(*) AS quantity, status';

                $criteria->order = 'status DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function buyIsDone(){
		$ticket = Tickets::model()->findByPk($this->ticket_id);
		TransactionLog::model()->updateAll(array("status" => 1),"status = 0 AND uniq = '$this->uniq'");
		//$user = mysql_fetch_array(mysql_query('select phone from tbl_user where user_id="' .$log['user_id']. '"'));
		if(isset(Yii::app()->user->phone)){
			$phone = Yii::app()->user->phone;
		}else{
			$phone = $this->phone;
		}

			//Отправляем пользователю смс.
		if (isset($phone))
		{
			require_once('./soap/sms24x7.php');
			$EMAIL_SMS = 'rubtsov@complexsys.ru';
			$PASSWORD_SMS = 'MoZBdJsXG8';
			$message = 'Ваш билет находится здесь:' .PHP_EOL. '/ticket/view/' .$this->uniq. '?preview';
			$r = smsapi_push_msg_nologin($EMAIL_SMS, $PASSWORD_SMS, $phone, $message, array("unicode"=>"1"));
		}
		//Отправляем письмо с билетами на почту...
		$event = Events::model()->findByPk($this->event_id, array('select' => 'id,title,datetime,description'));
		$eventUniq = $event->uniqium;
		//$event = mysql_fetch_array(mysql_query('select id,title,description,datetime from tbl_events where id="' .$log['event_id']. '"'));
		$title = 'Билеты:' .$event['title'];
		$text = '';
		$text = $text.'<table cellspasing="0" border="0" cellpadding="0" width="100%" style="background-color:#dadada; border-collapse: collapse; border-spacing:0;">';
                $text = $text.'<tr>';
                $text = $text.'<td height="20"></td>';
                $text = $text.'</tr>';
                $text = $text.'<tr>';
                $text = $text.'<td align="center">';
                $text = $text.'<table cellspasing="0" border="0" cellpadding="0" height="460px" width="728px" style="margin: 0pt; padding:0; background-color: rgb(255, 255, 255); border-collapse: collapse; border-spacing:0;">';
                $text = $text.'<tr>';
                $text = $text.'<td style="background-image:url(/images/email/content_bg.png); background-repeat:no-repeat; background-position:left top; padding-top: 0px; padding-right:0; padding-bottom:0; padding-left: 21px;">';
                $text = $text.'<table cellspasing="0" border="0" cellpadding="0" width="698px" style="margin: 0pt; padding: 0pt; background-color: rgb(255, 255, 255); border-collapse: collapse; border-spacing:0;">';
                $text = $text.'<tr>';
                $text = $text.'<td colspan="2"><img src="/images/email/logo_thank_you.jpg" alt="Showcode. Спасибо за покупку." title="Showcode. Спасибо за покупку." style="margin: 0pt; padding: 0pt; border: 0pt none; display: block;"></td>';
                $text = $text.'</tr>';
                $text = $text.'<tr>';
                if(!$eventUniq)
                    $text = $text.'<td colspan="2"><div style="height: 40px;"><p style="font-family:Arial, Helvetica, sans-serif; font-size:24px; font-weight:normal; font-style:normal; color:#333; padding-bottom: 10px;">Здравствуйте, '.$this->family.'.</p></div></td>';
                else
                    $text = $text.'<td colspan="2"><div style="height: 30px;"><p style="font-family:Arial, Helvetica, sans-serif; font-size:24px; font-weight:normal; font-style:normal; color:#333; padding-bottom: 10px;">Здравствуйте, '.$this->family.'.</p></div></td>';
                $text = $text.'</tr>';
                $text = $text.'<tr>';
                if(!$eventUniq){
                    $text = $text.'<td style="background-color:#e5e5e5;"><p style=" font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; color:#333;line-height: 18px;padding-left: 10px;padding-right: 10px;margin-top: 10px;margin-bottom: 10px;">Вы купили билет на мероприятие под названием «<a target="_blank" title="' .$event['title']. '" href="/events/view/' .$event['id']. '">' .$event['title']. '</a>», которое состоится <b>';
                    if($this->type == 'travel'){
                            $text = $text.''. Events::getEventDate($ticket->date_begin) .' - ';
                            $text = $text.''. Events::getEventDate($ticket->date_end) .' года ';
                    }else{
                            $text = $text.''. Events::getEventDate($this->event_id) .' года ';
                    }

			if($ticket->time_begin){
					$text = $text.'(начало в '. $ticket->time_begin;
			}else{
					$text = $text.'(начало в '. Events::getEventTime($this->event_id);
			}
			if($ticket->time_end){
					$text = $text.', окончание в '. $ticket->time_end;
			}
			$text = $text.'). ';

                    if (isset($this->column) && isset($this->place))
                    {
                            $text = $text.'Ваш ряд №' .$this->column. ', место ' .$this->place. '.';
                    }
                    $text = $text.'</b></p></td>';
                    $text = $text.'<td style="padding-right: 10px;">&nbsp;</td>';
                }else{
                    $text = $text.'<td>';
                    $text = $text.'<table cellspasing="0" border="0" cellpadding="0" width="" style="margin: 0pt; padding: 20px 0; background-color: rgb(255, 255, 255); border-collapse: collapse;">';
                    $text = $text.'<tr>';
                    $text = $text.'<td width="120px">';
                    $text = $text.'<img src="/images/email/zoo/zoo-logo.png" alt="' .$event->title. '" title="' .$event->title. '" style="margin: 0pt; padding: 0pt; border: 0pt none; display: block; border:1px #cccccc solid;">';
                    $text = $text.'</td>';
                    $text = $text.'<td style="background-color:#30aabc; width:419px">';
                    $text = $text.'<p style="padding:0 10px 0 20px; font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:normal; font-style:normal; color:#fff; line-height:18px;"><b>Вы купили билет</b> в «' .$event->title. '»<br />';
                    $text = $text.'Часы работы: '.$eventUniq->time_work.'<br />';
                    $text = $text.'Адрес: '.$event->address.'<br />';
                    $text = $text.'Тел.: '.$eventUniq->phone.'</p>';
                    $text = $text.'</td>';
                    $text = $text.'<td style="background-color:#30aabc; vertical-align:bottom;">';
                    $text = $text.'<img style="vertical-align:bottom;" src="/images/email/zoo/bird_top_part.png" alt="" />';
                    $text = $text.'</td>';
                    $text = $text.'</tr>';
                    $text = $text.'<tr>';
                    $text = $text.'<td><img style="vertical-align:top;" src="/images/email/zoo/bird_bottom_part_empty.png" alt=""/></td>';
                    $text = $text.'<td><img style="vertical-align:top;" src="/images/email/zoo/bird_bottom_part_empty.png" alt=""/></td>';
                    $text = $text.'<td>';
                    $text = $text.'<img style="vertical-align:top;" src="/images/email/zoo/bird_bottom_part.png" alt=""/>';
                    $text = $text.'</td>';
                    $text = $text.'</tr>';
                    $text = $text.'</table>';
                    $text = $text.'</td>';
                }
                $text = $text.'</tr>';
                $text = $text.'<tr>';
                if(!$eventUniq){
                    $text = $text.'<td colspan="2" style="padding-top: 7px;   padding-bottom: 17px;padding-right: 10px;">';
                    $text = $text.'<table cellspasing="0" border="0" cellpadding="5" width="" style="margin: 0pt; padding: 20px 0; background-color: rgb(255, 255, 255); border-collapse: collapse;">';
                }else{
                    $text = $text.'<td colspan="2" style="padding-top: 7px;   padding-bottom: 0;padding-right: 10px;">';
                    $text = $text.'<table cellspasing="0" border="0" cellpadding="5" width="" style="margin: 0pt; padding: 0; background-color: rgb(255, 255, 255); border-collapse: collapse;">';
                }
                $text = $text.'<tr>';
                if(!$eventUniq)
                    $text = $text.'<td rowspan="3" width="120px"><a href="/ticket/view/' .$this->uniq. '" target="_blank" title=""><img src="' .$this->qr. '" alt="' .$event['title']. '" style="margin: 0pt; padding: 0pt; border: 0pt none; display: block; border:1px #cccccc solid;"></a></td>';
                else
                    $text = $text.'<td rowspan="3" width="120px"><a href="/ticket/view/' .$this->uniq. '" target="_blank" title=""><img src="' .$this->qr. '" alt="' .$event['title']. '" width="125" style="margin: 0pt; padding: 0pt; border: 0pt none; display: block; border:1px #cccccc solid;"></a></td>';
                $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; color:#000;">Номер билета:</td>';
                $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">' .$this->uniq. '</td>';
                $text = $text.'</tr>';
                $text = $text.'<tr>';
                $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; color:#000;">Ссылка на билет:</td>';
                $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; text-decoration:underline; color:#0000ff;"><a target="_blank" href="/ticket/view/' .$this->uniq. '" title="Здесь вы можете просмотреть статус покупки">' .$_SERVER['HTTP_HOST']. '/ticket/view/' .$this->uniq. '</a></td>';
                $text = $text.'</tr>';
                $text = $text.'<tr>';
                $text = $text.'<td colspan="2" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:normal; font-style:normal; color:#999;">';
                $text = $text.'Попасть на мероприятие вы сможете:';
                $text = $text.'<ul style="list-style:none; margin:0pt; padding-top: 3px;padding-right: 0;padding-bottom: 0;padding-left: 0;">';
                $text = $text.'<li>&mdash; распечастав изображение данного QR-кода на бумаге;</li>';
                $text = $text.'<li>&mdash; показав изображение на экране любого цифрового носителя.</li>';
                $text = $text.'</ul>';
                $text = $text.'</td>';
                $text = $text.'</tr>';
                $text = $text.'</table>';
                $text = $text.'</td>';
                $text = $text.'</tr>';
                $text = $text.'<tr>';
                $text .= '<td style="padding-top: 10px; border-top-color: #999; border-top-style: solid; border-top-width: 1px;"><p style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; color:#333;line-height: 0;">С уважением, администрация сайта <a target="_blank" href="' .$_SERVER['HTTP_HOST']. '" title="">ShowCode.ru</a>.</p></td>';
                $text = $text.'<td style="padding-right: 10px;">&nbsp;</td>';
                $text = $text.'</tr>';
                $text = $text.'</table>';
                $text = $text.'</td>';
                $text = $text.'</tr>';
                $text = $text.'</table>';
                $text = $text.'</td>';
                $text = $text.'</tr>';
                $text = $text.'<tr>';
                $text = $text.'<td height="20"></td>';
                $text = $text.'</tr>';
		$text = $text.'</table>';

		//E-mail от кого отправлено письмо
		//$fromMail = $Booking_Time = Control::model()->find("name = 'fromMail'")->value;
		$fromMail = 'noreply@'.$_SERVER['HTTP_HOST'];
		Yii::app()->mf->mail_html ($this->mail,$fromMail,Yii::app()->name,$text,$title);
	}

        /**
	 * Возвращает верстку текста для электронной почты при отправке письма о купленном билете.
	 */
	public function getTextEmailSendListTickets($tickets){
            $text = '';
            $text = $text.'<table cellspasing="0" border="0" cellpadding="0" width="697px" style="margin: 0pt; padding: 0pt; background-color: rgb(255, 255, 255); border-collapse: collapse;">';
            $text = $text.'<tr height="138px">';
            $text = $text.'<td><img src="/images/email/logo_empty.jpg" alt="Showcode." title="Showcode." style="margin: 0pt; padding: 0pt; border: 0pt none; display: block;"></td>';
            $text = $text.'</tr>';
            $text = $text.'<tr height="41px">';
            $text = $text.'<td><p style="font-family:Arial, Helvetica, sans-serif; font-size:24px; font-weight:normal; font-style:normal; color:#333;">Здравствуйте, '.Yii::app()->user->name.'.</p></td>';
            $text = $text.'</tr>';
            $text = $text.'<tr>';
            $text = $text.'<td style="background-color:#e5e5e5;"><p style="padding:16px 10px; font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; color:#333;">Вы запросили список всех билетов для мероприятия под названием «<a target="_blank" title="' .$this->title. '" href="/events/view/' .$this->id. '">' .$this->title. '</a>»</p></td>';
            $text = $text.'</tr>';
            $text = $text.'<tr>';
            $text = $text.'<td style="padding-top:15px; padding-bottom:15px; border-bottom-width:1px; border-bottom-color:#999999; border-bottom-style:solid;">';
            $text = $text.'<table cellspasing="0" border="0" cellpadding="5" width="" style="text-align: center; margin: 0pt; padding: 20px 0; background-color: rgb(255, 255, 255); border-collapse: collapse;">';
            $text = $text.'<tr style="border: 1px solid #000;">';
                $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">' .$tickets[0]->getAttributeLabel('user_id'). '</td>';
                $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">' .$tickets[0]->getAttributeLabel('status'). '</td>';
                $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">' .$tickets[0]->getAttributeLabel('quantity'). '</td>';
                $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">' .$tickets[0]->getAttributeLabel('total'). '</td>';
                $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">' .$tickets[0]->getAttributeLabel('column'). '</td>';
                $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">' .$tickets[0]->getAttributeLabel('place'). '</td>';
                $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">' .$tickets[0]->getAttributeLabel('uniq'). '</td>';
                $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">' .$tickets[0]->getAttributeLabel('payment'). '</td>';
            $text = $text.'</tr>';
            foreach($tickets AS $i => $ticket){
                $text = $text.'<tr style="border-bottom: 1px solid #000;">';
                    if($ticket->user_id)
                        $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">' . Yii::app()->user->getAuthorName($ticket->user_id). '</td>';
                    else
                        $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">' .$ticket->family. '</td>';
                    $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">' .TransactionLog::$status[$ticket->status]. '</td>';
                    $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">' .$ticket->quantity. '</td>';
                    $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">' .$ticket->total. '</td>';
                    $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">' .$ticket->column. '</td>';
                    $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">' .$ticket->place. '</td>';
                    $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">' .$ticket->uniq. '</td>';
                    $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">' .TransactionLog::$payment_type[$ticket->payment]. '</td>';
                $text = $text.'</tr>';
            }
            $text = $text.'</table>';
            $text = $text.'</td>';
            $text = $text.'</tr>';
            $text = $text.'<tr>';
            $text = $text.'<td style="padding-top:5px;"><p style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; color:#333;">С уважением, администрация сайта <a target="_blank" href="' .$_SERVER['HTTP_HOST']. '" title="">ShowCode.ru</a>.</p></td>';
            $text = $text.'</tr>';
            $text = $text.'</table>';

            return $text;
	}

        /* Отправляем запрос в Bank Payment Client */
        public function virtualPaymentClient(){
            $Payment_Client_URL = "https://migs.mastercard.com.au/vpcpay"; // URL для доступа клиента-оплаты банка
            $urlBack = 'https://'.$_SERVER['HTTP_HOST'].'/ticket/paymentClient';
            $vpcURL = $Payment_Client_URL . "?";

            $md5HashData = Yii::app()->params['bank_secure_hash_secret'];
            //ksort ($_POST);
            $uniqTransaktion = $this->uniq.'/'.substr(sprintf('%x',crc32(rand(10000000,99999999).time())),0,5);

            if (strlen(Yii::app()->params['bank_access_code']) > 0){
                $vpcURL .= urlencode('vpc_AccessCode') . '=' . urlencode(Yii::app()->params['bank_access_code']);
                $md5HashData .= Yii::app()->params['bank_access_code'];
            }

            if ($this->total > 0){
                $vpcURL .= '&' . urlencode('vpc_Amount') . '=' . urlencode($this->total*100);
            }
            $md5HashData .= $this->total*100;

            $vpcURL .= '&' . urlencode('vpc_Command') . '=' . urlencode('pay');
            $md5HashData .= 'pay';
            $vpcURL .= '&' . urlencode('vpc_Locale') . '=' . urlencode('RU_ru');
            $md5HashData .= 'RU_ru';

            if (strlen($uniqTransaktion) > 0){
                $vpcURL .= '&' . urlencode('vpc_MerchTxnRef') . '=' . urlencode($uniqTransaktion);
                $md5HashData .= $uniqTransaktion;
            }

            if (strlen(Yii::app()->params['bank_merchant_id']) > 0){
                $vpcURL .= '&' . urlencode('vpc_Merchant') . '=' . urlencode(Yii::app()->params['bank_merchant_id']);
                $md5HashData .= Yii::app()->params['bank_merchant_id'];
            }

            if (strlen($uniqTransaktion) > 0){
                $vpcURL .= '&' . urlencode('vpc_OrderInfo') . '=' . urlencode($uniqTransaktion);
                $md5HashData .= $uniqTransaktion;
            }

            $vpcURL .= '&' . urlencode('vpc_ReturnURL') . '=' . urlencode($urlBack);
            $md5HashData .= $urlBack;
            $vpcURL .= '&' . urlencode('vpc_Version') . '=' . urlencode('1');
            $md5HashData .= '1';

            if (strlen(Yii::app()->params['bank_secure_hash_secret']) > 0)
                $vpcURL .= '&' . urlencode('vpc_SecureHash') . '=' . strtoupper(md5($md5HashData));

            header("Location: ".$vpcURL);
        }
}
