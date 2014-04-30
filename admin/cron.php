<?php
/**
 * 计划任务的配置文件
 */
ini_set('date.timezone','UTC-07:00');
ini_set('display_errors', '1');
error_reporting(E_ALL & ~E_NOTICE);
// Set the local configuration parameters - mainly for developers
if (file_exists('includes/configure_local.php')){
	include('includes/configure_local.php');
}elseif(file_exists('includes/local/configure.php')){
	include('includes/local/configure.php');
}else{
	// include server parameters
	include('includes/configure.php');
}

require(DIR_WS_FUNCTIONS . 'general.php');
require(DIR_WS_FUNCTIONS . 'database.php');
require(DIR_WS_FUNCTIONS . 'zhh_function.php');
tep_db_connect() or die('Unable to connect to database server!');

//取得数据库中定义的常量
$configuration_query = tep_db_query('select configuration_key as cfgKey, configuration_value as cfgValue from configuration ');
while ($configuration = tep_db_fetch_array($configuration_query)) {
	define($configuration['cfgKey'], $configuration['cfgValue']);
}
?>