<?php

class StatisticsController extends Controller
{
    /**
     * 	 * Инициализация.
     * 	 * Здесь инициализируем представление для вывода обычной или мобильной версии сайта.
     * 	 */
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
     * Настройка прав доступа для пользователей.
     * @return array access control rules
     */
    public function accessRules()
    {
            return array(
                array('allow',			// Для Admin разрешено: 'index', 'view', 'admin' и 'create'
                    'actions'=>array('index'),
                    'expression' => 'yii::app()->user->isAdmin()',
                    //'expression' => array($this, 'isOrganizer'),
                ),
                array('allow',			//для создателей мероприятия разрешено редактировать его и проверять билеты.
                        'actions'=>array('index'),
                        'expression' => 'yii::app()->user->isCreator($_POST["TransactionLog"]["event_id"])',
                        //'expression' => array($this, 'isCreator'),
                ),
                array('deny',			// Для Организатора разрешено: 'index'
                    'actions'=>array('index'),
                    'expression' => 'Yii::app()->user->isGuest',
                    //'expression' => array($this, 'isOrganizer'),
                ),
            );
    }

    public function actionIndex()
    {
        /**
        * Переменная относительно каких периодов делать статистику
        */
        $sortDate = array(
            'days'=>'дням',
            'weeks'=>'неделям',
            'mounths'=>'месяцам',
        );

        $tickets = new TransactionLog;

        if(isset($_POST['TransactionLog'])){
        	/**            Заполняется фильтр            */
            $tickets->attributes = $_POST['TransactionLog'];
            $tickets->date_begin = $_POST['TransactionLog']['date_begin'];
            $tickets->date_end = $_POST['TransactionLog']['date_end'];
            $tickets->period = $_POST['TransactionLog']['period'];

            if ($_POST['TransactionLog']['user_id'] != ''){
                $eventsDropList = Events::model()->findAll('status = "published" AND author=:author', array(':author'=>$_POST['TransactionLog']['user_id']));
                if(count($eventsDropList) == 0)
                    $eventsDropList = new Events;
            }else{
                $eventsDropList = new Events;
            }

            /**            Массив дней            */
            if($_POST['TransactionLog']['date_begin'] && $_POST['TransactionLog']['date_end']){
                $dayTIMEarray = $this->getArrayDatePeriod($_POST['TransactionLog']['period'], $_POST['TransactionLog']['date_begin'], $_POST['TransactionLog']['date_end']);
            }else{
            	$exp = '';
            	if ($_POST['TransactionLog']['user_id'] != ''){
            		$exp .= 'event_id IN (SELECT id FROM tbl_events WHERE author = "' .$_POST['TransactionLog']['user_id']. '") AND ';
            		if ($_POST['TransactionLog']['event_id'] != ''){
            			$exp .= 'event_id  = "' .$_POST['TransactionLog']['event_id']. '" AND ';
            		}
            	}

            	$MinMaxDays = Yii::app()->db->createCommand('SELECT MAX(datetime) AS MaxDay, MIN(datetime) AS MinDay FROM tbl_transaction_log WHERE '.$exp.'1')->queryAll();

                $dayTIMEarray = $this->getArrayDatePeriod($_POST['TransactionLog']['period'], $MinMaxDays[0]['MinDay'], $MinMaxDays[0]['MaxDay']);
            }

            if ($_POST['TransactionLog']['user_id'] != ''){
            	$users = User::model()->findByPk($_POST['TransactionLog']['user_id']);
            	$users = Array($users);
            }else{
            	$users = User::model()->findAll();

            }
        }else{
            $eventsDropList = new Events;/*            Список мероприятий пользователя            */

            $MinMaxDays = Yii::app()->db->createCommand('SELECT MAX(datetime) AS MaxDay, MIN(datetime) AS MinDay FROM tbl_transaction_log')->queryAll();
            $dayTIMEarray = $this->getArrayDatePeriod($_POST['TransactionLog']['period'], $MinMaxDays[0]['MinDay'], $MinMaxDays[0]['MaxDay']);

            $users = User::model()->findAll();
        }
        /**
        * Список пользователей
        */
        $usersDropList = User::model()->findAll('role <> :role', array(':role'=>'user'));

        $this->render(Yii::app()->mf->siteType(). '/index',array(
            'tickets'=>$tickets,
            'sortDate'=>$sortDate,
            'usersDropList'=>$usersDropList,
            'eventsDropList'=>$eventsDropList,
            'users'=>$users,
            'daysPeriod'=>$dayTIMEarray,
        ));
    }

    public function getArrayDatePeriod($period, $date_b, $date_e){
        $date_b = new DateTime(trim($date_b));
        $date_e = new DateTime(trim($date_e));
        $date_b->setTime(0, 0, 0);
        $date_e->setTime(0, 0, 0);

        $daysArray = Array();
        switch ($period) {
            case 'days':
                while ($date_b <= $date_e) {
                    array_push($daysArray, Array('day'=>$date_b->format("d"),'mounth'=>$date_b->format("m"),'year'=>$date_b->format("Y")));
                    $date_b->modify('+1 day');
                }
                break;
            case 'weeks':
                while ($date_b <= $date_e) {
                    if($date_b->format("m") == 1 && $date_b->format("W") == 52)
                        array_push($daysArray, Array('year'=>intval($date_b->format("Y"))-1,'week'=>$date_b->format("W")));
                    else
                        array_push($daysArray, Array('year'=>$date_b->format("Y"),'week'=>$date_b->format("W")));
                    $date_b->modify('+1 week');
                }
                break;
            case 'mounths':
                $date_b->setDate($date_b->format("Y"), $date_b->format("n"), 1);
                $date_e->setDate($date_e->format("Y"), $date_e->format("n"), 1);
                while ($date_b <= $date_e) {
                    array_push($daysArray, Array('mounth'=>$date_b->format("m"),'year'=>$date_b->format("Y")));
                    $date_b->modify('+1 month');
                }
                break;
        }
        return $daysArray;
    }

    // Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}