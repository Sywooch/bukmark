<?php

$params = require(__DIR__ . '/params.php');

$config = [
	'id' => 'basic',
	'name' => 'bukmark',
	'language' => 'es',
	'basePath' => dirname(__DIR__),
	'bootstrap' => ['log'],
	'components' => [
		'urlManager' => [
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			// Add rule for PDF estimate
			'rules' => [
				[
					'pattern' => 'estimate/get-pdf/<id:\d+>',
					'route' => 'estimate/get-pdf',
					'suffix' => '.pdf',
				]
			],
		],
		'request' => [
			// !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
			'cookieValidationKey' => 'UAdA1kGsFyylOBUxl4c9i8qlXvuTJpwG',
		],
		'cache' => [
			'class' => 'yii\caching\FileCache',
		],
		'user' => [
			'identityClass' => 'app\models\User',
			'enableAutoLogin' => true,
		],
		'errorHandler' => [
			'errorAction' => 'site/error',
		],
		'mailer' => [
			'class' => 'yii\swiftmailer\Mailer',
			// send all mails to a file by default. You have to set
			// 'useFileTransport' to false and configure a transport
			// for the mailer to send real emails.
			'useFileTransport' => true,
		],
		'log' => [
			'traceLevel' => YII_DEBUG ? 3 : 0,
			'targets' => [
				[
					'class' => 'yii\log\FileTarget',
					'levels' => ['error', 'warning'],
				],
			],
		],
		'db' => require(__DIR__ . '/db.php'),
		'formatter' => [
			'class' => 'yii\i18n\Formatter',
			'thousandSeparator' => '',
			'decimalSeparator' => ',',
			'dateFormat' => 'dd/MM/yyyy',
			'nullDisplay' => '',
		],
	],
	'modules' => [
		'gridview' =>  [
			'class' => '\kartik\grid\Module',
		],
	],
	'params' => $params,
	'aliases' => [
		'@images' => '@app/web/images',
	],
];

if (YII_ENV_DEV) {
	// configuration adjustments for 'dev' environment
	$config['bootstrap'][] = 'debug';
	$config['modules']['debug'] = [
		'class' => 'yii\debug\Module',
		'allowedIPs' => ['*']
	];

	$config['bootstrap'][] = 'gii';
	$config['modules']['gii'] = [
		'class' => 'yii\gii\Module',
		'allowedIPs' => ['*'] // adjust this to your needs
	];
	// publish files even there are published before
	$config['components']['assetManager']['forceCopy'] = true;
}

// Set the class for the grid-view action column
\Yii::$container->set('yii\grid\ActionColumn', [
	'contentOptions' => [
		'class' => 'action-column',
	]
]);

// Change the padding of the grid-view data cells
$contentOptions = ['style' => 'padding: 2px'];
\Yii::$container->set('yii\grid\DataColumn', [
	'contentOptions' => $contentOptions,
]);
\Yii::$container->set('yii\grid\ActionColumn', [
	'contentOptions' => $contentOptions,
]);
\Yii::$container->set('kartik\grid\DataColumn', [
	'contentOptions' => $contentOptions,
]);
\Yii::$container->set('kartik\grid\EditableColumn', [
	'contentOptions' => $contentOptions,
]);

return $config;
