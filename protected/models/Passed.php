<?php

/**
 * This is the model class for table "{{passed}}".
 *
 * The followings are the available columns in table '{{passed}}':
 * @property string $id
 * @property string $datetime
 * @property string $log_id
 * @property string $user_id
 * @property string $event_id
 * @property string $ticket_id
 */
class Passed extends CActiveRecord
{

        /**
	 * This method is invoked before saving a record (after validation, if any).
	 * The default implementation raises the {@link onBeforeSave} event.
	 * You may override this method to do any preparation work for record saving.
	 * Use {@link isNewRecord} to determine whether the saving is
	 * for inserting or updating record.
	 * Make sure you call the parent implementation so that the event is raised properly.
	 * @return boolean whether the saving should be executed. Defaults to true.
	 */
	protected function beforeSave()
	{
		if(parent::beforeSave())
		{
			if($this->isNewRecord)
			{
				$ticket = TransactionLog::model()->find(array('condition'=>'uniq=:uniq', 'params'=>array(':uniq'=>$this->log_id)));
                $this->user_id = $ticket->user_id;
                $this->ticket_id = $ticket->ticket_id;
                $this->datetime = date("Y-m-d H:i:s");
			}
                }

		return true;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Passed the static model class
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
		return '{{passed}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, event_id, ticket_id', 'length', 'max'=>10),
			array('log_id', 'length', 'max'=>25),
			array('datetime', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, datetime, log_id, user_id, event_id, ticket_id', 'safe', 'on'=>'search'),
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
			'datetime' => 'Datetime',
			'log_id' => 'Log',
			'user_id' => 'User',
			'event_id' => 'Event',
			'ticket_id' => 'Ticket',
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
		$criteria->compare('datetime',$this->datetime,true);
		$criteria->compare('log_id',$this->log_id,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('event_id',$this->event_id,true);
		$criteria->compare('ticket_id',$this->ticket_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}