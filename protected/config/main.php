<?php
return array(
	'theme' => 'cerulean',
	'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
	'name' => 'Capella GEM',
	'preload' => array(),
	'import' => array(
		'application.models.*',
		'application.components.*',
		'application.modules.admin.models.*',
		'ext.fpdf.*',
		'ext.tcpdf.*',
		'ext.yii-easyui.web.*',
		'ext.yii-easyui.widgets.*'
	),
	'modules' => array(
		'admin' => array(),
		'common' => array(),
		'accounting' => array(),
		'inventory' => array(),
		'purchasing' => array(),
		'production' => array(),
		'project' => array(),
		'hr' => array(),
		'order' => array(),
		'api' => array(),
		'reportgroup' => array()
	),
	'components' => array(
		'authManager' => array(
			'class' => 'CDbAuthManager',
			'connectionID' => 'db',
		),
		'format' => array(
			'class' => 'application.components.Formatter',
		),
		'user' => array(
			'allowAutoLogin' => true,
			'class' => 'WebUser'
		),
		'urlManager' => array(
			'urlFormat' => 'path',
			'rules' => array(
				'<controller:\w+>/<id:\d+>' => '<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
				'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
			),
			'showScriptName' => false,
			'caseSensitive' => false,
		),
		'db' => array(
			'connectionString' => 'mysql:port=5000;dbname=agemlive;host=localhost',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => 'martoni14',
			'charset' => 'utf8',
			'initSQLs' => array('set names utf8'),
			'schemaCachingDuration' => 3600,
		),
		/*'cache'=>array(
			'class'=>'CMemCache',
			'useMemcached'=>true,
			'servers'=>array(
				array(
					'host'=>'localhost',
					'port'=>11211,
					'weight'=>60,
				),
			),
		),*/
	),
	'params' => array(
		'themes' => 'cerulean',
		'adminEmail' => 'romy@prismagrup.com',
		'defaultPageSize' => 10,
		'defaultYearFrom' => date('Y') - 1,
		'defaultYearTo' => date('Y'),
		'sizeLimit' => 10 * 1024 * 1024,
		'allowedext' => array("xls", "csv", "xlsx", "vsd", "pdf", "doc", "docx", "jpg", "gif", "png", "rar", "zip", "jpeg"),
		'language' => 1,
		'defaultnumberqty' => '#,##0.00',
		'defaultnumberprice' => '#,##0.00',
		'dateviewfromdb' => 'd-m-Y',
		'dateviewcjui' => 'dd-mm-yy',
		'dateviewgrid' => 'dd-MM-yyyy',
		'datetodb' => 'Y-m-d',
		'timeviewfromdb' => 'h:m',
		'datetimeviewfromdb' => 'd-M-Y H:i:s',
		'timeviewcjui' => 'h:m',
		'datetimeviewgrid' => 'dd-MM-yyyy H:m',
		'datetimetodb' => 'Y-m-d H:i:s'
	),
);
