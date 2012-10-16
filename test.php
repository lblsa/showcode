<?php

// change the following paths if necessary
$yii=dirname(__FILE__).'/yii/framework/yiilite.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
//defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);



include_once 'protected/components/MyFunctions.php';
$mf = new MyFunctions;
$session=new CHttpSession;
$session->open();

	//Если пользователь зашёл с мобилы:
if (!$session['siteType'] && $mf->isMobile())
{
	$session['siteType']='mobile';
}
if ($_GET['type']=='mobile')
{
	$session['siteType']='mobile';
	/*$request = explode ('?',$_SERVER['REQUEST_URI']);
	header('location: ' .$request[0]);*/
        header('location: /');
}
if ($_GET['type']=='site')
{
	$session['siteType']='site';
	/*$request = explode ('?',$_SERVER['REQUEST_URI']);
	header('location: ' .$request[0]);*/
        header('location: /');
}



Yii::createWebApplication($config)->run();
