<?php

class ControlController extends Controller
{
	/**
	 * �������������.
	 * ����� �������������� ������������� ��� ������ ������� ��� ��������� ������ �����.
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
				'expression' => 'yii::app()->user->isAdmin()',
				),
			array('deny',  // deny all users
				'users'=>array('*'),
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
		$this->render(Yii::app()->mf->siteType(). '/view',array(
			'model'=>$this->loadModel($id)
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Control;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Control']))
		{
			$model->attributes=$_POST['Control'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->control_id));
		}

		$this->render(Yii::app()->mf->siteType(). '/create',array(
			'model'=>$model,
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

		if(isset($_POST['Control']))
		{
			$model->attributes=$_POST['Control'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->control_id));
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
		if(Yii::app()->request->isPostRequest)
		{
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
		//$dataProvider=new CActiveDataProvider('Control');
		$this->render(Yii::app()->mf->siteType(). '/index',array(
			'data'=>  Control::model()->findAll(),
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
            $this->layout='//layouts/' .Yii::app()->mf->siteType(). '/column2';
		$model=new Control('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Control']))
			$model->attributes=$_GET['Control'];

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
		$model=Control::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='control-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
