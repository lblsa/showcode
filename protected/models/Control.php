<?php

/**
 * This is the model class for table "{{control}}".
 *
 * The followings are the available columns in table '{{control}}':
 * @property integer $control_id
 * @property string $name
 * @property string $value
 * @property string $description
 */
class Control extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Control the static model class
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
		return '{{control}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, value', 'required', 'message'=>'Не может быть пустым'),
			array('control_id', 'numerical', 'integerOnly'=>true, 'message'=>'Вводите только числа'),
			array('name, value', 'length', 'max'=>50),
			array('description','safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('control_id, name, value, description', 'safe', 'on'=>'search'),
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
			'control_id' => 'Настройка',
			'name' => 'Название',
			'value' => 'Значение',
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

		$criteria->compare('control_id',$this->control_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('description',$this->description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}