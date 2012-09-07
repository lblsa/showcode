<?php

class PageContentController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public function init()
	{
		$this->layout='//layouts/' .Yii::app()->mf->siteType(). '/column2';
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
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
                $this->layout='//layouts/' .Yii::app()->mf->siteType(). '/column3';
		$model=new PageContent;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['PageContent']))
		{
			$model->attributes=$_POST['PageContent'];
			if($model->save())
				$this->redirect(array('index'));
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
                $this->layout='//layouts/' .Yii::app()->mf->siteType(). '/column3';
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['PageContent']))
		{
			$model->attributes=$_POST['PageContent'];
			if($model->save())
				$this->redirect(array('index'));
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
		//$dataProvider=new CActiveDataProvider('PageContent');
                $criteria=new CDbCriteria();
                
                $count=  PageContent::model()->count($criteria);
                $pages=new CPagination($count);

                // results per page
                $pages->pageSize=10;
                $pages->applyLimit($criteria);
                
		$dataProvider=new CActiveDataProvider('PageContent', array(
			'criteria'=>$criteria,
		));
                
		$this->render(Yii::app()->mf->siteType(). '/index',array(
			'data'=> PageContent::model()->findAll($criteria),
                        'pages' => $pages,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new PageContent('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['PageContent']))
			$model->attributes=$_GET['PageContent'];

		$this->render('admin',array(
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
		$model=PageContent::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='page-content-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
