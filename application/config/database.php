<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$active_group = 'default';
$query_builder = TRUE;

$acc = array();
if($_SERVER['HTTP_HOST'] == "localhost"){
	$acc['host'] = "localhost";
	$acc['user'] = "root";
	$acc['pass'] = "";
	$acc['db'] = '446PontHu';
}else{
	$acc['host'] = "mysql.clanweb.hu";
	$acc['user'] = "clanwebh_amatorradio";
	$acc['pass'] = "Kyocera1995#%";
	$acc['db'] = 'clanwebh_446ponthu';
}


$db['default'] = array(
	'dsn'	=> '',
	'hostname' => $acc['host'],
	'username' => $acc['user'],
	'password' => $acc['pass'],
	'database' => $acc['db'],
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);
