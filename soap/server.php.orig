<?php
/**
 * На этот скрипт приходят уведомления от QIWI Кошелька.
 * SoapServer парсит входящий SOAP-запрос, извлекает значения тегов login, password, txn, status,
 * помещает их в объект класса Param и вызывает функцию updateBill объекта класса TestServer.
 *
 * Логика обработки магазином уведомления должна быть в updateBill.
 */
define('HTTP_HOST','showcode.ru');
define('LOGIN', 16029);
define('PASSWORD', 'Dfm3rnn7snnbbd?as');

require_once "sms24x7.php";
define('EMAIL_SMS', 'rubtsov@complexsys.ru');
define('PASSWORD_SMS', 'MoZBdJsXG8');

define('NAME', 'showcode.ru');


$link = mysql_connect('mysql.showco01.mass.hc.ru','showco01','pepsi1');
mysql_query("SET NAMES 'utf8'");
mysql_select_db('wwwshowcoderu',$link) or die('Ошибка подключения к Базе Данных');


$s = new SoapServer('IShopClientWS.wsdl', array('classmap' => array('tns:updateBill' => 'Param', 'tns:updateBillResponse' => 'Response')));
$s->setClass('TestServer');
$s->handle();

class Response
{
	public $updateBillResult;
}

class Param
{
	public $login;
	public $password;
	public $txn;
	public $status;
}
/**
*	СТАТУСЫ:
*	прошёл: 60
*	отклонён: 150
*	не прошёл: 160
*	Время жизни истекло: 161
*/

class TestServer
{
	function updateBill($param)
	{

		// Выводим все принятые параметры в качестве примера и для отладки
		/*
		$f = fopen('c:\\phpdump.txt', 'w');
		fwrite($f, $param->login); 			//16029
		fwrite($f, ', ');
		fwrite($f, $param->password);		// пустой
		fwrite($f, ', ');
		fwrite($f, $param->txn);			//мой uniq
		fwrite($f, ', ');
		fwrite($f, $param->status);
		fclose($f);
		*/
		// проверить password, login
		$error=false;
		$type_ticket = array(
			'disposable'=>'Одноразовый',
			'reusable'=>'Многоразовый',
			'travel'=>'Проездной',
			'free'=>'Бесплатный',
			);

			// заказ оплачен
		if ($param->status == 60)
		{
			$logs = mysql_query('select * from tbl_transaction_log where status=0 and uniq="' .$param->txn. '"');
			if (mysql_num_rows($logs))
			{
				$log=mysql_fetch_array($logs);
                                $user = mysql_fetch_array(mysql_query('select phone from tbl_user where user_id="' .$log['user_id']. '"'));
					//Если пользователь оплатил меньше, чем нужно отменяем заказ
				//if (checkStatusReady($log['mail'], $param->txn, $param) == 0){
                                    mysql_query('update tbl_transaction_log set status=1 where status=0 and uniq="' .$param->txn. '"');
                                    if(empty($user)){
                                            $phone = $logs['phone'];
                                    }else{
                                            $phone = $user['phone'];
                                    }

                                            //Отправляем пользователю смс.
                                    if (isset($phone))
                                    {
                                            $message = 'Ваш билет находится здесь:' .PHP_EOL. 'http://' .HTTP_HOST. '/ticket/view/' .$log['uniq']. '?preview';
                                            $r = smsapi_push_msg_nologin(EMAIL_SMS, PASSWORD_SMS, $phone, $message, array("unicode"=>"1"));
                                            /*
                                            if ($r[0]=='0')
                                                    echo 'Сообщение отправлено';
                                            else
                                                    echo 'ошибка: ' .$r[0];
                                            */
                                    }
                                            //Отправляем письмо с билетами на почту...
                                    $event = mysql_fetch_array(mysql_query('select id,title,description,datetime, address from tbl_events where id="' .$log['event_id']. '"'));
                                    $eventUniq = mysql_fetch_array(mysql_query('select phone, time_work from tbl_event_uniq where event_id="' .$log['event_id']. '"'));
                                    $title = 'Билеты:' .$event['title'];

                                    $text = '';
                                    $text = $text.'<table cellspasing="0" border="0" cellpadding="0" width="100%" style="background-color:#dadada; border-collapse: collapse; border-spacing:0;">';
                                    $text = $text.'<tr>';
                                    $text = $text.'<td height="20"></td>';
                                    $text = $text.'</tr>';
                                    $text = $text.'<tr>';
                                    $text = $text.'<td align="center">';
                                    $text = $text.'<table cellspasing="0" border="0" cellpadding="0" height="460px" width="728px" style="margin: 0pt; padding:0; background-color: rgb(255, 255, 255); border-collapse: collapse; border-spacing:0;">';
                                    $text = $text.'<tr>';
                                    $text = $text.'<td style="background-image:url(http://' .$_SERVER['HTTP_HOST']. '/images/email/content_bg.png); background-repeat:no-repeat; background-position:left top; padding-top: 0px; padding-right:0; padding-bottom:0; padding-left: 21px;">';
                                    $text = $text.'<table cellspasing="0" border="0" cellpadding="0" width="698px" style="margin: 0pt; padding: 0pt; background-color: rgb(255, 255, 255); border-collapse: collapse; border-spacing:0;">';
                                    $text = $text.'<tr>';
                                    $text = $text.'<td colspan="2"><img src="http://' .$_SERVER['HTTP_HOST']. '/images/email/logo_thank_you.jpg" alt="Showcode. Спасибо за покупку." title="Showcode. Спасибо за покупку." style="margin: 0pt; padding: 0pt; border: 0pt none; display: block;"></td>';
                                    $text = $text.'</tr>';
                                    $text = $text.'<tr>';
                                    if(!empty($eventUniq))
                                        $text = $text.'<td colspan="2"><div style="height: 30px;"><p style="font-family:Arial, Helvetica, sans-serif; font-size:24px; font-weight:normal; font-style:normal; color:#333; padding-bottom: 10px;">Здравствуйте, '.$log['family'].'.</p></div></td>';
                                    else
                                        $text = $text.'<td colspan="2"><div style="height: 40px;"><p style="font-family:Arial, Helvetica, sans-serif; font-size:24px; font-weight:normal; font-style:normal; color:#333; padding-bottom: 10px;">Здравствуйте, '.$log['family'].'.</p></div></td>';
                                    $text = $text.'</tr>';
                                    $text = $text.'<tr>';
                                    if(!empty($eventUniq)){
                                        $text = $text.'<td>';
                                        $text = $text.'<table cellspasing="0" border="0" cellpadding="0" width="" style="margin: 0pt; padding: 20px 0; background-color: rgb(255, 255, 255); border-collapse: collapse;">';
                                        $text = $text.'<tr>';
                                        $text = $text.'<td width="120px">';
                                        $text = $text.'<img src="http://' .HTTP_HOST. '/images/email/zoo/zoo-logo.png" alt="' .$event['title']. '" title="' .$event['title']. '" style="margin: 0pt; padding: 0pt; border: 0pt none; display: block; border:1px #cccccc solid;">';
                                        $text = $text.'</td>';
                                        $text = $text.'<td style="background-color:#30aabc; width:419px">';
                                        $text = $text.'<p style="padding:0 10px 0 20px; font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:normal; font-style:normal; color:#fff; line-height:18px;"><b>Вы купили билет</b> в «' .$event['title']. '»<br />';
                                        $text = $text.'Часы работы: '.$eventUniq['time_work'].'<br />';
                                        $text = $text.'Адрес: '.$event['address'].'<br />';
                                        $text = $text.'Тел.: '.$eventUniq['phone'].'</p>';
                                        $text = $text.'</td>';
                                        $text = $text.'<td style="background-color:#30aabc; vertical-align:bottom;">';
                                        $text = $text.'<img style="vertical-align:bottom;" src="http://' .HTTP_HOST. '/images/email/zoo/bird_top_part.png" alt="" />';
                                        $text = $text.'</td>';
                                        $text = $text.'</tr>';
                                        $text = $text.'<tr>';
                                        $text = $text.'<td><img style="vertical-align:top;" src="http://' .HTTP_HOST. '/images/email/zoo/bird_bottom_part_empty.png" alt=""/></td>';
                                        $text = $text.'<td><img style="vertical-align:top;" src="http://' .HTTP_HOST. '/images/email/zoo/bird_bottom_part_empty.png" alt=""/></td>';
                                        $text = $text.'<td>';
                                        $text = $text.'<img style="vertical-align:top;" src="http://' .HTTP_HOST. '/images/email/zoo/bird_bottom_part.png" alt=""/>';
                                        $text = $text.'</td>';
                                        $text = $text.'</tr>';
                                        $text = $text.'</table>';
                                        $text = $text.'</td>';
                                    }else{
                                        $text = $text.'<td style="background-color:#e5e5e5;"><p style=" font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; color:#333;line-height: 18px;padding-left: 10px;padding-right: 10px;margin-top: 10px;margin-bottom: 10px;">Вы купили билет на мероприятие под названием «<a target="_blank" title="' .$event['title']. '" href="http://' .$_SERVER['HTTP_HOST']. '/events/view/' .$event['id']. '">' .$event['title']. '</a>», которое состоится <b>';
                                        if($log['type'] == 'travel'){
                                                $text = $text.''.normalViewDate($ticket['date_begin']).' года - ';
                                                $text = $text.''.normalViewDate($ticket['date_end']).'</td></tr> ';
                                        }else{
                                                $text = $text.''. normalViewDate($event['datetime']) .' года ';
                                        }

                                        if($ticket['time_begin']){
                                                $text = $text.'(начало в '. $ticket->time_begin;
                                        }else{
                                                $text = $text.'(начало в '. normalViewTime($event['datetime']);
                                        }
                                        if($ticket['time_end']){
                                                $text = $text.', окончание в '. $ticket->time_end;
                                        }
                                        $text = $text.'). ';

                                        if (isset($log['column']) && isset($log['place']))
                                        {
                                                $text = $text.'Ваш рад №' .$log['column']. ', место ' .$log['place'];
                                        }
                                        $text = $text.'</b>.</p></td>';
                                        $text = $text.'<td style="padding-right: 10px;">&nbsp;</td>';
                                    }

                                    $text = $text.'</tr>';
                                    $text = $text.'<tr>';
                                    if(!empty($eventUniq)){
                                        $text = $text.'<td colspan="2" style="padding-top: 7px;   padding-bottom: 0;padding-right: 10px;">';
                                        $text = $text.'<table cellspasing="0" border="0" cellpadding="5" width="" style="margin: 0pt; padding: 0; background-color: rgb(255, 255, 255); border-collapse: collapse;">';
                                    }else{
                                        $text = $text.'<td colspan="2" style="padding-top: 7px;   padding-bottom: 17px;padding-right: 10px;">';
                                        $text = $text.'<table cellspasing="0" border="0" cellpadding="5" width="" style="margin: 0pt; padding: 20px 0; background-color: rgb(255, 255, 255); border-collapse: collapse;">';
                                    }
                                    $text = $text.'<tr>';
                                    if(!empty($eventUniq))
                                        $text = $text.'<td rowspan="3" width="120px"><a href="http://' .$_SERVER['HTTP_HOST']. '/ticket/view/' .$log['uniq']. '" target="_blank" title=""><img src="http://' .$_SERVER['HTTP_HOST']. '' .$log['qr']. '" alt="' .$event['title']. '" width="120" style="margin: 0pt; padding: 0pt; border: 0pt none; display: block; border:1px #cccccc solid;"></a></td>';
                                    else
                                        $text = $text.'<td rowspan="3" width="120px"><a href="http://' .$_SERVER['HTTP_HOST']. '/ticket/view/' .$log['uniq']. '" target="_blank" title=""><img src="http://' .$_SERVER['HTTP_HOST']. '' .$log['qr']. '" alt="' .$event['title']. '" style="margin: 0pt; padding: 0pt; border: 0pt none; display: block; border:1px #cccccc solid;"></a></td>';
                                    $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; color:#000;">Номер билета:</td>';
                                    $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal;">' .$log['uniq']. '</td>';
                                    $text = $text.'</tr>';
                                    $text = $text.'<tr>';
                                    $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; color:#000;">Ссылка на билет:</td>';
                                    $text = $text.'<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; text-decoration:underline; color:#0000ff;"><a target="_blank" href="http://' .HTTP_HOST. '/ticket/view/' .$log['uniq']. '" title="Здесь вы можете просмотреть статус покупки">' .HTTP_HOST. '/ticket/view/' .$log['uniq']. '</a></td>';
                                    $text = $text.'</tr>';
                                    $text = $text.'<tr>';
                                    $text = $text.'<td colspan="2" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:normal; font-style:normal; color:#999;">';
                                    $text = $text.'Попасть на мероприятие вы сможете:';
                                    $text = $text.'<ul style="list-style:none; margin:0pt; padding-top: 3px;padding-right: 0;padding-bottom: 0;padding-left: 0;">';
                                    $text = $text.'<li>&mdash; распечастав изображение данного QR-кода на бумаге;</li>';
                                    $text = $text.'<li>&mdash; показав изображение на экране любого цифрового носителя.</li>';
                                    $text = $text.'</ul>';
                                    $text = $text.'</td>';
                                    $text = $text.'</tr>';
                                    $text = $text.'</table>';
                                    $text = $text.'</td>';
                                    $text = $text.'</tr>';
                                    $text = $text.'<tr>';
                                    $text = $text.'<td style="padding-top: 10px; border-top-color: #999; border-top-style: solid; border-top-width: 1px;"><p style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; color:#333;line-height: 0;">С уважением, администрация сайта <a target="_blank" href="' .$_SERVER['HTTP_HOST']. '" title="">ShowCode.ru</a>. Ждём вас снова!</p></td>';
                                    $text = $text.'<td style="padding-right: 10px;">&nbsp;</td>';
                                    $text = $text.'</tr>';
                                    $text = $text.'</table>';
                                    $text = $text.'</td>';
                                    $text = $text.'</tr>';
                                    $text = $text.'</table>';
                                    $text = $text.'</td>';
                                    $text = $text.'</tr>';
                                    $text = $text.'<tr>';
                                    $text = $text.'<td height="20"></td>';
                                    $text = $text.'</tr>';
                                    $text = $text.'</table>';

                                    //E-mail от кого отправлено письмо
                                    //$fromMail = $Booking_Time = Control::model()->find("name = 'fromMail'")->value;
                                    $fromMail = 'noreply@'.HTTP_HOST;
                                    if($log['mail'])
                                            mail_html ($log['mail'],$fromMail,NAME,$text,$title);
				//}else
                                    //$error=true;
			}
			else
				$error=true;
		}
		else if ($param->status > 100)
		{
			// заказ не оплачен (отменен пользователем, недостаточно средств на балансе и т.п.)
                        if ($param->status >= 160)
                            cancelOrder($param->txn);
                        else{
                            $control = mysql_fetch_array(mysql_query('select value from tbl_control where name="Admin_Email"'));
                            $title = 'Билеты. '.'Qiwi: отклонена операция';
                            $fromMail = 'noreply@'.HTTP_HOST;
                            $TeXt = getTextEmail("Оплата отменена.");
                            mail_html ($log['mail'],$fromMail,NAME,$TeXt,$title);
                        }
		}
		else if ($param->status >= 50 && $param->status < 60) {
			// счет в процессе проведения
		} else {
			// неизвестный статус заказа
		}

		// формируем ответ на уведомление
		// если все операции по обновлению статуса заказа в магазине прошли успешно, отвечаем кодом 0
		// $temp->updateBillResult = 0
		// если произошли временные ошибки (например, недоступность БД), отвечаем ненулевым кодом
		// в этом случае QIWI Кошелёк будет периодически посылать повторные уведомления пока не получит код 0
		// или не пройдет 24 часа
		$temp = new Response();
		if ($error)
			$temp->updateBillResult = 1;
		else
			$temp->updateBillResult = 0;
		return $temp;
	}
}

//Проверяем состояние готовности Qiwi.
    function checkStatusReady($mail, $txn_id, $param){
	include("IShopServerWSService.php");
	$service = new IShopServerWSService('IShopServerWS.wsdl', array('location' => 'http://ishop.qiwi.ru/services/ishop', 'trace' => TRACE));

	$params = new checkBill();
	$params->login = LOGIN;
	$params->password = PASSWORD;
	$params->txn = $txn_id;

	$res = $service->checkBill($params);

        $status = abs($res->status);

        if($param->status != 0){
            $control = mysql_fetch_array(mysql_query('select value from tbl_control where name="Admin_Email"'));
            switch ($param->status){
                case 13:
                    $mess = 'Сервер занят, повторите запрос позже';
                    $messAdmin = $mess;
                    break;
                case 150:
                    $messAdmin = 'Ошибка авторизации (неверный логин/пароль)';
                    break;
                case 210:
                    $mess = 'Счет не найден';
                    $messAdmin = $mess;
                    break;
                case 215:
                    $messAdmin = 'Счет с таким txn-id уже существует';
                    break;
                case 241:
                    $mess = 'Сумма слишком мала';
                    $messAdmin = $mess;
                    break;
                case 242:
                    $mess = 'Превышена максимальная сумма платежа – 15 000р';
                    $messAdmin = $mess;
                    break;
                case 278:
                    $messAdmin = 'Превышение максимального интервала получения списка счетов';
                    break;
                case 298:
                    $mess = 'Агента не существует в системе';
                    $messAdmin = $mess;
                    break;
                case 300:
                    $messAdmin = 'Неизвестная ошибка';
                    break;
                case 330:
                    $messAdmin = 'Ошибка шифрования';
                    break;
                case 370:
                    $mess = 'Превышено максимальное кол-во одновременно выполняемых запросов';
                    $messAdmin = $mess;
                    break;
				default:
					$mess = 'Status '.$status;
                    $messAdmin = $mess;
					break;
            }
            $title = 'Билеты. '.'Qiwi: отклонена операция';
            $fromMail = 'noreply@'.HTTP_HOST;
            if($mess){
                $TeXt = getTextEmail($mess);
                mail_html ($mail,$fromMail,NAME,$TeXt,$title);
            }
            $TeXt = getTextEmail($messAdmin);
            mail_html ($control['value'],$fromMail,NAME,$TeXt,$title);
        }        
		mysql_query('INSERT INTO tbl_qiwi_log ("STATUS","TICKET","PAY","STATUSOPP") VALUES ("'.$param->status.'","'.$param->txn.'","'.$res->amount.'","'.$status.'")');

	return $status;
}

    //отправка почты и смс
    function getTextEmail($messenge){
        $text = '';
        $text = $text.'<table cellspasing="0" border="0" cellpadding="0" width="697px" style="margin: 0pt; padding: 0pt; background-color: rgb(255, 255, 255); border-collapse: collapse;">';
        $text = $text.'<tr height="138px">';
        $text = $text.'<td><img src="http://' .$_SERVER['HTTP_HOST']. '/images/email/logo_empty.jpg" alt="Showcode" title="Showcode." style="margin: 0pt; padding: 0pt; border: 0pt none; display: block;"></td>';
        $text = $text.'</tr>';
        $text = $text.'<tr height="41px">';
        $text = $text.'<td><p style="font-family:Arial, Helvetica, sans-serif; font-size:24px; font-weight:normal; font-style:normal; color:#333;">Здравствуйте.</p></td>';
        $text = $text.'</tr>';
        $text = $text.'<tr>';
        $text = $text.'<td>При запросе к сервису QIWI, возратилась ошибка.';
        $text = $text.'</td>';
        $text = $text.'</tr>';
        $text = $text.'<tr>';
        $text = $text.'<td style="background-color:#e5e5e5;"><p style="padding:16px 10px; font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; color:#333;">';
        $text = $text.$messenge;
        $text = $text.'.</p></td>';
        $text = $text.'</tr>';

        $text = $text.'<tr>';
        $text = $text.'<td style="padding-top:5px;"><p style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; color:#333;">С уважением, администрация сайта <a target="_blank" href="' .HTTP_HOST. '" title="">ShowCode.ru</a>. Ждём вас снова!</p></td>';
        $text = $text.'</tr>';
        $text = $text.'</table>';

        return $text;
    }

	//Возвращает сумму, на которую был выставлен счёт.
function checkBill($txn_id)
{
	include("IShopServerWSService.php");
	$service = new IShopServerWSService('IShopServerWS.wsdl', array('location' => 'http://ishop.qiwi.ru/services/ishop', 'trace' => TRACE));

	$params = new checkBill();
	$params->login = LOGIN;
	$params->password = PASSWORD;
	$params->txn = $txn_id;

	$res = $service->checkBill($params);

	return $res->amount;
}


function cancelOrder($uniq)
{
	$logs = mysql_query('select * from tbl_transaction_log where status=0 and uniq="' .$uniq. '"');
	if (mysql_num_rows($logs))
	{
		$log = mysql_fetch_array($logs);
		mysql_query('update tbl_transaction_log set status=2 where log_id="' .$log['log_id']. '"');
			//плюсуем не купленные билеты обратно
		$ticket = mysql_fetch_array(mysql_query('select quantity, ticket_id from tbl_tickets where event_id="' .$log['event_id']. '" and type="' .$log['type']. '"'));
		$quantity = $ticket['quantity'] + $log['quantity'];
		mysql_query('update tbl_tickets set quantity="' .$quantity. '" where ticket_id="' .$ticket['ticket_id']. '"');

			//Отправляем письмо на почту, что бронь аннулирована...
		$event = mysql_fetch_array(mysql_query('select id,title from tbl_events where id="' .$log['event_id']. '"'));
		$title = 'Бронь аннулирована';
		$text='Здравствуйте, ' .$log['family']. '<br>Ваша бронь на мероприятие: <a href="http://' .HTTP_HOST. '/events/view/' .$event['id']. '">' .$event['title']. '</a> аннулирована! <br /> <br />
		<a href="http://' .HTTP_HOST. '/ticket/view/' .$log['uniq']. '">Здесь</a> вы можете просмотреть информацию по брони.';

		//E-mail от кого отправлено письмо
		//$fromMail = $Booking_Time = Control::model()->find("name = 'fromMail'")->value;
		$fromMail = 'noreply@'.HTTP_HOST;
		if($log['mail'])
			mail_html ($log['mail'],$fromMail,NAME,$text,$title);
	}
}

////////////////////////////////////////////////////////////////////////////////////////////
// Отправка почты в ХТМЛ формате в win-1251
// http://ru.php.net/manual/en/ref.mail.php
// jcwebb at dicoe dot com
function mail_html($to_addr,$from_addr,$from_name,$message,$title){
	$eol="\n"; // unix only

	if(!$from_name){
		$from_name=$from_addr;
	}
	$returnPath = '-f'.$from_addr;
	$title = iconv("utf-8","Windows-1251",$title);
	$title= "=?Windows-1251?B?".base64_encode($title)."?=";

	# Common Headers
	$headers .= "From: {$from_name} <{$from_addr}>".$eol;
	$headers .= "Reply-To: {$from_name} <{$from_addr}>".$eol;
	$headers .= "Return-Path: <{$from_addr}>".$eol;
	# Boundary for marking the split & Multitype Headers
	$mime_boundary=md5(time());
	$headers .= 'MIME-Version: 1.0'.$eol;
	$headers .= "Content-Type: text/html; charset=windows-1251".$eol;
	$headers .= "Content-Transfer-Encoding: 8bit".$eol;
	$msg = "";
	$from_name = "=?Windows-1251?B?".base64_encode($from_name)."?=";

	# SEND THE EMAIL
	$headers = iconv("utf-8","Windows-1251",$headers);
	$message = iconv("utf-8","Windows-1251",$message);

	mail($to_addr, $title, $message, $headers, $returnPath);
}

/**
	 * Функция преобразовывает дату в ЧеловекоПонятный формат.
	 * Сама определяет дата со временем или нет.
	 * @param string $datetime дата формата mysql.
	 * @return string ЧеловекоПонятная дата.
	 */
	function normalViewDate($datetime)
	{
		list($date,$time) = explode(' ',$datetime);
		list($y,$m,$d) = explode('-',$date);
		if (isset($d))
		{
			return $d.'.'.$m.'.'.$y;
		}
		else
			return $event->datetime;

	}

	function normalViewTime($datetime)
	{
		list($date,$time) = explode(' ',$datetime);

		if (isset($time)){
                        list($h,$min,$sec) = explode(':',$time);
                        return $h.':'.$min;
                }
                else
                        return 'В любое время';
	}
        /*$param = Array('login' => '16029','password' => 'Dfm3rnn7snnbbd?as','txn' => '49e9b0dd','status' => '60');
        print_r(TestServer::updateBill($param));*/
?>
