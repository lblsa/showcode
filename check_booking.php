#!/usr/local/apache/bin/php-cgi.5
<?php
// Скрипт меняет статус у "устаревшего" лога, посылает qiwi запрос на отмену и плюсует не купленные билеты обратно.
define('PATH', dirname(__FILE__) );
include_once(PATH. '/soap/IShopServerWSService.php');
define('LOGIN', 16029);
define('PASSWORD', 'Dfm3rnn7snnbbd?as');
define('TRACE', 1);				// просмотр SOAP-запросов/ответов (для отладки)
define('HTTP_HOST','showcode.ru');


function checkBill($txn_id)
{
	$service = new IShopServerWSService(PATH. '/soap/IShopServerWS.wsdl', array('location'      => 'http://ishop.qiwi.ru/services/ishop', 'trace' => TRACE));

	$params = new checkBill();
	$params->login = LOGIN;
	$params->password = PASSWORD;
	$params->txn = $txn_id;

	$res = $service->checkBill($params);

	return $res->status;
}
function cancelBill($txn_id)
{
	$service = new IShopServerWSService(PATH. '/soap/IShopServerWS.wsdl', array('location'      => 'http://ishop.qiwi.ru/services/ishop', 'trace' => TRACE));

	$params = new cancelBill();
	$params->login = LOGIN;
	$params->password = PASSWORD;
	$params->txn = $txn_id;

	$res = $service->cancelBill($params);

	return $res->cancelBillResult;
}
/*
 * Отправка почты в ХТМЛ формате в win-1251
 * http://ru.php.net/manual/en/ref.mail.php
 * jcwebb at dicoe dot com
 */
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

$link = mysql_connect('mysql.showco01.mass.hc.ru','showco01','pepsi1');
mysql_select_db('wwwshowcoderu',$link) or die('Ошибка подключения к Базе Данных');
mysql_query("SET NAMES 'utf8'");
$hour = mysql_query('select value from tbl_control where name="Booking_Time"');
$hour = mysql_fetch_array($hour);

$date =  date('Y.m.d ');
$date.=(date('H')) - ($hour['value']);
$date.=date(':i:s');

$type_ticket = array(
	'disposable'=>'Одноразовый',
	'reusable'=>'Многоразовый',
	'travel'=>'Проездной',
	'free'=>'Бесплатный',
	);

$logs = mysql_query('select * from tbl_transaction_log where status=0 and datetime < "' .$date. '"');
for($i=0;$i<mysql_num_rows($logs);$i++)
{
	$log = mysql_fetch_array($logs);
	if ($log['payment'] == 'qiwi' && checkBill($log['uniq'])==50 && cancelBill($log['uniq'])==0 || checkBill($log['uniq'])==-210)
	{
		mysql_query('update tbl_transaction_log set status=2 where log_id="' .$log['log_id']. '"');
			//плюсуем не купленные билеты обратно
		$ticket = mysql_fetch_array(mysql_query('select quantity, ticket_id from tbl_tickets where event_id="' .$log['event_id']. '" and type="' .$log['type']. '"'));
		$quantity = $ticket['quantity'] + $log['quantity'];
		mysql_query('update tbl_tickets set quantity="' .$quantity. '" where ticket_id="' .$ticket['ticket_id']. '"');

			//Отправляем письмо на почту, что бронь анулирована...
		$event = mysql_fetch_array(mysql_query('select id,title from tbl_events where id="' .$log['event_id']. '"'));
		$title = 'Бронь анулирована';

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
        $text = $text.'<td colspan="2"><img src="http://' .$_SERVER['HTTP_HOST']. '/images/email/logo_empty.jpg" alt="Showcode." title="Showcode." style="margin: 0pt; padding: 0pt; border: 0pt none; display: block;"></td>';
        $text = $text.'</tr>';
        $text = $text.'<tr>';
        $text = $text.'<td colspan="2"><div style="height: 40px;"><p style="font-family:Arial, Helvetica, sans-serif; font-size:24px; font-weight:normal; font-style:normal; color:#333; padding-bottom: 10px;">Здравствуйте, '.$log['family'].'.</p></div></td>';
        $text = $text.'</tr>';
        $text = $text.'<tr>';
        $text = $text.'<td style="background-color:#e5e5e5;"><p style=" font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; color:#333;line-height: 18px;padding-left: 10px;padding-right: 10px;margin-top: 10px;margin-bottom: 10px;">Ваша бронь на мероприятие: <a href="http://' .HTTP_HOST. '/events/view/' .$event['id']. '">' .$event['title']. '</a> анулирована!</p></td>';
        $text = $text.'<td style="padding-right: 10px;">&nbsp;</td>';
        $text = $text.'</tr>';
        $text = $text.'<tr>';
        $text = $text.'<td colspan="2" style="padding-top: 7px;   padding-bottom: 17px;padding-right: 10px;">';
        $text = $text.'<table cellspasing="0" border="0" cellpadding="5" width="" style="margin: 0pt; padding: 20px 0; background-color: rgb(255, 255, 255); border-collapse: collapse;">';
        $text = $text.'<tr style="border: 1px solid #000;">';
            $text = $text.'<td style="background-color:#e5e5e5;"><p style="padding:16px 10px; font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; color:#333;"><a href="http://' .HTTP_HOST. '/transactionLog/view/' .$log['uniq']. '">Здесь</a> вы можете просмотреть информацию по брони.';
            $text = $text.'</p></td>';
        $text = $text.'</tr>';

        $text = $text.'</table>';
        $text = $text.'</td>';
        $text = $text.'</tr>';
        $text = $text.'<tr>';
        $text .= '<td style="padding-top: 10px; border-top-color: #999; border-top-style: solid; border-top-width: 1px;"><p style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; font-style:normal; color:#333;line-height: 0;">С уважением, администрация сайта <a target="_blank" href="' .$_SERVER['HTTP_HOST']. '" title="">ShowCode.ru</a>.</p></td>';
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
		if($log['mail']){
			$fromMail = 'noreply@'.HTTP_HOST;
			mail_html ($log['mail'],$fromMail,'ShowCode',$text,$title);
		}
	}else if($log['payment'] == 'credit_card'){
            mysql_query('update tbl_transaction_log set status=2 where log_id="' .$log['log_id']. '"');

            $event_uniq = mysql_fetch_array(mysql_query('select * from tbl_event_uniq where event_id="' .$log['event_id']. '"'));
                    //плюсуем не купленные билеты обратно
            if(!$event_uniq['infinity_qantitty'] ){
                $ticket = mysql_fetch_array(mysql_query('select quantity, ticket_id from tbl_tickets where event_id="' .$log['event_id']. '" and type="' .$log['type']. '"'));
                $quantity = $ticket['quantity'] + $log['quantity'];
                mysql_query('update tbl_tickets set quantity="' .$quantity. '" where ticket_id="' .$ticket['ticket_id']. '"');
            }
        }
}
?>