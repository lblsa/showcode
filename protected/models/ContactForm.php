<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class ContactForm extends CFormModel
{
	public $name;
	public $email;
	public $subject;
	public $body;
	public $datetime;
	public $verifyCode;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('name, email, subject, body', 'required', 'message'=>'Не может быть пустым'),
			// email has to be a valid email address
			array('email', 'email'),
			// verifyCode needs to be entered correctly
			array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements(), 'message'=>'Неправильно ввели код'),
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'verifyCode'=>'Verification Code',
		);
	}

        /**
	 * Возвращает верстку текста для электронной почты при обратной связи.
	 */
	public function getTextEmailOfFeedback(){
		$text = '';
		$text .= '<table cellspasing="0" border="0" cellpadding="0" width="100%" style="background-color:#dadada; border-collapse: collapse; border-spacing:0;">';
                $text .= '<tr>';
                $text .= '<td height="20"></td>';
                $text .= '</tr>';
                $text .= '<tr>';
                $text .= '<td align="center">';
                $text .= '<table cellspasing="0" border="0" cellpadding="0" height="460px" width="728px" style="margin: 0pt; padding:0; background-color: rgb(255, 255, 255); border-collapse: collapse; border-spacing:0;">';
                $text .= '<tr>';
                $text .= '<td style="background-image:url(http://' .$_SERVER['HTTP_HOST']. '/images/email/content_bg.png); background-repeat:no-repeat; background-position:left top; padding-top: 0px; padding-right:0; padding-bottom:0; padding-left: 21px;">';
                $text .= '<table cellspasing="0" border="0" cellpadding="0" width="698px" style="margin: 0pt; padding: 0pt; background-color: rgb(255, 255, 255); border-collapse: collapse; border-spacing:0;">';
                $text .= '<tr>';
                $text .= '<td colspan="2"><img src="http://' .$_SERVER['HTTP_HOST']. '/images/email/logo_feedback.jpg" alt="Showcode. Обратная связь." title="Showcode. Обратная связь." style="margin: 0pt; padding: 0pt; border: 0pt none; display: block;"></td>';
                $text .= '</tr>';
                $text .= '<tr>';
                $text .= '<td colspan="2"><div style="height: 40px;"><p style="font-family:Arial, Helvetica, sans-serif; font-size:24px; font-weight:normal; font-style:normal; color:#333; padding-bottom: 10px;">Было отправлено письмо от незарегистрированного пользователя на корпаративную почту сервиса.</p></div></td>';
                $text .= '</tr>';
                $text .= '<tr>';
                $text .= '<td style="background-color:#e5e5e5;"><p style=" font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; color:#333;line-height: 18px;padding-left: 10px;padding-right: 10px;margin-top: 10px;margin-bottom: 10px;">'.$this->body.'</p>.</td>';
                $text .= '</tr>';
                $text .= '<tr>';
                $text .= '<td colspan="2" style="padding-top: 7px;   padding-bottom: 17px;padding-right: 10px;">';
                $text .= '<table cellspasing="0" border="0" cellpadding="5" width="" style="margin: 0pt; padding: 20px 0; background-color: rgb(255, 255, 255); border-collapse: collapse;">';
                $text .= '<tr>';
                $text .= '<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; color:#000;">Адрес для ответа:</td>';
                $text .= '<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;"><b>' .$this->email.'</b></td>';
                $text .= '</tr>';
                $text .= '</table>';
                $text .= '</td>';
                $text .= '</tr>';
                $text .= '<tr>';
                $text .= '<td style="padding-top:5px;"><p style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; color:#333;">С уважением, администрация сайта <a target="_blank" href="' .$_SERVER['HTTP_HOST']. '" title="">ShowCode.ru</a>.</p></td>';
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
                $text .= '</tr>';
		$text .= '</table>';

		return $text;
	}
}