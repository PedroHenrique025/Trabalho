<?php

// tabela produtos
/*

id int
nome varchar
valor double
categoria varchar
img varchar

*/

global $db;

$config = array();
define("BASE_URL","http://localhost/api/");
$config['dbname'] = 'books';
$config['host']   = 'localhost';
$config['dbuser'] = 'root';
$config['dbpass'] = '';


try{
	$db = new PDO("mysql:dbname=".$config['dbname'].";host=".$config['host'], $config['dbuser'], $config['dbpass'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
	echo "erro bd: ".$e->getMessage();
	exit;
}