<?php

class SiteController extends Controller
{
	/**
	 * Инициализация.
	 * Здесь инициализируем представление для вывода обычной или мобильной версии сайта.
	 */
	public function init()
	{
            if(preg_match('/\/page\/.*/i', $_SERVER['REQUEST_URI']))
                $this->layout='//layouts/' .Yii::app()->mf->siteType(). '/column2';
            else
                $this->layout='//layouts/' .Yii::app()->mf->siteType(). '/column1';
	}

	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=> 0xFFFFFF,
                'testLimit' => 1,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
    {
        $this->render(Yii::app()->mf->siteType(). '/index');
    }

	/**
	 * Восстановление пароля
	 *
	 */
	public function actionRecovery()
	{
        $this->layout='//layouts/' .Yii::app()->mf->siteType(). '/column3';
		if(isset($_POST['phone']))
        {
            if(preg_match("~[\d]{10}~i", $_POST['phone'])){
                $user = User::model()->find('phone=:phone',array(':phone'=>'7'.$_POST['phone']));
                if($user){
                    $new_pass = $user->generatePassword(10);
                    User::model()->updateAll(array("password" => $new_pass.'/'.md5($new_pass)),"phone = '" .$user->phone. "'");

                    $text = $user->getTextEmailAboutRecoveryPassword($new_pass);
                    if($user->email){
                        $fromMail = 'noreply@'.$_SERVER[HTTP_HOST];
                        Yii::app()->mf->mail_html($user->email,$fromMail,Yii::app()->name,$text,'Восстановление пароля');
                    }
                    $message = Yii::app()->name. '.Новый пароль: ' .$new_pass;
                    $user->sendMessenge($message, $user->phone);
                    $this->render(Yii::app()->mf->siteType(). '/recovery',array('answer'=>1,'phone'=>$user->phone));
                }else{
                    $this->render(Yii::app()->mf->siteType(). '/recovery',array('error_user'=>1,'phone'=>$_POST['phone']));
                }
            }else{
                $this->render(Yii::app()->mf->siteType(). '/recovery',array('error_phone'=>1,'phone'=>$_POST['phone']));
            }
        }
		else
			$this->render(Yii::app()->mf->siteType(). '/recovery');
	}
	/*
	 * Генерация случайного пароля.
	 * Функция генерирует случайный пароль вида "xayoze12"
	 * @return string сгенерированный пароль.
	 */
	public function generate_password()
	{
		$gl="aeiouy";
		$sogl="bcdfghjklmnpqrstvwxz";
		mt_srand(preg_replace("/^0\.(\d+).+$/","\\1",microtime()));
		$password=$sogl[mt_rand(0,19)].$gl[mt_rand(0,5)].$sogl[mt_rand(0,19)].$gl[mt_rand(0,5)].$sogl[mt_rand(0,19)].$gl[mt_rand(0,5)].mt_rand(0,9).mt_rand(0,9);
		return $password;
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
            $this->layout='//layouts/' .Yii::app()->mf->siteType(). '/column2';
	    if($error = Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else{
                    $this->render(Yii::app()->mf->siteType(). '/error', array(
                        'error'=>$error,
                    ));
                }
	    }
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$fromMail = 'noreply@'.$_SERVER[HTTP_HOST];
				$text = $model->getTextEmailOfFeedback();
                $Admin_Email = User::model()->findAll(array('select'=>'`email`','condition'=>'role="admin" and send_mail = 1'));
                if($Admin_Email)
                    foreach($Admin_Email as $name=>$value){
                        Yii::app()->mf->mail_html($value->email,$fromMail,Yii::app()->name,$text,$model->subject);
                    }

                Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render(Yii::app()->mf->siteType(). '/contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$this->layout='//layouts/' .Yii::app()->mf->siteType(). '/column3';
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];

			$pass = $model->password;
			$md5Pass = md5($model->password);
			$model->password = $pass.'/'.$md5Pass;
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
			else
				$model->password = $pass;
		}

		$this->render(Yii::app()->mf->siteType(). '/login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

        /**
         * Функция которая исправляет вид полей для всех билетов
         */
/*
        public function actionReplaceFildsTickets(){
            $tickets = TransactionLog::model()->findAll();
            $securityKey = 3;

            foreach($tickets as $t => $ticket){
                set_time_limit(100);
                //изменяем Цифровой ключ
                $uniq = substr(sprintf('%x',crc32(rand(10000000,99999999).time())),0,8);
                if(strlen($uniq) < 8){
                	$uniq .= substr(sprintf('%x',crc32(rand(10000000,99999999).time())),0,8-intval(strlen($uniq)));
                }

                TransactionLog::model()->updateByPk($ticket->log_id, array('uniq'=>$uniq));

                //Создаём RSA подпись
                $RSA = new RSA();
                $event = Events::model()->findByPk($ticket->event_id, array('select' => 'id,title,datetime,close_key,general_key,online'));
                if(!$event->online){
                        $message = 'event_id=' .$event->id. '&datetime=' .$event->datetime. '&quantity=' .$ticket->quantity. '&uniq=' .$uniq;
                        $rsa = $RSA->encrypt($message, $event->close_key, $event->general_key, 90);
                        //// и коэф. сложности кодирования(настраивается в зависимости от величины входящих простых чисел)
                }
                //создаем QR-код
                include_once("./phpqrcode/qrlib.php");
                $errorCorrectionLevel = 'L';
                $matrixPointSize = 3;
                if($event->online){
                        $data='http://' .$_SERVER['HTTP_HOST']. '/ticket/view/' .$uniq;
                }else{
                        $data='http://' .$_SERVER['HTTP_HOST']. '/ticket/view/' .$uniq. '#rsa=' .$rsa;
                        TransactionLog::model()->updateByPk($ticket->log_id, array('rsa'=>$rsa));
                }
                $filename = $ticket->qr;
                $filepath = '.' .$ticket->qr;
                QRcode::png($data, $filepath, $errorCorrectionLevel, $matrixPointSize, 2);
            }
            $this->redirect(Yii::app()->homeUrl);
        }
*/

	//для быстрых отзывов
	public function actionAjaxFeed($mess, $url)
	{
		$user = User::model()->findByAttributes(array('user_id'=>Yii::app()->user->id))->name;
		$email = User::model()->findByAttributes(array('user_id'=>Yii::app()->user->id))->email;
		
		$to  = 'x+1226812676402@mail.asana.com';
		$title = 'Отзыв';
		
		$mess ='Письмо от пользователя'.$user.' email: '.$email.' 
		Адрес страницы: '.$url.' 
		'.$mess;
		mail($to, $title, $mess);
	}
	
	//вконтакт
	public function actionVk($uid, $first_name, $last_name, $hash)
	{
		$this->layout='//layouts/' .Yii::app()->mf->siteType(). '/column3';
		
		$uid = (int)$uid;
		$vkontakte_app_id = '3235214';
		$vkontakte_secret_key = 'yJLLnNaNEiHZ1qdQYfDu';

		$vk_authorized = md5($vkontakte_app_id.$uid.$vkontakte_secret_key) == $hash;

		if ($vk_authorized)
		{
			$user_model = User::model()->findByAttributes(array('vkontakte_id' => $uid));
			if (!$user_model)
			{
				$user_model = new User;
				
				$user_model->vkontakte_id = $uid;
				$user_model->name = $first_name.' '.$last_name;
				$pass = User::model()->generatePassword(10);
				$user_model->password = $pass.'/'.md5($pass);
				
				if (isset($_POST['User']))
				{
					$user_model->attributes = $_POST['User'];
					//если пользователь уже зарегистрировался в шк, но ни разу не вошел через вк
					$user = User::model()->findByAttributes(array('phone' => '7'.$user_model->phone));
					if ($user)
					{
						$identity = new UserIdentity($user->phone, $user->password);
						$identity->authenticate_vkontankte($user);
						$user->vkontakte_id = $user_model->vkontakte_id;
						$user->phone = substr($user->phone, 1);
						$user->save();
						Yii::app()->user->login($identity);
						$this->redirect(Yii::app()->user->returnUrl);
					}
					//новый юзер, пришедший через вк
					else
					{
						if ($user_model->validate())
						{
							//echo '<pre>'; print_r($user_model->attributes); echo '</pre>';exit;
							$user_model->save();
							$identity = new UserIdentity($user_model->phone, $user_model->password);
							$identity->authenticate_vkontankte($user_model);
							Yii::app()->user->login($identity);
							$this->redirect(Yii::app()->user->returnUrl);
						}
					}
				}
				
				$roles = User::$ROLE;
				if (!Yii::app()->user->isAdmin())
					array_pop($roles);
			
				$this->render(Yii::app()->mf->siteType(). '/vk', array('model'=>$user_model, 'roles'=>$roles));
				return;
			}
			
			$identity = new UserIdentity(0,0);
			$identity->authenticate_vkontankte($user_model);
			Yii::app()->user->login($identity);
			$this->redirect(Yii::app()->user->returnUrl);
		}
		else
		{
			$render_data['vk'] = 'При авторизации произошла ошибка';
			$this->render(Yii::app()->mf->siteType(). '/vk', $render_data);
		}
	}
}
