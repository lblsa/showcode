<?php
class ContactForm2 extends CFormModel
{
	public $name;
	public $email;
	public $subject;
	public $body;
	public $verifyCode2;

	public function rules()
	{

		return array(
			array('verifyCode2', 'captcha'),
		);
	}
	public function attributeLabels()
	{
		return array(
			'verifyCode2'=>'Verification Code',
		);
	}
}
?>