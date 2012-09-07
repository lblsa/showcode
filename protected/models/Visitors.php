<?php

/**
 * This is the model class for table "{{visitors}}".
 *
 * The followings are the available columns in table '{{visitors}}':
 * @property string $id
 * @property string $ip
 * @property string $count
 * @property string $time_last_come
 * @property string $time
 * @property integer $BAN
 * @property string $time_ban
 */
class Visitors extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Visitors the static model class
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
		return '{{visitors}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('BAN', 'numerical', 'integerOnly'=>true),
			array('ip', 'length', 'max'=>150),
			array('count', 'length', 'max'=>10),
			array('time_last_come, time, time_ban', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, ip, count, time_last_come, time, BAN, time_ban', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'ip' => 'Ip',
			'count' => 'Count',
			'time_last_come' => 'Time Last Come',
			'time' => 'Time',
			'BAN' => 'Ban',
			'time_ban' => 'Time Ban',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('count',$this->count,true);
		$criteria->compare('time_last_come',$this->time_last_come,true);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('BAN',$this->BAN);
		$criteria->compare('time_ban',$this->time_ban,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}