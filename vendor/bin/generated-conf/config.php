<?php
$serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
$serviceContainer->checkVersion('2.0.0-dev');
$serviceContainer->setAdapterClass('default', 'mysql');
$serviceContainer->setAdapterClass('remote', 'mysql');
$manager = new \Propel\Runtime\Connection\ConnectionManagerSingle();
$manager->setConfiguration(array (
  'dsn' => 'mysql:host=127.0.0.1;port=3306;dbname=seng401cachedb',
  'user' => 'root',
  'password' => 'abc123',
  'settings' =>
  array (
    'charset' => 'utf8',
    'queries' =>
    array (
    ),
  ),
  'classname' => '\\Propel\\Runtime\\Connection\\ConnectionWrapper',
  'model_paths' =>
  array (
    0 => 'src',
    1 => 'vendor',
  ),
));
$manager->setName('default');
$serviceContainer->setConnectionManager('default', $manager);
$manager = new \Propel\Runtime\Connection\ConnectionManagerSingle();
$manager->setConfiguration(array (
	'dsn' => 'mysql:host=127.0.0.1;port=3306;dbname=seng401globalcachedb',
	'user' => 'root',
	'password' => 'abc123',
	'settings' =>
	array (
		'charset' => 'utf8',
		'queries' =>
		array (
		),
	),
	'classname' => '\\Propel\\Runtime\\Connection\\ConnectionWrapper',
	'model_paths' =>
	array (
		0 => 'src',
		1 => 'vendor',
	),
));
$manager->setName('remote');
$serviceContainer->setConnectionManager('remote', $manager);
$serviceContainer->setDefaultDatasource('default');