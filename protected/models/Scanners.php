<?php

/**
 * This is the model class for table "{{scanners}}".
 *
 * The followings are the available columns in table '{{scanners}}':
 * @property string $SCANNERS_ID
 * @property string $UNIQ
 * @property string $DESCRIPTION
 * @property integer $ACCESS
 * @property string $DATE_CREATED
 * @property string $DATE_LAST_ACCESS
 * @property string $DATE_CHANGED
 */
class Scanners extends CActiveRecord
{

        protected function beforeSave()
	{
            if(parent::beforeSave()){
                    if($this->isNewRecord){
                        /* UNIQ */
                        $this->UNIQ = md5($this->DESCRIPTION.time());

                        /*** USERS_ID ***/
                        $this->USERS_ID = Yii::app()->user->id;

                        /*** DATE_CREATED ***/
                        $this->DATE_CREATED = date('Y-m-d H:i:s');
                    }
                }else
                    return false;
                return true;
        }
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Scanners the static model class
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
		return '{{scanners}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('DESCRIPTION', 'required'),
			array('ACCESS', 'boolean'),
			array('UNIQ', 'length', 'max'=>128),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('SCANNERS_ID, UNIQ, DESCRIPTION, USERS_ID, ACCESS, DATE_CREATED, DATE_LAST_ACCESS, DATE_CHANGED', 'safe', 'on'=>'search'),
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
			'SCANNERS_ID' => 'Индификатор сканера',
			'UNIQ' => 'Уникальный ключ',
			'DESCRIPTION' => 'Описание',
                        'USERS_ID' => 'Владелец',
			'ACCESS' => 'Статус',
			'DATE_CREATED' => 'Дата создание',
			'DATE_LAST_ACCESS' => 'Дата последнего доступа',
			'DATE_CHANGED' => 'Дата изменения',
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

		$criteria->compare('SCANNERS_ID',$this->SCANNERS_ID,true);
		$criteria->compare('UNIQ',$this->UNIQ,true);
		$criteria->compare('DESCRIPTION',$this->DESCRIPTION,true);
		$criteria->compare('ACCESS',$this->ACCESS);
		$criteria->compare('DATE_CREATED',$this->DATE_CREATED,true);
		$criteria->compare('DATE_LAST_ACCESS',$this->DATE_LAST_ACCESS,true);
		$criteria->compare('DATE_CHANGED',$this->DATE_CHANGED,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

        /**
	 * Возвращает верстку текста для электронной почты при ошибке проверки работоспособности устройства.
	 */
	public function getTextEmailOnDevice($messenge){
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
            $text .= '<td colspan="2"><img src="http://' .$_SERVER['HTTP_HOST']. '/images/email/logo_empty.jpg" alt="Showcode" title="Showcode" style="margin: 0pt; padding: 0pt; border: 0pt none; display: block;"></td>';
	    $text .= '</tr>';
	    $text .= '<tr>';
	    $text .= '<td colspan="2"><div style="height: 90px;"><p style="font-family:Arial, Helvetica, sans-serif; font-size:24px; font-weight:normal; font-style:normal; color:#333; padding-bottom: 10px;">При запросе устройства к серверу произошла нестандартная ситуация</p></div></td>';
	    $text .= '</tr>';
	    $text .= '<tr>';
	    $text .= '<td style="background-color:#e5e5e5;"><p style=" font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; color:#333;line-height: 18px;padding-left: 10px;padding-right: 10px;margin-top: 10px;margin-bottom: 10px;">'.$messenge.'</p></td>';
            $text .= '<td style="padding-right: 10px;">&nbsp;</td>';
	    $text .= '</tr>';
	    $text .= '<tr>';
	    $text .= '<td colspan="2" style="padding-top: 7px;   padding-bottom: 17px;padding-right: 10px;">';
	    $text = $text.'<table cellspasing="0" border="0" cellpadding="5" width="" style="margin: 0pt; padding: 20px 0; background-color: rgb(255, 255, 255); border-collapse: collapse;">';
	    $text .= '<tr>';
	    $text .= '<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; color:#000;"></td>';
	    $text .= '<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;"><b></b></td>';
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
}