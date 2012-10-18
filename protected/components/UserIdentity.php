<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
    public $_id;

    // Данный метод вызывается один раз при аутентификации пользователя.
	public function authenticate()
    {
        $phone = $this->username;
        $pass = $this->password;
        $user = User::model()->findByAttributes(array('phone'=>$phone));

        if($user===null)
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        else if($user->validatePassword($pass)!==true)
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        else
        {
            $this->_id=$user->user_id;
            $this->username=$user->phone;
            $this->errorCode=self::ERROR_NONE;
        }
        return $this->errorCode==self::ERROR_NONE;
    }

    public function getId()
	{
        return $this->_id;
    }
}
