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
	public $sQuant;
	public $sPrice;
	public $b;
	public $e;

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
                            if($event->online){
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
                            if(!$event->online){
                                    $data='http://' .$_SERVER['HTTP_HOST']. '/ticket/view/' .$this->uniq;
                            }else{
                                    $data='http://' .$_SERVER['HTTP_HOST']. '/ticket/view/' .$this->uniq. '#rsa=' .$this->rsa;
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
		if(!$eventUniq->infinity_qantitty)
		{
			$count = $ticket->quantity - $this->quantity;
			Tickets::model()->updateAll(array("quantity" => "$count"),"ticket_id = '$this->ticket_id' AND type = '$this->type'");
		}
				
		//отправляем письмо на почту + смс
		if($this->type == 'free')
		{
			$title = 'Билеты:' .$event['title'];
			TransactionLog::model()->updateAll(array("status" => 1),"status = 0 AND uniq = '$this->uniq'");			
			$model = $this;
			Yii::app()->controller->buyIsDoneFree($model, $ticket, $eventUniq, $event);
		}
		else
		{
			if($this->mail && $this->payment != 'credit_card')
			{				
				$tit = Events::getEventTitle($this->event_id);
				$ev_date = Events::getEventDate($this->event_id);
				$title = 'Бронь: ' .$tit;
				Yii::app()->controller->buyIsDonePay($model, $ticket, $eventUniq, $event, $tit);
			}
		}
		//Yii::app()->mf->mail_html($this->mail,$fromMail,Yii::app()->name,$text,$title);
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
	public function search($id, $flag = false, $period = '', $date_begin = '', $date_end = '')
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
		
		if($flag)
		{
			$transaction = Yii::app()->db->beginTransaction();
			
			//удаляем все из временной таблицы
			$sql = "delete from tbl_tmp_interval";
			$command = Yii::app()->db->createCommand($sql);
			$command->execute();

			$interval = array();
			if ($period=='days')
			{
				$n = 0;
				$interval[$n]['begin'] = $date_begin;
				$interval[$n]['end'] = $date_end;
			}
			else
			{
				$dif = strtotime($date_end) - strtotime($date_begin);
				if ($period=='weeks')
					$n = substr($dif/(86400*7), 0, 1);
				if ($period=='mounths')
					$n = substr($dif/(86400*30), 0, 1);
				$date = new DateTime($date_begin);
				$n_date = $date;
				
				//полные недели/месяцы
				if($n!=0)
				{
					for ($i = 0; $i<$n; $i++)
					{
						$interval[$i]['begin'] = $n_date->format('Y-m-d');		
						if ($period=='weeks')
							$n_date = $date->add(new DateInterval('P6D'));	
						if ($period=='mounths')
							$n_date = $date->add(new DateInterval('P29D'));	
						$interval[$i]['end'] = $n_date->format('Y-m-d');	
						$n_date = $date->add(new DateInterval('P1D'));			
					}
					
					//оставшиеся дни
					if(strtotime($date_end)!=strtotime($interval[$n-1]['end'])) 
					{
						$interval[$n]['begin'] = $n_date->format('Y-m-d');
						$interval[$n]['end'] = $date_end;
					}
				}
				else
				{
					$interval[$n]['begin'] = $date_begin;
					$interval[$n]['end'] = $date_end;
				}
			}
			
			//добавляем ов временную талицу интервалы
			for ($i = 0; $i<$n+1; $i++)
			{
				$sql = "insert into tbl_tmp_interval (number, begin, end) values (".$i.", '".$interval[$i]['begin']."', '".$interval[$i]['end']."')";
				$command = Yii::app()->db->createCommand($sql);
				$command->execute();
			}
		}		

		$criteria=new CDbCriteria;

		if($flag)
		{
			if($period=='days')
				$select = '';
			else
				$select = ", tbl_tmp_interval.begin as b, tbl_tmp_interval.end as e";
			
			$criteria->select = "t.event_id, t.datetime, t.type, t.status, sum(t.quantity) as sQuant, sum(t.price) as sPrice".$select;
		}			
			
		//echo '<pre>'; print_r($id); echo '</pre>';
		$criteria->compare('event_id',$id,true);
		$criteria->compare('log_id',$this->log_id);
		$criteria->compare('uniq',$this->uniq);		
		$criteria->compare('type',$this->type,true);
		$criteria->compare('quantity',$this->quantity);
		$criteria->compare('price',$this->price);
		$criteria->compare('total',$this->total);
		//$criteria->compare('user_id',$this->user_id);
		$criteria->compare('datetime',$this->datetime);
		$criteria->compare('payment',$this->payment);
		$criteria->compare('status',$this->status);
		$criteria->compare('mail',$this->mail,true);
		$criteria->compare('family',$this->family,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('phone',$this->phone,true);

		if(!$flag)
			$criteria->order = 'datetime DESC';
		else
		{
			$criteria->join = "inner join tbl_tmp_interval on t.datetime between tbl_tmp_interval.begin and tbl_tmp_interval.end";
			if($period=='days')
				$criteria->group = "t.datetime";
			else
				$criteria->group = "tbl_tmp_interval.number";
				
			$criteria->order = 't.datetime ASC';
		}

		$pagination = array(
				'pageSize' => 10,
			);
		if($flag) $pagination = false;
		
		$return = new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' =>$pagination,
		));
		
		$transaction->commit();
		
		return $return;
	}

	public function searchTickets($user_id, $flag, $page)
	{
		$criteria=new CDbCriteria;
		
		if($flag==1 && !empty($user_id))
		{
			//Выводим все купленные билеты на созданное тобой мероприятие.
			$myEvents = Events::model()->findAllByAttributes(array('author'=>$user_id));
			
			$evid = Array();
			foreach ($myEvents as $myEvent)
			{
				$evid[] = "event_id = '".$myEvent->id."'";					
			}				
		
			$event_id = implode(' or ', $evid);
			$criteria->condition = $event_id;
		}
		else
		{
			if($flag==0 && !empty($user_id))
			{
				$criteria->compare('user_id',$user_id);
			}
		} 
		$criteria->order = 'datetime DESC';
		
		$page = (isset($page)) ? $page : 1;
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=> array(
				'currentPage' => $page,
			),
		));
	}

	/* Отправляем запрос в Bank Payment Client */
	public function virtualPaymentClient()
	{
		$Payment_Client_URL = "https://migs.mastercard.com.au/vpcpay"; // URL для доступа клиента-оплаты банка
		$urlBack = 'http://'.$_SERVER['HTTP_HOST'].'/ticket/paymentClient';
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

	public function getData($flag, $id_user)
	{
		$criteria=new CDbCriteria();  
		
		if($flag==1 && !empty($id_user))
		{
			//Выводим все купленные билеты на созданное тобой мероприятие.
			$myEvents = Events::model()->findAllByAttributes(array('author'=>$id_user));
			
			$evid = Array();
			foreach ($myEvents as $myEvent)
			{
				$evid[] = "event_id = '".$myEvent->id."'";					
			}				
		
			$event_id = implode(' or ', $evid);
			$criteria->condition = $event_id;
		}
		else
		{
			if($flag==0 && !empty($id_user))
			{
				$criteria->compare('user_id',$id_user);
			}
		} 
		$criteria->order = 'datetime DESC';
		
		if(!Yii::app()->mf->isMobile())
		{
			$count = TransactionLog::model()->count($criteria);
			$pages=new CPagination($count);

			// results per page
			$pages->pageSize=10;
			$pages->applyLimit($criteria);
		}
		
		$render_data['data'] = TransactionLog::model()->findAll($criteria);
		$render_data['pages'] = $pages;

		return $render_data;
	
	}
	
	public function searchForStat($user_id, $event_id, $lastDate, $now, $use = 0)
	{
		$criteria=new CDbCriteria(); 
		
		$criteria->select = "quantity, price";
		$criteria->compare('user_id',$user_id);			
		$criteria->compare('event_id',$event_id);	
		//считаем использованные
		if($use==1)
			$criteria->compare('status', 3);
		if($lastDate!=$now)
			$criteria->addBetweenCondition('datetime', $lastDate, $now, 'AND');	
		else
			$criteria->compare('datetime',$lastDate);	
		
		$data = $this->findAll($criteria); 
		
		$allPrice = 0;
		$quantity = 0;
		foreach ($data as $key=>$item)
		{
			$allPrice += $item->quantity*$item->price;
			$quantity += $item->quantity;
		}
		
		//echo '<pre>'; print_r($allPrice); echo '</pre>';exit;
		$mass = array();		
		$mass['allPrice'] = $allPrice;
		$mass['quantity'] = $quantity;

		return $mass;
	}
}
