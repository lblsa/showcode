<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
	//public $email;
	public $phone;
	public $password;
	//public $rememberMe;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that email and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// phone and password are required
			array('phone, password', 'required', 'message'=>'Не может быть пустым'),
			array('phone', 'match', 'pattern'=>'/^[\d]{10}$/', 'message'=>'Телефонный номер должен состоять из цифр'),
			// rememberMe needs to be a boolean
			//array('rememberMe', 'boolean'),
			// password needs to be authenticated
			array('password', 'authenticate'),
		);
	}
	
	
	
	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
            $this->_identity=new UserIdentity('7'.$this->phone,$this->password);
			if(!$this->_identity->authenticate())
				$this->addError('password','Неправильная пара телефон / пароль');
		}
	}

	
	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'password' => 'Пароль',
			'phone' => 'Мобильный телефон',
			//'rememberMe'=>'Запомнить меня',
		);
	}


	/**
	 * Logs in the user using the given phone and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if($this->_identity===null)
		{
            $phone = '7'.$this->phone;
			$this->_identity=new UserIdentity($phone,$this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
            $duration = 3600*24*30;
			Yii::app()->user->login($this->_identity,$duration);
			return true;
		}
		else
			return false;
	}
	
	/**
	 * Регистрация и Авторизация пользователя, зашедшего через "vkontakte.ru" или "facebook"
	 * @return boolean whether login is successful
	 */
	 /*
	public function loginSocial($socialNetwork,$uid=null,$uname=null,$uemail=null,$access_token=null)
	{
		switch ($socialNetwork)
		{
			case 'vkontakte':
				$uid = $_GET['uid'];
				$uname = $_GET['last_name'].' '.$_GET['first_name'];
				$hashPass = md5(Yii::app()->params['vk_id'] . $uid . Yii::app()->params['vk_code']);
				break;
			case 'facebook':
				$hashPass = md5(Yii::app()->params['face_id'] . $uid . Yii::app()->params['face_code']);
				break;
			default:
				return false;
		}
		
		$user = User::model()->find('type = "' .$socialNetwork. '" and uid = "' .$uid. '" and password="' .$hashPass. '"');
		
			//регистрируем нового пользователя
		if (count($user)==0)
		{
			$a = new User();
			$a->uid=$uid;
			$a->type=$socialNetwork;
			$a->email=$uemail;
			$a->password=$hashPass;
			$a->name=$uname;
			$a->role='user';
			$a->access_token=$access_token;
			$a->insert();
			$user = User::model()->find('type = "' .$socialNetwork. '" and uid = "' .$uid. '" and password="' .$hashPass. '"');
		}
		
			//авторизуем пользователя
		$duration=3600*24*30;	// 30 days
		$this->_identity=new UserIdentity($uid,$hashPass);
		$this->_identity->_id = $user->user_id;
		Yii::app()->user->login($this->_identity,$duration);
		return true;
	}
	*/
}
