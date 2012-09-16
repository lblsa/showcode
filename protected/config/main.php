<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'showcode.ru',
	'sourceLanguage' => 'ru',
    'language' => 'ru',

	// preloading 'log' component
	'preload'=>array('log'),
	
	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
		'isWeb'=>'1',							//Если isWeb==0, то приложение тестируется на локальной машине.
		'qiwiLogin'=>'16029',
		'qiwiPass'=>'Dfm3rnn7snnbbd?as',
		'vk_id'=>'2647518',						//ID API Контакта
		'vk_code'=>'PiWbuy2FQaYwkhItsI32',		//секретный ключ для API контакта
		'face_id'=>'275201229183713',			//ID APP Facebook
		'face_code'=>'84e772b9e26cf9281d3aad8576782cd2',		//секретный ключ для API Facebook
		'access_token'=>'275201229183713|uEhhNWS_ehHCWdrJ6tWidr55xXk',	// Access_token для доступа к API graph.facebook.com
		//Получение: $access_token = file_get_contents('https://graph.facebook.com/oauth/access_token?client_id=' .Yii::app()->params['face_id']. '&client_secret=' .Yii::app()->params['face_code']. '&grant_type=client_credentials');
                'bank_access_code' => '62249AED',   //Accees Code for bank payment client
                'bank_merchant_id' => '9293469573',   //Merchant ID for bank payment client
                'bank_secure_hash_secret' => 'AABAB6935EF02D2AD57D941C56EA91F5',   //Hash Code for Bank Payment Client

	),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool

		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'0000',
		 	// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('***.***.***.***','::1'),
		),

	),
	
	// application components
	'components'=>array(
		'request'=>array(
                //'enableCsrfValidation'=>true,
                'enableCookieValidation'=>true,
            ),
		'user'=>array(
			'class' => 'WebUser',
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),

		'ih'=>array(
			'class'=>'CImageHandler',
		),
		
		// Подключение моих функций для использования.
		'mf'=>array(
			'class'=>'MyFunctions',
		),
		
		'clientScript' => array(
			'scriptMap' => array(
				'jquery.js' => false,
		)),
			
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules'=>array(
				'ticket' => 'transactionLog',
				'ticket/<action:(view|admin|getQrCodeTicket|delete)>/<id:[\w\d]+>' => 'transactionLog/<action>',
				'ticket/<action:\w+>' => 'transactionLog/<action>',
			
				'feedback' => 'contacts',
				'feedback/<action:(view|admin)>/<id:[\w\d]+>' => 'contacts/<action>',
				'feedback/<action:\w+>' => 'contacts/<action>',
					
				'<controller:\w+>/<action:\w+>/<id:[\w\d]+>'=>'<controller>/<action>',
				'page/<view:\w+>' => 'site/page'
				//'<_c:(events|user)>/<id:[\w\d]+>' => '<_c>/view',
				//'<_c:(events|user)>' => '<_c>/list',
				//'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				//'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		/*
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
		*/
		// uncomment the following to use a MySQL database
		'db'=>array(
			'connectionString' => 'mysql:host=eu-cdbr-azure-west-a.cloudapp.net;dbname=showcode',
			'emulatePrepare' => true,
			// Set the charset of the connection
			'charset' => 'utf8',
			// Save null instead of empty strings
			'nullConversion' => PDO::NULL_EMPTY_STRING,
			// Cache queries
			'schemaCachingDuration' => 1000,
			'username' => 'b9b78b6b8334cf',
			'password' => '7342842c',
			//'username' => 'root',
			//'password' => '',
			'tablePrefix' => 'tbl_',
		),

		'errorHandler'=>array(
			// use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),
);
