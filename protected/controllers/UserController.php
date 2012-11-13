<?php

class UserController extends Controller
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
			
			array('allow',  		// * - все пользователи. Разрешено только действие create, view
				'actions'=>array('create','view','permissionDenied'),
				'users'=>array('*'),
			),
			array('allow',
				'actions'=>array('update'),
				'expression' => 'yii::app()->user->isCurUser($_GET["id"])',
			),
			array('allow',			// Для Админа разрешено всё!
				'expression' => 'yii::app()->user->isAdmin()',
			),
			array('deny',			// Всем остальным пользователям запрещено всё.
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
		if (Yii::app()->user->id==$id || Yii::app()->user->isAdmin())
		{
			$model = $this->loadModel($id);
			if (Yii::app()->user->id!=$id)
				$model->uniq='скрыт';
			$this->render(Yii::app()->mf->siteType(). '/view',array(
				'model'=>$model,
				));
		}			
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new User;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			if($_POST['User']['role'] == 'admin')
				$_POST['User']['send_mail'] = 1;
			$model->attributes=$_POST['User'];
			if($model->save())
				$user_created=true;
		}
		
		$roles = User::$ROLE;
		if (!Yii::app()->user->isAdmin())
			array_pop($roles);
		
		$this->render(Yii::app()->mf->siteType(). '/create',array(
			'model'=>$model,
			'roles'=>$roles,
			'user_created'=>$user_created,
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
		$model->phone=substr($model->phone,1);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			if($_POST['User']['role'] == 'admin')
				$_POST['User']['send_mail'] = 1;
			$model->attributes=$_POST['User'];

			if($model->save())
				$this->redirect(array('view','id'=>$model->user_id));
		}
		if(isset($_GET['newpass'])){
			$new_pass = $model->generatePassword(10);
                        if($model->email){
                        		$text = $model->getTextEmailAboutRecoveryPassword($new_pass);
                                $fromMail = 'noreply@'.$_SERVER[HTTP_HOST];
                                Yii::app()->mf->mail_html($model->email,$fromMail,Yii::app()->name,$text,'Восстановление пароля');
                                //mail($_POST['email'],'Восстановление пароля ' .Yii::app()->name,$text);
                        }
                        
			$message = Yii::app()->name. '.Новый пароль: ' .$new_pass;
			if($model->phone){
				$model->sendMessenge($message);
			}
			User::model()->updateByPk($id,array('password' =>  md5($new_pass)));
		}
		
		$roles = User::$ROLE;
		if (!Yii::app()->user->isAdmin())
			array_pop($roles);
		$model->password=null;
		$model->oldPassword=null;
		
		$this->render(Yii::app()->mf->siteType(). '/update',array(
			'model'=>$model,
			'roles'=>$roles,
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
		//$dataProvider=new CActiveDataProvider('User');
		$this->render(Yii::app()->mf->siteType(). '/index',array(
			'data'=>  User::model()->findAll(),
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
            $this->layout='//layouts/' .Yii::app()->mf->siteType(). '/column2';
		$model=new User('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

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
		$model=User::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'Запрошенная страница не существует.');
		return $model;
	}
        
        /**
	 * 550 permission denied.
	 */
	public function actionPermissionDenied(){
            $this->layout='//layouts/' .Yii::app()->mf->siteType(). '/column3';
            $this->render(Yii::app()->mf->siteType(). '/permissionDenied');
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}