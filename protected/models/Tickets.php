<?php

/**
 * This is the model class for table "{{tickets}}".
 *
 * The followings are the available columns in table '{{tickets}}':
 * @property integer $ticket_id
 * @property integer $event_id
 * @property string $type
 * @property integer $quantity
 * @property integer $price
 * @property string $date_begin
 * @property string $date_end
 * @property mixed $type_ticket Все возможные типы билетов на мероприятия
 */
class Tickets extends CActiveRecord
{
	public static $type_ticket = array(
		'disposable'=>'Одноразовый',
		'reusable'=>'Многоразовый',
		'travel'=>'Проездной',
		'free'=>'Бесплатный',
		);
	public $count_tickets;

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

			}

			if ($this->type=='travel')
			{
				list($d1,$m1,$y1)=explode('.',$this->date_begin);
				list($d2,$m2,$y2)=explode('.',$this->date_end);
				if ($y1 && $y2)
				{
					$this->date_begin = $y1. '-' .$m1. '-' .$d1;
					$this->date_end = $y2. '-' .$m2. '-' .$d2;
				}
				else
				{
					$this->addError('date_end','некорректный формат даты');
					return false;
				}
			}
			else
			{
				$this->date_begin=null;
				$this->date_end=null;
			}

			if(!$this->description)
				$this->description=null;
			if(!$this->time_begin)
				$this->time_begin=null;
			if(!$this->time_end)
				$this->time_end=null;

			//CActiveForm::validate($this);
			if (CModel::getErrors())
				return false;

			return true;
		}
		else
			return false;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * @return Tickets the static model class
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
		return '{{tickets}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('event_id, type, quantity, price', 'required', 'message'=>'Не может быть пустым'),
			array('quantity, price', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>10),
			array('description', 'length', 'max'=>300),
			array('event_id, date_begin, date_end, time_begin, time_end', 'length', 'max'=>30),
			//array('date_begin, date_end', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ticket_id, event_id, type, quantity, price, date_begin, date_end, time_begin, time_end, description', 'safe', 'on'=>'search'),
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
			'ticket_id' => 'Ticket',
			'event_id' => 'id Мероприятия',
			'type' => 'Тип билета',
			'quantity' => 'Количество',
			'price' => 'Цена',
			'date_begin' => 'Дата начала',
			'date_end' => 'Дата окончания',
			'time_begin' => 'Время начала',
			'time_end' => 'Время окончания',
			'description' => 'Описание',
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

		$criteria->compare('ticket_id',$this->ticket_id);
		$criteria->compare('event_id',$this->event_id);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('quantity',$this->quantity);
		$criteria->compare('price',$this->price);
		$criteria->compare('date_begin',$this->date_begin,true);
		$criteria->compare('date_end',$this->date_end,true);
		$criteria->compare('time_begin',$this->time_begin,true);
		$criteria->compare('time_end',$this->time_end,true);
		$criteria->compare('description',$this->description,true);
                $criteria->order = 'date_begin DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}