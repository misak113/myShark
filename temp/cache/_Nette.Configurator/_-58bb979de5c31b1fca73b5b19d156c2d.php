<?php //netteCache[01]000255a:2:{s:4:"time";s:21:"0.03512100 1315852155";s:9:"callbacks";a:1:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:85:"C:\Users\misak113\programing\internet\apache2.2\avantcore\myShark\myShark/config.neon";i:2;i:1315852066;}}}?><?php
// source file C:\Users\misak113\programing\internet\apache2.2\avantcore\myShark\myShark/config.neon

$container->addService('authenticator', function($container) {
	$class = 'UsersModel'; $service = new $class();
	return $service;
}, NULL);

$container->addService('robotLoader', function($container) {
	return call_user_func(
		array ( 0 => 'Nette\\Configurator', 1 => 'createServicerobotLoader', ),
		$container
	);
}, array ( 0 => 'run', ));

date_default_timezone_set('Europe/Prague');

Nette\Caching\Storages\FileStorage::$useDirectories = true;

foreach ($container->getServiceNamesByTag("run") as $name => $foo) { $container->getService($name); }
