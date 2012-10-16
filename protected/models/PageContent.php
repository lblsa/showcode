<?php

/**
 * This is the model class for table "{{page_content}}".
 *
 * The followings are the available columns in table '{{page_content}}':
 * @property string $id
 * @property string $description
 * @property string $tag_uniq
 * @property string $body
 */
class PageContent extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PageContent the static model class
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
		return '{{page_content}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
                        array('description, tag_uniq, body', 'required', 'message'=>'Не может быть пустым'),
			array('description', 'length', 'max'=>555),
			array('tag_uniq', 'length', 'max'=>100),
			array('body', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, description, tag_uniq, body', 'safe', 'on'=>'search'),
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
			'description' => 'Описание',
			'tag_uniq' => 'Уникальный ключ',
			'body' => 'Текст',
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
		$criteria->compare('description',$this->description,true);
		$criteria->compare('tag_uniq',$this->tag_uniq,true);
		$criteria->compare('body',$this->body,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        /**
         * Возвращает текст контента по уникальному ключу
         */
        public function getContentByUniq($tag){
            if($tag){
                $content = PageContent::model()->findByAttributes(array('tag_uniq' => $tag));
                if($content){
                    return $content->body;
                }else{
                    return;
                }
            }
        }
}