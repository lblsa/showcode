<?php

class ScannersController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
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
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
                        array('allow',  	// разрешает всем пользователям выполнять действия index и view, WebApi и iframe
				'actions'=>array('device','addTicket'),
				'users'=>array('*','@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('index','view','create','update','delete'),
				'expression' => 'yii::app()->user->isAdmin()',
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
            $model = $this->loadModel($id);
            $this->validateAccessDevice($model);
            $this->render(Yii::app()->mf->siteType(). '/view',array(
                'model'=>$model,
            ));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
            $this->layout='//layouts/' .Yii::app()->mf->siteType(). '/column3';
		$model=new Scanners;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Scanners']))
		{
			$model->attributes=$_POST['Scanners'];
			if($model->save())
				$this->redirect(array('index'));
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
            $this->layout='//layouts/' .Yii::app()->mf->siteType(). '/column3';
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Scanners']))
		{
			$model->attributes=$_POST['Scanners'];
			if($model->save())
				$this->redirect(array('index'));
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
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(array('index'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$criteria=new CDbCriteria();

                $criteria->order = 'DATE_CREATED DESC';

                $models = Scanners::model()->findAll($criteria);

                $this->validateAccessDevice($models);

		$this->render(Yii::app()->mf->siteType(). '/index',array(
                    'data'=> $models,
                ));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Scanners::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='scanners-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

        /*
         * Функция проверяет сколько времени прошло с момента последнего запроса доступа с мобильного устройства
         */

        private function validateAccessDevice($devices){
            $nowDate = date('Y-m-d H:i:s');
            if(is_array($devices)){
                foreach ($devices as $i=>$device){
                    $interval = (strtotime($nowDate)-strtotime($device['DATE_LAST_ACCESS']))/60;
                    if($interval > 5){
                        Scanners::model()->updateByPk($device['SCANNERS_ID'], array('ACCESS' => 0));
                    }
                }
            }else{
                $interval = (strtotime($nowDate)-strtotime($devices['DATE_LAST_ACCESS']))/60;
                if($interval > 5){
                    Scanners::model()->updateByPk($devices['SCANNERS_ID'], array('ACCESS' => 0));
                }
            }
        }

        /**
	 * Проверка устройства на работоспособность. Device
         * Обновляет дату последного доступа
	 * @return json результат 0/1 работоспособности.
	 * @param string $id id мероприятия
	 */
	public function actionDevice($id)
	{
            try{
                $device = Scanners::model()->find('UNIQ = :uniq',array(':uniq' => $id));
                if($device){
                    Scanners::model()->updateByPk($device->attributes['SCANNERS_ID'], array('ACCESS' => 1,'DATE_LAST_ACCESS' => date('Y-m-d H:i:s')));
                    /*if(!$device->save()){
                         throw new Exception('Сервер не смог обновить дату последнего доступа устройство с ключом '.$id.'.');
                         echo '0';
                         break;
                    }*/
                    echo '1';
                }else{
                     throw new Exception('Устройство с ключом '.$id.' не было обнаружено.');
                     echo '0';
                }

            }
            catch (Exception $ex){
                /*$fromMail = 'noreply@'.$_SERVER['HTTP_HOST'];
                $title = 'Error: Проверка устройства на работоспособность';
                $text = Scanners::getTextEmailOnDevice($ex->getMessage());
                $Admin_Email = User::model()->findAll(array('select'=>'`email`','condition'=>'role="admin" and send_mail = 1'));
                if($Admin_Email)
                        foreach($Admin_Email as $name=>$value){
                                Yii::app()->mf->mail_html($value->email,$fromMail,Yii::app()->name,$text,$title);
                        }*/
            }

            die();
	}

        /**
	 * Создание нового билета и подтверждение его покупки. Device
	 * @return json результат 0/1 работоспособности.
	 * @param string $id id мероприятия
	 */
	public function actionAddTicket($id)
	{
            /* Находим пользователя на имя кого будет создаваться билет */
            $user = User::model()->find('phone=:phone',array(':phone'=>'70004110670'));

            /* Мероприятие */
            $event = Events::model()->find('uniq=:uniq',array(':uniq'=>$id));

            //Атрибуты нового билета
            $attrTicket = Array();
            $attrTicket['event_id'] = $event['id'];
            $attrTicket['user_id'] = $user->user_id;
            $attrTicket['phone'] = '0004110670';
            $tickets = $event->tickets;
            $attrTicket['ticket_id'] = $tickets[0]['ticket_id'];
            $attrTicket['payment'] = 'credit_card';
            $attrTicket['status'] = 1;

            $newTicket = new TransactionLog();
            $newTicket->attributes = $attrTicket;

            if($newTicket->save())
                echo '1';
            else
                echo '0';

            die();
        }
}
