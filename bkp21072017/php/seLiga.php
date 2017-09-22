<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: charset=utf-8');

ini_set('display_errors', 1);
date_default_timezone_set('UTC');

if($_SERVER['HTTP_HOST'] == 'localhost:81'){
	$banco  	= 'tvondb';
	$host 		= '127.0.0.1';
	$usuario 	= 'root';
	$senha 		= '';
}else{
	$banco  	= 'tvondb';
	$host 		= '179.188.16.80';
	$usuario 	= 'tvondb';
	$senha 		= 'a4ed3368';
}
	
//Conexão
$dbh = new PDO(
	"mysql:host=$host;dbname=$banco",
	$usuario,
	$senha,
	array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
);

$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);