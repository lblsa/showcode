<?php

/**
 * This is the model class for table "{{contacts}}".
 *
 * The followings are the available columns in table '{{contacts}}':
 * @property integer $contact_id
 * @property integer $user_id
 * @property string $email
 * @property string $type
 * @property string $message
 * @property integer $isread
 */
class Contacts extends CActiveRecord
{
	public $verifyCode;

	public static $type = array(
			'report'=>'Отзыв',
			'proposal'=>'Предложение',
			'complaint'=>'Жалоба',
	);
	public static $isread = array(
			'0'=>'Новое',
			'1'=>'Прочтенное',
	);

	/**
	 * This method is invoked before validation starts.
	 * The default implementation calls {@link onBeforeValidate} to raise an event.
	 * You may override this method to do preliminary checks before validation.
	 * Make sure the parent implementation is invoked so that the event can be raised.
	 * @return boolean whether validation should be executed. Defaults to true.
	 * If false is returned, the validation will stop and the model is considered invalid.
	*/
	protected function beforeValidate()
	{
		if (Yii::app()->user->id)
			$this->user_id = Yii::app()->user->id;
		else
			$this->user_id = 0;
		if (Yii::app()->user->email)
			$this->email = Yii::app()->user->email;
		return true;
	}

	/**
	 * This method is invoked after saving a record successfully.
	 * The default implementation raises the {@link onAfterSave} event.
	 * You may override this method to do postprocessing after record saving.
	 * Make sure you call the parent implementation so that the event is raised properly.
	 */
	protected function afterSave()
	{
		parent::afterSave();
                if(!isset($this->datetime)){
                    Contacts::model()->updateByPk($this->contact_id, array('datetime'=>Yii::app()->mf->dateForMysql(date('Y-m-d')).' '.date('H:i:s')));
                }
		//E-mail от кого отправлено письмо
		//$fromMail = $Booking_Time = Control::model()->find("name = 'fromMail'")->value;
		if($this->email){
			$fromMail = 'noreply@'.$_SERVER[HTTP_HOST];
			$title = Contacts::$type[$this->type];
			$text = $this->getTextEmailOfFeedback();
			$Admin_Email = User::model()->findAll(array('select'=>'`email`','condition'=>'role="admin" and send_mail = 1'));
			if($Admin_Email)
				foreach($Admin_Email as $name=>$value){
					Yii::app()->mf->mail_html($value->email,$this->email,Yii::app()->name,$text,$title);
				}
		}
	}

	/**
	 * Возвращает верстку текста для электронной почты при обратной связи.
	 */
	public function getTextEmailOfFeedback(){
            $typeD = array(
                'report'=>'Отзыв',
                'proposal'=>'Предложение',
                'complaint'=>'Жалобу',
            );

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
	    $text .= '<td colspan="2"><div style="height: 40px;"><p style="font-family:Arial, Helvetica, sans-serif; font-size:24px; font-weight:normal; font-style:normal; color:#333; padding-bottom: 10px;">Пользователь <a href="http://' .$_SERVER['HTTP_HOST']. '/user/view/' .$this->user_id. '">' .Yii::app()->user->getAuthorName($this->user_id). '</a> отправил '.$typeD[$this->type].'</p></div></td>';
	    $text .= '</tr>';
	    $text .= '<tr>';
	    $text .= '<td style="background-color:#e5e5e5;"><p style=" font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; color:#333;line-height: 18px;padding-left: 10px;padding-right: 10px;margin-top: 10px;margin-bottom: 10px;">'.$this->message.'</p></td>';
            $text .= '<td style="padding-right: 10px;">&nbsp;</td>';
	    $text .= '</tr>';
	    $text .= '<tr>';
	    $text .= '<td colspan="2" style="padding-top: 7px;   padding-bottom: 17px;padding-right: 10px;">';
	    $text = $text.'<table cellspasing="0" border="0" cellpadding="5" width="" style="margin: 0pt; padding: 20px 0; background-color: rgb(255, 255, 255); border-collapse: collapse;">';
            if(!Yii::app()->user->isGuest){
                $text .= '<tr>';
                $text .= '<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; color:#000;">Пользователь:</td>';
                $text .= '<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;"><b>' .Yii::app()->user->getAuthorName($this->user_id).'</b></td>';
                $text .= '</tr>';
            }
	    $text .= '<tr>';
	    $text .= '<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; color:#000;">Адрес для ответа:</td>';
	    $text .= '<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;"><b>' .$this->email.'</b></td>';
	    $text .= '</tr>';
	    $text .= '</table>';
	    $text .= '</td>';
	    $text .= '</tr>';
	    $text .= '<tr>';
            $text .= '<td style="padding-top: 10px; border-top-color: #999; border-top-style: solid; border-top-width: 1px;"><p style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; color:#333;line-height: 0;">С уважением, администрация сайта <a target="_blank" href="' .$_SERVER['HTTP_HOST']. '" title="">ShowCode.ru</a>.</p></td>';
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

	/**
	 * Returns the static model of the specified AR class.
	 * @return Contacts the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{contacts}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
                    array('user_id, email, type, message', 'required', 'message'=>'Не может быть пустым'),
                    array('user_id, isread', 'numerical', 'integerOnly'=>true, 'message'=>'Вводите только числа'),
                    array('email', 'length', 'max'=>30),
                    array('email', 'email'),
                    array('type', 'length', 'max'=>9),
                    array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements(), 'message'=>'Неправильно ввели код'),
                    // The following rule is used by search().
                    // Please remove those attributes that should not be searched.
                    array('contact_id, user_id, email, datetime, type, message, isread', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'contact_id' => 'Отзыв',
			'user_id' => 'Пользователь',
            'datetime' => 'Дата и время',
			'email' => 'Email',
			'type' => 'Тип',
			'message' => 'Сообщение',
			'isread' => 'Статус',
			'verifyCode' => 'Проверочный код',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('contact_id',$this->contact_id);
		$criteria->compare('user_id',$this->user_id);
        $criteria->compare('datetime',$this->datetime,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('isread',$this->isread);
                $criteria->order = 'datetime DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
