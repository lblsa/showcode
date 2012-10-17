<?php

class TransactionLogController extends Controller
{
	/**
	 * Инициализация.
	 * Здесь инициализируем представление для вывода обычной или мобильной версии сайта.
	 */
	public function init()
	{
		$this->layout='//layouts/' .Yii::app()->mf->siteType(). '/column3';
	}

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  	// разрешает всем пользователям выполнять действия delete.
				'actions'=>array('GetQrCodeTicket','paymentClient'),
                                'users'=>array('*','@'),
			),

            array('allow',  	// разрешает всем пользователям выполнять действия view.
				'actions'=>array('view'),
				'expression' => 'isset($_GET["preview"])',
			),

			array('allow',  	// разрешает авторизованным пользователям выполнять действия index, delete и view.
				'actions'=>array('index', 'view', 'delete','ToPay','createBill'),
				'users'=>array('@'),
			),
			array('allow',			//для создателей мероприятия разрешено заходить в админку
				'actions'=>array('admin'),
				'expression' => 'yii::app()->user->isCreator($_GET["id"])',
			),
			array('allow',			// Для Админа разрешено всё!
				'expression' => 'yii::app()->user->isAdmin()',
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Выводит все записи
	 */
	public function actionIndex()
	{
            $this->layout='//layouts/' .Yii::app()->mf->siteType(). '/column2';
            $criteria=new CDbCriteria();

            if (!yii::app()->user->isAdmin())
            {
                    //Выводим все купленные билеты пользователя, или все купленные билеты на созданное тобой мероприятие.
                    /*$event = Events::model()->findAllBySql('select id from tbl_events where author=' .yii::app()->user->id. ' group by id');
                    if (count($event)>0)
                    {
                            $events_id=' or event_id IN (';
                            foreach ($event as $v)
                                    $events_id.='"'.$v->id.'",';
                            $events_id = substr($events_id, 0, strlen($events_id)-1);
                            $events_id.=')';
                    }*/
                    $criteria->condition = 'user_id = ' .yii::app()->user->id. $events_id;
            }
            $criteria->condition = 'status = 1';
            $criteria->order = 'datetime DESC';

            if(!Yii::app()->mf->isMobile()){
                $count = TransactionLog::model()->count($criteria);
                $pages=new CPagination($count);

                // results per page
                $pages->pageSize=10;
                $pages->applyLimit($criteria);

                $dataProvider=new CActiveDataProvider('TransactionLog', array(
                    'criteria'=>$criteria,
                ));
            }
            $this->render(Yii::app()->mf->siteType(). '/index',array(
                'data'=>  TransactionLog::model()->findAll($criteria),
                'pages' => $pages,
            ));
	}

	/**
	 * Выводит Билет на дисплей.
	 * @param integer $id уникальный номер билета.
	 */
	public function actionView($id)
	{
            $this->layout='//layouts/' .Yii::app()->mf->siteType(). '/column2';
                $model = $this->loadModel($id);
                if(isset($_POST['Card'])){
                    $model->buyIsDone();
                    $model = $this->loadModel($id);
                }

		$ticket = Tickets::model()->findByPk($model->ticket_id);
		$event = Events::model()->find(array('select'=>'datetime,id,address','condition'=>'id=:event_id','params'=>array(':event_id'=>$model->event_id)));
                $eventUniq = $event->uniqium;

                if(isset($_GET['preview'])){
                    $this->layout='//layouts/' .Yii::app()->mf->siteType(). '/column0';
                    $this->render('mobile/viewMobile',array(
                            'model'=>$model,
                            'ticket'=>$ticket,
                            'event'=>$event,
                            'datetime'=>Events::normalViewDate($event->datetime),
                            'date_begin'=>Events::normalViewDate($ticket->date_begin),
                            'date_end'=>Events::normalViewDate($ticket->date_end),
                            'eventUniq'=>$eventUniq
                            ));
                }else{
                    $this->render(Yii::app()->mf->siteType(). '/view',array(
                            'model'=>$model,
                            'event'=>$event,
                            'ticket'=>$ticket,
                            'datetime'=>Events::normalViewDate($event->datetime),
                            'date_begin'=>Events::normalViewDate($ticket->date_begin),
                            'date_end'=>Events::normalViewDate($ticket->date_end),
                            'eventUniq'=>$eventUniq
                            ));
                }
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new TransactionLog;

		// Uncomment the following line if AJAX validation is needed
		//$this->performAjaxValidation($model);

		if(isset($_POST['TransactionLog']))
		{
			$model->attributes=$_POST['TransactionLog'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->uniq));
		}

		$this->render(Yii::app()->mf->siteType(). '/create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['TransactionLog']))
		{
			$model->attributes=$_POST['TransactionLog'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->uniq));
		}

		$this->render(Yii::app()->mf->siteType(). '/update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			$log = $this->loadModel($id);
			if ($log->status==0)
			{
					//Плюсуем билеты обратно
				$ticket=Tickets::model()->find('ticket_id=:ticket_id and type=:type',array(':ticket_id'=>$log->ticket_id,':type'=>$log->type));
				$quantity=$log->quantity + $ticket->quantity;
				Tickets::model()->updateAll(array('quantity' => $quantity), 'ticket_id="' .$log->ticket_id. '" and type="' .$log->type. '"');
				@unlink('.' .$log->qr);

				$log->delete();

					//отправляем запрос qiwi на удаление заказа.
				/*if($log->payment == 'qiwi'){
					include_once('./soap/IShopServerWSService.php');
					$service = new IShopServerWSService('./soap/IShopServerWS.wsdl', array('location'      => 'http://ishop.qiwi.ru/services/ishop', 'trace' => 0));
					$params = new cancelBill();
					$params->login = yii::app()->params['qiwiLogin'];
					$params->password = yii::app()->params['qiwiPass'];
					$params->txn = $log->uniq;
					$res = $service->cancelBill($params);
				}*/
			}
			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(array('/ticket'));
                        else
                            echo '/ticket';
		}
		else
			throw new CHttpException(400,'Неверный запрос. Пожалуйста, не повторяйте этот запрос еще раз.');
	}

	/**
	 * Выводит все билеты определённого мероприятия
	 * @param string $id id мероприятия.
	 */
	public function actionAdmin($id)
	{
            $this->layout='//layouts/' .Yii::app()->mf->siteType(). '/column2';
		$model=new TransactionLog('search');
		$model->unsetAttributes();  // clear any default values

		if(isset($_GET['TransactionLog']))
			$model->attributes=$_GET['TransactionLog'];

		$this->render(Yii::app()->mf->siteType(). '/admin',array(
			'model'=>$model,
			'id'=>$id,
			'title'=>Events::getEventTitle($id),
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the uniq of the model to be loaded
	 */
	public function loadModel($uniq)
	{
		$model=TransactionLog::model()->find('uniq=:uniq',array(':uniq'=>$uniq));
		if($model===null){
                        $IP = $_SERVER["REMOTE_ADDR"];
                        $visitor = Visitors::model()->find('ip=:ip',array(':ip'=>$IP));
                        if($visitor){
                            $diffMinutes = floor(abs(strtotime(date('Y-m-d H:i:s')) - strtotime($visitor['time_last_come']))/60);
                            if($diffMinutes >= 30){
                                Visitors::model()->updateByPk($visitor->id, array('count'=>1, 'time_last_come'=>Yii::app()->mf->dateForMysql(date('Y-m-d')).' '.date('H:i:s')));
                            }else{
                                if(intval($visitor['count']) > 20){
                                    $time_ban = mktime(0, 0, 0, date("m")  , date("d")+7, date("Y"));
                                    Visitors::model()->updateByPk($visitor->id, array('count'=>0,'BAN' => 1, 'time_ban'=>Yii::app()->mf->dateForMysql(date("Y-m-d",$time_ban)),'time_last_come'=>Yii::app()->mf->dateForMysql(date('Y-m-d')).' '.date('H:i:s')));
                                    Yii::app()->user->logout();
                                    $this->redirect('/user/permissionDenied');
                                    return false;
                                }else{
                                    if(intval($visitor['count']) > 5)
                                        sleep (5);
                                    $visitor->saveCounters(array('count'=>1));
                                    Visitors::model()->updateByPk($visitor->id, array('time_last_come'=>Yii::app()->mf->dateForMysql(date('Y-m-d')).' '.date('H:i:s')));
                                }
                            }
                        }else{
                            $visitor = new Visitors();
                            $params = array("ip" => $IP, "time_last_come" => Yii::app()->mf->dateForMysql(date('Y-m-d')).' '.date('H:i:s'), "time" => Yii::app()->mf->dateForMysql(date('Y-m-d')).' '.date('H:i:s'));
                            $visitor->attributes = $params;
                            $visitor->insert();
                        }

			throw new CHttpException(404,'Запрошенная страница не существует.');
                }
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='log-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}


	/**
	 *  Возвращает картинку с qr-кодом.
	 */
	public function actionGetQrCodeTicket($id)
	{
		include_once("./phpqrcode/qrlib.php");
		$errorCorrectionLevel = 'L';
		$matrixPointSize = 4;                                    
		$data = $id;
            $filename = sprintf('%x',crc32($id.time())). '.png';
            $filepath = '.' .DIRECTORY_SEPARATOR. 'images' .DIRECTORY_SEPARATOR. 'qrcodeMobile' .DIRECTORY_SEPARATOR.$filename;
            $pathQRcode = '/images/qrcodeMobile/' .$filename;
            QRcode::png($data, $filepath, $errorCorrectionLevel, $matrixPointSize, 2);

            print_r($pathQRcode);
            die();
        }

        /**
         *  Обработка ответа сервера Payment Client банка
         */
        public function actionPaymentClient(){
            // $SECURE_SECRET = "secure-hash-secret";
            $SECURE_SECRET = Yii::app()->params['bank_secure_hash_secret'];

            // get and remove the vpc_TxnResponseCode code from the response fields as we
            // do not want to include this field in the hash calculation
            $vpc_Txn_Secure_Hash = $_GET["vpc_SecureHash"];
            unset($_GET["vpc_SecureHash"]);

            // set a flag to indicate if hash has been validated
            $errorExists = false;

            if (strlen($SECURE_SECRET) > 0 && $_GET["vpc_TxnResponseCode"] != "7" && $_GET["vpc_TxnResponseCode"] != "No Value Returned") {

                $md5HashData = $SECURE_SECRET;

                // sort all the incoming vpc response fields and leave out any with no value
                foreach($_GET as $key => $value) {
                    if ($key != "vpc_SecureHash" or strlen($value) > 0) {
                        $md5HashData .= $value;
                    }
                }

                // Validate the Secure Hash (remember MD5 hashes are not case sensitive)
                    // This is just one way of displaying the result of checking the hash.
                    // In production, you would work out your own way of presenting the result.
                    // The hash check is all about detecting if the data has changed in transit.
                if (strtoupper($vpc_Txn_Secure_Hash) == strtoupper(md5($md5HashData))) {
                    // Secure Hash validation succeeded, add a data field to be displayed
                    // later.
                    $hashValidated = "<FONT color='#00AA00'><strong>CORRECT</strong></FONT>";
                } else {
                    // Secure Hash validation failed, add a data field to be displayed
                    // later.
                    $hashValidated = "<FONT color='#FF0066'><strong>INVALID HASH</strong></FONT>";
                    $errorExists = true;
                }
            } else {
                // Secure Hash was not validated, add a data field to be displayed later.
                $hashValidated = "<FONT color='orange'><strong>Not Calculated - No 'SECURE_SECRET' present.</strong></FONT>";
            }

            $responsePaymentClient = Array();

            // Standard Receipt Data
            $responsePaymentClient['amount']          = Yii::app()->mf->null2unknown($_GET["vpc_Amount"]);
            $responsePaymentClient['locale']          = Yii::app()->mf->null2unknown($_GET["vpc_Locale"]);
            $responsePaymentClient['batchNo']         = Yii::app()->mf->null2unknown($_GET["vpc_BatchNo"]);
            $responsePaymentClient['command']         = Yii::app()->mf->null2unknown($_GET["vpc_Command"]);
            $responsePaymentClient['message']         = Yii::app()->mf->null2unknown($_GET["vpc_Message"]);
            $responsePaymentClient['version']         = Yii::app()->mf->null2unknown($_GET["vpc_Version"]);
            $responsePaymentClient['cardType']        = Yii::app()->mf->null2unknown($_GET["vpc_Card"]);
            $responsePaymentClient['orderInfo']       = Yii::app()->mf->null2unknown($_GET["vpc_OrderInfo"]);
            $responsePaymentClient['receiptNo']       = Yii::app()->mf->null2unknown($_GET["vpc_ReceiptNo"]);
            $responsePaymentClient['merchantID']      = Yii::app()->mf->null2unknown($_GET["vpc_Merchant"]);
            $responsePaymentClient['authorizeID']     = Yii::app()->mf->null2unknown($_GET["vpc_AuthorizeId"]);
            $responsePaymentClient['merchTxnRef']     = Yii::app()->mf->null2unknown($_GET["vpc_MerchTxnRef"]);
            $responsePaymentClient['transactionNo']   = Yii::app()->mf->null2unknown($_GET["vpc_TransactionNo"]);
            $responsePaymentClient['acqResponseCode'] = Yii::app()->mf->null2unknown($_GET["vpc_AcqResponseCode"]);
            $responsePaymentClient['txnResponseCode'] = Yii::app()->mf->null2unknown($_GET["vpc_TxnResponseCode"]);


            // 3-D Secure Data
            $responsePaymentClient['verType']         = array_key_exists("vpc_VerType", $_GET)          ? $_GET["vpc_VerType"]          : "No Value Returned";
            $responsePaymentClient['verStatus']       = array_key_exists("vpc_VerStatus", $_GET)        ? $_GET["vpc_VerStatus"]        : "No Value Returned";
            $responsePaymentClient['token']           = array_key_exists("vpc_VerToken", $_GET)         ? $_GET["vpc_VerToken"]         : "No Value Returned";
            $responsePaymentClient['verSecurLevel']   = array_key_exists("vpc_VerSecurityLevel", $_GET) ? $_GET["vpc_VerSecurityLevel"] : "No Value Returned";
            $responsePaymentClient['enrolled']        = array_key_exists("vpc_3DSenrolled", $_GET)      ? $_GET["vpc_3DSenrolled"]      : "No Value Returned";
            $responsePaymentClient['xid']             = array_key_exists("vpc_3DSXID", $_GET)           ? $_GET["vpc_3DSXID"]           : "No Value Returned";
            $responsePaymentClient['acqECI']          = array_key_exists("vpc_3DSECI", $_GET)           ? $_GET["vpc_3DSECI"]           : "No Value Returned";
            $responsePaymentClient['authStatus']      = array_key_exists("vpc_3DSstatus", $_GET)        ? $_GET["vpc_3DSstatus"]        : "No Value Returned";

            $uniqarr = explode('/', $responsePaymentClient['orderInfo']);
            $ticket = TransactionLog::model()->find('uniq=:uniq',array(':uniq' => $uniqarr[0]));

            if ($responsePaymentClient['txnResponseCode'] == "0" && $responsePaymentClient['txnResponseCode'] != "No Value Returned" && !$errorExists) {
                if(count($ticket) > 0){
                    $ticket->buyIsDone();
                    $this->redirect('/ticket/view/'.$ticket['uniq']);
                }
            }else{
                switch ($responsePaymentClient['txnResponseCode']) {
                    case "0" : $result = "Оплата прошла успешно"; break;
                    case "?" : $result = "Статус оплаты неизвестен"; break;
                    case "1" : $result = "Неизвестная ошибка"; break;
                    case "2" : $result = "Банк отклонил операцию об оплате"; break;
                    case "3" : $result = "Нет ответа от банка"; break;
                    case "4" : $result = "Срок действия карты истек"; break;
                    case "5" : $result = "Недостаточно средств на карте"; break;
                    case "6" : $result = "Неудалось соединиться с банком"; break;
                    case "7" : $result = "Ошибка на сервере оплаты"; break;
                    case "8" : $result = "Тип транзакции не поддерживается"; break;
                    case "9" : $result = "Банк отказался проводить операцию об оплате (Обращаться в банк не нужно)"; break;
                    case "A" : $result = "Оперция об оплате прервана"; break;
                    case "C" : $result = "Операция об оплате отменена"; break;
                    case "D" : $result = "Отложенная оперция об оплате была получена и находится в ожидании обработки"; break;
                    case "F" : $result = "Ошибка аутентификации"; break;
                    case "I" : $result = "Card Security Code введен неверно"; break;
                    case "L" : $result = "В данное время произвести оплату не возможно (Пожалуйста, попробуйте еще раз позже)"; break;
                    case "N" : $result = "Владелец карты не включен в схему аутентификации"; break;
                    case "P" : $result = "Оплата была получена от Payment Adaptor и обрабатывается"; break;
                    case "R" : $result = "Операция об оплате не была обработана - Достигнут лимит разрешенных повторных попыток"; break;
                    case "S" : $result = "Дублирование SessionID (OrderInfo)"; break;
                    case "T" : $result = "Неверно введен адрес"; break;
                    case "U" : $result = "Card Security Code введен неверно"; break;
                    case "V" : $result = "Address Verification и Card Security Code неверны"; break;
                    default  : $result = "Невозможно определить";
                }
                $event_uniq = EventUniq::model()->findByPk($ticket['log_id']);
                //плюсуем не купленные билеты обратно
                TransactionLog::model()->updateByPk($ticket['log_id'], array('status'=>2));
                if(!$event_uniq['infinity_qantitty'] ){
                    $elemTicket = Tickets::model()->findByPk($ticket['ticket_id']);
                    $quantity = $elemTicket['quantity'] + $ticket['quantity'];
                    Tickets::model()->updateByPk($elemTicket['ticket_id'], array('quantity'=>$quantity));
                }

                $ticket->addError('responsePaymentClientError', $result);

                $this->render(Yii::app()->mf->siteType(). '/view',array(
                            'model'=>$ticket
                            ));
            }
        }
}