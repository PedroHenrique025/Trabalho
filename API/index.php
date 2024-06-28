<?php
session_start();

require 'config/database.php';
require 'config/routes.php';
require 'util/functions.php';

spl_autoload_register(
	function($class){
		if(file_exists('controllers/'.$class.'.php')){
			require_once 'controllers/'.$class.'.php';
		}elseif(file_exists('models/'.$class.'.php')){
			require_once 'models/'.$class.'.php';
		}elseif(file_exists('core/'.$class.'.php')){
			require_once 'core/'.$class.'.php';
		}
	}
);

$routes = new Routes();
$routes->exec();