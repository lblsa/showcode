<?php

class EventsController extends Controller
{
	public $date_begin;		//дата начала действия билета
	public $date_end;		//дата окончания действия билета

	/**
	 * Инициализация.
	 * Здесь инициализируем представление для вывода обычной или мобильной версии сайта.
	 */
	public function init()
	{
		$this->layout='//layouts/' .Yii::app()->mf->siteType(). '/column2';
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
	 * Настройка прав доступа для пользователей.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  	// разрешает всем пользователям выполнять действия index и view, WebApi и iframe
				'actions'=>array('index', 'view', 'WebApi', 'iframe','device'),
				'users'=>array('*','@'),
			),
			array('allow',			// Для Организатора разрешено: 'index', 'view', 'admin' и 'create'
				'actions'=>array('index', 'view', 'create', 'admin'),
				'expression' => 'yii::app()->user->isOrganizer()',
				//'expression' => array($this, 'isOrganizer'),
			),
			array('allow',			//для создателей мероприятия разрешено редактировать его и проверять билеты.
				'actions'=>array('admin','update','checkTicket','passedList','public','protectionEmail', 'sendAlert'),
				'expression' => 'yii::app()->user->isCreator($_GET["id"])',
				//'expression' => array($this, 'isCreator'),
			),
			array('allow',			// Для Админа разрешено всё!
				'expression' => 'yii::app()->user->isAdmin()',
				//'expression' => array($this, 'isAdmin'),
			),
			array('deny',			// Всем остальным пользователям запрещено всё.
				'users'=>array('*'),
			),
		);
	}
	/*
	function isAdmin($user, $rule)
	{
		return Yii::app()->user->isAdmin();
	}
	function isOrganizer($user, $rule)
	{
		return Yii::app()->user->isOrganizer();
	}
	function isCreator($user, $rule)
	{
		return yii::app()->user->isCreator($_GET['id']);
	}
	*/

	/**
	 * Функция подключения Jquery datepicker
	 */
	public function datepicker()
	{
		$cs=Yii::app()->clientScript;
		//$cs->registerCoreScript('jquery');
		$cs->registerCSSFile(Yii::app()->request->baseUrl.'/js/theme-redmond/jquery-ui-1.8.13.custom.css');
		$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery-ui-1.8.16.custom.min.js', CClientScript::POS_HEAD);
		$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery-ui-timepicker-addon.js', CClientScript::POS_HEAD);
		/*
		$script = '$(function(){
			$(".datetimepicker").datetimepicker({minDate:"0"});
			});';
		$cs->registerScript('datepicker_init_local', $script, CClientScript::POS_BEGIN);
		*/
	}

	/**
	 * Добавляет пользователя во встречу на Facebook.
	 * @param string $access_token сгенерированный ключ "access_token" для конкретного пользователя для доступа к API "graph.facebook.com"
	 * @param integer $facebook_eid id встречи в facebook
	 * @param string $type 'attending'=>'добавить во встречу на facebook', 'declined'=>'удалить из встречи'.
	 * @return bool if success true.
	 */
	public function addFacebookAttending($access_token,$facebook_eid,$type)
	{
		$postdata = http_build_query(array(
			'access_token' => $access_token,
			));
		$opts = array('http' =>	array(
				'method'  => 'POST',
				'header'  => 'Content-type: application/x-www-form-urlencoded',
				'content' => $postdata,
			));
		$context  = stream_context_create($opts);
		return @file_get_contents('https://graph.facebook.com/' .$facebook_eid. '/' .$type, false, $context);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->datepicker();
		$model=$this->loadModel($id);
                if($model->status != 'published' && !Yii::app()->user->isAdmin() && !Yii::app()->user->isOrganizer()){
                    if(Yii::app()->user->isGuest)
                            $this->redirect('/site/login');
                    else
                            $this->redirect('/events');
                }
		$buy_place = array();
		if(isset($_POST['TransactionLog']))
		{
			if (!isset(Yii::app()->user->id)){
                            $user = User::model()->find('`phone` = :phone', array(':phone'=>'7'.$_POST['TransactionLog']['phone']));
                            //регистрируем нового пользователя
                            if (count($user)==0){
                                $a = new User();
                                $a->type = 'self';
                                $a->email = $_POST['TransactionLog']['mail'];
                                $a->name = $_POST['TransactionLog']['family'];
                                $a->role = 'user';
                                $a->phone = $_POST['TransactionLog']['phone'];
                                $yourpass = User::generatePassword(10);
                                $a->password = md5($yourpass);
                                $a->save();
                                if($a->email){
                                    //$text = $a->getTextEmailAboutRegistration($yourpass);
                                    //Yii::app()->mf->mail_html($a->email,'noreply@'.$_SERVER[HTTP_HOST],Yii::app()->name,$text,'Регистрация в ' .Yii::app()->name. '!');
                                    $user = User::model()->find('`email`=:email and `phone`=:phone', array(':phone'=>$a->phone, ':email'=>$a->email));
                                }else{
                                    $user = User::model()->find('`phone`=:phone', array(':phone'=>$a->phone));
                                }

                                //$message = Yii::app()->name. '.Пароль:' .$yourpass;
                                //$a->sendMessenge($message, $a->phone);
                            }
			}

			if(count($a->errors)==0){
                                $_POST['TransactionLog']['user_id'] = $user->user_id;
				if ($model->column && $model->place){
                                        if(count($_POST['TransactionLog']['place']) == 0 && count($_POST['TransactionLog']['column']) == 0){
                                            $log=new TransactionLog;
                                            $log->addError('place', 1);
                                        }else{
                                            $valid = true;
                                            $n = 0;
                                            $logs = Array();
                                            foreach($_POST['TransactionLog']['place'] as $i=>$value){
                                                    $log=new TransactionLog;
                                                    $temp = $_POST['TransactionLog'];
                                                    $temp['place'] = intval($_POST['TransactionLog']['place'][$i]);
                                                    $temp['column'] = intval($_POST['TransactionLog']['column'][$i]);
                                                    $log->attributes=$temp;
                                                    $valid=$log->validate() && $valid;
                                                    $logs[$n] = $log;
                                                    $n ++;
                                            }
                                            if($valid){
                                                    for($i = 0; $i < $n; $i ++){
                                                            if ($valid && $logs[$i]->insert()){
                                                                    $valid = true;
                                                            }else{
                                                                    $valid = false;
                                                            }
                                                    }
                                                    if($valid){
                                                        if (!Yii::app()->user->isGuest)
                                                            $this->redirect(array('ticket/view/' .$log->uniq));
                                                        else{
                                                            $log->addError('doAuth', 1);
                                                        }

                                                    }
                                            }
                                        }
				}else{
					$log=new TransactionLog;
					$log->attributes = $_POST['TransactionLog'];
					if ($log->save())
					{
                                            if($log->payment == 'credit_card')
                                                $log->virtualPaymentClient ();
                                            else{
                                                if (!Yii::app()->user->isGuest)
                                                    $this->redirect(array('ticket/view/' .$log->uniq));
                                                else{
                                                    $log->addError('doAuth', 1);
                                                }
                                            }
					}
				}
			}else{
				$log=new TransactionLog;
				foreach($a->errors as $column=>$value){
					$log->addError($column, $value[0]);
				}

			}
		}else{
			$log=new TransactionLog;
		}

		if (!isset($_GET['code']) && ($_GET['facebook']=='attending' || $_GET['facebook']=='declined'))
		{
			if (!Yii::app()->user->access_token || !$this->addFacebookAttending(Yii::app()->user->access_token, $model->facebook_eid, $_GET['facebook']))
			{
				header('Location: https://www.facebook.com/dialog/oauth?client_id=' .Yii::app()->params['face_id']. '&scope=offline_access,email,rsvp_event&display=page&redirect_uri=http://' .$_SERVER['HTTP_HOST']. '/events/view/' .$model->id. '?facebook=' .$_GET['facebook']);
				die();
			}
		}

		if (isset($_GET['code']) && ($_GET['facebook']=='attending' || $_GET['facebook']=='declined'))
		{
			$token_url = 'https://graph.facebook.com/oauth/access_token?client_id=' .Yii::app()->params['face_id']. '&client_secret=' .Yii::app()->params['face_code']. '&code=' .$_GET['code']. '&redirect_uri=http://' .$_SERVER['HTTP_HOST']. '/events/view/' .$model->id. '?facebook=' .$_GET['facebook'];
			$access_token = @file_get_contents($token_url);
			if (!$access_token)	die('Facebook: ошибка обработки запроса');
			$params = null;
			parse_str($access_token, $params);
			/*if (Yii::app()->user->isGuest)
			{
				$user = json_decode(@file_get_contents('https://graph.facebook.com/me?access_token=' .$params['access_token']));
				$newUser = new LoginForm;
				$newUser->loginSocial('facebook',$user->id,$user->name,$user->email,$params['access_token']);
			}*/
			if (!Yii::app()->user->isGuest && !Yii::app()->user->access_token)
			{
				if (!Yii::app()->user->uid)
				{
					$user_info = json_decode(@file_get_contents('https://graph.facebook.com/me?access_token=' .$params['access_token'].'&fields=id'));
					User::model()->updateByPk(Yii::app()->user->id,array('access_token'=>$params['access_token'],'uid'=>$user_info->id));
				}
				else
					User::model()->updateByPk(Yii::app()->user->id,array('access_token'=>$params['access_token']));
				Yii::app()->user->refresh();
			}
			$this->addFacebookAttending($params['access_token'], $model->facebook_eid, $_GET['facebook']);
		}

		if (Yii::app()->user->access_token && $model->facebook_eid)
		{
			$fql_query_url = 'https://graph.facebook.com/'.'/fql?q=SELECT+uid+,+rsvp_status+FROM+event_member+WHERE+eid='.$model->facebook_eid.''.'&access_token='.Yii::app()->params['access_token'];
			$attending = json_decode(@file_get_contents($fql_query_url), true);
			if ($attending && $attending['data'])
			{
				foreach ($attending['data'] as $value)
					$ids[]=$value['uid'];
				if (in_array(Yii::app()->user->model->uid,$ids))
					$facebook_event=true;
			}

		}

		$tr = TransactionLog::model()->findAll(array(
			'select'=>'`column`, `place`',
			'condition'=>'event_id=:id and status != 2',
                        'params'=>array(':id'=>$id)
			));
		for ($i=0;$i<count($tr);$i++)
		{
			$buy_place[] = ($tr[$i]->column-1)*$model->place + $tr[$i]->place;
		}

		if ($model->facebook_eid){
			$model->facebook_eid = 'http://www.facebook.com/event.php?eid=' .$model->facebook_eid;
		}else{
			if (isset($_GET['code'])){
				$data = array();
	            $ticket = $this->loadTicket($model->id);
	            $typing = $ticket[0]->attributes['type'];
	            if ($typing=='travel'){
	                    $bdate = strtotime( $ticket[0]->attributes['date_begin'] );
	                    $edate = strtotime( $ticket[0]->attributes['date_end'] );
	                    $start = date('Y-m-d H:i:s', mktime(0, 0, 0, date("m",$bdate), date("d",$bdate), date("y",$bdate)));
	                    $ending = date('Y-m-d H:i:s', mktime(0, 0, 0, date("m",$edate), date("d",$edate), date("y",$edate)));
	            }{
	                    $end_time = $ticket[0]->attributes['time_end'];
	                    $ndate = strtotime( $model->datetime );
	                    $start = date('Y-m-d H:i:s', $ndate);
	                    if($end_time){
								$end_time = strtotime($end_time);
	                            $ending = date("Y-m-d H:i:s",mktime(date("H",$end_time), date("i",$end_time), 0, date("m",$ndate), date("d",$ndate), date("y",$ndate)));
	                    }else{
	                            $ending = date("Y-m-d H:i:s",$ndate + 3600 * 24);
						}
	            }

	            $data['name'] = $model->title;
	            $data['description'] = $model->description;
	            $data['location'] = 'Showcode.ru';
	            $data['street'] = $model->address;
	            $data['city'] = 'Москва';
				$data['country'] = 'Россия';
	            $data['privacy_type'] = 'OPEN'; # OPEN, CLOSED, SECRET
	            $data['start_time'] = $start; # timezone info is stripped
	            $data['end_time'] = $ending;

				$data['source'] = '@'.realpath('.'.$model->logo);
	            //$data['picture'] = '@' . realpath($_SERVER[HTTP_HOST].$model->logo);

				$token_url = 'https://graph.facebook.com/oauth/access_token?client_id=' .Yii::app()->params['face_id']. '&client_secret=' .Yii::app()->params['face_code']. '&code=' .$_GET['code']. '&redirect_uri=http://' .$_SERVER['HTTP_HOST']. '/events/view/' .$model->id;
				$access_token = @file_get_contents($token_url);
				if (!$access_token)	die('Facebook: ошибка обработки запроса');
				$params = null;
				parse_str($access_token, $params);

				$url = 'https://graph.facebook.com/me/events?access_token=' .$params['access_token'];

				$ch = curl_init();
	    		curl_setopt($ch, CURLOPT_URL, $url);
	    		curl_setopt($ch, CURLOPT_POST, true);
			    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			    $result = curl_exec($ch);
			    $decoded = json_decode($result, true);
			    curl_close($ch);

			    if(is_array($decoded) && isset($decoded['id'])) {
			    	if (!Yii::app()->user->isGuest && !Yii::app()->user->access_token)
					{
						if (!Yii::app()->user->uid)
						{
							$user_info = json_decode(@file_get_contents('https://graph.facebook.com/me?access_token=' .$params['access_token'].'&fields=id'));
							User::model()->updateByPk(Yii::app()->user->id,array('access_token'=>$params['access_token'],'uid'=>$user_info->id));
						}
						else
							User::model()->updateByPk(Yii::app()->user->id,array('access_token'=>$params['access_token']));
						Yii::app()->user->refresh();
					}

			        Events::model()->updateByPk($model->id,array('facebook_eid'=>$decoded['id']));
			        $model->facebook_eid = 'http://www.facebook.com/event.php?eid=' .$decoded['id'];
			    }
			}
		}

		$this->render(Yii::app()->mf->siteType(). '/view',array(
			'model'=>$model,
			'ticket'=>$this->loadTicket($id),
			'log'=>$log,
			'buy_place'=>$buy_place,
			'facebook_event'=>$facebook_event,
            'uniqEvent'=>$model->uniqium
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
        $this->layout='//layouts/' .Yii::app()->mf->siteType(). '/column3';
		$this->datepicker();
		$model=new Events;

		// Uncomment the following line if AJAX validation is needed
		//$this->performAjaxValidation($model);
		if(isset($_POST['Events']))
		{
			$model->attributes=$_POST['Events'];
			$model->active = $_POST['Events']['active'];
			
			$model->id = sprintf('%x',crc32($model->title.time()));
			$valid=true;
			$item = Array();
			//for ($i = 0;$i < $_POST['Tickets']['count_tickets']; $i++)
	      	$tickets1s = Array();

	        for($i = 0; $i < $_POST['Tickets']['count_tickets']; $i++)
	        {
	        	$item = Array();
	        	$tickets1=new Tickets;
	        	if($_POST['Tickets']['type'])
	        		$item['type'] = $_POST['Tickets']['type'];
	        	if($_POST['Tickets']['quantity'][$i])
	        		$item['quantity'] = intval($_POST['Tickets']['quantity'][$i]);
	        	if($_POST['Tickets']['time_begin'][$i])
	        		$item['time_begin'] = $_POST['Tickets']['time_begin'][$i];
	        	if($_POST['Tickets']['time_end'][$i])
	        		$item['time_end'] = $_POST['Tickets']['time_end'][$i];
	        	if($_POST['Tickets']['price'][$i] >= 0)
	        		$item['price'] = intval($_POST['Tickets']['price'][$i]);
	        	if($_POST['Tickets']['description'][$i])
	        		$item['description'] = $_POST['Tickets']['description'][$i];
	        	if($_POST['Tickets']['date_begin'])
	        		$item['date_begin'] = $_POST['Tickets']['date_begin'];
	        	if($_POST['Tickets']['date_end'])
	        		$item['date_end'] = $_POST['Tickets']['date_end'];
	            $tickets1->attributes = $item;
	            $tickets1->event_id = $model->id;
	            $valid=$tickets1->validate() && $valid;
	            $tickets1s[$i] = $tickets1;
	        }

			//$tickets1->attributes=$_POST['Tickets'];

			//$tickets1->event_id=$model->id;
			if ($valid){
				foreach($tickets1s as $i=>$t)
	        	{
	        		if ($t->insert()){
	        			continue;
	        		}else{
	        			$valid = false;
	        			break;
	        		}
	        	}
				if ($valid and $model->save())
				{
					$this->redirect(array('view','id'=>$model->id));
				}
			}
		}
		else
		{
			$tickets1=new Tickets;
			$model->time='12:00';
		}
		$this->render(Yii::app()->mf->siteType(). '/create',array(
			'model'=>$model,
			'tickets1'=>$tickets1,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
        $this->layout='//layouts/' .Yii::app()->mf->siteType(). '/column3';
		$this->datepicker();
		$model=$this->loadModel($id);
		$tickets=$this->loadTicket($id);
		$valid=true;

		// Uncomment the following line if AJAX validation is needed
		if(isset($_POST['Events']))
		{
			
			$model->attributes=$_POST['Events'];			
			$model->active = $_POST['Events']['active'];
			$model->delete_logo=$_POST['Events']['delete_logo'];

			$tickets1s = Array();
	        for($i = 0; $i < $_POST['Tickets']['count_tickets']; $i++)
	        {
	        	$item = Array();
	        	$tickets1=new Tickets;
	        	if($_POST['Tickets']['ticket_id'][$i])
	        		$item['ticket_id'] = $_POST['Tickets']['ticket_id'][$i];
	        	$item['type'] = $_POST['Tickets']['type'];
	        	$item['quantity'] = $_POST['Tickets']['quantity'][$i];
	        	if($_POST['Tickets']['time_begin'][$i])
	        		$item['time_begin'] = $_POST['Tickets']['time_begin'][$i];
	        	if($_POST['Tickets']['time_end'][$i])
	        		$item['time_end'] = $_POST['Tickets']['time_end'][$i];
	        	$item['price'] = $_POST['Tickets']['price'][$i];
	        	$item['description'] = $_POST['Tickets']['description'][$i];
	        	if($_POST['Tickets']['date_begin'])
	        		$item['date_begin'] = $_POST['Tickets']['date_begin'];
	        	if($_POST['Tickets']['date_end'])
	        		$item['date_end'] = $_POST['Tickets']['date_end'];
	        	$item['event_id'] = $model->id;

	        	if($_POST['Tickets']['ticket_id'][$i]){
	        		$tickets1 = Tickets::model()->findByPk($_POST['Tickets']['ticket_id'][$i]);
	        	}
	        	$tickets1->attributes = $item;
	            $valid=$tickets1->validate() && $valid;
	            $tickets1s[$i] = $tickets1;
	        }

			if ($valid){
				foreach($tickets1s as $i=>$t)
	        	{
	        		if ($t->save()){
	        			continue;
	        		}else{
	        			$valid = false;
	        			break;
	        		}
	        	}
				if ($valid and $model->save())
				{
					$this->redirect(array('view','id'=>$model->id));
				}
			}
		}
		else
		{
			$model->date=$model->NormalViewDate(substr($model->datetime,0,10));
			$model->time=substr($model->datetime,11,5);
			$tickets1=new Tickets;
		}

		$this->render(Yii::app()->mf->siteType(). '/update',array(
			'model'=>$model,
			'tickets1'=>$tickets,
			'ticket'=>$tickets1,
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
			$event = $this->loadModel($id);
			if ($event->logo!='/images/logo/default.png')
			{
				@unlink('.' .$event->logo);
				@unlink('.' .Events::changeNameImageOnMini($event->logo));
			}
			$event->delete();


			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Неверный запрос. Пожалуйста, не повторяйте этот запрос еще раз.');
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDeleteTicket($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			//$this->loadTicket($id)->delete();
			Tickets::model()->findByPk($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Неверный запрос. Пожалуйста, не повторяйте этот запрос еще раз.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$criteria=new CDbCriteria();
		switch ($_GET['view'])
		{
			case organizer:
				$criteria->addCondition('author = '.yii::app()->user->id);
				break;
			case events:
			default:
				if(!Yii::app()->user->isAdmin()){
                                        $criteria->addSearchCondition('status','published');
					//$criteria->addSearchCondition('MONTH(datetime)',$now_month);
                                        $criteria->join = "LEFT JOIN `tbl_event_uniq` ON `id` = `tbl_event_uniq`.`event_id`";
					$criteria->addCondition('(datetime >= now() AND datetime <= (now() + INTERVAL 1 MONTH)) OR tbl_event_uniq.infinity_time=1');
					//$criteria->addSearchCondition('DAY(datetime)','24',true, 'OR', '<=');
					//$criteria->addSearchCondition('DATE(datetime)',$next_date,false, 'AND', '<=');
				}
				break;
		}

                $criteria->order = 'datetime DESC';

                if(!Yii::app()->mf->isMobile()){

                    $count=  Events::model()->count($criteria);
                    $pages=new CPagination($count);

                    // results per page
                    $pages->pageSize=12;
                    $pages->applyLimit($criteria);

                }

                $dataProvider = Events::model()->findAll($criteria);
                foreach($dataProvider as $k => $event){
                    list($path, $ext) = split('\.', $event->logo);
                    $event->logo = $path.'_mini.'.$ext;
                }


                /*if(Yii::app()->user->isGuest){
                    $formLogin = new LoginForm();
                }*/

		$this->render(Yii::app()->mf->siteType(). '/index',array(
                    'data'=>  $dataProvider,
                    'pages' => $pages,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$this->datepicker();
		$model=new Events('search');

		$model->unsetAttributes();  // clear any default values

		if(isset($_GET['Events']))
			$model->attributes=$_GET['Events'];

		$this->render(Yii::app()->mf->siteType(). '/admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Events::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'Запрошенная страница не существует.');
		return $model;
	}

	/**
	 * @param string $event_id id мероприятия
	 * @return Cmodel модель билета мероприятия.
	 */
	public function loadTicket($event_id)
	{
		$model=Tickets::model()->findAll("event_id = :event_id", array(":event_id" => $event_id));
		//if($model===null)
			//throw new CHttpException(404,'Запрошенная страница не существует.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='events-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}



	/**
	 * Функция проверяет билет на корректность и осушествляет проход по нему.
	 * @param integer $uniq Уникальный номер билета.
	 * @param integer $user_id id пользователя.
	 * @return CModel модель купленного билета.
	 */
	function entryTicket($uniq,$event_id)
	{
            $model=new TransactionLog;
            $model->uniq = $uniq;

            $tr = TransactionLog::model()->find(array('alias'=>'log','select'=>'log.event_id,log.type,log.quantity,log.status,ts.date_begin,ts.date_end,log.datetime,log.phone,log.column,log.place', 'condition'=>'log.uniq=:uniq and log.event_id=:event_id', 'join'=>'left join tbl_tickets ts on ts.event_id = log.event_id AND ts.ticket_id = log.ticket_id left join tbl_events event on event.id = log.event_id','params'=>array(':event_id'=>$event_id,':uniq'=>$uniq)));
            if ($tr){
                $model->type=$tr->type;
                $model->status=$tr->status;
                $model->quantity=$tr->quantity;
                $model->phone=$tr->phone;
                $model->datetime=$tr->datetime;
                $model->column=$tr->column;
                $model->place=$tr->place;
                $model->event_id = $event_id;
                $pass = new Passed;
                $pass->event_id = $event_id;
                $pass->log_id = $uniq;

                //Осушествляем проход по билету.
                //markticket - флаг нужно ли помечать билет как пройденный
                if ($model->status==1 && count($model->errors)==0 && isset($_GET['markticket'])){
                    if ($model->type=='travel'){
                        $this->date_begin = Events::normalViewDate($tr->date_begin);
                        $this->date_end = Events::normalViewDate($tr->date_end);

                        if ($tr->date_begin > date('Y-m-d') || $tr->date_end < date('Y-m-d'))
                            $model->status=4;
                        else
                            $pass->save();
                        }

                        if ($model->type=='disposable' || $model->type=='free'){
                            TransactionLog::model()->updateAll(array('status' => 3), 'uniq="' .$model->uniq. '"');
                            $pass->save();
                            }
                        if ($model->type=='reusable'){
                            $model->quantity--;
                            if ($model->quantity==0) $model->status=3;
                            TransactionLog::model()->updateAll(array('quantity' => $model->quantity, 'status' => $model->status), 'uniq="' .$model->uniq. '"');
                            if($model->status == 3)
                                $pass->save();
                            $model->status=1;
                        }
                    }
		}
		else
		{
			$model->addError('uniq','Код не корректен');
		}
		return $model;
	}

	/**
	 * Проверка билетов на валидность. WebApi
	 * @return json результат проверки билета.
	 */
	public function actionWebApi()
	{
		header ('Content-type: text/html; charset=utf-8');
		$answer = array();
		$uniq = trim($_GET['ticket']);
		//$event = trim($_GET['event']);
                if (($uniq && !preg_match("/^[0-9a-z]{5,25}$/i", $uniq)) || !preg_match("/^[0-9a-z]{3,60}$/i", trim($_GET['event']))){
                    $answer['error'] = 'Data is not correct';
                }else{
                    $event = Events::model()->find(array('condition'=>'uniq=:uniq','params'=>array(':uniq'=>trim($_GET['event']))));
                }
		$name = trim($_GET['name']);
                $counts = trim($_GET['count']);

		if($name){
			if (!$answer['error']){
				$event_name = $event->title;
				if(!$event_name){
					$answer['error'] = 'Data is not correct';
					echo utf8_encode($answer['error']);
					return utf8_encode($answer['error']);
				}
				echo $event_name;
				return $event_name;
			}else{
				//$answer['error'] = 'Data is not correct';
				echo utf8_encode($answer['error']);
				return utf8_encode($answer['error']);
			}

		}

                if($counts){
                    $tickets = Passed::model()->findAll(array('condition'=>'event_id=:event_id','params'=>array(':event_id'=>$event->id)));
                    echo utf8_encode(count($tickets));
                    return utf8_encode(count($tickets));
                }

		if (!$answer['error'])
		{
			$event_id = $event->id;
                        /*if (Events::getEventDate($event->id) != date('d.m.Y')){
                            $model = TransactionLog::model()->find(array('alias'=>'log','select'=>'log.event_id,log.type,log.quantity,log.status,ts.date_begin,ts.date_end,log.datetime,log.phone,log.column,log.place', 'condition'=>'log.uniq="' .$uniq. '" and log.event_id="' .$event_id. '"', 'join'=>'left join tbl_tickets ts on ts.event_id = log.event_id AND ts.ticket_id = log.ticket_id left join tbl_events event on event.id = log.event_id'));
                            $model->status=4;
                        }else{*/
                            $model = $this->entryTicket($uniq, $event_id);
                        //}
			if (count($model->errors)>0)
			{
				if ($model->errors['uniq'][0])
					$answer['error'] = 'Code is not correct';
			}
			else{
                            $answer['phone'] = $model->phone;
                            $answer['datetime'] = Events::normalViewDate($model->datetime);
                            $answer['status'] = $model->status;
                            $answer['type'] = $model->type;
                            $answer['column'] = $model->column;
                            $answer['place'] = $model->place;

                            if ($model->type=='travel'){
                                $answer['date_begin'] = $this->date_begin;
                                $answer['date_end'] = $this->date_end;
                            }
                            if ($model->type=='reusable'){
                                $answer['quantity'] = $model->quantity;
                            }
			}
		}
		//else 			$answer['error'] = 'Data is not correct';

            if($answer['error']){
                echo utf8_encode($answer['error']);
                 return utf8_encode($answer['error']);
            }else{
                $mess = utf8_encode($answer['status']).';'.utf8_encode($answer['phone']).';'.utf8_encode($answer['datetime']);
                if ($answer['column'] && $answer['place']){
                    $mess = $mess.';'.utf8_encode($answer['column']).';'.utf8_encode($answer['place']);
                }
                echo $mess;
                return $mess;
            }
	}

	/**
	 * Проверка билетов на валидность
	 * @param string $id id мероприятия
	 */
	public function actionCheckTicket($id)
    {
        $event = Events::model()->findByPk($id,array('select'=>'id, title, DATE(datetime) as datetime'));
        $eventUniq = $event->uniqium;
        $model=new TransactionLog;

        $ticket = $this->loadTicket($id);
        if(isset($_POST['TransactionLog'])){
            $type = $ticket[0]->type;
            $date_begin = $ticket[0]->date_begin;
            $date_end = $ticket[0]->date_end;
            /*
            if ((($type == 'travel' && ($date_begin > date('Y-m-d') && $date_end < date('Y-m-d'))) || ($type != 'travel' && $event->datetime != date('Y-m-d'))) && !$eventUniq->infinity_time)
                $model=null;
            else{
            */
                $model = $this->entryTicket(trim($_POST['TransactionLog']['uniq']),$id);
//            }
        }
        if(!isset($_GET['ajax'])){
            $this->render(Yii::app()->mf->siteType(). '/checkTicket',array(
                        'event_id'=>$event->id,
                        'title'=>$event->title,
                        'model'=>$model,
                        'date_begin'=>$ticket[0]->date_begin,
                        'date_end'=>$ticket[0]->date_end,
                        ));
        }else{
            $isAdmin = Yii::app()->user->isAdmin();
            $isOrg = Yii::app()->user->isOrganizer();

            if ($isAdmin===null || $isOrg===null){
                echo 'Вы не можете проверять билеты. Данное мероприятие сегодня не проходит';
            }else{
                if($model->status==1){
                    $text = 'Вы успешно активировали билет.';
                    if ($model->type=='reusable') $text .= 'проходов осталось: ' .$model->quantity;
                    if ($model->type=='travel') $text .= 'Период действия: с '.Events::model()->normalViewDate($date_begin).' по '.Events::model()->normalViewDate($date_end);
                    echo $text;
                }elseif(isset($model->status)){
                    $text = 'Билет не действителен. статус: ';
                    if ($model->status==4) $text .= 'Билет сегодня не действует';
                    else $text .= TransactionLog::$status[$model->status];
                    echo $text;
                }

            }
        }
    }

        /**
	 * посмотреть список прошедших билетов и список оставшихся к проходу
	 * @param string $id id мероприятия
	 */
	public function actionPassedList($id)
	{
            $tickets_passed = Passed::model()->findAll(array('condition'=>'event_id=:event_id','params'=>array(':event_id'=>$id)));
            $tickets_remained = TransactionLog::model()->findAll(array('condition'=>'event_id=:event_id AND status=1','params'=>array(':event_id'=>$id)));

            $event = Events::model()->findByPk($id);
            $eventUniq = $event->uniqium;
            $model=new TransactionLog;
            if(isset($_POST['TransactionLog'])){
                $model = $this->entryTicket(trim($_POST['TransactionLog']['uniq']),$id);
            }

		if ($event->datetime != date('Y-m-d') && !$eventUniq->infinity_time)
			$model=null;
		$this->render(Yii::app()->mf->siteType(). '/checkTicket',array(
			'title'=>$event->title,
			'model'=>$model,
			'date_begin'=>$this->date_begin,
			'date_end'=>$this->date_end,
			));
	}

	/**
	 * Генерирует Iframe мероприятия для вставки на другой сайт
	 * @param string $id id мероприятия
	 */
	public function actionIframe($id)
	{
            header ('Content-type: text/html; charset=utf-8');
		//$log=new TransactionLog;
            $model = $this->loadModel($id);
            $ticket = $this->loadTicket($id);

                $buy_place = array();
                $tr = TransactionLog::model()->findAll(array(
			'select'=>'`column`, `place`',
			'condition'=>'event_id=:event_id and status != 2',
                        'params'=>array(':event_id'=>$id)
			));
		for ($i=0;$i<count($tr);$i++)
			$buy_place[] = ($tr[$i]->column-1)*$model->place + $tr[$i]->place;

		$flag=false;
		if(isset($_POST['TransactionLog']))
		{
                    if($_POST['TransactionLog']['phone']){
                        $user = User::model()->find('phone = :phone',array(':phone'=>'7'.$_POST['TransactionLog']['phone']));
                        //регистрируем нового пользователя
                        if (count($user)==0){
                            $a = new User();
                            $a->type = 'self';
                            $a->email = $_POST['TransactionLog']['mail'];
                            $a->name = $_POST['TransactionLog']['family'];
                            $a->role = 'user';
                            $a->phone = $_POST['TransactionLog']['phone'];
                            $yourpass = User::generatePassword(10);
                            $a->password = md5($yourpass);
                            if($a->save()){
                                if($a->email){
                                    $text = $a->getTextEmailAboutRegistration($yourpass);
                                    Yii::app()->mf->mail_html($a->email,'noreply@'.$_SERVER[HTTP_HOST],Yii::app()->name,$text,'Регистрация в ' .Yii::app()->name. '!');
                                }
                                $user = User::model()->find('phone = :phone',array(':phone'=>$a->phone));

                                $message = Yii::app()->name. '.Пароль:' .$yourpass;
                                $a->sendMessenge($message, $a->phone);
                                $_POST['TransactionLog']['user_id'] = $user->user_id;
                            }
                        }
                        if(count($a->errors)==0){
                            if ($model->column && $model->place){
                                    if(count($_POST['TransactionLog']['place']) == 0 && count($_POST['TransactionLog']['column']) == 0){
                                        $log=new TransactionLog;
                                        $log->addError('place', 1);
                                    }else{
                                        $valid = true;
                                        $n = 0;
                                        $logs = Array();
                                        foreach($_POST['TransactionLog']['place'] as $i=>$value){
                                                $log=new TransactionLog;
                                                $temp = $_POST['TransactionLog'];
                                                $temp['place'] = intval($_POST['TransactionLog']['place'][$i]);
                                                $temp['column'] = intval($_POST['TransactionLog']['column'][$i]);
                                                $log->attributes=$temp;
                                                $valid=$log->validate() && $valid;
                                                $logs[$n] = $log;
                                                $n ++;
                                        }
                                        if($valid){
                                                for($i = 0; $i < $n; $i ++){
                                                        if ($valid && $logs[$i]->insert()){
                                                                $valid = true;
                                                        }else{
                                                                $valid = false;
                                                        }
                                                }
                                                if($valid){
                                                    $flag=true;
                                                    $this->renderPartial('iframe',array(
                                                            'log'=>$log,
                                                            'saveDB'=>'true',
                                                    ));
                                                }
                                        }
                                    }
                            }else{
                                    $log=new TransactionLog;
                                    $log->attributes = $_POST['TransactionLog'];
                                    if ($log->insert())
                                    {
                                        $flag=true;
                                        $this->renderPartial('iframe',array(
                                                'log'=>$log,
                                                'saveDB'=>'true',
                                        ));
                                    }
                            }
                        }else{
				$log=new TransactionLog;
				foreach($a->errors as $column=>$value){
                                    if($column == 'name')
                                        $column = 'family';
                                    $log->addError($column, $value[0]);
                                }

			}
                    }else{
                        $log=new TransactionLog;
                        $log->addError('phone', 1);
                    }

		}else{
                    $log=new TransactionLog;
                }
		if(!$flag)
			$this->renderPartial('iframe',array(
				'model'=>$model,
				'ticket'=>$ticket,
				'log'=>$log,
                                'buy_place'=>$buy_place,
			));
	}

        /**
	 * Ставит мероприятию опубликован
	 * @param string $id id мероприятия
	 */
	public function actionPublic($id)
        {
            Events::model()->updateByPk($id, array('status'=>'published'));

            $this->render(Yii::app()->mf->siteType(). '/public',array('id'=>$id));
        }

        /**
	 * Высылает письмо организатору с билетами
	 * @param string $id id мероприятия
	 */
    public function actionProtectionEmail($id)
    {
        if(isset($_POST['email'])){
            if($_POST['email'] || strlen($_POST['email']) > 5){
                $event = $this->loadModel($id);
                $tickets = TransactionLog::model()->findAll(array('condition'=>'event_id=:event_id','params'=>array(':event_id'=>$id)));
                $text = $event->getTextEmailSendListTickets($tickets);
                Yii::app()->mf->mail_html($_POST['email'],'noreply@'.$_SERVER[HTTP_HOST],Yii::app()->name,$text,'Список билетов на мероприятие «' .$event->title. '»');

                $tr = TransactionLog::model()->findAll(array(
                            'select'=>'`column`, `place`',
                            'condition'=>'event_id=:event_id and status != 2',
                            'params'=>array(':event_id'=>$id)
                            ));
                for ($i=0;$i<count($tr);$i++)
                {
                    $buy_place[] = ($tr[$i]->column-1)*$model->place + $tr[$i]->place;
                }

                $this->render(Yii::app()->mf->siteType(). '/view',array(
                            'model'=>$event,
                            'ticket'=>$this->loadTicket($id),
                            'log'=>new TransactionLog,
                            'buy_place'=>$buy_place,
                            ));
            }else
                $errors = 1;
        }
        $event = $this->loadModel($id);
        $organizator = Yii::app()->user;
        $this->render(Yii::app()->mf->siteType(). '/protectionEmail',
                array(
                    'id'=>$id,
                    'event' => $event,
                    'user' => $organizator,
                    'error' => $errors
                    ));

    }
	
	//рассылка оповещений
	public function actionSendAlert($id)
	{
		$this->layout='//layouts/' .Yii::app()->mf->siteType(). '/column3';
		
		$transactions = TransactionLog::model()->findAllByAttributes(array('event_id'=>$id));	
		
		$i = 0;
		$data = array();
		
		foreach ($transactions as $transaction)
		{
			$data[$i]['phone'] = $transaction->phone;
			$data[$i]['mail'] = $transaction->mail;			
			$data[$i]['family'] = $transaction->family;			
			$data[$i]['user_id'] = $transaction->user_id;			
			$i++;
		}

		//чтобы 2 раза не высылать
		$data=array_unique($data);

		if (!empty($_POST))
		{
			$title = $_POST['title'];
			
			if(empty($title))
				$title = "Информация о мероприятии 'название'";
			$message = $_POST['text'];
			
			if (isset($_POST['mobile']))
				$mobile = 1;
			else
				$mobile = 0;
				
			if (isset($_POST['mail']))
				$mail = 1;
			else
				$mail = 0;
			
			$users = $_POST['user'];
			
			foreach ($users as $user_id)
			{
				$transactions = TransactionLog::model()->findByAttributes(array('event_id'=>$id, 'user_id'=>$user_id));			

				//$data = array();
				
				//foreach ($transactions as $transaction)
				{
					if ($mobile==1)
						$phone = $transactions->phone;
					if ($mail==1)
						$email = $transactions->mail;				
				}

				//foreach ($data as $item)
				{
					//шлем спам :)
					if ($mobile==1)
						User::model()->sendMessenge($message, $phone, 0);
					if ($mail==1)
					{
						mail($email, $title, $message);
					}
				}
			}
		}
		
		$this->render(Yii::app()->mf->siteType(). '/sendAlert', array('data'=>$data));
	}
}
