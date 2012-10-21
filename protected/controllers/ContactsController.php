<?php

class ContactsController extends Controller
{
	/**
	 * Инициализация.
	 * Здесь инициализируем представление для вывода обычной или мобильной версии сайта.
	 */
	public function init()
	{
		$this->layout='//layouts/' .Yii::app()->mf->siteType(). '/column3';
	}

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('create', 'captcha'),
				'users'=>array('*'),
			),
			array('allow',		// Для Админа разрешено всё!
				'expression' => 'yii::app()->user->isAdmin()',
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}


	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
        return array(
            'captcha'=>array(
                'class'=>'CCaptchaAction',
                'backColor'=> 0xFFFFFF,
                'testLimit' => 1,
            ),
        );
    }

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
            $this->layout='//layouts/' .Yii::app()->mf->siteType(). '/column2';
		$model = $this->loadModel($id);
		$model->user_id = Yii::app()->user->getAuthorName($model->user_id);
		$model->type = Contacts::$type[$model->type];
		if ($model->isread==0)
			Contacts::model()->updateByPk($model->contact_id, array('isread'=>1));
		$this->render(Yii::app()->mf->siteType(). '/view',array(
			'model'=>$model,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Contacts;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Contacts']))
		{
			$model->attributes=$_POST['Contacts'];
			if($model->save())
			{
				if (Yii::app()->user->isAdmin())
					$this->redirect(array('view','id'=>$model->contact_id));
				else
					$message_send = true;
			}
		}

		$this->render(Yii::app()->mf->siteType(). '/create',array(
			'model'=>$model,
			'message_send'=>$message_send,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Contacts']))
		{
			$model->attributes=$_POST['Contacts'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->contact_id));
		}

		$this->render(Yii::app()->mf->siteType(). '/update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest || $id){
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Неверный запрос. Пожалуйста, не повторяйте этот запрос еще раз.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
        $this->layout='//layouts/' .Yii::app()->mf->siteType(). '/column2';
		//$dataProvider=new CActiveDataProvider('Contacts');
		$this->render(Yii::app()->mf->siteType(). '/index',array(
			'data'=>  Contacts::model()->findAll(),
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
            $this->layout='//layouts/' .Yii::app()->mf->siteType(). '/column2';
		$model=new Contacts('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Contacts']))
			$model->attributes=$_GET['Contacts'];

		$this->render(Yii::app()->mf->siteType(). '/admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Contacts::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'Запрошенная страница не существует.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='contacts-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
