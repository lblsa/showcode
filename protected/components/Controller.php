<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	public $layout2='//layouts/column3';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();

	public $headering = '';

	protected function beforeAction($action) 
	{
		$IP = $_SERVER["REMOTE_ADDR"];
		$visitor_ban = Visitors::model()->find('ip=:ip', array(':ip'=>$IP));
		if(intval($visitor_ban['BAN']) && $this->id.'/'.$action->id !== 'user/permissionDenied')
		{
			if(date('Y-m-d H:i:s') < $visitor_ban['time_ban'])
			{
				$this->redirect('/user/permissionDenied');
				return false;
			}
			else
			{
				Visitors::model()->updateByPk($visitor_ban->id, array('count'=>0,'BAN' => 0, 'time_ban'=>NULL,'time_last_come'=>Yii::app()->mf->dateForMysql(date('Y-m-d')).' '.date('H:i:s')));
			}
		}
		return true;
	}
	
	public function getTextEmailSendListTickets($tickets, $event, $organizator)
	{
		$text = $this->renderPartial(Yii::app()->mf->siteType(). '/_emailTickers',
			array(
				'tickets'=>$tickets,
				'event'=>$event,
				'organizator'=>$organizator,
		), true);
		
		return $text;
	}
	
	public function getTextEmailAboutRegistration($password, $model)
	{
		$text = $this->renderPartial(Yii::app()->mf->siteType(). '/_registrationEmail',
			array(
				'password'=>$password,
				'model'=>$model,
		), true);
		
		return $text;
	}
	
	public function getTextEmailAboutRecoveryPassword($password, $model)
	{
		$text = $this->renderPartial(Yii::app()->mf->siteType(). '/_recoveryEmail',
			array(
				'password'=>$password,
				'model'=>$model,
		), true);
		
		return $text;
	}
	
	public function buyIsDoneFree($model, $ticket, $eventUniq, $event)
	{		
		//Отправляем пользователю смс.		
		//$user = mysql_fetch_array(mysql_query('select phone from tbl_user where user_id="' .$log['user_id']. '"'));
		if(isset(Yii::app()->user->phone))
			$phone = Yii::app()->user->phone;
		else
			$phone = $model->phone;		
		
		if (isset($phone))
		{
			require_once('./soap/sms24x7.php');
			$EMAIL_SMS = 'rubtsov@complexsys.ru';
			$PASSWORD_SMS = 'MoZBdJsXG8';
			$message = 'Ваш билет находится здесь:' .PHP_EOL. 'http://' .$_SERVER['HTTP_HOST']. '/ticket/view/' .$model->uniq. '?preview';
			$r = smsapi_push_msg_nologin($EMAIL_SMS, $PASSWORD_SMS, $phone, $message, array("unicode"=>"1"));
		}
		
		//отправляем письмо
		$fromMail = 'noreply@'.$_SERVER[HTTP_HOST];
		
		$text = $this->renderPartial(Yii::app()->mf->siteType(). '/_buyIsDoneFree',
			array(
				'model'=>$model,
				'ticket'=>$ticket,
				'eventUniq'=>$eventUniq,
				'event'=>$event,
		), true);
		
		//return $text;
		Yii::app()->mf->mail_html($model->mail,$fromMail,Yii::app()->name,$text,$title);
	}
	
	public function buyIsDonePay($model, $ticket, $eventUniq, $event, $tit)
	{
		//Отправляем пользователю смс.		
		//$user = mysql_fetch_array(mysql_query('select phone from tbl_user where user_id="' .$log['user_id']. '"'));
		if(isset(Yii::app()->user->phone))
			$phone = Yii::app()->user->phone;
		else
			$phone = $model->phone;		
		
		if (isset($phone))
		{
			require_once('./soap/sms24x7.php');
			$EMAIL_SMS = 'rubtsov@complexsys.ru';
			$PASSWORD_SMS = 'MoZBdJsXG8';
			$message = 'Ваш билет находится здесь:' .PHP_EOL. 'http://' .$_SERVER['HTTP_HOST']. '/ticket/view/' .$model->uniq. '?preview';
			$r = smsapi_push_msg_nologin($EMAIL_SMS, $PASSWORD_SMS, $phone, $message, array("unicode"=>"1"));
		}
		
		//отправляем письмо
		$fromMail = 'noreply@'.$_SERVER[HTTP_HOST];
		
		$text = $this->renderPartial(Yii::app()->mf->siteType(). '/_buyIsDonePay',
			array(
				'model'=>$model,
				'ticket'=>$ticket,
				'eventUniq'=>$eventUniq,
				'event'=>$event,
				'tit'=>$tit,
		), true);
		
		//return $text;
		Yii::app()->mf->mail_html($model->mail,$fromMail,Yii::app()->name,$text,$title);
	}
}