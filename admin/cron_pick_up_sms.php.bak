<?php
/**
 * 每天自动发导游信息的计划任务，由系统执行！
 */
ini_set('display_errors', '1'); 
error_reporting(E_ALL & ~E_NOTICE);
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );

require('cron.php');
require('includes/classes/PickUpSms.class.php');

PickUpSms::cron_send_pick_up_sms();
PickUpSms::record_log();
echo date('Y-m-d H:i:s').PHP_EOL;
?>