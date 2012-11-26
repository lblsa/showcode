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
                    'actions'=>array('index, ajaxSendStat'),
                    'expression' => 'yii::app()->user->isAdmin()',
                    //'expression' => array($this, 'isOrganizer'),
                ),
                array('allow',			//для создателей мероприятия разрешено редактировать его и проверять билеты.
                        'actions'=>array('index,ajaxSendStat'),
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
	
		//фильтр по датам
		if(isset($_POST['TransactionLog']))
		{
        	//Заполняется фильтр            
            $tickets->attributes = $_POST['TransactionLog'];			

			$date_begin = '';
			$date_end = '';
			if (isset($_POST['TransactionLog']['period']))
			{
				$period = $_POST['TransactionLog']['period'];
				$tickets->period = $period;
			}
			if(!empty($_POST['TransactionLog']['date_begin']))
			{
				$tickets->date_begin = $_POST['TransactionLog']['date_begin'];
				$date_begin = date('Y-m-d', strtotime($_POST['TransactionLog']['date_begin']));
				
				if(!empty($_POST['TransactionLog']['date_end']))
				{
					$tickets->date_end = $_POST['TransactionLog']['date_end'];
					$date_end = date('Y-m-d', strtotime($_POST['TransactionLog']['date_end']));
				}
			}
			
			$sql = "select sum(quantity) as sq, sum(price) as sp from tbl_transaction_log where event_id = '".$tickets->event_id."' and datetime between '".$date_begin."' and '".$date_end."'";
			$command = Yii::app()->db->createCommand($sql);
			$dataReader = $command->query();
			$data = $dataReader->read();		
			$quantityAll = $data['sq'];	
			$priceAll = $data['sp'];
			$qXp = $quantityAll*$priceAll;
			
			$sql = "select sum(quantity) as sq, sum(price) as sp from tbl_transaction_log where event_id = '".$tickets->event_id."' and datetime between '".$date_begin."' and '".$date_end."' and status = 3";
			$command = Yii::app()->db->createCommand($sql);
			$dataReader = $command->query();
			$data = $dataReader->read();			
			$quantityAllu = $data['sq'];	
			$priceAllu = $data['sp'];
			$qXpu = $quantityAllu*$priceAllu;
			
		}

        $this->render(Yii::app()->mf->siteType(). '/index',array(
            'tickets' => $tickets,
            'sortDate' => $sortDate,
			'date_begin' => $date_begin,
			'date_end' => $date_end,
			'period' => $period,
			'quantityAll' => $quantityAll,
			'quantityAllu' => $quantityAllu,
			'qXp' => $qXp,
			'qXpu' => $qXpu,
        ));
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
	
	public function actionTest()
	{
		//все переделываю...	
		$event_id = 'eabf880c';
		$user_id = 90;	
			
		//последний день рассылки передать в вызове
		$sql = "select max(last_date) AS maxDate, send_stat from tbl_event_stat where user_id = ".$user_id." and event_id = '".$event_id."'";
		$command = Yii::app()->db->createCommand($sql);
		$dataReader = $command->query();
		$data = $dataReader->read();			
				
		$lastDate = date('Y-m-d', strtotime($data['maxDate']));
		$now = date('Y-m-d');
		
		$model = new TransactionLog;
		$info = array();
		//получаем дату создания события
		$dateCreate = date('Y-m-d', strtotime(Events::model()->findByAttributes(array('id'=>$event_id))->datetime));
		
		//начало периода
		$byeStart = $model->searchForStat($user_id, $event_id, $dateCreate, $lastDate);
		$useStart = $model->searchForStat($user_id, $event_id, $dateCreate, $lastDate, 1);
		$info['cByeStart'] = $byeStart['quantity'];
		$info['cUseStart'] = $useStart['quantity'];
		
		//в зависимости от периода найти все билеты, попавшие в этот период, конец периода	
		$byeEnd = $model->searchForStat($user_id, $event_id, $lastDate, $now);
		$useEnd = $model->searchForStat($user_id, $event_id, $lastDate, $now, 1);
		$info['cByeEnd'] = $byeEnd['quantity'];
		$info['cUseEnd'] = $useEnd['quantity'];		
		
		//получам цену билета
		$info['pByeStart'] = $byeStart['allPrice'];
		$info['pUseStart'] = $useStart['allPrice'];
		$info['pByeEnd'] = $byeEnd['allPrice'];
		$info['pUseEnd'] = $useEnd['allPrice'];
		
		$this->render(Yii::app()->mf->siteType(). '/_stat',array(
						'model'=>$model,
						'event_id'=>$event_id,
						'user_id'=>$user_id,
						'info'=>$info,
		));
	}
	
	//рассылка статистики
	public function generateMail($event_id, $user_id, $lastDate, $now)
	{
		$model = new TransactionLog;
		$info = array();
		//получаем дату создания события
		$dateCreate = date('Y-m-d', strtotime(Events::model()->findByAttributes(array('id'=>$event_id))->datetime));
		
		//начало периода
		$byeStart = $model->searchForStat($user_id, $event_id, $dateCreate, $lastDate);
		$useStart = $model->searchForStat($user_id, $event_id, $dateCreate, $lastDate, 1);
		$info['cByeStart'] = $byeStart['quantity'];
		$info['cUseStart'] = $useStart['quantity'];
		
		//в зависимости от периода найти все билеты, попавшие в этот период, конец периода	
		$byeEnd = $model->searchForStat($user_id, $event_id, $lastDate, $now);
		$useEnd = $model->searchForStat($user_id, $event_id, $lastDate, $now, 1);
		$info['cByeEnd'] = $byeEnd['quantity'];
		$info['cUseEnd'] = $useEnd['quantity'];		
		
		//получам цену билета
		$info['pByeStart'] = $byeStart['allPrice'];
		$info['pUseStart'] = $useStart['allPrice'];
		$info['pByeEnd'] = $byeEnd['allPrice'];
		$info['pUseEnd'] = $useEnd['allPrice'];
		
		//текст письма (статистика)
		$data = $this->renderPartial(Yii::app()->mf->siteType(). '/_stat',array(
						'model'=>$model,
						'event_id'=>$event_id,
						'user_id'=>$user_id,
						'info'=>$info,
		), true);
		
		//echo '<pre>'; print_r($data); echo '</pre>';
		//отправляем письмо
		$fromMail = 'noreply@'.$_SERVER[HTTP_HOST];
	
		$to = User::model()->findByAttributes(array('user_id'=>Yii::app()->user->id))->email;
		$title = 'Статистика  по мероприятию «'.Events::model()->getEventTitle($event_id).'»';
		
		$eol="\n";
		# Common Headers
		$headers .= "From: <{$fromMail}>".$eol;
		$headers .= "Reply-To: <{$fromMail}>".$eol;
		# Boundary for marking the split & Multitype Headers
		$mime_boundary=md5(time());
		$headers .= 'MIME-Version: 1.0'.$eol;
		$headers .= "Content-Type: text/html; charset=utf-8".$eol;
		
		mail($to, $title, $data, $headers);
		//Yii::app()->mf->mail_html($to,$fromMail,Yii::app()->name,$data,$title);
	}
	public function SendMail($event_id = 0, $user_id = 0)
	{
		if($event_id==0 || $user_id==0 )
		{
			$EventStat = EventStat::model()->findAll();
			
			foreach ($EventStat as $item)
			{
				$user_id = $item->user_id;
				$event_id = $item->event_id;
				$lastDate = date('d.m.y', strtotime($item->last_date));
				$sendStat = $item->send_stat;
				$now = date('d.m.y');

				$arr1 = explode('.', $lastDate);
				$arr2 = explode('.', $now);
				$time1 = mktime(0,0,0,$arr1[1],$arr1[0],$arr1[2]);
				$time2 = mktime(0,0,0,$arr2[1],$arr2[0],$arr2[2]);
				$dif = ($time2 - $time1) / 86400;
			
				switch($sendStat)
				{
					case 0:
						break;
					case 1:
					{
						if($dif>=1 && $dif<7)
						{
							$this->generateMail($event_id, $user_id, $lastDate, $now);
							
							$sql = "update tbl_event_stat set last_date = '".date('Y-m-d')."' where user_id = ".$user_id." and event_id = ' ".$event_id." ' ";
							$command = Yii::app()->db->createCommand($sql);
							$command->execute();
							break;
						}
					}
					case 2:
					{
						if($dif>=7 && $dif<30)
						{
							$this->generateMail($event_id, $user_id, $lastDate, $now);
							
							$sql = "update tbl_event_stat set last_date = '".date('Y-m-d')."' where user_id = ".$user_id." and event_id = ' ".$event_id." ' ";
							$command = Yii::app()->db->createCommand($sql);
							$command->execute();
							break;
						}
					}
					case 3:
					{
						if($dif>=30)
						{
							$this->generateMail($event_id, $user_id, $lastDate, $now);
							
							$sql = "update tbl_event_stat set last_date = '".date('Y-m-d')."' where user_id = ".$user_id." and event_id = ' ".$event_id." ' ";
							$command = Yii::app()->db->createCommand($sql);
							$command->execute();
							break;
						}
					}
				}				
			}
		}
		//else
			//$this->generateMail($event_id, $user_id, $lastDate, $now);
	}
	
	public function actionSendStat()
	{
		if (isset($_SERVER['HTTP_CLIENT_IP']) || isset($_SERVER['HTTP_X_FORWARDED_FOR']) || !in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1',))) 
		{
				header('HTTP/1.0 403 Forbidden');
				exit('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
		}
		else
			$this->SendMail();
	}
	
	public function actionAjaxSendStat($select, $event_id)
	{
		//заносим в базу данные о рассылке
		$sql = "select * from tbl_event_stat where user_id = ".Yii::app()->user->id." and event_id = ' ".$event_id."'";
		$command = Yii::app()->db->createCommand($sql);
		$dataReader = $command->query();
		$data = $dataReader->readAll();
		if(empty($data))
		{
			$sql = "insert into tbl_event_stat (user_id, event_id, send_stat, last_date) values(".Yii::app()->user->id.", '".$event_id."', ".$select.",  '".date('Y-m-d')."')" ;
			$command = Yii::app()->db->createCommand($sql);
			$command->execute();
		}
		else
		{
			$sql = "update tbl_event_stat set send_stat = ".$select." and last_date = '".date('Y-m-d')."' where user_id = ".Yii::app()->user->id." and event_id = ' ".$event_id." ' ";
			$command = Yii::app()->db->createCommand($sql);
			$command->execute();
		}
		
		//генерируем и отправляем письмо
		if($select!=0)
		{
			$this->SendMail($event_id, Yii::app()->user->id);
		}
	}
}