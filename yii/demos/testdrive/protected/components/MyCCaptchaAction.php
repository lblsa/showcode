<?php
class MyCCaptchaAction extends CCaptchaAction //extends CCaptcha
{
	//public $captchaAction='captcha';
	
	
	public $captcha=10;		//тестовая цифра
	public $cipher;
	
	
	
	public function run()		//Запускается после клика на ссылку обновления изображения.
	{
		/*
		$cap = $this->getCaptchaAction();
		$code=$cap->getVerifyCode(false);
		
		*/
		
		//print_r ($this->getController());
		
		if(isset($_GET[self::REFRESH_GET_VAR]))  // AJAX request for regenerating code
		{
			echo CJSON::encode(array(
				'code'=>$this->getVerifyCode(true),
				'hash1'=>$this->generateValidationHash($code),
				'hash2'=>$this->generateValidationHash(strtolower($code)),
				// we add a random 'v' parameter so that FireFox can refresh the image
				// when src attribute of image tag is changed
				'url'=>$this->getController()->createUrl($this->getId(),array('v' => uniqid())),
			));
		}
		else
			CCaptchaAction::renderImage($this->getVerifyCode().'');		//передаём случайное значение, которое выводится на картинке.
		Yii::app()->end();
	}
	
	protected function getCaptchaAction()
	{
		if(($captcha=Yii::app()->getController()->createAction($this->captchaAction))===null)
		{
			if(strpos($this->captchaAction,'/')!==false) // contains controller or module
			{
				if(($ca=Yii::app()->createController($this->captchaAction))!==null)
				{
					list($controller,$actionID)=$ca;
					$captcha=$controller->createAction($actionID);
				}
			}
			if($captcha===null)
				throw new CException(Yii::t('yii','CCaptchaValidator.action "{id}" is invalid. Unable to find such an action in the current controller.',
						array('{id}'=>$this->captchaAction)));
		}
		return $captcha;
	}

    protected function generateVerifyCode()
    {
        return rand(50, 100);
    }
	
   /*	Это оригинал
	* Возвращается случайно сгенерированное число.
	*/		
	public function getVerifyCode($regenerate=false)
	{
		if($this->fixedVerifyCode !== null)
			return $this->fixedVerifyCode;

		if (isset($_GET['my_id']))
			$this->captcha = $_GET['my_id'];
			
		$session = Yii::app()->session;
		$session->open();
		$name = $this->getSessionKey();
		if($session[$name] === null || $regenerate)
		{
			$session[$name] = $this->generateVerifyCode();
			$session[$name . 'count'] = 1;
		}
		return $session[$name];
	}
	
	public function validate($input,$caseSensitive)			//конечная проверка на уровне сервера
	{
		$code = $this->getVerifyCode();
		$valid = $caseSensitive ? ($input === $code) : !strcasecmp($input,$code);
		$session = Yii::app()->session;
		$session->open();
		$name = $this->getSessionKey() . 'count';
		$session[$name] = $session[$name] + 1;
		if($session[$name] > $this->testLimit && $this->testLimit > 0)
			$this->getVerifyCode(true);
		return $valid;
	}
	
	public function getSessionKey()
	{
		return self::SESSION_VAR_PREFIX . $this->getController()->getUniqueId() . '.' . $this->captcha;//sprintf('%x',crc32($this->captcha));
	}
}
?>