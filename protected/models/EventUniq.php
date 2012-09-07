<?php

/**
 * This is the model class for table "{{event_uniq}}".
 *
 * The followings are the available columns in table '{{event_uniq}}':
 * @property string $event_id
 * @property string $prefix_class
 */
class EventUniq extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return EventUniq the static model class
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
		return '{{event_uniq}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('event_id', 'required'),
			array('event_id', 'length', 'max'=>50),
			array('prefix_class', 'length', 'max'=>45),
			array('prefix_title', 'length', 'max'=>50),
			array('infinity_qantitty', 'boolean'),
			array('is_not_sale', 'boolean'),
			array('infinity_time', 'boolean'),
			array('phone', 'length', 'max'=>200),
			array('fax', 'length', 'max'=>30),
			array('email', 'length', 'max'=>100),
			array('sait', 'length', 'max'=>100),
			array('time_work', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('event_id, prefix_class, infinity_qantitty, infinity_time, javascript_text, prefix_title, phone, fax, email, sait, time_work, location,is_not_sale', 'safe', 'on'=>'search'),
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
			'event_id' => 'Event',
			'prefix_class' => 'Prefix Class',
			'infinity_qantitty' => 'Prefix Class',
			'infinity_time' => 'Prefix Class',
			'javascript_text' => 'Prefix Class',
			'prefix_title' => 'Prefix Class',
			'phone' => 'Телефон',
			'fax' => 'Факс',
			'email' => 'E-mail',
			'sait' => 'Сайт',
			'time_work' => 'Время работы',
			'location' => 'Карта',
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

		$criteria->compare('event_id',$this->event_id,true);
		$criteria->compare('prefix_class',$this->prefix_class,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}